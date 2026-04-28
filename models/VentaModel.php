<?php
require_once 'config/db.php';

class VentaModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    /**
     * Crear una venta pendiente para un pedido
     */
    public function crearVentaPendiente($pedido_id, $total) {
        $sql = "INSERT INTO ventas (pedido_id, total, metodo_pago, estado) VALUES (?, ?, 'pendiente', 'pendiente')";
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
     * Cancelar una venta pendiente (eliminarla)
     */
    public function cancelarVenta($venta_id) {
        $sql = "DELETE FROM ventas WHERE id = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$venta_id]);
    }

    /**
     * Borrar todas las ventas pendientes
     */
    public function borrarVentasPendientes() {
        $sql = "DELETE FROM ventas WHERE estado = 'pendiente'";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([]);
    }
}
?>