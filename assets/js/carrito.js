// Variables globales
const cart = [];
const cartItemsContainer = document.getElementById('cartItemsContainer');
const cartTotal = document.getElementById('cartTotal');
const cartBadge = document.getElementById('cartBadge');
const cartData = document.getElementById('cartData');
const floatingCart = document.getElementById('floatingCart');
const cartModal = document.getElementById('cartModal');
const closeCart = document.getElementById('closeCart');
const checkoutBtn = document.getElementById('checkoutBtn');
const pedidoForm = document.getElementById('pedidoForm');
const orderModal = document.getElementById('orderModal');
const closeOrderModal = document.getElementById('closeOrderModal');
const orderDetails = document.getElementById('orderDetails');
const downloadTicketBtn = document.getElementById('downloadTicket');
const continueOrderingBtn = document.getElementById('continueOrdering');
const closeTicketBtn = document.getElementById('closeTicket');

// Modal de personalización
const customiseModal = document.getElementById('customiseModal');
const closeCustomiseModal = document.getElementById('closeCustomiseModal');
const cancelCustomiseBtn = document.getElementById('cancelCustomiseBtn');
const confirmCustomiseBtn = document.getElementById('confirmCustomiseBtn');
const ingredientsListContainer = document.getElementById('ingredientsListContainer');
const customiseTitle = document.getElementById('customiseTitle');
const customiseNotes = document.getElementById('customiseNotes');

// Objeto temporal para el producto que se está personalizando
let itemBajoPersonalizacion = null;
let currentPedidoId = null;

// Event Listeners para botones "Agregar"
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;
        const precio = parseFloat(btn.dataset.precio);

        itemBajoPersonalizacion = { id, nombre, precio };

        // Intentar cargar ingredientes del producto para personalizar
        fetch(BASE_URL + 'index.php?url=menu/obtenerIngredientesProducto&id=' + id)
            .then(res => res.json())
            .then(data => {
                const ingredientes = (data.success && data.ingredientes) ? data.ingredientes : [];
                // Siempre abrir el modal de personalización, incluso sin ingredientes configurados
                abrirModalPersonalizacion(ingredientes);
            })
            .catch(err => {
                console.error("Error al cargar ingredientes:", err);
                abrirModalPersonalizacion([]);
            });
    });
});

// Función para agregar al carrito directo sin modal
function agregarAlCarritoDirecto() {
    const item = itemBajoPersonalizacion;
    if (!item) return;

    // Buscar si ya existe un item idéntico (sin notas) en el carrito
    const existingItem = cart.find(x => x.id === item.id && !x.notas);
    
    if (existingItem) {
        existingItem.cantidad++;
    } else {
        cart.push({ 
            id: item.id, 
            nombre: item.nombre, 
            precio: item.precio, 
            cantidad: 1,
            notas: "",
            uniqueKey: item.id + "_" + Date.now()
        });
    }
    
    renderCart();
    showNotification(`${item.nombre} agregado al carrito`);
    itemBajoPersonalizacion = null;
}

// Abrir modal de personalización
function abrirModalPersonalizacion(ingredientes) {
    customiseTitle.innerHTML = `<i class="fas fa-sliders"></i> Personalizar ${itemBajoPersonalizacion.nombre}`;
    ingredientsListContainer.innerHTML = '';
    customiseNotes.value = '';

    if (ingredientes && ingredientes.length > 0) {
        ingredientes.forEach(ing => {
            const div = document.createElement('div');
            div.style.cssText = `
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 14px;
                background: rgba(255,255,255,0.04);
                border-radius: 12px;
                border: 1px solid rgba(255,255,255,0.06);
            `;
            
            div.innerHTML = `
                <span style="font-weight:600; font-size:0.95rem; color:#eee;">${ing.nombre}</span>
                <label class="switch-premium" style="position: relative; display: inline-block; width: 44px; height: 24px;">
                    <input type="checkbox" class="ingrediente-checkbox" data-nombre="${ing.nombre}" checked style="opacity: 0; width: 0; height: 0;">
                    <span class="slider-premium" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #555; transition: .3s; border-radius: 24px;"></span>
                </label>
            `;

            ingredientsListContainer.appendChild(div);

            // Estilos rápidos para el switch
            const input = div.querySelector('input');
            const slider = div.querySelector('.slider-premium');
            
            input.addEventListener('change', function() {
                if (this.checked) {
                    slider.style.backgroundColor = '#43A047';
                } else {
                    slider.style.backgroundColor = '#d32f2f';
                }
            });
            // trigger color por defecto
            slider.style.backgroundColor = '#43A047';
        });
    } else {
        const p = document.createElement('p');
        p.style.cssText = 'color: #888; font-style: italic; font-size: 0.9rem; text-align: center; margin: 10px 0;';
        p.innerHTML = '<i class="fas fa-info-circle"></i> No hay ingredientes configurados para remover en este producto.';
        ingredientsListContainer.appendChild(p);
    }

    customiseModal.style.display = 'flex';
}

function cerrarModalPersonalizacion() {
    customiseModal.style.display = 'none';
    itemBajoPersonalizacion = null;
}

if (closeCustomiseModal) closeCustomiseModal.addEventListener('click', cerrarModalPersonalizacion);
if (cancelCustomiseBtn) cancelCustomiseBtn.addEventListener('click', cerrarModalPersonalizacion);

if (confirmCustomiseBtn) {
    confirmCustomiseBtn.addEventListener('click', () => {
        const item = itemBajoPersonalizacion;
        if (!item) return;

        // Compilar ingredientes desmarcados (sin...)
        const excluidos = [];
        document.querySelectorAll('.ingrediente-checkbox').forEach(cb => {
            if (!cb.checked) {
                excluidos.push(`Sin ${cb.dataset.nombre}`);
            }
        });

        // Notas adicionales del textarea
        const extraNotes = customiseNotes.value.trim();
        if (extraNotes) {
            excluidos.push(extraNotes);
        }

        const notasCompiladas = excluidos.join(', ');

        // Buscar si ya existe un item idéntico en el carrito (mismo ID y mismas notas)
        const existingItem = cart.find(x => x.id === item.id && x.notas === notasCompiladas);

        if (existingItem) {
            existingItem.cantidad++;
        } else {
            cart.push({
                id: item.id,
                nombre: item.nombre,
                precio: item.precio,
                cantidad: 1,
                notas: notasCompiladas,
                uniqueKey: item.id + "_" + Date.now()
            });
        }

        renderCart();
        showNotification(`${item.nombre} personalizado agregado`);
        cerrarModalPersonalizacion();
    });
}

// Abrir modal del carrito
floatingCart.addEventListener('click', () => {
    cartModal.style.display = 'flex';
});

// Cerrar modal del carrito
closeCart.addEventListener('click', () => {
    cartModal.style.display = 'none';
});

// Cerrar modal al hacer clic fuera
cartModal.addEventListener('click', (e) => {
    if (e.target === cartModal) {
        cartModal.style.display = 'none';
    }
});

// Finalizar pedido
checkoutBtn.addEventListener('click', () => {
    if (cart.length === 0) {
        showNotification('El carrito está vacío');
        return;
    }
    
    // Enviar pedido a mesero y cocina
    enviarPedido();
});

// Función para enviar pedido a mesero y cocina
function enviarPedido() {
    // Actualizar el campo hidden con los datos del carrito
    cartData.value = JSON.stringify(cart);
    
    // Enviar el formulario (esto enviará a mesero y cocina)
    fetch(pedidoForm.action, {
        method: 'POST',
        body: new FormData(pedidoForm)
    })
    .then(response => response.json().then(data => ({status: response.status, body: data})))
    .then(result => {
        if (result.status >= 200 && result.status < 300 && result.body.success) {
            console.log('Pedido enviado exitosamente a mesero y cocina');
            currentPedidoId = result.body.pedido_id;
            showOrderConfirmation(result.body.ticket, result.body.total);
        } else {
            const msg = result.body.message || 'Error al enviar el pedido';
            console.error(msg);
            showNotification(msg);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error de conexión');
        showOrderConfirmation();
    });
}

// Mostrar confirmación de pedido
async function showOrderConfirmation(ticketText = null, total = null) {
    // Cerrar modal del carrito
    cartModal.style.display = 'none';
    
    let fecha = '';
    let hora = '';
    
    try {
        const response = await fetch(BASE_URL + 'index.php?url=menu/getServerDateTime');
        const data = await response.json();
        if (data.success) {
            fecha = data.fecha;
            hora = data.hora.substring(0, 5); // Mostrar solo HH:MM
        }
    } catch (error) {
        console.error('Error al obtener fecha del servidor:', error);
        const now = new Date();
        fecha = now.toLocaleDateString('es-ES');
        hora = now.toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'});
    }

    let orderHTML = '<div class="ticket-container">';
    orderHTML += '<div class="ticket-header">';
    orderHTML += '<h2>🍽️ TAQUERÍA EL INFORMÁTICO</h2>';
    orderHTML += '<div class="ticket-line"></div>';
    orderHTML += '<p class="ticket-info"><strong>Mesa:</strong> ' + (window.MESA_NUMBER || document.querySelector('.header-mesa span')?.textContent || '1') + '</p>';
    orderHTML += '<p class="ticket-info"><strong>Fecha:</strong> ' + fecha + ' | <strong>Hora:</strong> ' + hora + '</p>';
    orderHTML += '<div class="ticket-line"></div>';
    orderHTML += '</div>';

    if (ticketText) {
        orderHTML += '<div class="ticket-body"><pre class="ticket-text">' + ticketText + '</pre></div>';
    } else {
        orderHTML += '<div class="ticket-body">';
        orderHTML += '<div class="ticket-items">';
        let totalCalc = 0;
        cart.forEach(item => {
            const itemTotal = item.precio * item.cantidad;
            totalCalc += itemTotal;
            orderHTML += '<div class="ticket-item">';
            orderHTML += '<span class="item-name">' + item.nombre + (item.notas ? ` (${item.notas})` : '') + '</span>';
            orderHTML += '<span class="item-qty">x' + item.cantidad + '</span>';
            orderHTML += '<span class="item-price">$' + itemTotal.toFixed(2) + '</span>';
            orderHTML += '</div>';
        });
        orderHTML += '</div>';
        orderHTML += '<div class="ticket-line"></div>';
        orderHTML += '<div class="ticket-total"><strong>TOTAL: $' + (total || totalCalc).toFixed(2) + '</strong></div>';
        orderHTML += '</div>';
    }

    orderHTML += '<div class="ticket-footer">';
    orderHTML += '<div class="ticket-line"></div>';
    orderHTML += '<p class="ticket-thanks">¡Gracias por tu pedido!</p>';
    orderHTML += '<p class="ticket-note">Pasa a pagar en caja para que tu orden comience a prepararse.</p>';
    orderHTML += '</div>';
    orderHTML += '</div>';
    
    orderDetails.innerHTML = orderHTML;
    orderModal.style.display = 'flex';
}

// Función para renderizar el carrito
function renderCart() {
    cartItemsContainer.innerHTML = '';
    
    let total = 0;
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<div class="empty-cart-message">Tu carrito está vacío</div>';
        cartTotal.textContent = '$0.00';
        cartBadge.textContent = '0';
        cartData.value = '';
        return;
    }
    
    cart.forEach(item => {
        const itemTotal = item.precio * item.cantidad;
        total += itemTotal;
        
        const cartItemElement = document.createElement('div');
        cartItemElement.className = 'cart-item';
        cartItemElement.innerHTML = `
            <div class="cart-item-info" style="width: 100%;">
                <div class="cart-item-name" style="font-weight:600; font-size:1.05rem;">
                    ${item.nombre}
                    ${item.notas ? `<small style="display:block; color:#FFA000; font-size:0.8rem; margin-top:3px; font-weight:400;"><i class="fas fa-info-circle"></i> ${item.notas}</small>` : ''}
                </div>
                <div class="cart-item-price" style="color:#aaa; font-size:0.9rem; margin-top:2px;">$${item.precio.toFixed(2)} c/u</div>
                <div class="cart-item-controls" style="display:flex; align-items:center; gap:10px; margin-top:8px;">
                    <button class="quantity-btn decrease-btn" data-key="${item.uniqueKey}">-</button>
                    <span class="quantity-display" style="font-weight:bold; font-size:1.05rem;">${item.cantidad}</span>
                    <button class="quantity-btn increase-btn" data-key="${item.uniqueKey}">+</button>
                    <button class="cart-item-remove" data-key="${item.uniqueKey}" style="background:none; border:none; color:#ff5252; cursor:pointer; margin-left:10px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="cart-item-total" style="font-weight:800; font-size:1.1rem; color:#43A047; white-space:nowrap; margin-left:10px;">$${itemTotal.toFixed(2)}</div>
        `;
        
        cartItemsContainer.appendChild(cartItemElement);
    });
    
    cartTotal.textContent = `$${total.toFixed(2)}`;
    cartBadge.textContent = cart.reduce((sum, item) => sum + item.cantidad, 0);
    cartData.value = JSON.stringify(cart);
    
    addCartItemEventListeners();
}

// Función para agregar event listeners a los controles del carrito
function addCartItemEventListeners() {
    // Botones de aumentar cantidad
    document.querySelectorAll('.increase-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const uniqueKey = btn.dataset.key;
            const item = cart.find(x => x.uniqueKey === uniqueKey);
            if (item) {
                item.cantidad++;
                renderCart();
            }
        });
    });
    
    // Botones de disminuir cantidad
    document.querySelectorAll('.decrease-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const uniqueKey = btn.dataset.key;
            const item = cart.find(x => x.uniqueKey === uniqueKey);
            if (item) {
                if (item.cantidad > 1) {
                    item.cantidad--;
                } else {
                    const index = cart.findIndex(x => x.uniqueKey === uniqueKey);
                    if (index !== -1) {
                        cart.splice(index, 1);
                    }
                }
                renderCart();
            }
        });
    });
    
    // Botones de eliminar
    document.querySelectorAll('.cart-item-remove').forEach(btn => {
        btn.addEventListener('click', () => {
            const uniqueKey = btn.dataset.key;
            const index = cart.findIndex(x => x.uniqueKey === uniqueKey);
            if (index !== -1) {
                const removedItem = cart.splice(index, 1)[0];
                showNotification(`${removedItem.nombre} eliminado del carrito`);
                renderCart();
            }
        });
    });
}

// Función para mostrar notificaciones
function showNotification(message) {
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Inicializar carrito
document.addEventListener('DOMContentLoaded', function() {
    renderCart();
    
    // Event listeners para el modal de pedido
    downloadTicketBtn.addEventListener('click', () => {
        showNotification('Ticket descargado exitosamente (Simulación)');
    });

    continueOrderingBtn.addEventListener('click', () => {
        orderModal.style.display = 'none';
        cart.length = 0;
        renderCart();
        showNotification('Pedido registrado. Puedes seguir pidiendo.');
    });

    closeTicketBtn.addEventListener('click', () => {
        orderModal.style.display = 'none';
        cart.length = 0;
        renderCart();
        showNotification('Pedido registrado. Pasa a caja a pagar.');
    });

    closeOrderModal.addEventListener('click', () => {
        orderModal.style.display = 'none';
    });

    orderModal.addEventListener('click', (e) => {
        if (e.target === orderModal) {
            orderModal.style.display = 'none';
        }
    });
});