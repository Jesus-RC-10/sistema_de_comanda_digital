<?php
require_once __DIR__ . '/../models/PedidoModel.php';

class MeseroController {
    public function __construct() {
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['usuario_rol'], ['mesero', 'admin', 'caja', 'cocina'])) {
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit();
        }
    }

    public function index() {
        $pedidos = $this->obtenerPedidosFiltrados();

        $data = [
            'pedidos' => $pedidos,
            'assets_url' => BASE_URL . "public/css/"
        ];

        require_once __DIR__ . '/../views/mesero/mesero.php';
    }

    // Nuevo método para obtener pedidos actualizados (AJAX)
    public function obtenerPedidosActualizados() {
        $pedidos = $this->obtenerPedidosFiltrados();
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'pedidos' => $pedidos
        ]);
        exit();
    }

    // Método helper para obtener pedidos filtrados por mesero
    private function obtenerPedidosFiltrados() {
        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->obtenerPedidosActivosConDetalles();

        // Si es rol mesero, filtrar solo por sus mesas asignadas
        if ($_SESSION['usuario_rol'] === 'mesero') {
            $mesero_id = $_SESSION['usuario_id'];
            
            $db = Database::getConnection();
            $stmtM = $db->prepare("SELECT id FROM mesas WHERE mesero_id = ? AND activa = 1");
            $stmtM->execute([$mesero_id]);
            $mesasAsignadas = $stmtM->fetchAll(PDO::FETCH_COLUMN);

            $pedidosFiltrados = [];
            foreach ($pedidos as $pedido) {
                if (in_array($pedido['mesa_id'], $mesasAsignadas)) {
                    $pedidosFiltrados[] = $pedido;
                }
            }
            return $pedidosFiltrados;
        }

        return $pedidos;
    }

    // Actualizar estado de un detalle
    public function actualizarDetalle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $detalle_id = $_POST['detalle_id'];
            $nuevoEstado = $_POST['estado'];

            $pedidoModel = new PedidoModel();
            $pedidoModel->actualizarEstadoDetalle($detalle_id, $nuevoEstado);

            echo json_encode(['success' => true]);
            exit();
        }
    }

    // Cerrar pedido completo
    public function cerrarPedido() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedido_id = $_POST['pedido_id'];

            $pedidoModel = new PedidoModel();
            $pedidoModel->cerrarPedido($pedido_id);

            echo json_encode(['success' => true]);
            exit();
        }
    }

    // Obtener alertas de asistencia activas para este mesero
    public function obtenerAlertasAyuda() {
        $db = Database::getConnection();
        
        // Si es mesero, solo mostramos las alertas de asistencia de sus mesas asignadas!
        if ($_SESSION['usuario_rol'] === 'mesero') {
            $mesero_id = $_SESSION['usuario_id'];
            $sql = "SELECT a.* 
                    FROM alertas_sistema a
                    JOIN mesas m ON a.mensaje LIKE CONCAT('%', m.numero_mesa, '%')
                    WHERE a.tipo = 'ayuda_mesa' AND a.leida = 0 AND m.mesero_id = ?
                    ORDER BY a.fecha_creacion DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute([$mesero_id]);
        } else {
            // Admin ve todas
            $sql = "SELECT * FROM alertas_sistema WHERE tipo = 'ayuda_mesa' AND leida = 0 ORDER BY fecha_creacion DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
        }
        
        $alertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'alertas' => $alertas
        ]);
        exit();
    }

    // Atender (marcar como leída) una alerta de asistencia
    public function atenderAlerta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alerta_id = isset($_POST['alerta_id']) ? intval($_POST['alerta_id']) : 0;
            
            $db = Database::getConnection();
            $sql = "UPDATE alertas_sistema SET leida = 1 WHERE id = ?";
            $stmt = $db->prepare($sql);
            $success = $stmt->execute([$alerta_id]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit();
        }
    }
}
?>