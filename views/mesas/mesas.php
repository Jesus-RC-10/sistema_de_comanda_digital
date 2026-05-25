<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seleccionar Mesa | SCD</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/estilos.css?v=6">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/mesas.css?v=5">
  
  <style>
    /* Estilos Premium Autocontenidos - TaquerÃ­a Mexicana Modern Dark */
    :root {
      --bg-dark: #0A0907;
      --panel-bg: rgba(22, 19, 16, 0.7);
      --card-bg-libre: rgba(30, 26, 22, 0.55);
      --card-bg-ocupada: rgba(18, 15, 13, 0.8);
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
      background-image: radial-gradient(circle at 10% 20%, rgba(211, 47, 47, 0.05) 0%, transparent 45%),
                        radial-gradient(circle at 90% 80%, rgba(255, 160, 0, 0.05) 0%, transparent 45%);
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
      z-index: 50;
    }

    .header-content {
      max-width: 1200px;
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
      color: var(--text-main);
      letter-spacing: 0.5px;
    }

    .brand i {
      color: var(--accent-red);
      filter: drop-shadow(0 0 5px rgba(229, 57, 53, 0.6));
    }

    .brand span {
      background: linear-gradient(135deg, #FFF 60%, var(--accent-orange));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    h1.header-title {
      font-size: 1.5rem;
      font-weight: 900;
      margin: 0;
      color: var(--accent-orange);
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    main {
      flex: 1;
      max-width: 1200px;
      margin: 40px auto;
      padding: 0 20px;
      width: 100%;
      box-sizing: border-box;
    }

    .mesas-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 25px;
      width: 100%;
    }

    .mesa-card {
      background: var(--card-bg-libre);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-glass);
      border-radius: 20px;
      padding: 30px 20px;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .mesa-card.libre {
      border-color: rgba(255, 255, 255, 0.08);
      cursor: pointer;
    }

    .mesa-card.libre:hover {
      transform: translateY(-8px);
      border-color: rgba(255, 160, 0, 0.4);
      box-shadow: 0 12px 35px rgba(255, 160, 0, 0.15);
      background: rgba(50, 50, 50, 0.7);
    }

    .mesa-card.ocupada {
      background: var(--card-bg-ocupada);
      border-color: rgba(229, 57, 53, 0.15);
      opacity: 0.85;
      cursor: not-allowed;
    }

    .mesa-card.ocupada::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: var(--accent-red);
    }

    .mesa-status {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    .status-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      display: inline-block;
    }

    .status-dot.green {
      background: var(--accent-green);
      box-shadow: 0 0 8px var(--accent-green);
    }

    .status-dot.red {
      background: var(--accent-red);
      box-shadow: 0 0 8px var(--accent-red);
    }

    .status-text {
      color: var(--text-muted);
    }

    .mesa-card.libre .status-text {
      color: var(--accent-green);
    }

    .mesa-card.ocupada .status-text {
      color: var(--accent-red);
    }

    .mesa-icon {
      font-size: 2.8rem;
      color: var(--accent-orange);
      margin: 8px 0;
      transition: transform 0.4s ease;
      filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));
    }

    .mesa-card.libre:hover .mesa-icon {
      transform: scale(1.18) rotate(5deg);
      color: #FFB300;
    }

    .mesa-number {
      font-size: 2.2rem;
      font-weight: 900;
      color: var(--text-main);
      letter-spacing: 1px;
    }

    .mesa-ubicacion {
      font-size: 0.85rem;
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .mesa-mesero {
      margin-top: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      padding: 6px 14px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border-glass);
      color: var(--text-muted);
      display: flex;
      align-items: center;
      gap: 8px;
      width: 90%;
      justify-content: center;
      transition: all 0.3s ease;
    }

    .mesa-mesero.asignado {
      background: rgba(67, 160, 71, 0.08);
      border-color: rgba(67, 160, 71, 0.2);
      color: #A5D6A7;
    }

    .mesa-mesero.sin-asignar {
      background: rgba(255, 160, 0, 0.06);
      border-color: rgba(255, 160, 0, 0.15);
      color: #FFE082;
      animation: pulseAlert 2s infinite alternate;
    }

    @keyframes pulseAlert {
      from { box-shadow: 0 0 2px rgba(255, 160, 0, 0.1); }
      to { box-shadow: 0 0 10px rgba(255, 160, 0, 0.25); }
    }

    .btn-cambiar-mesero {
      background: none;
      border: none;
      color: var(--accent-orange);
      font-size: 0.75rem;
      text-transform: uppercase;
      font-weight: bold;
      cursor: pointer;
      padding: 2px 6px;
      margin-top: 2px;
      border-radius: 4px;
      transition: background 0.2s;
    }

    .btn-cambiar-mesero:hover {
      background: rgba(255, 160, 0, 0.15);
      text-decoration: underline;
    }

    /* Modal Estilo Premium Glassmorphism */
    .modal-mesero-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(8px);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      animation: fadeIn 0.3s ease;
    }

    .modal-mesero-content {
      background: rgba(25, 25, 25, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 24px;
      padding: 35px 30px;
      max-width: 450px;
      width: 90%;
      box-shadow: var(--shadow-premium);
      animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
      position: relative;
    }

    .modal-mesero-content h2 {
      margin-top: 0;
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--accent-orange);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .modal-mesero-content p {
      color: var(--text-muted);
      line-height: 1.5;
      font-size: 0.95rem;
      margin-bottom: 25px;
    }

    .form-group-mesero {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 30px;
    }

    .form-group-mesero label {
      font-weight: 600;
      font-size: 0.9rem;
      color: var(--text-main);
    }

    .form-group-mesero select {
      background: #333;
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 12px;
      padding: 12px 16px;
      color: white;
      font-size: 1rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.3s;
    }

    .form-group-mesero select:focus {
      border-color: var(--accent-orange);
      box-shadow: 0 0 0 2px rgba(255, 160, 0, 0.2);
    }

    .modal-mesero-actions {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
    }

    .btn-mesero-cancelar {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.08);
      color: var(--text-main);
      padding: 12px 24px;
      border-radius: 12px;
      font-family: inherit;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-mesero-cancelar:hover {
      background: rgba(255, 255, 255, 0.15);
    }

    .btn-mesero-confirmar {
      background: linear-gradient(135deg, var(--accent-red), #B71C1C);
      border: none;
      color: white;
      padding: 12px 24px;
      border-radius: 12px;
      font-family: inherit;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(229, 57, 53, 0.3);
    }

    .btn-mesero-confirmar:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(229, 57, 53, 0.45);
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes scaleUp {
      from { transform: scale(0.9); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    /* Ocultar overlay por defecto de mesas activas */
    .mesa-overlay {
      display: none !important;
    }
  </style>
</head>
<body>
  <header>
    <div class="header-content">
      <div class="brand">
        <i class="fas fa-fire"></i>
        <span>TaquerÃ­a El InformÃ¡tico</span>
      </div>
      <h1 class="header-title">SelecciÃ³n de Mesa</h1>
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
          $nombreMesero = $mesa['nombre_mesero'] ?? null;
          $meseroId = $mesa['mesero_id'] ?? null;
        ?>
          <div class="mesa-card <?php echo $esLibre ? 'libre' : 'ocupada'; ?>" 
               onclick="procesarClickMesa(<?php echo $mesa['id']; ?>, '<?php echo $numero; ?>', '<?php echo $estado; ?>', <?php echo $meseroId ? $meseroId : 'null'; ?>)"
               style="animation-delay: <?php echo $index * 0.06; ?>s">
            
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

            <?php if ($nombreMesero): ?>
              <div class="mesa-mesero asignado">
                <i class="fas fa-user-tie"></i>
                <span><?php echo htmlspecialchars($nombreMesero); ?></span>
              </div>
              <?php if ($esLibre): ?>
                <button class="btn-cambiar-mesero" onclick="event.stopPropagation(); abrirModalMesero(<?php echo $mesa['id']; ?>, '<?php echo $numero; ?>', <?php echo $meseroId; ?>)">
                  Cambiar
                </button>
              <?php endif; ?>
            <?php else: ?>
              <div class="mesa-mesero sin-asignar">
                <i class="fas fa-user-slash"></i>
                <span>Sin Mesero</span>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="no-mesas">
          <i class="fas fa-exclamation-triangle"></i>
          <p>No hay mesas activas disponibles en el sistema.</p>
        </div>
      <?php endif; ?>
    </div>
  </main>

  <!-- FAB para Administradores -->
  <div class="help-fab" onclick="window.location.href='<?php echo BASE_URL; ?>login'" title="Panel de Control (Admin)">
    <i class="fas fa-user-shield"></i>
  </div>

  <!-- Modal de AsignaciÃ³n de Mesero -->
  <div id="meseroOverlay" class="modal-mesero-overlay" style="display: none;">
    <div class="modal-mesero-content">
      <h2><i class="fas fa-bell-concierge"></i> Inicializar Mesa</h2>
      <p id="modalMesaTexto">Selecciona el mesero que atenderÃ¡ la mesa durante este turno.</p>
      
      <form id="meseroForm" onsubmit="confirmarAsignacion(event)">
        <input type="hidden" id="modalMesaId">
        <div class="form-group-mesero">
          <label for="mesero_select">Mesero en Turno:</label>
          <select id="mesero_select" required>
            <option value="">-- Seleccionar Mesero --</option>
            <?php foreach ($data['meseros'] as $mes): ?>
              <option value="<?php echo $mes['id']; ?>">
                <?php echo htmlspecialchars($mes['nombre']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="modal-mesero-actions">
          <button type="button" class="btn-mesero-cancelar" onclick="cerrarMeseroModal()">Cancelar</button>
          <button type="submit" class="btn-mesero-confirmar">Asignar e Ir al MenÃº</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
    
    function procesarClickMesa(idMesa, numeroMesa, estado, meseroId) {
      if (estado !== 'libre') {
        showGlobalNotification("Esta mesa estÃ¡ ocupada preparando o consumiendo alimentos.", "error");
        return;
      }
      
      // Si no tiene mesero asignado, es obligatorio inicializarla asignando un mesero
      if (!meseroId) {
        abrirModalMesero(idMesa, numeroMesa, null);
      } else {
        // Si ya estÃ¡ asignada, vamos directo al menÃº de esa mesa
        window.location.href = BASE_URL + "index.php?url=menu&mesa=" + idMesa;
      }
    }

    function abrirModalMesero(idMesa, numeroMesa, meseroIdActual) {
      document.getElementById('modalMesaId').value = idMesa;
      document.getElementById('modalMesaTexto').innerHTML = `Para iniciar el turno de la <strong>Mesa ${numeroMesa}</strong>, por favor asigna el mesero encargado.`;
      
      const select = document.getElementById('mesero_select');
      select.value = meseroIdActual ? meseroIdActual : "";
      
      document.getElementById('meseroOverlay').style.display = 'flex';
    }

    function cerrarMeseroModal() {
      document.getElementById('meseroOverlay').style.display = 'none';
    }

    function confirmarAsignacion(event) {
      event.preventDefault();
      
      const idMesa = document.getElementById('modalMesaId').value;
      const idMesero = document.getElementById('mesero_select').value;
      
      if (!idMesa || !idMesero) return;
      
      const formData = new FormData();
      formData.append('mesa_id', idMesa);
      formData.append('mesero_id', idMesero);
      
      fetch(BASE_URL + "mesa/asignarMesero", {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          cerrarMeseroModal();
          // Redirigir al menÃº ya inicializado
          window.location.href = BASE_URL + "index.php?url=menu&mesa=" + idMesa;
        } else {
          showGlobalNotification("Error al asignar el mesero en el servidor.", "error");
        }
      })
      .catch(err => {
        console.error("Error:", err);
        showGlobalNotification("Error de conexiÃ³n con el servidor.", "error");
      });
    }

    function showGlobalNotification(message, type = "success") {
      const existing = document.querySelector('.global-notification');
      if (existing) existing.remove();

      const notif = document.createElement('div');
      notif.className = 'global-notification';
      notif.textContent = message;
      notif.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: ${type === 'success' ? '#43A047' : '#D32F2F'};
        color: white;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        z-index: 1100;
        transition: all 0.3s;
        font-size: 0.95rem;
      `;
      document.body.appendChild(notif);
      setTimeout(() => {
        notif.style.opacity = '0';
        setTimeout(() => notif.remove(), 300);
      }, 3500);
    }
  </script>
</body>
</html>

