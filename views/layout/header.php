<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema de Comanda Digital</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
    <script src="<?php echo BASE_URL; ?>public/css/js/admin.js" defer></script>
    <style>
        .content-section {
            display: block;
        }
        .sidebar-menu .menu-item a {
            text-decoration: none;
            color: inherit;
            display: block;
            padding: 8px 12px;
        }
        .sidebar-menu .menu-item.active {
            background-color: #e0f0ff;
            font-weight: bold;
        }
    </style>
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
                    <a href="index.php?action=admin&seccion=dashboard">📊 Dashboard</a>
                </li>
                <li class="menu-item" data-section="mesas">
                    <a href="index.php?action=admin&seccion=mesas">🪑 Gestión de Mesas</a>
                </li>
                <li class="menu-item" data-section="menu">
                    <a href="index.php?action=admin&seccion=menu">🍽️ Gestión de Menú</a>
                </li>
                <li class="menu-item" data-section="usuarios">
                    <a href="index.php?action=admin&seccion=usuarios">👥 Gestión de Usuarios</a>
                </li>
                <li class="menu-item" data-section="inventario">
                    <a href="index.php?action=admin&seccion=inventario">📦 Control de Inventario</a>
                </li>
                <li class="menu-item" data-section="reportes">
                    <a href="index.php?action=admin&seccion=reportes">📊 Reportes</a>
                </li>
            </ul>
        </div>

        <div class="main-content">