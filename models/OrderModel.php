<?php

class OrderModel {
    private $pdo;

    public function __construct() {
        $host = DB_HOST;
        $dbname = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    // Guardar pedido y detalles
    public function saveOrder($mesa, $items) {
        try {
            $this->pdo->beginTransaction();

            // Calcular total
            $total = 0;
            foreach ($items as $item) {
                $total += $item['precio_unitario'] * $item['cantidad'];
            }

            // Insertar pedido
            $stmt = $this->pdo->prepare("INSERT INTO pedidos (mesa_id, usuario_id, estado, total) VALUES (?, 1, 'pendiente', ?)");
            $stmt->execute([$mesa, $total]);
            $pedidoId = $this->pdo->lastInsertId();

            // Insertar detalles
            $stmtDetalle = $this->pdo->prepare("INSERT INTO pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($items as $item) {
                $subtotal = $item['precio_unitario'] * $item['cantidad'];
                $stmtDetalle->execute([
                    $pedidoId,
                    $item['producto_id'],
                    $item['cantidad'],
                    $item['precio_unitario'],
                    $subtotal
                ]);
            }

            $this->pdo->commit();
            return $pedidoId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Obtener total de un pedido por ID
    public function getOrderTotal($pedidoId) {
        $stmt = $this->pdo->prepare("SELECT total FROM pedidos WHERE id = ?");
        $stmt->execute([$pedidoId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }

    // Marcar pedido como completado
    public function completeOrder($pedidoId) {
        $stmt = $this->pdo->prepare("UPDATE pedidos SET estado = 'completado' WHERE id = ?");
        $stmt->execute([$pedidoId]);
        return $stmt->rowCount();
    }
}
