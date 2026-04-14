<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Área de Caja</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/caja.css">
</head>
<body>
  <header>
    <div class="header-main">
        <h1>Interfaz de Caja</h1>
        <div class="user-info">
            <span>Usuario: <strong><?php echo $_SESSION['usuario_nombre'] ?? 'Invitado'; ?></strong></span>
            <span>(<?php echo $_SESSION['usuario_rol'] ?? 'Sin Rol'; ?>)</span>
            <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="logout-link">Cerrar Sesión</a>
        </div>
    </div>
  </header>

  <main>
    <?php if (empty($data['pedidos'])): ?>
      <p>No hay pedidos listos para cobrar</p>
    <?php else: ?>
      <?php foreach ($data['pedidos'] as $pedido): ?>
        <div class="pedido">
          <h3>Mesa <?php echo $pedido['mesa_id']; ?> - Pedido #<?php echo $pedido['id']; ?></h3>
          <p>Total: $<?php echo number_format($pedido['total'], 2); ?></p>

          <ul>
            <?php foreach ($pedido['detalles'] as $detalle): ?>
              <li>
                <?php echo htmlspecialchars($detalle['nombre']); ?> 
                x<?php echo $detalle['cantidad']; ?> 
                - $<?php echo number_format($detalle['subtotal'], 2); ?>
              </li>
            <?php endforeach; ?>
          </ul>

          <form method="POST" action="<?php echo BASE_URL; ?>caja/registrarPago" class="pago-form">
            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
            <input type="hidden" name="metodo_pago" value="efectivo">
            <button type="submit">Registrar Pago en Efectivo</button>
          </form>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>
</body>
</html>