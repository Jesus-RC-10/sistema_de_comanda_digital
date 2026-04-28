<?php
// Front Controller - Punto de entrada único

// Configuración básica
session_start();

// IMPORTANTE: Configurar la ruta correcta dinámicamente
$baseDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseDir === '/') $baseDir = '';
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $baseDir . '/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Resto del código permanece igual...
// Cargar clases automáticamente
spl_autoload_register(function($class) {
    if (file_exists('controllers/' . $class . '.php')) {
        require_once 'controllers/' . $class . '.php';
    } elseif (file_exists('models/' . $class . '.php')) {
        require_once 'models/' . $class . '.php';
    }
});

// Obtener la acción o la URL solicitada
$action = isset($_GET['action']) ? $_GET['action'] : null;
$url = isset($_GET['url']) ? $_GET['url'] : null;

if ($action) {
    $action = rtrim(filter_var($action, FILTER_SANITIZE_URL), '/');

    if ($action === 'login' || $action === 'logout') {
        $controllerName = 'AuthController';
        $method = $action;
        $params = [];
    } elseif ($action === 'admin') {
        $controllerName = 'AdminController';
        $method = 'index';
        $params = [];
    } else {
        $actionSegments = explode('/', $action);
        $controllerName = ucfirst($actionSegments[0]) . 'Controller';
        $method = isset($actionSegments[1]) ? $actionSegments[1] : 'index';
        $params = array_slice($actionSegments, 2);
    }

    if (file_exists('controllers/' . $controllerName . '.php')) {
        $controller = new $controllerName();
        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
            exit;
        }
    }
    http_response_code(404);
    echo "Página no encontrada";
    exit;
}

// Si no hay action, usamos la URL normal
$url = $url ? rtrim(filter_var($url, FILTER_SANITIZE_URL), '/') : 'mesa';
$urlSegments = explode('/', $url);

// Enrutamiento básico
$controllerName = ucfirst($urlSegments[0]) . 'Controller';
$action = isset($urlSegments[1]) ? $urlSegments[1] : 'index';
$params = array_slice($urlSegments, 2);

// Verificar si el controlador existe
if (file_exists('controllers/' . $controllerName . '.php')) {
    $controller = new $controllerName();
    
    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
    } else {
        http_response_code(404);
        echo "Página no encontrada";
    }
} else {
    header('Location: ' . BASE_URL . 'mesa');
    exit;
}
?>