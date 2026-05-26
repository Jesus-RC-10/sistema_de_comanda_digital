<?php
$dsn = "mysql:host=127.0.0.1;port=3307;dbname=sistema_comanda_digital_v1;charset=utf8mb4";
$db = new PDO($dsn, 'root', 'root');
$stmt = $db->query("SELECT id, usuario, rol FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Usuarios:\n";
print_r($usuarios);
