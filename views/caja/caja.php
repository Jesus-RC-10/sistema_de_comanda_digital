<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terminal de Caja | SCD</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/estilos.css?v=6">
  
  <style>
    /* Estilos Premium Autocontenidos - Caja POS Terminal */
    :root {
      --bg-dark: #0A0907;
      --panel-bg: rgba(22, 19, 16, 0.7);
      --card-bg-pendiente: rgba(35, 30, 26, 0.65);
      --card-bg-pagada: rgba(26, 45, 26, 0.7);
      --accent-red: #D32F2F;
      --accent-orange: #FFA000;
      --accent-green: #43A047;
      --text-main: #F5F5F0;
      --text-muted: #9E9A90;
      --border-glass: rgba(255, 255, 255, 0.08);
      --shadow-premium: 0 15px 35px rgba(0, 0, 0, 0.6);
    }

    body {
      background-color: var(--bg-dark);
      background-image: radial-gradient(circle at 5% 5%, rgba(255, 160, 0, 0.04) 0%, transparent 40%),
                        radial-gradient(circle at 95% 95%, rgba(67, 160, 71, 0.04) 0%, transparent 40%);
      color: var(--text-main);
      font-family: 'Outfit', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      background: rgba(10, 9, 7, 0.92);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border-bottom: 1px solid var(--border-glass);
      padding: 15px 30px;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header-content {
      max-width: 1300px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 1.4rem;
      font-weight: 800;
    }

    .brand i {
      color: var(--accent-orange);
      filter: drop-shadow(0 0 5px rgba(255, 160, 0, 0.5));
    }

    .brand span {
      background: linear-gradient(135deg, #FFF 60%, var(--accent-orange));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .header-title-panel {
      font-size: 1.3rem;
      font-weight: 900;
      color: var(--accent-green);
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
    }

    .btn-exit-caja {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-glass);
      color: var(--text-muted);
      padding: 8px 16px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s;
      text-decoration: none;
      font-size: 0.85rem;
    }

    .btn-exit-caja:hover {
      background: var(--accent-red);
      color: white;
      border-color: var(--accent-red);
    }

    main {
      flex: 1;
      max-width: 1300px;
      margin: 30px auto;
      padding: 0 20px;
      width: 100%;
      box-sizing: border-box;
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
    }

    @media (max-width: 1024px) {
      main {
        grid-template-columns: 1fr;
      }
    }

    .caja-search-box {
      background: rgba(30, 30, 30, 0.5);
      border: 1px solid var(--border-glass);
      border-radius: 16px;
      padding: 15px 20px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .caja-search-box i {
      color: var(--accent-orange);
      font-size: 1.2rem;
    }

    .caja-search-input {
      background: transparent;
      border: none;
      color: white;
      font-size: 1.1rem;
      font-family: inherit;
      outline: none;
      width: 100%;
    }

    .caja-search-input::placeholder {
      color: #777;
    }

    .section-title {
      font-size: 1.4rem;
      font-weight: 800;
      color: var(--text-main);
      margin-top: 0;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid var(--border-glass);
      padding-bottom: 8px;
    }

    .mensaje-alert {
      background: rgba(67, 160, 71, 0.15);
      border-left: 4px solid var(--accent-green);
      color: #A5D6A7;
      padding: 15px 20px;
      border-radius: 12px;
      margin-bottom: 25px;
      font-weight: 600;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      animation: fadeIn 0.3s;
    }

    .mensaje-alert button {
      background: none;
      border: none;
      color: inherit;
      cursor: pointer;
      font-size: 1.2rem;
      line-height: 1;
    }

    .ventas-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }

    .venta-card {
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-glass);
      border-radius: 20px;
      padding: 22px;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .venta-card.pendiente {
      background: var(--card-bg-pendiente);
    }

    .venta-card.pendiente:hover {
      transform: translateY(-5px);
      border-color: rgba(255, 160, 0, 0.3);
      box-shadow: 0 10px 25px rgba(255, 160, 0, 0.08);
      background: rgba(60, 60, 60, 0.85);
    }

    .venta-card.pagada {
      background: var(--card-bg-pagada);
      border-color: rgba(67, 160, 71, 0.2);
    }

    .venta-card.pagada::after {
      content: 'PAGADO';
      position: absolute;
      top: 15px;
      right: -25px;
      background: var(--accent-green);
      color: white;
      font-size: 0.65rem;
      font-weight: 900;
      padding: 4px 25px;
      transform: rotate(45deg);
      letter-spacing: 0.5px;
    }

    .venta-card h3 {
      margin: 0;
      font-size: 1.3rem;
      font-weight: 800;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .mesa-badge-pos {
      background: rgba(255,255,255,0.08);
      border: 1px solid var(--border-glass);
      padding: 4px 10px;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: bold;
    }

    .venta-card .pedido-num {
      color: var(--accent-orange);
      font-size: 0.9rem;
      font-weight: 600;
    }

    .venta-card .pedido-total {
      font-size: 1.5rem;
      font-weight: 900;
      color: var(--accent-green);
      margin-top: 8px;
    }

    .venta-card .pedido-meta {
      font-size: 0.8rem;
      color: #999;
      display: flex;
      align-items: center;
      gap: 6px;
      margin-top: 4px;
    }

    .pedido-detalles-mini {
      border-top: 1px solid rgba(255,255,255,0.06);
      padding-top: 8px;
      margin-top: 8px;
      font-size: 0.85rem;
      color: var(--text-muted);
    }

    .pedido-detalles-mini ul {
      margin: 0;
      padding-left: 15px;
    }

    .pedido-detalles-mini li {
      margin-bottom: 2px;
    }

    .no-ventas {
      background: rgba(30, 30, 30, 0.3);
      border: 1px dashed var(--border-glass);
      border-radius: 20px;
      padding: 50px 20px;
      text-align: center;
      color: #777;
      grid-column: 1 / -1;
    }

    .no-ventas i {
      font-size: 2.5rem;
      margin-bottom: 12px;
      color: #555;
    }

    /* Panel de Detalle de Venta y Cobro */
    .pos-checkout-sidebar {
      background: rgba(25, 25, 25, 0.95);
      border: 1px solid var(--border-glass);
      border-radius: 24px;
      padding: 30px;
      display: flex;
      flex-direction: column;
      gap: 25px;
      box-shadow: var(--shadow-premium);
      position: sticky;
      top: 100px;
      max-height: calc(100vh - 150px);
      overflow-y: auto;
    }

    .pos-checkout-sidebar.empty-state {
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #777;
    }

    .pos-checkout-sidebar.empty-state i {
      font-size: 3.5rem;
      color: rgba(255,255,255,0.05);
      margin-bottom: 15px;
    }

    .checkout-header {
      border-bottom: 1px solid var(--border-glass);
      padding-bottom: 15px;
    }

    .checkout-header h2 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--accent-orange);
    }

    .checkout-items-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
      max-height: 200px;
      overflow-y: auto;
      border-bottom: 1px solid var(--border-glass);
      padding-bottom: 15px;
    }

    .checkout-item-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      font-size: 0.95rem;
    }

    .checkout-item-name {
      font-weight: 600;
    }

    .checkout-item-notes {
      display: block;
      color: var(--accent-orange);
      font-size: 0.75rem;
      margin-top: 2px;
    }

    .checkout-item-subtotal {
      font-weight: bold;
      color: #ccc;
    }

    .checkout-summary {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255, 255, 255, 0.03);
      padding: 15px 20px;
      border-radius: 16px;
      border: 1px solid var(--border-glass);
    }

    .checkout-summary span {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--text-muted);
    }

    .checkout-summary .checkout-total-num {
      font-size: 2.2rem;
      font-weight: 900;
      color: var(--accent-green);
    }

    /* Calculadora POS de Cambio */
    .calculator-container {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .calc-input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .calc-input-group label {
      font-weight: 600;
      font-size: 0.85rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .calc-input-row {
      display: flex;
      position: relative;
    }

    .calc-input-row span {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.4rem;
      font-weight: 800;
      color: #999;
    }

    .calc-input-field {
      width: 100%;
      background: #252525;
      border: 1px solid rgba(255,255,255,0.12);
      border-radius: 14px;
      padding: 14px 14px 14px 40px;
      color: white;
      font-size: 1.5rem;
      font-weight: 900;
      outline: none;
      font-family: inherit;
      box-sizing: border-box;
      transition: all 0.3s;
    }

    .calc-input-field:focus {
      border-color: var(--accent-orange);
      box-shadow: 0 0 10px rgba(255, 160, 0, 0.2);
    }

    .quick-cash-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }

    .quick-cash-btn {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-glass);
      color: white;
      padding: 10px;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      font-family: inherit;
      font-size: 0.95rem;
      transition: all 0.2s;
    }

    .quick-cash-btn:hover {
      background: var(--accent-orange);
      color: black;
      border-color: var(--accent-orange);
    }

    .calc-change-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(67, 160, 71, 0.05);
      border: 1px solid rgba(67, 160, 71, 0.15);
      padding: 12px 20px;
      border-radius: 14px;
    }

    .calc-change-row span {
      font-weight: 600;
      color: #A5D6A7;
    }

    .calc-change-num {
      font-size: 1.8rem;
      font-weight: 900;
      color: var(--accent-green);
    }

    .calc-change-num.insufficient {
      color: var(--accent-red);
    }

    .checkout-form {
      margin-top: 10px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .btn-checkout-pay {
      background: linear-gradient(135deg, var(--accent-green), #2E7D32);
      border: none;
      color: white;
      padding: 16px;
      border-radius: 14px;
      font-size: 1.15rem;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(67, 160, 71, 0.3);
      font-family: inherit;
      width: 100%;
    }

    .btn-checkout-pay:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(67, 160, 71, 0.45);
    }

    .btn-checkout-cancel {
      background: rgba(229, 57, 53, 0.1);
      border: 1px solid rgba(229, 57, 53, 0.2);
      color: #FF8A80;
      padding: 10px;
      border-radius: 12px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      font-family: inherit;
      width: 100%;
    }

    .btn-checkout-cancel:hover {
      background: var(--accent-red);
      color: white;
      border-color: var(--accent-red);
    }

    .footer-panel {
      text-align: center;
      padding: 20px;
      background: rgba(18, 18, 18, 0.9);
      border-top: 1px solid var(--border-glass);
      margin-top: 40px;
      font-size: 0.9rem;
      color: #666;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-content">
      <div class="brand">
        <i class="fas fa-coins"></i>
        <span>TaquerÃ­a El InformÃ¡tico</span>
      </div>
      <div class="header-title-panel">
        <i class="fas fa-cash-register"></i>
        <span>Terminal de Caja</span>
      </div>
      <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="btn-exit-caja"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </div>
  </header>

  <main>
    <!-- Columna Izquierda: Grid de Ventas y Buscador -->
    <div class="main-left-column">
      <?php if (isset($_GET['mensaje'])): ?>
        <div class="mensaje-alert" id="mensajeAlert">
          <span><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($_GET['mensaje']); ?></span>
          <button onclick="document.getElementById('mensajeAlert').remove()">&times;</button>
        </div>
      <?php endif; ?>

      <div class="caja-search-box">
        <i class="fas fa-search"></i>
        <input type="text" class="caja-search-input" id="cajaSearchInput" placeholder="Buscar ticket por nÃºmero de mesa o pedido (ej. M01 o #5)..." autocomplete="off">
      </div>

      <section style="margin-bottom: 40px;">
        <h2 class="section-title"><i class="fas fa-hourglass-half"></i> Ventas Pendientes de Pago</h2>
        <?php if (!empty($data['ventas_pendientes'])): ?>
          <div class="acciones-generales" style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
            <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=caja/borrarPendientes" onsubmit="return confirm('Â¿EstÃ¡s seguro de borrar todos los tickets pendientes?');">
              <button type="submit" class="btn-checkout-cancel" style="padding: 10px 20px; width: auto; font-size: 0.85rem;"><i class="fas fa-trash-sweep"></i> Limpiar Todo lo Pendiente</button>
            </form>
          </div>
          <div class="ventas-grid" id="ventasPendientesGrid">
            <?php foreach ($data['ventas_pendientes'] as $venta): ?>
              <?php
                // Compilar detalles para el data attribute
                $itemsArray = [];
                if (!empty($venta['detalles'])) {
                    foreach ($venta['detalles'] as $d) {
                        $itemsArray[] = [
                            'nombre' => $d['nombre'],
                            'cantidad' => $d['cantidad'],
                            'notas' => $d['notas'] ?? '',
                            'subtotal' => $d['subtotal']
                        ];
                    }
                }
                $itemsJson = htmlspecialchars(json_encode($itemsArray), ENT_QUOTES, 'UTF-8');
              ?>
              <div class="venta-card pendiente" 
                   data-venta-id="<?php echo $venta['id']; ?>"
                   data-pedido-id="<?php echo $venta['pedido_id']; ?>"
                   data-mesa-id="<?php echo htmlspecialchars($venta['numero_mesa']); ?>"
                   data-total="<?php echo $venta['pedido_total']; ?>"
                   data-items="<?php echo $itemsJson; ?>"
                   onclick="seleccionarVenta(this)">
                <h3>
                  <span>Mesa <?php echo htmlspecialchars($venta['numero_mesa']); ?></span>
                  <span class="mesa-badge-pos">Pendiente</span>
                </h3>
                <div class="pedido-num">Pedido #<?php echo $venta['pedido_id']; ?></div>
                <div class="pedido-total">$<?php echo number_format($venta['pedido_total'], 2); ?></div>
                <div class="pedido-meta"><i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($venta['fecha_creacion'])); ?></div>
                
                <?php if (!empty($venta['detalles'])): ?>
                  <div class="pedido-detalles-mini">
                    <ul>
                      <?php foreach (array_slice($venta['detalles'], 0, 3) as $detalle): ?>
                        <li><?php echo htmlspecialchars($detalle['nombre']); ?> x<?php echo $detalle['cantidad']; ?></li>
                      <?php endforeach; ?>
                      <?php if (count($venta['detalles']) > 3): ?>
                        <li style="list-style:none; font-style:italic; margin-top:2px;">+ <?php echo (count($venta['detalles']) - 3); ?> mÃ¡s...</li>
                      <?php endif; ?>
                    </ul>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="no-ventas">
            <i class="fas fa-clipboard-list"></i>
            <p>No hay ventas pendientes de pago en este momento.</p>
          </div>
        <?php endif; ?>
      </section>

      <section>
        <h2 class="section-title"><i class="fas fa-check-circle"></i> Ventas Pagadas Recientemente</h2>
        <?php if (!empty($data['ventas_pagadas'])): ?>
          <div class="ventas-grid">
            <?php foreach (array_slice($data['ventas_pagadas'], 0, 8) as $venta): ?>
              <div class="venta-card pagada">
                <h3>
                  <span>Mesa <?php echo htmlspecialchars($venta['numero_mesa']); ?></span>
                </h3>
                <div class="pedido-num">Pedido #<?php echo $venta['pedido_id']; ?></div>
                <div class="pedido-total">$<?php echo number_format($venta['total'], 2); ?></div>
                <div class="pedido-meta"><i class="fas fa-check-double"></i> <?php echo date('H:i', strtotime($venta['fecha_pago'])); ?></div>
                
                <?php if (!empty($venta['detalles'])): ?>
                  <div class="pedido-detalles-mini">
                    <ul>
                      <?php foreach ($venta['detalles'] as $detalle): ?>
                        <li><?php echo htmlspecialchars($detalle['nombre']); ?> x<?php echo $detalle['cantidad']; ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="no-ventas">
            <i class="fas fa-money-bill-wave"></i>
            <p>No hay ventas pagadas recientes en esta sesiÃ³n.</p>
          </div>
        <?php endif; ?>
      </section>
    </div>

    <!-- Columna Derecha: Sidebar Checkout de Pago -->
    <div class="main-right-column">
      <!-- Estado vacÃ­o inicial del panel de cobro -->
      <div class="pos-checkout-sidebar empty-state" id="checkoutPanelEmpty">
        <i class="fas fa-cash-register"></i>
        <h3>Listo para Cobrar</h3>
        <p>Selecciona una mesa o pedido pendiente a la izquierda para procesar su cobro en efectivo.</p>
      </div>

      <!-- Formulario de Checkout activo -->
      <div class="pos-checkout-sidebar" id="checkoutPanelActive" style="display: none;">
        <div class="checkout-header">
          <h2 id="checkoutTitleMesa">Mesa XX</h2>
          <span style="color:var(--text-muted); font-size:0.9rem;" id="checkoutTitlePedido">Pedido #00</span>
        </div>

        <div class="checkout-items-list" id="checkoutItemsList">
          <!-- DinÃ¡mico -->
        </div>

        <div class="checkout-summary">
          <span>TOTAL A PAGAR:</span>
          <span class="checkout-total-num" id="checkoutTotalNum">$0.00</span>
        </div>

        <div class="calculator-container">
          <div class="calc-input-group">
            <label for="efectivoRecibido">Efectivo Recibido:</label>
            <div class="calc-input-row">
              <span>$</span>
              <input type="number" step="0.01" min="0" class="calc-input-field" id="efectivoRecibido" placeholder="0.00" oninput="calcularCambio()">
            </div>
          </div>

          <div class="quick-cash-grid">
            <button class="quick-cash-btn" onclick="agregarEfectivoRapido(50)">$50</button>
            <button class="quick-cash-btn" onclick="agregarEfectivoRapido(100)">$100</button>
            <button class="quick-cash-btn" onclick="agregarEfectivoRapido(200)">$200</button>
            <button class="quick-cash-btn" onclick="agregarEfectivoRapido(500)">$500</button>
          </div>

          <div class="calc-change-row">
            <span>Cambio a Entregar:</span>
            <span class="calc-change-num" id="calcChangeNum">$0.00</span>
          </div>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=caja/pagar" class="checkout-form" onsubmit="return validarCobro()">
          <input type="hidden" name="venta_id" id="checkoutFormVentaId">
          <input type="hidden" name="monto_pagado" id="checkoutFormMontoPagado">
          <input type="hidden" name="metodo_pago" value="efectivo">
          <button type="submit" class="btn-checkout-pay">CONFIRMAR PAGO</button>
        </form>

        <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=caja/cancelar" onsubmit="return confirm('Â¿EstÃ¡s seguro de que deseas CANCELAR esta comanda por completo?');">
          <input type="hidden" name="venta_id" id="cancelFormVentaId">
          <button type="submit" class="btn-checkout-cancel">Cancelar Comanda</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="footer-panel">
    <p>Comanda Digital - TaquerÃ­a El InformÃ¡tico &copy; 2026. Todos los derechos reservados.</p>
    <div id="refresh-indicator" style="margin-top: 8px;">
      ðŸ”„ Sondeo automÃ¡tico de pedidos activos. SincronizaciÃ³n en <span id="countdown">30</span> segundos.
    </div>
  </footer>

  <script>
    // Buscador interactivo en tiempo real
    const searchInput = document.getElementById('cajaSearchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        const cards = document.querySelectorAll('#ventasPendientesGrid .venta-card');
        
        cards.forEach(card => {
          const mesa = card.dataset.mesaId.toLowerCase();
          const pedido = card.dataset.pedidoId.toLowerCase();
          
          if (!query || mesa.includes(query) || pedido.includes(query)) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        });
      });
    }

    let activeVentaTotal = 0;

    function seleccionarVenta(card) {
      // Destacar tarjeta seleccionada
      document.querySelectorAll('.venta-card').forEach(c => c.style.borderColor = 'rgba(255, 255, 255, 0.08)');
      card.style.borderColor = 'var(--accent-orange)';

      const ventaId = card.dataset.ventaId;
      const pedidoId = card.dataset.pedidoId;
      const mesaId = card.dataset.mesaId;
      const total = parseFloat(card.dataset.total);
      const items = JSON.parse(card.dataset.items);

      activeVentaTotal = total;

      // Actualizar sidebar
      document.getElementById('checkoutTitleMesa').textContent = `Mesa ${mesaId}`;
      document.getElementById('checkoutTitlePedido').textContent = `Pedido #${pedidoId}`;
      document.getElementById('checkoutTotalNum').textContent = `$${total.toFixed(2)}`;
      
      document.getElementById('checkoutFormVentaId').value = ventaId;
      document.getElementById('cancelFormVentaId').value = ventaId;

      // Limpiar calculadora
      document.getElementById('efectivoRecibido').value = '';
      const changeEl = document.getElementById('calcChangeNum');
      changeEl.textContent = '$0.00';
      changeEl.classList.remove('insufficient');

      // Cargar lista de items en sidebar
      const listContainer = document.getElementById('checkoutItemsList');
      listContainer.innerHTML = '';
      items.forEach(item => {
        const row = document.createElement('div');
        row.className = 'checkout-item-row';
        row.innerHTML = `
          <div>
            <span class="checkout-item-name">${item.nombre} <span style="color:#aaa;">x${item.cantidad}</span></span>
            ${item.notas ? `<span class="checkout-item-notes"><i class="fas fa-info-circle"></i> ${item.notas}</span>` : ''}
          </div>
          <span class="checkout-item-subtotal">$${parseFloat(item.subtotal).toFixed(2)}</span>
        `;
        listContainer.appendChild(row);
      });

      // Mostrar panel activo
      document.getElementById('checkoutPanelEmpty').style.display = 'none';
      document.getElementById('checkoutPanelActive').style.display = 'flex';
      
      // Auto focusear al input de efectivo recibido
      document.getElementById('efectivoRecibido').focus();
    }

    function agregarEfectivoRapido(monto) {
      const input = document.getElementById('efectivoRecibido');
      const val = parseFloat(input.value) || 0;
      input.value = (val + monto).toFixed(2);
      calcularCambio();
    }

    function calcularCambio() {
      const recibo = parseFloat(document.getElementById('efectivoRecibido').value) || 0;
      const changeEl = document.getElementById('calcChangeNum');

      if (recibo === 0) {
        changeEl.textContent = '$0.00';
        changeEl.classList.remove('insufficient');
        return;
      }

      const diff = recibo - activeVentaTotal;
      if (diff < 0) {
        changeEl.textContent = 'Monto Insuficiente';
        changeEl.classList.add('insufficient');
      } else {
        changeEl.textContent = `$${diff.toFixed(2)}`;
        changeEl.classList.remove('insufficient');
      }
    }

    function validarCobro() {
      const recibo = parseFloat(document.getElementById('efectivoRecibido').value) || 0;
      if (recibo < activeVentaTotal) {
        alert("El monto de efectivo recibido es insuficiente para completar la venta.");
        return false;
      }
      // Setear monto pagado real
      document.getElementById('checkoutFormMontoPagado').value = recibo;
      return true;
    }

    // Auto-refresh inteligente cada 30 segundos
    let countdown = 30;
    const countdownElement = document.getElementById('countdown');
    
    const countdownInterval = setInterval(() => {
      countdown--;
      if (countdownElement) countdownElement.textContent = countdown;
      if (countdown <= 0) {
        clearInterval(countdownInterval);
        window.location.reload();
      }
    }, 1000);
  </script>
</body>
</html>
