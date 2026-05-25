<?php
require_once __DIR__ . '/../config/database.php';

class VentaModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    /**
     * Crear una venta pendiente para un pedido
     */
    public function crearVentaPendiente($pedido_id, $total) {
        $sql = "INSERT INTO ventas (pedido_id, total, metodo_pago, estado) VALUES (?, ?, 'efectivo', 'pendiente')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$pedido_id, $total]);
    }

    /**
     * Pagar una venta
     */
    public function pagarVenta($venta_id, $metodo_pago, $monto_pagado, $usuario_id) {
        // Obtener el pedido_id asociado
        $sqlGetPedido = "SELECT pedido_id FROM ventas WHERE id = ?";
        $stmtGetPedido = $this->conn->prepare($sqlGetPedido);
        $stmtGetPedido->execute([$venta_id]);
        $pedido_id = $stmtGetPedido->fetchColumn();

        $sql = "UPDATE ventas SET metodo_pago = ?, estado = 'pagado', fecha_pago = NOW(), usuario_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$metodo_pago, $usuario_id, $venta_id]);

        if ($result && $pedido_id) {
            // Descontar inventario automáticamente
            $this->descontarInventarioPorPedido($pedido_id);
        }

        return $result;
    }

    /**
     * Descontar ingredientes de inventario según las recetas de los productos en el pedido
     */
    public function descontarInventarioPorPedido($pedido_id) {
        try {
            // 1. Obtener detalles del pedido (producto_id, cantidad y notas de personalización)
            $sqlDetalles = "SELECT producto_id, cantidad, notas FROM pedido_detalles WHERE pedido_id = ?";
            $stmtDetalles = $this->conn->prepare($sqlDetalles);
            $stmtDetalles->execute([$pedido_id]);
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

            require_once __DIR__ . '/../observers/NotificationManager.php';
            require_once __DIR__ . '/../observers/StockObserver.php';
            $notificationManager = new NotificationManager();
            $notificationManager->attach(new StockObserver());

            foreach ($detalles as $detalle) {
                $producto_id = $detalle['producto_id'];
                $cantidad_pedido = intval($detalle['cantidad']);
                $notas = $detalle['notas'] ?? '';

                // Analizar personalizaciones de ingredientes en las notas
                // Ejemplos: "Sin cebolla", "Sin Cebolla", "Sin Tomate"
                $excluidos = [];
                if (!empty($notas)) {
                    // Convertir a minúsculas y normalizar para buscar coincidencias
                    $notasLower = mb_strtolower($notas, 'UTF-8');
                    // Separar notas por comas
                    $partesNotas = explode(',', $notasLower);
                    foreach ($partesNotas as $parte) {
                        $parte = trim($parte);
                        if (strpos($parte, 'sin ') === 0) {
                            $ingredienteExcluido = trim(substr($parte, 4));
                            $excluidos[] = $ingredienteExcluido;
                        }
                    }
                }

                // 2. Obtener receta para este producto
                $sqlReceta = "SELECT rp.ingrediente_id, rp.cantidad as cantidad_receta, i.nombre as ingrediente_nombre, i.cantidad_actual, i.cantidad_minima, i.unidad_medida
                              FROM recetas_producto rp
                              JOIN ingredientes i ON rp.ingrediente_id = i.id
                              WHERE rp.producto_id = ? AND i.activo = 1";
                $stmtReceta = $this->conn->prepare($sqlReceta);
                $stmtReceta->execute([$producto_id]);
                $receta = $stmtReceta->fetchAll(PDO::FETCH_ASSOC);

                foreach ($receta as $ing) {
                    $ingrediente_id = $ing['ingrediente_id'];
                    $nombre_ingrediente = mb_strtolower($ing['ingrediente_nombre'], 'UTF-8');
                    $cantidad_actual = floatval($ing['cantidad_actual']);
                    $cantidad_minima = floatval($ing['cantidad_minima']);
                    
                    // Verificar si este ingrediente fue excluido en las notas
                    $fueExcluido = false;
                    foreach ($excluidos as $excl) {
                        if (strpos($nombre_ingrediente, $excl) !== false || strpos($excl, $nombre_ingrediente) !== false) {
                            $fueExcluido = true;
                            break;
                        }
                    }

                    if ($fueExcluido) {
                        // Si está excluido ("Sin cebolla"), no descontamos este ingrediente!
                        continue;
                    }

                    // Calcular descuento total = cantidad en receta * cantidad de platos pedidos
                    $descuento = floatval($ing['cantidad_receta']) * $cantidad_pedido;
                    $nueva_cantidad = $cantidad_actual - $descuento;
                    if ($nueva_cantidad < 0) {
                        $nueva_cantidad = 0; // Evitar stock negativo físico
                    }

                    // 3. Actualizar la cantidad del ingrediente
                    $sqlUpdateIng = "UPDATE ingredientes SET cantidad_actual = ? WHERE id = ?";
                    $stmtUpdateIng = $this->conn->prepare($sqlUpdateIng);
                    $stmtUpdateIng->execute([$nueva_cantidad, $ingrediente_id]);

                    // 4. Si el ingrediente cae por debajo del mínimo, disparar la alerta de stock bajo
                    if ($nueva_cantidad <= $cantidad_minima) {
                        $ingredienteActualizado = [
                            'id' => $ingrediente_id,
                            'nombre' => $ing['ingrediente_nombre'],
                            'cantidad_actual' => $nueva_cantidad,
                            'cantidad_minima' => $cantidad_minima,
                            'unidad_medida' => $ing['unidad_medida']
                        ];
                        $notificationManager->notify('stock_bajo', $ingredienteActualizado);
                    }
                }
            }
            return true;
        } catch (Exception $e) {
            error_log("Error al descontar inventario del pedido $pedido_id: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener ventas pendientes con detalles del pedido
     */
    public function obtenerVentasPendientes() {
        $sql = "SELECT v.*, p.mesa_id, m.numero_mesa, p.total as pedido_total, p.fecha_creacion
                FROM ventas v
                JOIN pedidos p ON v.pedido_id = p.id
                JOIN mesas m ON p.mesa_id = m.id
                WHERE v.estado = 'pendiente'
                ORDER BY p.fecha_creacion DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener ventas pagadas
     */
    public function obtenerVentasPagadas() {
        $sql = "SELECT v.*, p.mesa_id, m.numero_mesa, p.fecha_creacion, v.fecha_pago
                FROM ventas v
                JOIN pedidos p ON v.pedido_id = p.id
                JOIN mesas m ON p.mesa_id = m.id
                WHERE v.estado = 'pagado'
                ORDER BY v.fecha_pago DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener detalles de un pedido para generar ticket
     */
    public function obtenerDetallesPedido($pedido_id) {
        $sql = "SELECT pd.*, pr.nombre, p.mesa_id, m.numero_mesa
                FROM pedido_detalles pd
                JOIN productos pr ON pd.producto_id = pr.id
                JOIN pedidos p ON pd.pedido_id = p.id
                JOIN mesas m ON p.mesa_id = m.id
                WHERE pd.pedido_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener venta por ID (para pagar)
     */
    public function obtenerVentaPorId($venta_id) {
        $sql = "SELECT * FROM ventas WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$venta_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cancelar una venta pendiente (marcar como cancelada)
     * También cancela el pedido asociado y sus detalles para que no aparezcan en cocina
     */
    public function cancelarVenta($venta_id) {
        $this->conn->beginTransaction();
        try {
            // Marcar venta como cancelada
            $sql = "UPDATE ventas SET estado = 'cancelado', fecha_pago = NOW() WHERE id = ? AND estado = 'pendiente'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$venta_id]);

            // Obtener el pedido_id de la venta
            $venta = $this->obtenerVentaPorId($venta_id);
            $pedido_id = $venta['pedido_id'] ?? null;

            if ($pedido_id) {
                // Cancelar el pedido
                $sqlPedido = "UPDATE pedidos SET estado = 'cancelado' WHERE id = ?";
                $stmtPedido = $this->conn->prepare($sqlPedido);
                $stmtPedido->execute([$pedido_id]);

                // Cancelar todos los detalles del pedido
                $sqlDetalles = "UPDATE pedido_detalles SET estado = 'cancelado' WHERE pedido_id = ?";
                $stmtDetalles = $this->conn->prepare($sqlDetalles);
                $stmtDetalles->execute([$pedido_id]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Borrar todas las ventas pendientes
     */
    public function borrarVentasPendientes() {
        $sql = "DELETE FROM ventas WHERE estado = 'pendiente'";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([]);
    }

    /**
     * Obtener ventas canceladas
     */
    public function obtenerVentasCanceladas() {
        $sql = "SELECT v.*, p.mesa_id, m.numero_mesa, p.total as pedido_total, p.fecha_creacion
                FROM ventas v
                JOIN pedidos p ON v.pedido_id = p.id
                JOIN mesas m ON p.mesa_id = m.id
                WHERE v.estado = 'cancelado'
                ORDER BY v.fecha_pago DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener resumen de caja (totales y conteos)
     */
    public function obtenerResumen() {
        $sql = "SELECT
                  COUNT(*) as total_ventas,
                  SUM(CASE WHEN estado = 'pagado' THEN 1 ELSE 0 END) as pagadas_count,
                  COALESCE(SUM(CASE WHEN estado = 'pagado' THEN total ELSE 0 END), 0) as pagadas_total,
                  SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes_count,
                  COALESCE(SUM(CASE WHEN estado = 'pendiente' THEN total ELSE 0 END), 0) as pendientes_total,
                  SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as canceladas_count,
                  COALESCE(SUM(CASE WHEN estado = 'cancelado' THEN total ELSE 0 END), 0) as canceladas_total,
                  COALESCE(SUM(CASE WHEN estado IN ('pagado', 'pendiente') THEN total ELSE 0 END), 0) as ventas_total
                FROM ventas";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return [
                'total_ventas' => 0,
                'pagadas_count' => 0, 'pagadas_total' => 0,
                'pendientes_count' => 0, 'pendientes_total' => 0,
                'canceladas_count' => 0, 'canceladas_total' => 0,
                'ventas_total' => 0
            ];
        }
        return $result;
    }
}
?>