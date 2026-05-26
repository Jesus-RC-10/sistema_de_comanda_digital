<?php
$dsn = "mysql:host=127.0.0.1;port=3307;dbname=sistema_comanda_digital_v1;charset=utf8mb4";
$db = new PDO($dsn, 'root', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $db->query("SELECT * FROM alertas_sistema ORDER BY id DESC LIMIT 5");
$alertas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtM = $db->query("SELECT id, numero_mesa, mesero_id FROM mesas");
$mesas = $stmtM->fetchAll(PDO::FETCH_ASSOC);

echo "Alertas:\n";
print_r($alertas);
echo "\nMesas:\n";
print_r($mesas);
