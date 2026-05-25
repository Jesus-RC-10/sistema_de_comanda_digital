<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seleccionar Mesa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/estilos.css?v=3">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/mesas.css">
</head>
<body>
  <header>
    <div class="header-content">
      <div class="brand">
        <i class="fas fa-utensils"></i>
        <span>Taquería El Informático</span>
      </div>
      <h1>Selecciona tu mesa</h1>
    </div>
  </header>

  <main>
    <div class="mesas-container">
      <?php if (!empty($data['mesas'])): ?>
        <?php foreach ($data['mesas'] as $index => $mesa): 
          $estado = $mesa['estado'] ?? 'libre';
          $esLibre = $estado === 'libre';
          $ubicacion = $mesa['ubicacion'] ?? '';
          $numero = htmlspecialchars($mesa['numero_mesa']);
        ?>
          <div class="mesa-card <?php echo $esLibre ? 'libre' : 'ocupada'; ?>" 
               onclick="<?php echo $esLibre ? "seleccionarMesa(" . $mesa['id'] . ")" : ''; ?>"
               style="animation-delay: <?php echo $index * 0.08; ?>s">
            <div class="mesa-status">
              <span class="status-dot <?php echo $esLibre ? 'green' : 'red'; ?>"></span>
              <span class="status-text"><?php echo $esLibre ? 'Disponible' : 'Ocupada'; ?></span>
            </div>
            <div class="mesa-icon">
              <i class="fas fa-chair"></i>
            </div>
            <div class="mesa-number"><?php echo $numero; ?></div>
            <?php if ($ubicacion): ?>
              <div class="mesa-ubicacion">
                <i class="fas fa-map-marker-alt"></i>
                <?php echo htmlspecialchars($ubicacion); ?>
              </div>
            <?php endif; ?>
            <?php if (!$esLibre): ?>
              <div class="mesa-overlay">
                <i class="fas fa-lock"></i>
                <span>Ocupada</span>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-mesas">
          <i class="fas fa-exclamation-triangle"></i>
          <p>No hay mesas activas disponibles</p>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <div class="help-fab" onclick="window.location.href='<?php echo BASE_URL; ?>login'">
    <i class="fas fa-user-shield"></i>
  </div>

  <script>
    function seleccionarMesa(idMesa) {
      window.location.href = "<?php echo BASE_URL; ?>index.php?url=menu&mesa=" + idMesa;
    }
  </script>
</body>
</html>
