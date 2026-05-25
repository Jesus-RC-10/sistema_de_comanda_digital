<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema de Comanda Digital</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css?v=6">
    <script>const BASE_URL = '<?php echo BASE_URL; ?>';</script>
    <script src="<?php echo BASE_URL; ?>public/js/admin.js" defer></script>
</head>
<body>
    <div class="header">
        <h1>Sistema de Comanda Digital - Panel de Administración</h1>
        <div class="user-info">
            <span id="userName"><?php echo $_SESSION['usuario_nombre']; ?></span>
            <span id="userRole">(<?php echo $_SESSION['usuario_rol']; ?>)</span>
            <span id="dbStatus" class="db-status connected" title="Base de datos conectada">● BD Conectada</span>
            <button class="logout-btn" onclick="logout()">Cerrar Sesión</button>
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li class="menu-item" data-section="dashboard">
                    <a href="<?php echo BASE_URL; ?>index.php?action=admin&seccion=dashboard">📊 Dashboard</a>
                </li>
                <li class="menu-item" data-section="mesas">
                    <a href="<?php echo BASE_URL; ?>index.php?action=admin&seccion=mesas">🪑 Gestión de Mesas</a>
                </li>
                <li class="menu-item" data-section="menu">
                    <a href="<?php echo BASE_URL; ?>index.php?action=admin&seccion=menu">🍽️ Gestión de Menú</a>
                </li>
                <li class="menu-item" data-section="usuarios">
                    <a href="<?php echo BASE_URL; ?>index.php?action=admin&seccion=usuarios">👥 Gestión de Usuarios</a>
                </li>
                <li class="menu-item" data-section="inventario">
                    <a href="<?php echo BASE_URL; ?>index.php?action=admin&seccion=inventario">📦 Control de Inventario</a>
                </li>
                <li class="menu-item" data-section="reportes">
                    <a href="<?php echo BASE_URL; ?>index.php?action=admin&seccion=reportes">📊 Reportes</a>
                </li>
            </ul>
        </div>

        <div class="main-content">