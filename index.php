<?php
// index.php - Front Controller Unificado

session_start();

$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $basePath . '/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Autoload para cargar modelos/controladores según se soliciten
spl_autoload_register(function($class) {
    if (file_exists('controllers/' . $class . '.php')) {
        require_once 'controllers/' . $class . '.php';
    } elseif (file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    } elseif (file_exists('models/observers/' . $class . '.php')) {
        require_once 'models/observers/' . $class . '.php';
    }
});

// 1. Manejo de URLs Legacy de Admin/Auth mediante $_GET['action']
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'login' || $action == 'logout') {
        require_once 'controllers/AuthController.php';
        $controller = new AuthController();
        if ($action == 'login') $controller->login();
        if ($action == 'logout') $controller->logout();
        exit;
    }
    if ($action == 'admin') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->index();
        exit;
    }
}

// 2. Enrutamiento Principal App (Mesero, Caja, Cocina, Menu...)
$url = isset($_GET['url']) ? $_GET['url'] : 'mesa';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlSegments = explode('/', $url);

$controllerName = ucfirst($urlSegments[0]) . 'Controller';

// Rutas directas para admin / login cuando vienen sin '?action='
if (strtolower($urlSegments[0]) === 'admin') {
    require_once 'controllers/AdminController.php';
    $controller = new AdminController();
    $controller->index();
    exit;
}
if (strtolower($urlSegments[0]) === 'login') {
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->login();
    exit;
}
if (strtolower($urlSegments[0]) === 'logout') {
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->logout();
    exit;
}

$action = isset($urlSegments[1]) ? $urlSegments[1] : 'index';
$params = array_slice($urlSegments, 2);

if (file_exists('controllers/' . $controllerName . '.php')) {
    $controller = new $controllerName();
    
    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        http_response_code(404);
        echo "Página de acción no encontrada";
    }
} else {
    // Fallback simple a Mesa
    header('Location: ' . BASE_URL . 'mesa');
    exit;
}
?>
