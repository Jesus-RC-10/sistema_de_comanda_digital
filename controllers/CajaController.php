<?php
require_once 'models/PedidoModel.php';

class CajaController {
    public function index() {
        $pedidoModel = new PedidoModel();
        // Traemos pedidos listos para cobrar
        $pedidos = $pedidoModel->obtenerPedidosListosParaCobrar();

        $data = [
            'pedidos' => $pedidos,
            'assets_url' => BASE_URL . "public/css/"
        ];

        require_once 'views/caja/caja.php';
    }

    public function registrarPago() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pedido_id = $_POST['pedido_id'];
            $metodo_pago = $_POST['metodo_pago'];
            $usuario_id = 1; // ID del cajero (ejemplo, luego se obtiene del login)

            $pedidoModel = new PedidoModel();
            $pedidoModel->registrarVenta($pedido_id, $metodo_pago, $usuario_id);

            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['success' => true]);
            } else {
                header('Location: ' . BASE_URL . 'caja');
            }
            exit;
        }
    }
}
?>