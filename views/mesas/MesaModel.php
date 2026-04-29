<?php
require_once __DIR__ . '/../../config/database.php';

class MesaModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function obtenerMesasActivas() {
        $sql = "SELECT * FROM mesas WHERE activa = 1 ORDER BY numero_mesa";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>