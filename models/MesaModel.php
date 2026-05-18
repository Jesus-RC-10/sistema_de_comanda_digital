<?php
require_once __DIR__ . '/../config/database.php';

class MesaModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function obtenerMesasActivas() {
        $sql = "SELECT * FROM mesas WHERE activa = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mesaExiste($mesa_id) {
        $sql = "SELECT id FROM mesas WHERE id = ? AND activa = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$mesa_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}