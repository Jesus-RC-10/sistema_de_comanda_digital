<?php
try {
    $db = new PDO('mysql:host=127.0.0.1;dbname=sistema_de_comanda_digital_v1;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "ULTIMOS PEDIDOS\n";
    $stmt = $db->query('SELECT id, mesa_id, total, fecha_creacion FROM pedidos ORDER BY id DESC LIMIT 5');
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['id']} | Mesa: {$row['mesa_id']} | Total: {$row['total']} | Fecha: {$row['fecha_creacion']}\n";
    }

    echo "---\nULTIMAS VENTAS\n";
    $stmt = $db->query('SELECT id, pedido_id, total, metodo_pago, estado, fecha_creacion, fecha_pago FROM ventas ORDER BY id DESC LIMIT 5');
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['id']} | Pedido: {$row['pedido_id']} | Total: {$row['total']} | Estado: {$row['estado']} | Fecha: {$row['fecha_creacion']} | FechaPago: {$row['fecha_pago']}\n";
    }
} catch (PDOException $e) {
    echo 'Error DB: '. $e->getMessage();
}
?>