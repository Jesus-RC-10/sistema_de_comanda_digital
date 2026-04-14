<?php
require_once 'config/db.php';

class PedidoModel {
    private $conn;

    public function __construct() {
        $db = new DatabasePDO();
        $this->conn = $db->getConnection();
    }

    /**
     * Crear un nuevo pedido con sus detalles
     */
    public function crearPedido($mesa_id, $usuario_id, $items) {
        try {
            $this->conn->beginTransaction();

            // Insertar pedido
            $sql = "INSERT INTO pedidos (mesa_id, usuario_id, estado, total) VALUES (?, ?, 'pendiente', 0)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$mesa_id, $usuario_id]);
            $pedido_id = $this->conn->lastInsertId();

            $total = 0;
            foreach ($items as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;

                $sqlDetalle = "INSERT INTO pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario, subtotal, estado) 
                               VALUES (?, ?, ?, ?, ?, 'pendiente')";
                $stmtDetalle = $this->conn->prepare($sqlDetalle);
                $stmtDetalle->execute([
                    $pedido_id,
                    $item['id'],
                    $item['cantidad'],
                    $item['precio'],
                    $subtotal
                ]);
            }

            // Actualizar total del pedido
            $sqlUpdate = "UPDATE pedidos SET total = ? WHERE id = ?";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->execute([$total, $pedido_id]);

            $this->conn->commit();
            return $pedido_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    /**
     * Obtener pedidos activos (sin detalles)
     */
    public function obtenerPedidosActivos() {
        $sql = "SELECT * FROM pedidos WHERE estado NOT IN ('entregado','cancelado')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener pedidos activos con detalles
     */
    public function obtenerPedidosActivosConDetalles() {
        $sql = "SELECT * FROM pedidos WHERE estado NOT IN ('entregado','cancelado')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pedidos as &$pedido) {
            $sqlDetalles = "SELECT d.*, p.nombre 
                            FROM pedido_detalles d 
                            JOIN productos p ON d.producto_id = p.id 
                            WHERE d.pedido_id = ?";
            $stmtDetalles = $this->conn->prepare($sqlDetalles);
            $stmtDetalles->execute([$pedido['id']]);
            $pedido['detalles'] = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);
        }

        return $pedidos;
    }

    /**
     * Actualizar estado de un detalle
     */
    public function actualizarEstadoDetalle($detalle_id, $nuevoEstado) {
        $sql = "UPDATE pedido_detalles SET estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nuevoEstado, $detalle_id]);
    }

    /**
     * Cerrar un pedido (marcar como entregado)
     */
    public function cerrarPedido($pedido_id) {
        $sql = "UPDATE pedidos SET estado = 'entregado' WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$pedido_id]);
    }

    /**
     * Obtener pedidos listos para cobrar (todos los que no han sido pagados ni cancelados)
     */
    public function obtenerPedidosListosParaCobrar() {
        $sql = "SELECT * FROM pedidos WHERE estado NOT IN ('pagado', 'cancelado')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pedidos as &$pedido) {
            $sqlDetalles = "SELECT d.*, p.nombre 
                            FROM pedido_detalles d 
                            JOIN productos p ON d.producto_id = p.id 
                            WHERE d.pedido_id = ?";
            $stmtDetalles = $this->conn->prepare($sqlDetalles);
            $stmtDetalles->execute([$pedido['id']]);
            $pedido['detalles'] = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);
        }

        return $pedidos;
    }

    /**
     * Registrar una venta y marcar el pedido como pagado (DESCONTAR INVENTARIO AQUÍ)
     */
    public function registrarVenta($pedido_id, $metodo_pago, $usuario_id) {
        try {
            $this->conn->beginTransaction();

            // 1. Obtener el total del pedido
            $sqlPedido = "SELECT total FROM pedidos WHERE id = ?";
            $stmtPedido = $this->conn->prepare($sqlPedido);
            $stmtPedido->execute([$pedido_id]);
            $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) {
                throw new Exception("Pedido no encontrado.");
            }

            // 2. Insertar en la tabla ventas
            $sqlVenta = "INSERT INTO ventas (pedido_id, usuario_id, total, metodo_pago, estado, fecha_pago) 
                         VALUES (?, ?, ?, ?, 'pagado', NOW())";
            $stmtVenta = $this->conn->prepare($sqlVenta);
            $stmtVenta->execute([
                $pedido_id,
                $usuario_id,
                $pedido['total'],
                $metodo_pago
            ]);

            // 3. Actualizar el estado del pedido a 'pagado'
            $sqlPedidoUpdate = "UPDATE pedidos SET estado = 'pagado' WHERE id = ?";
            $stmtPedidoUpdate = $this->conn->prepare($sqlPedidoUpdate);
            $stmtPedidoUpdate->execute([$pedido_id]);

            // 4. Descontar Inventario Automáticamente usando Recetas
            // a) Obtener los detalles del pedido
            trim(" \n");
            $sqlDetalles = "SELECT producto_id, cantidad FROM pedido_detalles WHERE pedido_id = ?";
            $stmtDet = $this->conn->prepare($sqlDetalles);
            $stmtDet->execute([$pedido_id]);
            $detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

            // Importar Observers
            require_once __DIR__ . '/observers/NotificationManager.php';
            require_once __DIR__ . '/observers/StockObserver.php';
            $notifier = new NotificationManager();
            $notifier->attach(new StockObserver());

            foreach ($detalles as $det) {
                // b) Obtener receta del producto
                $sqlReceta = "SELECT ingrediente_id, cantidad FROM recetas_producto WHERE producto_id = ?";
                $stmtReceta = $this->conn->prepare($sqlReceta);
                $stmtReceta->execute([$det['producto_id']]);
                $receta = $stmtReceta->fetchAll(PDO::FETCH_ASSOC);

                foreach ($receta as $insumo) {
                    $total_a_descontar = $insumo['cantidad'] * $det['cantidad'];

                    // c) Restar en base de datos de ingredientes
                    $sqlRestar = "UPDATE ingredientes SET cantidad_actual = cantidad_actual - ? WHERE id = ?";
                    $stmtRestar = $this->conn->prepare($sqlRestar);
                    $stmtRestar->execute([$total_a_descontar, $insumo['ingrediente_id']]);

                    // d) Verificar si quedó en stock bajo para disparar patrón Observer
                    $sqlVerificar = "SELECT * FROM ingredientes WHERE id = ?";
                    $stmtVerificar = $this->conn->prepare($sqlVerificar);
                    $stmtVerificar->execute([$insumo['ingrediente_id']]);
                    $ingrediente_actualizado = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

                    if ($ingrediente_actualizado && $ingrediente_actualizado['cantidad_actual'] <= $ingrediente_actualizado['cantidad_minima']) {
                        // DISPARAR OBSERVER AUTOMÁTICAMENTE
                        $notifier->notify('stock_bajo', $ingrediente_actualizado);
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
?>