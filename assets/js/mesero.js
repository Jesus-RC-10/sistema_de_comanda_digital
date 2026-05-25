// ==========================================================
// mesero.js — Premium Waiter Dashboard Controller
// Real-time order polling, LISTO animations, delivery flow
// ==========================================================

// Track previous order states to detect transitions to 'listo'
let previousPedidosMap = {};
let isFirstLoad = true;

// ---- Play a celebratory chime when a pedido becomes LISTO ----
function playReadyChime() {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        if (ctx.state === 'suspended') ctx.resume();

        // Two-note ascending major chord
        const notes = [
            { freq: 523.25, delay: 0 },      // C5
            { freq: 659.25, delay: 0.12 },    // E5
            { freq: 783.99, delay: 0.24 }     // G5
        ];

        notes.forEach(note => {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(note.freq, ctx.currentTime + note.delay);
            gain.gain.setValueAtTime(0.1, ctx.currentTime + note.delay);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + note.delay + 0.6);
            osc.start(ctx.currentTime + note.delay);
            osc.stop(ctx.currentTime + note.delay + 0.65);
        });
    } catch (e) {
        console.warn("Audio bloqueado:", e);
    }
}

// ---- Show toast notification ----
function showToast(message, icon = 'fa-check-circle') {
    const existing = document.querySelector('.toast-confirm');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.className = 'toast-confirm';
    toast.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'toastOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ---- Fetch updated orders ----
function actualizarPedidos() {
    fetch(MESERO_URL + 'obtenerPedidosActualizados')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                detectarCambiosEstado(data.pedidos);
                actualizarVistaPedidos(data.pedidos);
                actualizarEstadisticas(data.pedidos);
                actualizarReloj();
            }
        })
        .catch(error => {
            console.error('Error al actualizar pedidos:', error);
        });
}

// ---- Detect when a pedido transitions to 'listo' ----
function detectarCambiosEstado(pedidos) {
    if (isFirstLoad) {
        // On first load, just populate the map — don't trigger sounds
        pedidos.forEach(p => { previousPedidosMap[p.id] = p.estado; });
        isFirstLoad = false;
        return;
    }

    pedidos.forEach(pedido => {
        const prevEstado = previousPedidosMap[pedido.id];
        if (prevEstado && prevEstado !== 'listo' && pedido.estado === 'listo') {
            // This order just became LISTO!
            playReadyChime();
            showToast(`¡Pedido #${pedido.id} (Mesa ${pedido.mesa_id}) está LISTO!`, 'fa-bell');
        }
        previousPedidosMap[pedido.id] = pedido.estado;
    });

    // Clean up removed pedidos
    const activeIds = new Set(pedidos.map(p => p.id));
    Object.keys(previousPedidosMap).forEach(id => {
        if (!activeIds.has(parseInt(id))) {
            delete previousPedidosMap[id];
        }
    });
}

// ---- Update stats bar ----
function actualizarEstadisticas(pedidos) {
    const activos = pedidos.filter(p => p.estado !== 'listo' && p.estado !== 'entregado').length;
    const listos = pedidos.filter(p => p.estado === 'listo').length;
    const totalCuenta = pedidos.reduce((sum, p) => sum + parseFloat(p.total || 0), 0);

    const elActivos = document.getElementById('statActivos');
    const elListos = document.getElementById('statListos');
    const elTotal = document.getElementById('statTotal');

    if (elActivos) elActivos.textContent = activos;
    if (elListos) elListos.textContent = listos;
    if (elTotal) elTotal.textContent = '$' + totalCuenta.toFixed(0);
}

// ---- Update "last updated" label ----
function actualizarReloj() {
    const el = document.getElementById('lastUpdate');
    if (el) {
        const now = new Date();
        el.textContent = 'Actualizado: ' + now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
}

// ---- Render all order cards ----
function actualizarVistaPedidos(pedidos) {
    const container = document.getElementById('pedidosContainer');

    if (pedidos.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <i class="fas fa-check-circle"></i>
                <p>No hay pedidos activos</p>
                <small>Los nuevos pedidos aparecerán automáticamente</small>
            </div>
        `;
        return;
    }

    // Sort: LISTO orders first, then by creation time descending
    pedidos.sort((a, b) => {
        if (a.estado === 'listo' && b.estado !== 'listo') return -1;
        if (a.estado !== 'listo' && b.estado === 'listo') return 1;
        return 0;
    });

    let html = '<div class="pedidos-grid">';

    pedidos.forEach((pedido, index) => {
        const mid = pedido.mesa_id || '?';
        const isListo = (pedido.estado === 'listo');
        const estadoLabel = pedido.estado.replace('_', ' ').toUpperCase();

        // Time display
        let timeDisplay = '';
        if (pedido.fecha_creacion) {
            const fecha = new Date(pedido.fecha_creacion);
            timeDisplay = fecha.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        html += `
            <div class="pedido-card ${isListo ? 'pedido-listo' : ''}"
                 data-pedido-id="${pedido.id}"
                 style="animation: cardFadeIn 0.4s ease ${index * 0.06}s both;">
        `;

        // LISTO Banner
        if (isListo) {
            html += `
                <div class="ready-banner">
                    <i class="fas fa-bell"></i> ¡PEDIDO LISTO PARA ENTREGAR!
                </div>
            `;
        }

        // Header
        html += `
              <div class="pedido-card-header">
                <div class="pedido-card-meta">
                  <div class="pedido-card-title">
                    <span class="mesa-badge"><i class="fas fa-chair"></i> Mesa ${mid}</span>
                    <span class="pedido-num">#${pedido.id}</span>
                  </div>
                  <div class="pedido-time">
                    <i class="fas fa-clock"></i>
                    <span>${timeDisplay}</span>
                  </div>
                </div>
                <span class="estado-badge estado-${pedido.estado}">
                  ${estadoLabel}
                </span>
              </div>
        `;

        // Body — items
        html += `<div class="pedido-card-body"><ul class="detalle-list">`;

        if (pedido.detalles && pedido.detalles.length > 0) {
            pedido.detalles.forEach(detalle => {
                const estadoDetalle = detalle.estado.replace('_', ' ').toUpperCase();
                const notasHtml = detalle.notas
                    ? `<div class="detalle-notas"><i class="fas fa-pepper-hot"></i> ${escapeHtml(detalle.notas)}</div>`
                    : '';

                html += `
                    <li class="detalle-row">
                      <div class="detalle-info">
                        <span class="detalle-name">${escapeHtml(detalle.nombre)}</span>
                        <span class="detalle-qty">x${detalle.cantidad} — $${parseFloat(detalle.subtotal).toFixed(2)}</span>
                        ${notasHtml}
                      </div>
                      <span class="detalle-estado-pill pill-${detalle.estado}">
                        ${estadoDetalle}
                      </span>
                    </li>
                `;
            });
        }

        html += `</ul></div>`;

        // Footer
        const totalFormatted = parseFloat(pedido.total).toFixed(2);
        html += `
              <div class="pedido-card-footer">
                <div class="pedido-total">
                  <small>Total</small>$${totalFormatted}
                </div>
        `;

        if (isListo) {
            html += `
                <button class="btn-entregar" onclick="entregarPedido(${pedido.id}, this)">
                  <i class="fas fa-hand-holding-heart"></i> Entregar
                </button>
            `;
        } else {
            html += `
                <button class="btn-entregar btn-pending" disabled>
                  <i class="fas fa-hourglass-half"></i> En proceso...
                </button>
            `;
        }

        html += `</div></div>`; // close footer + card
    });

    html += '</div>'; // close grid
    container.innerHTML = html;
}

// ---- Deliver an order (Entregar) ----
function entregarPedido(pedidoId, btnEl) {
    if (btnEl) {
        btnEl.disabled = true;
        btnEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Entregando...';
    }

    fetch(MESERO_URL + "cerrarPedido", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "pedido_id=" + pedidoId
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(`Pedido #${pedidoId} entregado con éxito`, 'fa-check-double');
            // Animate card out before refreshing
            const card = document.querySelector(`.pedido-card[data-pedido-id="${pedidoId}"]`);
            if (card) {
                card.style.transition = 'all 0.4s ease';
                card.style.transform = 'scale(0.9)';
                card.style.opacity = '0';
                setTimeout(() => actualizarPedidos(), 400);
            } else {
                actualizarPedidos();
            }
        }
    })
    .catch(error => {
        console.error('Error al entregar pedido:', error);
        if (btnEl) {
            btnEl.disabled = false;
            btnEl.innerHTML = '<i class="fas fa-hand-holding-heart"></i> Entregar';
        }
    });
}

// ---- Legacy function compatibility ----
function cerrarPedido(idPedido) {
    entregarPedido(idPedido);
}

// ---- Utility: Escape HTML ----
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ---- Auto-refresh every 5 seconds ----
setInterval(actualizarPedidos, 5000);

// ---- Refresh on window focus ----
window.addEventListener('focus', actualizarPedidos);

// ---- Init ----
document.addEventListener('DOMContentLoaded', function() {
    actualizarEstadisticas([]); // Initialize counters
    actualizarReloj();

    // Initial stats from server-rendered data (if any cards exist)
    setTimeout(() => {
        // Trigger one fetch to get the accurate data + populate previousPedidosMap
        actualizarPedidos();
    }, 500);
});