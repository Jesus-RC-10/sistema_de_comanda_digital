<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/config/config.php')) {
    require_once BASE_PATH . '/config/config.php';
}

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');