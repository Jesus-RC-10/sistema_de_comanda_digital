<?php
$dsn = "mysql:host=127.0.0.1;port=3307;dbname=sistema_comanda_digital_v1;charset=utf8mb4";
$db = new PDO($dsn, 'root', 'root');
$mesero_id = 2; // mesero1
$sql = "SELECT a.*, m.numero_mesa, m.mesero_id 
        FROM alertas_sistema a
        JOIN mesas m ON a.mensaje LIKE CONCAT('%', m.numero_mesa, '%')
        WHERE a.tipo = 'ayuda_mesa' AND a.leida = 0 AND m.mesero_id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$mesero_id]);
$alertas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Alertas para mesero_id=2:\n";
print_r($alertas);

// check what's in alertas_sistema
$stmt = $db->query("SELECT * FROM alertas_sistema ORDER BY id DESC LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

// check mesa 6
$stmt = $db->query("SELECT * FROM mesas WHERE id=6 OR numero_mesa='M06' OR numero_mesa LIKE '%6%'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

