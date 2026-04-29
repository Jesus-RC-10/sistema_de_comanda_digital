<?php
require_once 'models/ProductoModel.php';
require_once 'models/PedidoModel.php';
require_once 'models/MesaModel.php';
require_once 'models/VentaModel.php';

class MenuController {
    public function index() {
        $mesa = isset($_GET['mesa']) ? intval($_GET['mesa']) : 1;

        // Validar que la mesa existe
        $mesaModel = new MesaModel();
        if (!$mesaModel->mesaExiste($mesa)) {
            echo "<p>Error: La mesa seleccionada no existe o no está disponible.</p>";
            echo "<a href='".BASE_URL."mesa'>Volver a Mesas</a>";
            return;
        }

        $productoModel = new ProductoModel();
        $productos = $productoModel->obtenerProductosActivos();

        $data = [
            'mesa' => $mesa,
            'productos' => $productos,
            'assets_url' => ASSETS_URL
        ];

        require_once 'views/menu/menu.php';
    }

    public function confirmar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mesa_id = $_POST['mesa_id'];
            $items = json_decode($_POST['items'], true);

            // Validar que la mesa existe y está activa
            $mesaModel = new MesaModel();
            if (!$mesaModel->mesaExiste($mesa_id)) {
                echo "<p>Error: La mesa seleccionada no existe o no está disponible.</p>";
                echo "<a href='".BASE_URL."mesa'>Volver a Mesas</a>";
                return;
            }

            $pedidoModel = new PedidoModel();
            $ventaModel = new VentaModel();
            $usuario_id = 2; // temporal, luego se usará login de mesero/cliente
            try {
                $pedido_id = $pedidoModel->crearPedido($mesa_id, $usuario_id, $items);

                // Obtener el total del pedido desde el modelo (no acceso directo a conn privado)
                $pedido = $pedidoModel->obtenerPedidoPorId($pedido_id);
                if (!$pedido) {
                    throw new Exception("No se encontró el pedido creado.");
                }
                $total = $pedido['total'];

                // Crear venta pendiente
                if (!$ventaModel->crearVentaPendiente($pedido_id, $total)) {
                    throw new Exception("Error al crear la venta pendiente.");
                }

                // Generar ticket
                $ticket = $this->generarTicket($pedido_id);

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Pedido enviado a caja. Ticket generado.',
                    'pedido_id' => $pedido_id,
                    'total' => $total,
                    'ticket' => $ticket
                ]);
                exit();
            } catch (Exception $e) {
                header('Content-Type: application/json', true, 400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear el pedido: ' . $e->getMessage()
                ]);
                exit();
            }
        }
    }

    private function generarTicket($pedido_id) {
        $ventaModel = new VentaModel();
        $detalles = $ventaModel->obtenerDetallesPedido($pedido_id);

        if (empty($detalles)) {
            return "Error: No se encontraron detalles del pedido.";
        }

        $mesa = $detalles[0]['numero_mesa'];
        $fecha = date('d/m/Y');
        $hora = date('H:i:s');

        $ticket = "🍽️ TAQUERÍA EL INFORMÁTICO\n";
        $ticket .= "═══════════════════════════════\n\n";
        $ticket .= "📍 Mesa: M" . str_pad($mesa, 2, '0', STR_PAD_LEFT) . "\n\n";
        $ticket .= "📅 Fecha: $fecha\n";
        $ticket .= "🕐 Hora: $hora\n";
        $ticket .= "═══════════════════════════════\n";

        $total = 0;
        foreach ($detalles as $detalle) {
            $ticket .= "• " . $detalle['nombre'] . "\n";
            $ticket .= "  Cantidad: x" . $detalle['cantidad'] . " - $" . number_format($detalle['subtotal'], 2) . "\n\n";
            $total += $detalle['subtotal'];
        }

        $ticket .= "═══════════════════════════════\n";
        $ticket .= "💰 TOTAL: $" . number_format($total, 2) . "\n";
        $ticket .= "═══════════════════════════════\n\n";
        $ticket .= "🙏 ¡Gracias por tu pedido!\n\n";
        $ticket .= "🍽️ Recoge tu pedido en caja cuando esté listo.\n";

        return $ticket;
    }
     public function solicitarAsistencia() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mesa_id = $_POST['mesa_id'] ?? null;
            
            if ($mesa_id) {
                // Validar que la mesa existe
                $mesaModel = new MesaModel();
                if (!$mesaModel->mesaExiste($mesa_id)) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'message' => 'La mesa seleccionada no existe o no está disponible.'
                    ]);
                    exit();
                }
                
                // Aquí puedes:
                // 1. Guardar en la base de datos
                // 2. Enviar notificación push
                // 3. Integrar con sistema de meseros
                
                // Por ahora, solo log y respuesta exitosa
                error_log("Asistencia solicitada para mesa: " . $mesa_id);
                
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Asistencia registrada para mesa ' . $mesa_id,
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
                exit();
            }
        }
        
        // Si hay error
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Error al solicitar asistencia'
        ]);
        exit();
    }

    public function enviarEmail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $pedido_id = $input['pedido_id'] ?? null;
            $email = $input['email'] ?? null;

            if (!$pedido_id || !$email) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos incompletos'
                ]);
                exit();
            }

            try {
                // Funcionalidad de email eliminada
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Funcionalidad de email no disponible'
                ]);
                exit();

            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
                exit();
            }
        }
    }

}
?>