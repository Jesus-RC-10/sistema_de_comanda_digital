<?php
require_once __DIR__ . '/../config/database.php';

class PedidoModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
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
            $todosListos = true;

            foreach ($items as $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;

                $notas = !empty($item['notas']) ? $item['notas'] : null;

                // Todos los items entran como pendiente.
                // Las bebidas se marcarán como 'listo' cuando el cajero confirme el pago.
                $estado = 'pendiente';
                $todosListos = false;

                $sqlDetalle = "INSERT INTO pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario, subtotal, notas, estado) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtDetalle = $this->conn->prepare($sqlDetalle);
                $stmtDetalle->execute([
                    $pedido_id,
                    $item['id'],
                    $item['cantidad'],
                    $item['precio'],
                    $subtotal,
                    $notas,
                    $estado
                ]);
            }

            // Actualizar total y mantener el pedido como pendiente hasta que se pague en caja
            $sqlUpdate = "UPDATE pedidos SET total = ?, estado = 'pendiente' WHERE id = ?";
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
        $result = $stmt->execute([$nuevoEstado, $detalle_id]);
        
        if ($result) {
            // Obtener el pedido_id asociado a este detalle
            $sqlPedido = "SELECT pedido_id FROM pedido_detalles WHERE id = ?";
            $stmtPedido = $this->conn->prepare($sqlPedido);
            $stmtPedido->execute([$detalle_id]);
            $pedido_id = $stmtPedido->fetchColumn();
            
            if ($pedido_id) {
                // Verificar si todos los detalles de este pedido están listos/entregados/cancelados
                // Es decir, contar cuántos detalles quedan en 'pendiente' o 'en_preparacion'
                $sqlCheck = "SELECT COUNT(*) FROM pedido_detalles 
                             WHERE pedido_id = ? AND estado IN ('pendiente', 'en_preparacion')";
                $stmtCheck = $this->conn->prepare($sqlCheck);
                $stmtCheck->execute([$pedido_id]);
                $detallesActivos = $stmtCheck->fetchColumn();
                
                if ($detallesActivos == 0) {
                    // Todos los detalles están listos, por lo tanto el pedido está listo
                    $sqlUpdate = "UPDATE pedidos SET estado = 'listo' WHERE id = ?";
                    $stmtUpdate = $this->conn->prepare($sqlUpdate);
                    $stmtUpdate->execute([$pedido_id]);
                } else {
                    // Si hay detalles pendientes o en preparación, y el pedido estaba en 'pendiente' o 'listo',
                    // lo marcamos como 'en_preparacion'
                    $sqlGetPedido = "SELECT estado FROM pedidos WHERE id = ?";
                    $stmtGetPedido = $this->conn->prepare($sqlGetPedido);
                    $stmtGetPedido->execute([$pedido_id]);
                    $estadoPedido = $stmtGetPedido->fetchColumn();
                    
                    if ($estadoPedido !== 'en_preparacion' && $estadoPedido !== 'cancelado' && $estadoPedido !== 'entregado') {
                        $sqlUpdate = "UPDATE pedidos SET estado = 'en_preparacion' WHERE id = ?";
                        $stmtUpdate = $this->conn->prepare($sqlUpdate);
                        $stmtUpdate->execute([$pedido_id]);
                    }
                }
            }
        }
        
        return $result;
    }

    /**
     * Obtener un pedido por ID
     */
    public function obtenerPedidoPorId($pedido_id) {
        $sql = "SELECT * FROM pedidos WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$pedido_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
     * Confirmar un pedido (marcar como confirmado para cocina al pagarse en caja)
     */
    public function confirmarPedido($pedido_id) {
        try {
            $this->conn->beginTransaction();
            
            // 1. Marcar pedido como confirmado
            $sql = "UPDATE pedidos SET estado = 'confirmado' WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$pedido_id]);
            
            // 2. Marcar bebidas (categoria_id = 3) como 'listo' automáticamente al pagar
            $sqlBebidas = "UPDATE pedido_detalles d 
                           JOIN productos p ON d.producto_id = p.id 
                           SET d.estado = 'listo' 
                           WHERE d.pedido_id = ? AND p.categoria_id = 3";
            $stmtBebidas = $this->conn->prepare($sqlBebidas);
            $stmtBebidas->execute([$pedido_id]);
            
            // 3. Verificar si todos los detalles están listos (ej. si el pedido era solo de bebidas)
            $sqlCheck = "SELECT COUNT(*) FROM pedido_detalles 
                         WHERE pedido_id = ? AND estado IN ('pendiente', 'en_preparacion')";
            $stmtCheck = $this->conn->prepare($sqlCheck);
            $stmtCheck->execute([$pedido_id]);
            $detallesActivos = $stmtCheck->fetchColumn();
            
            if ($detallesActivos == 0) {
                // Si todo está listo, pasar el pedido a listo directamente
                $sqlUpdate = "UPDATE pedidos SET estado = 'listo' WHERE id = ?";
                $stmtUpdate = $this->conn->prepare($sqlUpdate);
                $stmtUpdate->execute([$pedido_id]);
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>