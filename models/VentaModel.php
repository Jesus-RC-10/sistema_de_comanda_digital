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
        $sql = "UPDATE ventas SET metodo_pago = ?, estado = 'pagado', fecha_pago = NOW(), usuario_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$metodo_pago, $usuario_id, $venta_id]);

        // También marcar el pedido como entregado o algo, pero por ahora solo la venta
        return $stmt->rowCount() > 0;
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