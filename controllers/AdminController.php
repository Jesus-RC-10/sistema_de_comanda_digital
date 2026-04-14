<?php
// controllers/AdminController.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/observers/NotificationManager.php';
require_once __DIR__ . '/../models/observers/StockObserver.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Mesa.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Inventario.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Pedido.php';

class AdminController {
    private $notificationManager;
    
    public function __construct() {
        $this->notificationManager = new NotificationManager();
        $this->notificationManager->attach(new StockObserver());
    }
    
    public function index() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->procesarFormulario();
        }
        
        $seccion = $_GET['seccion'] ?? 'dashboard';
        
        switch($seccion) {
            case 'dashboard':
                $this->dashboard();
                break;
            case 'mesas':
                $this->gestionMesas();
                break;
            case 'menu':
                $this->gestionMenu();
                break;
            case 'usuarios':
                $this->gestionUsuarios();
                break;
            case 'inventario':
                $this->controlInventario();
                break;
            case 'reportes':
                $this->reportes();
                break;
            default:
                $this->dashboard();
        }
    }
    
    private function procesarFormulario() {
        if (!isset($_POST['accion'])) return;
        
        switch($_POST['accion']) {
            case 'agregar_mesa':
                $this->agregarMesa();
                break;
            case 'eliminar_mesa':
                $this->eliminarMesa();
                break;
            case 'agregar_producto':
                $this->agregarProducto();
                break;
            case 'eliminar_producto':
                $this->eliminarProducto();
                break;
            case 'agregar_usuario':
                $this->agregarUsuario();
                break;
            case 'eliminar_usuario':
                $this->eliminarUsuario();
                break;
            case 'actualizar_inventario':
                $this->actualizarInventario();
                break;
            case 'agregar_ingrediente':
                $this->agregarIngrediente();
                break;
        }
    }
    
    private function agregarIngrediente() {
        $inventarioModel = new Inventario();
        $inventarioModel->crear($_POST);
        
        $this->notificationManager->notify('inventario_actualizado', [
            'ingrediente' => $_POST['nombre'],
            'cantidad' => $_POST['cantidad_actual'] ?? 0,
            'usuario' => $_SESSION['usuario_nombre'],
            'anterior' => 0
        ]);
        
        $this->redirect('inventario');
    }
    
    private function agregarMesa() {
        $mesaModel = new Mesa();
        $mesaModel->crear($_POST);
        
        $this->notificationManager->notify('mesa_agregada', [
            'numero_mesa' => $_POST['numero_mesa'],
            'ubicacion' => $_POST['ubicacion'],
            'usuario' => $_SESSION['usuario_nombre']
        ]);
        
        $this->redirect('mesas');
    }
    
    private function agregarProducto() {
        // Manejar la imagen si se subió
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/images/platillos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = time() . '_' . basename($_FILES['imagen']['name']);
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetPath)) {
                $imagen = $filename;
            }
        }

        $productoModel = new Producto();
        $producto_id = $productoModel->crear($_POST, $imagen);
        
        if ($producto_id) {
            // Guardar receta si hay ingredientes
            if (isset($_POST['ingredientes_id']) && is_array($_POST['ingredientes_id'])) {
                $cantidades = $_POST['ingredientes_cantidad'];
                for ($i = 0; $i < count($_POST['ingredientes_id']); $i++) {
                    $ingrediente_id = $_POST['ingredientes_id'][$i];
                    $cantidad = $cantidades[$i];
                    if (!empty($ingrediente_id) && $cantidad > 0) {
                        $productoModel->agregarIngredienteReceta($producto_id, $ingrediente_id, $cantidad);
                    }
                }
            }

            $this->notificationManager->notify('producto_agregado', [
                'producto' => $_POST['nombre'],
                'precio' => $_POST['precio'],
                'usuario' => $_SESSION['usuario_nombre']
            ]);
        }
        
        $this->redirect('menu');
    }
    
    private function actualizarInventario() {
        $inventarioModel = new Inventario();
        $ingrediente_id = $_POST['ingrediente_id'];
        $cantidad_actual = $_POST['cantidad_actual'];
        
        $ingrediente_actual = $inventarioModel->getIngrediente($ingrediente_id);
        $inventarioModel->actualizarCantidad($ingrediente_id, $cantidad_actual);
        
        $this->notificationManager->notify('inventario_actualizado', [
            'ingrediente' => $ingrediente_actual['nombre'],
            'cantidad' => $cantidad_actual,
            'usuario' => $_SESSION['usuario_nombre'],
            'anterior' => $ingrediente_actual['cantidad_actual']
        ]);
        
        if ($cantidad_actual <= $ingrediente_actual['cantidad_minima']) {
            $ingrediente_actualizado = $inventarioModel->getIngrediente($ingrediente_id);
            $this->notificationManager->notify('stock_bajo', $ingrediente_actualizado);
        }
        
        $this->redirect('inventario');
    }
    
    private function eliminarMesa() {
        $mesaModel = new Mesa();
        $mesaModel->eliminar($_POST['mesa_id']);
        $this->redirect('mesas');
    }
    
    private function eliminarProducto() {
        $productoModel = new Producto();
        $productoModel->eliminar($_POST['producto_id']);
        $this->redirect('menu');
    }
    
    private function agregarUsuario() {
        $userModel = new User();
        $userModel->crear($_POST);
        $this->redirect('usuarios');
    }
    
    private function eliminarUsuario() {
        $userModel = new User();
        $userModel->eliminar($_POST['usuario_id']);
        $this->redirect('usuarios');
    }
    
    private function dashboard() {
        $ventasModel = new Venta();
        $pedidosModel = new Pedido();
        $mesasModel = new Mesa();
        $productoModel = new Producto();
        
        $data = [
            'ventas_hoy' => $ventasModel->getVentasHoy(),
            'pedidos_activos' => $pedidosModel->getPedidosActivos(),
            'total_mesas' => $mesasModel->getTotalMesas(),
            'mesas_ocupadas' => $mesasModel->getMesasOcupadas(),
            'productos' => $productoModel->getAllActive(),
            'alertas_recientes' => $this->getAlertasRecientes()
        ];
        
        require __DIR__ . '/../views/admin/dashboard.php';
    }
    
    private function gestionMesas() {
        $mesaModel = new Mesa();
        $data = ['mesas' => $mesaModel->getAll()];
        require __DIR__ . '/../views/admin/mesas.php';
    }
    
    private function gestionMenu() {
        $productoModel = new Producto();
        $db = Database::getConnection();
        
        $sql_categorias = "SELECT * FROM categorias_menu WHERE activa = 1";
        $categorias = $db->query($sql_categorias)->fetch_all(MYSQLI_ASSOC);
        
        $inventarioModel = new Inventario();
        $ingredientes = $inventarioModel->getAll();

        $data = [
            'productos' => $productoModel->getAllActive(),
            'categorias' => $categorias,
            'ingredientes' => $ingredientes
        ];
        
       require __DIR__ . '/../views/admin/menu.php';
    }
    
    private function gestionUsuarios() {
        $userModel = new User();
        $data = ['usuarios' => $userModel->getAll()];
         require __DIR__ . '/../views/admin/usuarios.php';
    }
    
    private function controlInventario() {
        $inventarioModel = new Inventario();
        
        $data = [
            'ingredientes' => $inventarioModel->getAll(),
            'alertas_stock' => $inventarioModel->getStockBajo(),
            'alertas_sistema' => $this->getAlertasSistema()
        ];
        
         require __DIR__ . '/../views/admin/inventario.php';
    }
    
    private function reportes() {
        require __DIR__ . '/../views/admin/reportes.php';
    }
    
    private function getAlertasSistema() {
        $db = Database::getConnection();
        $sql = "SELECT * FROM alertas_sistema WHERE leida = 0 ORDER BY fecha_creacion DESC LIMIT 10";
        return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    
    private function getAlertasRecientes() {
        $db = Database::getConnection();
        $sql = "SELECT * FROM alertas_sistema ORDER BY fecha_creacion DESC LIMIT 5";
        return $db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    
    private function redirect($seccion) {
        header("Location: " . BASE_URL . "index.php?action=admin&seccion=" . $seccion);

        exit();
    }
    
    private function checkAuth() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        // Solo administradores pueden entrar aquí
        if (isset($_SESSION['usuario_rol']) && $_SESSION['usuario_rol'] !== 'admin') {
            // Si es mesero, caja o cocina, lo mandamos al index unificado
            header("Location: index.php?action=login"); 
            exit();
        }
    }
}
?>