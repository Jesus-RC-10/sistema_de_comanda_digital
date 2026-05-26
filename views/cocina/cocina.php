<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área de Cocina</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/estilos.css?v=6">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/cocina.css?v=5">
  <style>
    .btn-todo-listo {
      background: linear-gradient(135deg, #1976D2, #1565C0);
      color: white;
      border: 1px solid #0D47A1;
      padding: 6px 12px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: bold;
      margin-left: 10px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.2);
      transition: all 0.2s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .btn-todo-listo:hover {
      background: linear-gradient(135deg, #2196F3, #1976D2);
      transform: translateY(-2px);
      box-shadow: 0 6px 8px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.3);
    }
    .btn-todo-listo:active {
      transform: translateY(1px);
      box-shadow: 0 2px 3px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>
  <header>
    <div class="header-top">
      <div class="header-brand">
        <i class="fas fa-utensils"></i>
        <span>Taquería El Informático</span>
      </div>
      <div class="header-page-title">
        <i class="fas fa-kitchen-set"></i>
        <span>Área de Cocina</span>
        <span class="last-update" id="lastUpdate"></span>
      </div>
    </div>
  </header>

  <main id="pedidosContainer">
    <?php if (empty($data['pedidos'])): ?>
      <div class="no-items">
        <i class="fas fa-check-circle"></i>
        <p>No hay pedidos activos de tacos o postres</p>
      </div>
    <?php else: ?>
      <?php foreach ($data['pedidos'] as $pedido):
        $mid = $pedido['mesa_id'] ?? '?';
      ?>
        <div class="pedido" data-pedido-id="<?php echo $pedido['id']; ?>">
          <div class="pedido-header">
            <div class="pedido-info">
              <div class="pedido-title-row">
                <span class="mesa-badge"><i class="fas fa-chair"></i> Mesa <?php echo $mid; ?></span>
                <span class="pedido-num">#<?php echo $pedido['id']; ?></span>
              </div>
              <div class="pedido-meta">
                <span class="meta-time"><i class="fas fa-clock"></i> <span class="order-time" data-time="<?php echo $pedido['fecha_creacion']; ?>"><?php echo date('H:i', strtotime($pedido['fecha_creacion'])); ?></span></span>
                <span class="estado-pedido estado-<?php echo $pedido['estado']; ?>"><?php echo strtoupper($pedido['estado']); ?></span>
              </div>
            </div>
          </div>

          <ul class="detalles-lista">
            <?php foreach ($pedido['detalles'] as $detalle): ?>
              <li class="detalle-item">
                <div class="detalle-info">
                  <span class="detalle-nombre" style="font-weight:600;"><?php echo htmlspecialchars($detalle['nombre']); ?></span>
                  <?php if (!empty($detalle['notas'])): ?>
                    <div class="detalle-notas" style="color: #FFA000; font-size: 0.85rem; font-weight: bold; margin-top: 3px;"><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($detalle['notas']); ?></div>
                  <?php endif; ?>
                  <span class="detalle-cantidad">x<?php echo $detalle['cantidad']; ?></span>
                </div>
                <div class="detalle-controls">
                  <span class="detalle-estado estado-<?php echo $detalle['estado']; ?>"
                        data-detalle-id="<?php echo $detalle['id']; ?>"
                        data-current-state="<?php echo $detalle['estado']; ?>"
                        onclick="cambiarEstado(this)">
                    <?php echo strtoupper(str_replace('_', ' ', $detalle['estado'])); ?>
                  </span>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>

  <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
    const COCINA_URL = BASE_URL + 'index.php?url=cocina/obtenerPedidosActualizados';
    const ESTADO_URL = BASE_URL + 'index.php?url=cocina/actualizarDetalle';
  </script>

  <script src="<?php echo ASSETS_URL; ?>js/cocina.js"></script>
</body>
</html>

