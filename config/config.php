<?php
// config/config.php

// Configuración de rutas base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script_name = $_SERVER['SCRIPT_NAME'];
$project_folder = trim(dirname(dirname($script_name)), '/\\');

if (!defined('BASE_URL')) {
    define('BASE_URL', $protocol . "://" . $host . "/" . $project_folder . "/");
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
define('DB_NAME', 'sistema_de_comanda_digital_v1');
?>
