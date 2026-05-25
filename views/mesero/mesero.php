<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Mesero | SCD</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/estilos.css?v=6">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/mesero-ayuda.css?v=5">

  <style>
    /* === Premium Dashboard â€” Mesero === */
    :root {
      --bg-dark: #0A0907;
      --bg-surface: #12100E;
      --panel-bg: rgba(22, 19, 16, 0.7);
      --card-bg: rgba(35, 30, 26, 0.65);
      --accent-red: #D32F2F;
      --accent-orange: #FFA000;
      --accent-green: #43A047;
      --accent-blue: #42A5F5;
      --text-main: #F5F5F0;
      --text-muted: #9E9A90;
      --text-dim: #6E6A62;
      --border-glass: rgba(255, 255, 255, 0.08);
      --shadow-premium: 0 15px 35px rgba(0, 0, 0, 0.6);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background-color: var(--bg-dark);
      background-image:
        radial-gradient(circle at 10% 10%, rgba(211, 47, 47, 0.04) 0%, transparent 45%),
        radial-gradient(circle at 90% 90%, rgba(67, 160, 71, 0.04) 0%, transparent 45%);
      color: var(--text-main);
      font-family: 'Outfit', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* === HEADER === */
    header {
      background: rgba(10, 9, 7, 0.92);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--border-glass);
      padding: 15px 30px;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header-content {
      max-width: 1400px;
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
      font-size: 1.35rem;
      font-weight: 800;
    }

    .brand i {
      color: var(--accent-orange);
      filter: drop-shadow(0 0 6px rgba(255, 160, 0, 0.45));
    }

    .brand span {
      background: linear-gradient(135deg, #FFF 60%, var(--accent-orange));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .header-center {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.2rem;
      font-weight: 900;
      color: var(--accent-red);
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .header-center i {
      filter: drop-shadow(0 0 4px rgba(229, 57, 53, 0.4));
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .btn-help {
      position: relative;
      background: rgba(255, 160, 0, 0.12);
      border: 1px solid rgba(255, 160, 0, 0.25);
      color: var(--accent-orange);
      padding: 9px 18px;
      border-radius: 12px;
      cursor: pointer;
      font-weight: 700;
      font-size: 0.9rem;
      font-family: inherit;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
    }

    .btn-help:hover {
      background: rgba(255, 160, 0, 0.25);
      border-color: var(--accent-orange);
      transform: translateY(-1px);
      box-shadow: 0 4px 15px rgba(255, 160, 0, 0.2);
    }

    .btn-exit {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid var(--border-glass);
      color: var(--text-muted);
      padding: 9px 18px;
      border-radius: 12px;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.85rem;
      font-family: inherit;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.3s;
    }

    .btn-exit:hover {
      background: var(--accent-red);
      color: white;
      border-color: var(--accent-red);
    }

    /* === STATS BAR === */
    .stats-bar {
      max-width: 1400px;
      margin: 20px auto 0;
      padding: 0 30px;
      width: 100%;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }

    .stat-chip {
      background: rgba(30, 30, 30, 0.6);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-glass);
      border-radius: 14px;
      padding: 12px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
      font-size: 0.9rem;
      color: var(--text-muted);
      min-width: 140px;
    }

    .stat-chip .stat-number {
      font-size: 1.5rem;
      font-weight: 900;
      color: white;
    }

    .stat-chip.stat-activos .stat-number { color: var(--accent-orange); }
    .stat-chip.stat-listos .stat-number { color: var(--accent-green); }
    .stat-chip.stat-total .stat-number { color: var(--accent-blue); }

    .stat-chip i {
      font-size: 1.2rem;
      opacity: 0.6;
    }

    /* === MAIN GRID === */
    .pedidos-main {
      flex: 1;
      max-width: 1400px;
      margin: 25px auto;
      padding: 0 30px;
      width: 100%;
    }

    .section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid var(--border-glass);
    }

    .section-title {
      font-size: 1.3rem;
      font-weight: 800;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .section-title i { color: var(--accent-red); }

    .last-update {
      font-size: 0.8rem;
      color: var(--text-dim);
      font-weight: 400;
    }

    .pedidos-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
      gap: 22px;
    }

    /* === PEDIDO CARD === */
    .pedido-card {
      background: var(--card-bg);
      backdrop-filter: blur(12px);
      border: 1px solid var(--border-glass);
      border-radius: 18px;
      overflow: hidden;
      transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      position: relative;
    }

    .pedido-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.45);
      border-color: rgba(255, 255, 255, 0.12);
    }

    /* --- LISTO glow animation --- */
    .pedido-card.pedido-listo {
      border-color: rgba(67, 160, 71, 0.5);
      background: rgba(30, 55, 30, 0.7);
      animation: pulseGlow 1.8s ease-in-out infinite;
    }

    .pedido-card.pedido-listo .pedido-card-header {
      background: linear-gradient(135deg, rgba(67, 160, 71, 0.3), rgba(67, 160, 71, 0.1));
    }

    /* Banner LISTO */
    .ready-banner {
      background: linear-gradient(135deg, #43A047, #2E7D32);
      color: white;
      text-align: center;
      padding: 10px 16px;
      font-weight: 900;
      font-size: 0.95rem;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      animation: readyBanner 0.4s ease;
    }

    .ready-banner i {
      font-size: 1rem;
      animation: bellShake 1s ease-in-out infinite;
    }

    /* Card Header */
    .pedido-card-header {
      padding: 16px 20px 12px;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }

    .pedido-card-meta {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .pedido-card-title {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .mesa-badge {
      background: linear-gradient(135deg, var(--accent-red), #c62828);
      color: white;
      padding: 4px 12px;
      border-radius: 8px;
      font-weight: 800;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }

    .pedido-num {
      color: var(--text-dim);
      font-weight: 600;
      font-size: 0.85rem;
    }

    .pedido-time {
      font-size: 0.78rem;
      color: var(--text-dim);
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .estado-badge {
      padding: 5px 12px;
      border-radius: 10px;
      font-size: 0.75rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      white-space: nowrap;
    }

    .estado-pendiente {
      background: rgba(255, 160, 0, 0.15);
      color: #FFB74D;
      border: 1px solid rgba(255, 160, 0, 0.3);
    }

    .estado-confirmado {
      background: rgba(66, 165, 245, 0.15);
      color: #90CAF9;
      border: 1px solid rgba(66, 165, 245, 0.3);
    }

    .estado-en_preparacion {
      background: rgba(255, 160, 0, 0.2);
      color: #FFD54F;
      border: 1px solid rgba(255, 160, 0, 0.35);
    }

    .estado-listo {
      background: rgba(67, 160, 71, 0.2);
      color: #A5D6A7;
      border: 1px solid rgba(67, 160, 71, 0.4);
    }

    .estado-entregado {
      background: rgba(255, 255, 255, 0.06);
      color: var(--text-dim);
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* Card Body â€” items list */
    .pedido-card-body {
      padding: 14px 20px;
    }

    .detalle-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .detalle-row {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 10px;
      padding: 8px 12px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.03);
      transition: all 0.2s;
    }

    .detalle-row:hover {
      background: rgba(255, 255, 255, 0.05);
    }

    .detalle-info {
      flex: 1;
      min-width: 0;
    }

    .detalle-name {
      font-weight: 600;
      font-size: 0.92rem;
      color: var(--text-main);
      display: block;
    }

    .detalle-qty {
      font-size: 0.8rem;
      color: var(--text-dim);
      margin-top: 2px;
    }

    .detalle-notas {
      margin-top: 4px;
      font-size: 0.78rem;
      font-weight: 700;
      color: #FFD54F;
      display: flex;
      align-items: center;
      gap: 5px;
      background: rgba(255, 160, 0, 0.08);
      padding: 3px 8px;
      border-radius: 6px;
      border-left: 3px solid var(--accent-orange);
    }

    .detalle-estado-pill {
      font-size: 0.7rem;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 8px;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      white-space: nowrap;
      flex-shrink: 0;
      margin-top: 2px;
    }

    .pill-pendiente {
      background: rgba(255, 160, 0, 0.12);
      color: #FFB74D;
    }

    .pill-en_preparacion {
      background: rgba(255, 213, 79, 0.12);
      color: #FFD54F;
    }

    .pill-listo {
      background: rgba(67, 160, 71, 0.18);
      color: #81C784;
    }

    .pill-entregado {
      background: rgba(255, 255, 255, 0.05);
      color: var(--text-dim);
    }

    /* Card Footer */
    .pedido-card-footer {
      padding: 12px 20px 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid rgba(255, 255, 255, 0.04);
    }

    .pedido-total {
      font-size: 1.3rem;
      font-weight: 900;
      color: var(--accent-green);
    }

    .pedido-total small {
      font-size: 0.75rem;
      color: var(--text-dim);
      font-weight: 400;
      margin-right: 4px;
    }

    .btn-entregar {
      background: linear-gradient(135deg, #43A047, #2E7D32);
      border: none;
      color: white;
      padding: 10px 22px;
      border-radius: 12px;
      font-weight: 800;
      font-size: 0.88rem;
      font-family: inherit;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
      letter-spacing: 0.3px;
    }

    .btn-entregar:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(67, 160, 71, 0.4);
    }

    .btn-entregar:active {
      transform: translateY(0);
    }

    .btn-entregar:disabled {
      background: rgba(255, 255, 255, 0.06);
      color: var(--text-dim);
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .btn-entregar.btn-pending {
      background: linear-gradient(135deg, rgba(255, 160, 0, 0.2), rgba(255, 160, 0, 0.08));
      border: 1px solid rgba(255, 160, 0, 0.25);
      color: var(--accent-orange);
    }

    /* === EMPTY STATE === */
    .empty-state {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 80px 30px;
      color: var(--text-dim);
      text-align: center;
    }

    .empty-state i {
      font-size: 3.5rem;
      margin-bottom: 15px;
      opacity: 0.3;
      color: var(--accent-green);
    }

    .empty-state p {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--text-muted);
    }

    .empty-state small {
      display: block;
      margin-top: 6px;
      font-size: 0.85rem;
      color: var(--text-dim);
    }

    /* === ANIMATIONS === */
    @keyframes cardFadeIn {
      from { opacity: 0; transform: translateY(15px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulseGlow {
      0%, 100% { box-shadow: 0 0 12px rgba(67, 160, 71, 0.25), inset 0 0 0 1px rgba(67, 160, 71, 0.15); }
      50%      { box-shadow: 0 0 30px rgba(67, 160, 71, 0.5), inset 0 0 0 1px rgba(67, 160, 71, 0.4); }
    }

    @keyframes readyBanner {
      from { transform: translateY(-8px); opacity: 0; }
      to   { transform: translateY(0); opacity: 1; }
    }

    @keyframes bellShake {
      0%   { transform: rotate(0); }
      15%  { transform: rotate(14deg); }
      30%  { transform: rotate(-14deg); }
      45%  { transform: rotate(10deg); }
      60%  { transform: rotate(-6deg); }
      75%  { transform: rotate(3deg); }
      100% { transform: rotate(0); }
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
      header { padding: 12px 16px; }
      .brand { font-size: 1.1rem; }
      .header-center { font-size: 0.95rem; }
      .stats-bar { padding: 0 16px; gap: 10px; }
      .stat-chip { padding: 10px 14px; min-width: 110px; }
      .stat-chip .stat-number { font-size: 1.2rem; }
      .pedidos-main { padding: 0 16px; }
      .pedidos-grid { grid-template-columns: 1fr; gap: 16px; }
      .header-actions { gap: 8px; }
      .btn-help, .btn-exit { padding: 8px 12px; font-size: 0.8rem; }
    }

    /* Toast confirmation */
    .toast-confirm {
      position: fixed;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%) translateY(20px);
      background: rgba(67, 160, 71, 0.95);
      color: white;
      padding: 14px 28px;
      border-radius: 14px;
      font-weight: 700;
      font-size: 0.95rem;
      z-index: 2000;
      box-shadow: 0 8px 30px rgba(67, 160, 71, 0.4);
      display: flex;
      align-items: center;
      gap: 10px;
      animation: toastIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
      font-family: 'Outfit', sans-serif;
    }

    @keyframes toastIn {
      from { transform: translateX(-50%) translateY(30px); opacity: 0; }
      to   { transform: translateX(-50%) translateY(0); opacity: 1; }
    }

    @keyframes toastOut {
      from { transform: translateX(-50%) translateY(0); opacity: 1; }
      to   { transform: translateX(-50%) translateY(30px); opacity: 0; }
    }
  </style>
</head>
<body>

  <!-- ============ HEADER ============ -->
  <header>
    <div class="header-content">
      <div class="brand">
        <i class="fas fa-utensils"></i>
        <span>Taquería El Informático</span>
      </div>
      <div class="header-center">
        <i class="fas fa-bell-concierge"></i>
        <span>Panel de Mesero</span>
      </div>
      <div class="header-actions">
        <button class="btn-help" id="botonAyuda">
          <i class="fas fa-bell"></i> Ayuda
        </button>
        <a href="<?php echo BASE_URL; ?>index.php?action=login" class="btn-exit">
          <i class="fas fa-right-from-bracket"></i> Salir
        </a>
      </div>
    </div>
  </header>

  <!-- ============ STATS BAR ============ -->
  <div class="stats-bar" id="statsBar">
    <div class="stat-chip stat-activos">
      <i class="fas fa-fire"></i>
      <div>
        <div class="stat-number" id="statActivos">0</div>
        <div>Activos</div>
      </div>
    </div>
    <div class="stat-chip stat-listos">
      <i class="fas fa-check-double"></i>
      <div>
        <div class="stat-number" id="statListos">0</div>
        <div>Listos</div>
      </div>
    </div>
    <div class="stat-chip stat-total">
      <i class="fas fa-coins"></i>
      <div>
        <div class="stat-number" id="statTotal">$0</div>
        <div>En cuenta</div>
      </div>
    </div>
  </div>

  <!-- ============ MAIN CONTENT ============ -->
  <div class="pedidos-main">
    <div class="section-header">
      <h2 class="section-title">
        <i class="fas fa-clipboard-list"></i> Pedidos Activos
      </h2>
      <span class="last-update" id="lastUpdate"></span>
    </div>

    <div id="pedidosContainer">
      <?php if (empty($data['pedidos'])): ?>
        <div class="empty-state">
          <i class="fas fa-check-circle"></i>
          <p>No hay pedidos activos</p>
          <small>Los nuevos pedidos aparecerán automáticamente</small>
        </div>
      <?php else: ?>
        <div class="pedidos-grid">
          <?php foreach ($data['pedidos'] as $pedido):
            $mid = $pedido['mesa_id'] ?? '?';
            $isListo = ($pedido['estado'] === 'listo');
          ?>
            <div class="pedido-card <?php echo $isListo ? 'pedido-listo' : ''; ?>"
                 data-pedido-id="<?php echo $pedido['id']; ?>"
                 style="animation: cardFadeIn 0.4s ease both;">

              <?php if ($isListo): ?>
                <div class="ready-banner">
                  <i class="fas fa-bell"></i> ¡PEDIDO LISTO PARA ENTREGAR!
                </div>
              <?php endif; ?>

              <div class="pedido-card-header">
                <div class="pedido-card-meta">
                  <div class="pedido-card-title">
                    <span class="mesa-badge"><i class="fas fa-chair"></i> Mesa <?php echo $mid; ?></span>
                    <span class="pedido-num">#<?php echo $pedido['id']; ?></span>
                  </div>
                  <div class="pedido-time">
                    <i class="fas fa-clock"></i>
                    <span class="order-time" data-time="<?php echo $pedido['fecha_creacion']; ?>">
                      <?php echo date('H:i', strtotime($pedido['fecha_creacion'])); ?>
                    </span>
                  </div>
                </div>
                <span class="estado-badge estado-<?php echo $pedido['estado']; ?>">
                  <?php echo strtoupper(str_replace('_', ' ', $pedido['estado'])); ?>
                </span>
              </div>

              <div class="pedido-card-body">
                <ul class="detalle-list">
                  <?php foreach ($pedido['detalles'] as $detalle): ?>
                    <li class="detalle-row">
                      <div class="detalle-info">
                        <span class="detalle-name"><?php echo htmlspecialchars($detalle['nombre']); ?></span>
                        <span class="detalle-qty">x<?php echo $detalle['cantidad']; ?> â€” $<?php echo number_format($detalle['subtotal'], 2); ?></span>
                        <?php if (!empty($detalle['notas'])): ?>
                          <div class="detalle-notas">
                            <i class="fas fa-pepper-hot"></i> <?php echo htmlspecialchars($detalle['notas']); ?>
                          </div>
                        <?php endif; ?>
                      </div>
                      <span class="detalle-estado-pill pill-<?php echo $detalle['estado']; ?>">
                        <?php echo strtoupper(str_replace('_', ' ', $detalle['estado'])); ?>
                      </span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>

              <div class="pedido-card-footer">
                <div class="pedido-total">
                  <small>Total</small>$<?php echo number_format($pedido['total'], 2); ?>
                </div>
                <?php if ($isListo): ?>
                  <button class="btn-entregar" onclick="entregarPedido(<?php echo $pedido['id']; ?>, this)">
                    <i class="fas fa-hand-holding-heart"></i> Entregar
                  </button>
                <?php else: ?>
                  <button class="btn-entregar btn-pending" disabled>
                    <i class="fas fa-hourglass-half"></i> En proceso...
                  </button>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- ============ POPUP DE AYUDA ============ -->
  <div class="ayuda-popup" id="ayudaPopup" style="display: none;">
    <button class="cerrar-ayuda" id="cerrarAyuda">&times;</button>
    <h3>Solicitudes de Ayuda</h3>
    <div id="listaMesas">
      <!-- Se rellena via AJAX -->
    </div>
  </div>

  <!-- ============ SCRIPTS ============ -->
  <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
    const MESERO_URL = '<?php echo BASE_URL; ?>index.php?url=mesero/';
  </script>

  <script src="<?php echo BASE_URL; ?>assets/js/mesero.js?v=5"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/mesero-ayuda.js?v=5"></script>
</body>
</html>
