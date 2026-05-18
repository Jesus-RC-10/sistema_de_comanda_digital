<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Caja - Taquería El Informático</title>
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/estilos.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/caja.css">
</head>
<body>
  <header>
    <h1>Caja - Taquería El Informático</h1>
  </header>

  <main>
    <?php if (isset($_GET['mensaje'])): ?>
      <div class="mensaje">
        <p><?php echo htmlspecialchars($_GET['mensaje']); ?></p>
      </div>
    <?php endif; ?>

    <section>
      <h2>⏳ Ventas Pendientes</h2>
      <?php if (!empty($data['ventas_pendientes'])): ?>
        <div class="acciones-generales">
          <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=caja/borrarPendientes" onsubmit="return confirm('¿Estás seguro de borrar todos los tickets pendientes?');">
            <button type="submit" class="btn-borrar-todos">Borrar Todos los Tickets Pendientes</button>
          </form>
        </div>
        <div class="ventas-grid">
          <?php foreach ($data['ventas_pendientes'] as $venta): ?>
            <div class="venta-card pendiente">
              <h3>Mesa <?php echo htmlspecialchars($venta['numero_mesa']); ?></h3>
              <p>Pedido #<?php echo $venta['pedido_id']; ?></p>
              <p>Total: $<?php echo number_format($venta['pedido_total'], 2); ?></p>
              <p>Fecha: <?php echo date('d/m/Y H:i', strtotime($venta['fecha_creacion'])); ?></p>
              <!-- Modificado por Oswaldo Ramírez: Agregar detalles del pedido en ventas pendientes -->
              <?php if (!empty($venta['detalles'])): ?>
                <div class="pedido-detalles">
                  <h4>Productos:</h4>
                  <ul>
                    <?php foreach ($venta['detalles'] as $detalle): ?>
                      <li><?php echo htmlspecialchars($detalle['nombre']); ?> x<?php echo $detalle['cantidad']; ?> - $<?php echo number_format($detalle['subtotal'], 2); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>
              <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=caja/pagar">
                <input type="hidden" name="venta_id" value="<?php echo $venta['id']; ?>">
                <input type="number" step="0.01" name="monto_pagado" placeholder="Monto pagado" required>
                <select name="metodo_pago">
                  <option value="efectivo">Efectivo</option>
                </select>
                <button type="submit">Pagar</button>
              </form>
              <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=caja/cancelar" onsubmit="return confirm('¿Cancelar esta venta?');">
                <input type="hidden" name="venta_id" value="<?php echo $venta['id']; ?>;">
                <button type="submit" class="btn-cancelar">Cancelar Venta</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>📋 No hay ventas pendientes en este momento.</p>
      <?php endif; ?>
    </section>

    <section>
      <h2>✅ Ventas Pagadas</h2>
      <?php if (!empty($data['ventas_pagadas'])): ?>
        <div class="ventas-grid">
          <?php foreach ($data['ventas_pagadas'] as $venta): ?>
            <div class="venta-card pagada">
              <h3>Mesa <?php echo htmlspecialchars($venta['numero_mesa']); ?> ✓</h3>
              <p>Pedido #<?php echo $venta['pedido_id']; ?></p>
              <p>Total: $<?php echo number_format($venta['total'], 2); ?></p>
              <p>Pagado: <?php echo date('d/m/Y H:i', strtotime($venta['fecha_pago'])); ?></p>
              <!-- Modificado por Oswaldo Ramírez: Agregar detalles del pedido en ventas pagadas -->
              <?php if (!empty($venta['detalles'])): ?>
                <div class="pedido-detalles">
                  <h4>Productos:</h4>
                  <ul>
                    <?php foreach ($venta['detalles'] as $detalle): ?>
                      <li><?php echo htmlspecialchars($detalle['nombre']); ?> x<?php echo $detalle['cantidad']; ?> - $<?php echo number_format($detalle['subtotal'], 2); ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>💰 No hay ventas pagadas recientes.</p>
      <?php endif; ?>
    </section>
  </main>

  <footer style="text-align: center; padding: 1rem; background: #f8f9fa; border-top: 2px solid var(--border-color); margin-top: 2rem;">
    <p style="color: #666; margin: 0;">Sistema de Comanda Digital - Taquería El Informático © 2026</p>
    <div id="refresh-indicator" style="margin-top: 0.5rem; font-size: 0.9rem; color: #888;">
      🔄 Actualización automática en <span id="countdown">30</span> segundos
    </div>
  </footer>

  <script>
    let countdown = 30;
    const countdownElement = document.getElementById('countdown');
    const refreshIndicator = document.getElementById('refresh-indicator');

    // Función para actualizar el contador
    function updateCountdown() {
      countdown--;
      countdownElement.textContent = countdown;
      
      if (countdown <= 5) {
        refreshIndicator.classList.add('updating');
      }
      
      if (countdown <= 0) {
        refreshIndicator.innerHTML = '🔄 Actualizando...';
      }
    }

    // Iniciar contador
    const countdownInterval = setInterval(updateCountdown, 1000);

    // Auto-refresh cada 30 segundos
    setTimeout(() => {
      clearInterval(countdownInterval);
      window.location.reload();
    }, 30000);
  </script>
    }, 30000);
  </script>
</body>
</html>