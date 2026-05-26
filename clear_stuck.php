<?php
$dsn = "mysql:host=127.0.0.1;port=3307;dbname=sistema_comanda_digital_v1;charset=utf8mb4";
$db = new PDO($dsn, 'root', 'root');
$db->exec("UPDATE pedidos SET estado='entregado'");
$db->exec("UPDATE pedido_detalles SET estado='entregado'");
$db->exec("UPDATE ventas SET estado='pagado'");
echo 'OK';
