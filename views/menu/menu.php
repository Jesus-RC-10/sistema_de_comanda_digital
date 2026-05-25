<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú - Mesa <?php echo $data['mesa']; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/estilos.css?v=6">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/help-buttons.css?v=1">
</head>
<body>
  <header>
    <div class="header-top">
      <div class="header-brand">
        <i class="fas fa-utensils"></i>
        <span>Taquería El Informático</span>
      </div>
      <div class="header-mesa">
        <i class="fas fa-chair"></i>
        <span>Mesa <?php echo str_pad($data['mesa'], 2, '0', STR_PAD_LEFT); ?></span>
      </div>
    </div>
    <div class="search-container">
      <i class="fas fa-search search-icon"></i>
      <input type="text" class="search-bar" id="searchInput" placeholder="Buscar en el menú..." autocomplete="off">
    </div>
  </header>

  <main>
    <?php 
    $categorias = [];
    $categoriaIconos = [
      'Tacos' => '<i class="fas fa-taco" style="font-weight:900;">ðŸŒ®</i>',
      'Bebidas' => '<i class="fas fa-wine-bottle"></i>',
      'Postres' => '<i class="fas fa-cake"></i>',
    ];
    $categoriaDefaultIcon = '<i class="fas fa-utensil-spoon"></i>';

    foreach ($data['productos'] as $producto) {
        $cat = $producto['categoria'];
        if (!isset($categorias[$cat])) {
            $categorias[$cat] = [];
        }
        $categorias[$cat][] = $producto;
    }
    $catKeys = array_keys($categorias);
    ?>

    <div class="category-tabs">
      <button class="category-tab active" data-category="all">
        <i class="fas fa-th-large"></i>
        Todo
      </button>
      <?php foreach ($catKeys as $cat): 
        $icono = $categoriaIconos[$cat] ?? $categoriaDefaultIcon;
      ?>
        <button class="category-tab" data-category="<?php echo htmlspecialchars($cat); ?>">
          <?php echo $icono; ?>
          <?php echo htmlspecialchars($cat); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <?php foreach ($categorias as $categoria => $productos): ?>
      <section class="menu-section" data-category="<?php echo htmlspecialchars($categoria); ?>">
        <h2><?php echo htmlspecialchars($categoria); ?></h2>
        <div class="menu-items">
          <?php foreach ($productos as $p): 
            $imagen = $p['imagen'] ?? '';
            $imgPath = '';
            $tieneImagen = false;
            if ($imagen && file_exists(__DIR__ . '/../../public/images/platillos/' . $imagen)) {
                $imgPath = BASE_URL . 'public/images/platillos/' . $imagen;
                $tieneImagen = true;
            }
            $nombre = htmlspecialchars($p['nombre']);
            $descripcion = htmlspecialchars($p['descripcion'] ?? '');
            $precio = number_format($p['precio'], 2);
            $tiempo = $p['tiempo_preparacion'] ?? 15;
            $stock = $p['stock'] ?? 0;
            $sinStock = $stock <= 0;
          ?>
            <div class="menu-item <?php echo $sinStock ? 'out-of-stock' : ''; ?>" data-nombre="<?php echo mb_strtolower($nombre, 'UTF-8'); ?>">
              <div class="item-image">
                <?php if ($tieneImagen): ?>
                  <img src="<?php echo $imgPath; ?>" alt="<?php echo $nombre; ?>" loading="lazy">
                <?php else: ?>
                  <div class="item-image-placeholder">
                    <span><?php echo strtoupper(substr($p['nombre'], 0, 2)); ?></span>
                  </div>
                <?php endif; ?>
                <?php if ($sinStock): ?>
                  <div class="stock-badge out">Sin stock</div>
                <?php elseif ($stock > 0 && $stock <= 5): ?>
                  <div class="stock-badge low">Stock: <?php echo $stock; ?></div>
                <?php endif; ?>
                <div class="prep-time">
                  <i class="fas fa-clock"></i> <?php echo $tiempo; ?> min
                </div>
              </div>
              <h3><?php echo $nombre; ?></h3>
              <?php if ($descripcion): ?>
                <p class="item-desc"><?php echo $descripcion; ?></p>
              <?php endif; ?>
              <p class="price">$<?php echo $precio; ?></p>
              <button class="add-to-cart" 
                      data-id="<?php echo $p['id']; ?>" 
                      data-nombre="<?php echo $nombre; ?>" 
                      data-precio="<?php echo $p['precio']; ?>"
                      <?php echo $sinStock ? 'disabled' : ''; ?>>
                <?php if ($sinStock): ?>
                  <i class="fas fa-times-circle"></i> No disponible
                <?php else: ?>
                  <i class="fas fa-plus-circle"></i> Agregar
                <?php endif; ?>
              </button>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>

    <?php if (empty($categorias)): ?>
      <div class="no-items">
        <i class="fas fa-box-open"></i>
        <p>No hay productos disponibles en este momento</p>
      </div>
    <?php endif; ?>
  </main>

  <!-- Botones flotantes -->
  <div class="help-buttons">
    <button class="help-btn" id="helpBtn" title="Ayuda">
      <i class="fas fa-question-circle"></i>
    </button>
    <button class="assistance-btn" id="assistanceBtn" title="Solicitar Asistencia">
      <i class="fas fa-bell"></i>
    </button>
  </div>

  <!-- Modal de ayuda -->
  <div class="modal" id="helpModal">
    <div class="modal-content help-modal">
      <div class="modal-header">
        <h1>¿Cómo usar el menú?</h1>
        <span class="close-modal" id="closeHelpModal">&times;</span>
      </div>
      <div class="help-content">
        <ul>
          <li><i class="fas fa-search"></i> Usa el buscador para encontrar productos rápido</li>
          <li><i class="fas fa-folder-open"></i> Filtra por categorías con las pestañas</li>
          <li><i class="fas fa-mouse-pointer"></i> Haz clic en "Agregar" para añadir items a tu pedido</li>
          <li><i class="fas fa-shopping-cart"></i> Usa el carrito para ver y modificar tu pedido</li>
          <li><i class="fas fa-plus-minus"></i> Puedes ajustar cantidades con los botones + y -</li>
          <li><i class="fas fa-paper-plane"></i> Presiona "FINALIZAR PEDIDO" cuando termines</li>
        </ul>
      </div>
      <div class="modal-buttons">
        <button class="modal-btn" id="closeHelpBtn">Entendido</button>
      </div>
    </div>
  </div>

  <!-- Modal de Personalización de Platillo (Ingredientes y Notas) -->
  <div class="modal" id="customiseModal" style="display: none; justify-content: center; align-items: center; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); z-index: 1000; backdrop-filter: blur(5px);">
    <div class="modal-content" style="background: #1e1e1e; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; padding: 30px; max-width: 450px; width: 90%; color: white; max-height: 85vh; overflow-y: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
      <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 20px;">
        <h2 id="customiseTitle" style="margin: 0; font-size: 1.4rem; color: #FFA000; font-weight: 800; display: flex; align-items: center; gap: 10px;"><i class="fas fa-sliders"></i> Personalizar Platillo</h2>
        <span class="close-modal" id="closeCustomiseModal" style="font-size: 2rem; cursor: pointer; color: #aaa; line-height: 1;">&times;</span>
      </div>
      <div id="customiseBody">
        <p style="color: #bbb; font-size: 0.95rem; margin-bottom: 20px;">Elige qué ingredientes deseas quitar de tu preparación:</p>
        
        <!-- Contenedor dinámico de ingredientes -->
        <div id="ingredientsListContainer" style="display: flex; flex-direction: column; gap: 15px; margin-bottom: 25px;">
          <!-- Cargando... -->
        </div>

        <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 25px;">
          <label for="customiseNotes" style="font-weight: 600; font-size: 0.9rem;">Notas especiales / Instrucciones:</label>
          <textarea id="customiseNotes" placeholder="Ej. Bien dorado, sin picante, etc." style="background: #2a2a2a; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 12px; color: white; font-family: inherit; font-size: 0.95rem; resize: vertical; min-height: 70px; outline: none; width: 100%; box-sizing: border-box;"></textarea>
        </div>
      </div>
      <div class="modal-buttons" style="display: flex; justify-content: flex-end; gap: 12px;">
        <button class="modal-btn" id="cancelCustomiseBtn" style="background: rgba(255,255,255,0.08); color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 600; cursor: pointer; font-family: inherit;">Cancelar</button>
        <button class="modal-btn" id="confirmCustomiseBtn" style="background: linear-gradient(135deg, #E53935, #D32F2F); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(229,57,53,0.3); font-family: inherit;">Agregar al Carrito</button>
      </div>
    </div>
  </div>


  <!-- Carrito flotante -->
  <div class="floating-cart" id="floatingCart">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-badge" id="cartBadge">0</span>
  </div>

  <!-- Modal del carrito -->
  <div class="cart-modal" id="cartModal">
    <div class="cart-content">
      <div class="cart-header">
        <h2>Tu Pedido - Mesa <?php echo $data['mesa']; ?></h2>
        <span class="close-cart" id="closeCart">&times;</span>
      </div>
      <div class="cart-items" id="cartItemsContainer">
      </div>
      <div class="cart-total">
        <span>Total:</span>
        <span id="cartTotal">$0.00</span>
      </div>
      <form method="POST" action="<?php echo BASE_URL; ?>index.php?url=menu/confirmar" id="pedidoForm">
        <input type="hidden" name="mesa_id" value="<?php echo $data['mesa']; ?>">
        <input type="hidden" name="items" id="cartData">
        <button type="button" class="checkout-btn" id="checkoutBtn">FINALIZAR PEDIDO</button>
      </form>
    </div>
  </div>

  <!-- Modal de confirmación de pedido -->
  <div class="modal" id="orderModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Ticket de Pedido</h2>
        <span class="close-modal" id="closeOrderModal">&times;</span>
      </div>
      <div class="order-details" id="orderDetails">
      </div>
      <div class="modal-buttons">
        <button class="modal-btn" id="downloadTicket">Descargar Ticket</button>
        <button class="modal-btn" id="continueOrdering">Seguir Pidiendo</button>
        <button class="modal-btn" id="closeTicket">Cerrar Ticket</button>
      </div>
    </div>
  </div>

  <script>
    const BASE_URL = '<?php echo BASE_URL; ?>';
    const MENU_URL = '<?php echo BASE_URL; ?>index.php?url=menu';
    const CAJA_URL = '<?php echo BASE_URL; ?>index.php?url=caja';
    const COCINA_URL = '<?php echo BASE_URL; ?>index.php?url=cocina/actualizarDetalle';
    const MESA_NUMBER = '<?php echo $data['mesa']; ?>';
  </script>

  <script>
    // Tabs de categorías
    document.querySelectorAll('.category-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const category = tab.dataset.category;
        document.querySelectorAll('.menu-section').forEach(section => {
          if (category === 'all' || section.dataset.category === category) {
            section.style.display = '';
            section.style.opacity = '0';
            setTimeout(() => { section.style.opacity = '1'; }, 20);
          } else {
            section.style.display = 'none';
          }
        });
      });
    });

    // Normalizar texto (quitar acentos, pasar a minúsculas)
    function normalizeText(text) {
      return text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
      searchInput.addEventListener('input', function() {
        const query = normalizeText(this.value);
        const activeTab = document.querySelector('.category-tab.active');
        const activeCategory = activeTab ? activeTab.dataset.category : 'all';

        document.querySelectorAll('.menu-item').forEach(item => {
          const nombre = normalizeText(item.dataset.nombre || '');
          const match = !query || nombre.includes(query);
          if (match) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });

        document.querySelectorAll('.menu-section').forEach(section => {
          const hiddenItems = section.querySelectorAll('.menu-item[style*="display: none"]');
          const allItems = section.querySelectorAll('.menu-item');
          const allHidden = allItems.length > 0 && allItems.length === hiddenItems.length;
          const categoryMatch = activeCategory === 'all' || section.dataset.category === activeCategory;

          if (allHidden) {
            section.style.display = 'none';
          } else if (query) {
            section.style.display = categoryMatch ? '' : 'none';
          } else {
            section.style.display = categoryMatch ? '' : 'none';
          }
        });
      });
    }
  </script>

  <script src="<?php echo ASSETS_URL; ?>js/carrito.js?v=5"></script>
  <script src="<?php echo ASSETS_URL; ?>js/help-buttons.js"></script>
</body>
</html>

