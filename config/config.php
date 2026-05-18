<?php
// config/config.php

// Configurar zona horaria del servidor
date_default_timezone_set('America/Mexico_City'); // Ajusta según tu ubicación

// Configuración de rutas base - Funciona tanto en Docker como en XAMPP
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

// Obtener el path del script actual (index.php)
$script_name = $_SERVER['SCRIPT_NAME'];
$script_dir = dirname($script_name);

// Normalizar el path (eliminar barras dobles, slashes finales)
$script_dir = str_replace('\\', '/', $script_dir);
$script_dir = rtrim($script_dir, '/');

// Si estamos en la raíz del servidor, script_dir será "/"
if ($script_dir === '' || $script_dir === '/') {
    $project_folder = '';
} else {
    $project_folder = ltrim($script_dir, '/');
}

if (!defined('BASE_URL')) {
    if ($project_folder) {
        define('BASE_URL', $protocol . "://" . $host . "/" . $project_folder . "/");
    } else {
        define('BASE_URL', $protocol . "://" . $host . "/");
    }
}
define('PROJECT_PATH', __DIR__ . '/..');

// Función helper para URLs
function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

// Configuración de la base de datos (con soporte para variables de entorno de Docker)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', 'sistema_comanda_digital_v1');
?>
