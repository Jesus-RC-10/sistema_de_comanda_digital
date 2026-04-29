<?php
require_once 'models/VentaModel.php';

class CajaController {
    public function index() {
        $ventaModel = new VentaModel();
        $ventasPendientes = $ventaModel->obtenerVentasPendientes();
        $ventasPagadas = $ventaModel->obtenerVentasPagadas();

        // Modificado por Oswaldo Ramírez: Obtener detalles de cada pedido pendiente y pagado para mostrar en caja
        // Obtener detalles de cada pedido pendiente
        foreach ($ventasPendientes as &$venta) {
            $venta['detalles'] = $ventaModel->obtenerDetallesPedido($venta['pedido_id']);
        }

        // Obtener detalles de cada pedido pagado
        foreach ($ventasPagadas as &$venta) {
            $venta['detalles'] = $ventaModel->obtenerDetallesPedido($venta['pedido_id']);
        }

        $data = [
            'ventas_pendientes' => $ventasPendientes,
            'ventas_pagadas' => $ventasPagadas,
            'assets_url' => ASSETS_URL
        ];

        require_once 'views/caja/caja.php';
    }

    public function pagar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $venta_id = $_POST['venta_id'];
            $monto_pagado = floatval($_POST['monto_pagado']);
            $metodo_pago = $_POST['metodo_pago'] ?? 'efectivo';
            $usuario_id = 1; // temporal

            $ventaModel = new VentaModel();

            // Obtener la venta desde el modelo (evita acceso a propiedad privada)
            $venta = $ventaModel->obtenerVentaPorId($venta_id);
            $total = $venta['total'] ?? 0;
            $pedido_id = $venta['pedido_id'] ?? null;

            if ($monto_pagado < $total) {
                $mensaje = "Monto insuficiente. Total: $" . number_format($total, 2);
            } else {
                $cambio = $monto_pagado - $total;
                $ventaModel->pagarVenta($venta_id, $metodo_pago, $monto_pagado, $usuario_id);

                // Marcar pedido como entregado
                if ($pedido_id) {
                    require_once 'models/PedidoModel.php';
                    $pedidoModel = new PedidoModel();
                    $pedidoModel->cerrarPedido($pedido_id);
                }

                $mensaje = "Pago registrado. Monto pagado: $" . number_format($monto_pagado, 2) .
                          ". Cambio: $" . number_format($cambio, 2);
            }

            // Redirigir con mensaje
            header("Location: " . BASE_URL . "caja?mensaje=" . urlencode($mensaje));
            exit();
        }
    }

    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $venta_id = $_POST['venta_id'];

            $ventaModel = new VentaModel();
            $exito = $ventaModel->cancelarVenta($venta_id);

            $mensaje = $exito ? "Venta cancelada exitosamente." : "Error al cancelar la venta.";
            header("Location: " . BASE_URL . "caja?mensaje=" . urlencode($mensaje));
            exit();
        }
    }

    public function borrarPendientes() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ventaModel = new VentaModel();
            $exito = $ventaModel->borrarVentasPendientes();

            $mensaje = $exito ? "Todas las ventas pendientes han sido eliminadas." : "Error al borrar las ventas pendientes.";
            header("Location: " . BASE_URL . "caja?mensaje=" . urlencode($mensaje));
            exit();
        }
    }
}
?>