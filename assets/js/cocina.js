const ESTADOS_MAP = {
    'pendiente': 'en_preparacion',
    'en_preparacion': 'listo',
    'listo': 'listo'
};

function actualizarPedidos() {
    fetch(COCINA_URL)
        .then(response => {
            if (!response.ok) throw new Error('Error de red: ' + response.status);
            return response.json();
        })
        .then(data => {
            const el = document.getElementById('lastUpdate');
            if (data.success) {
                actualizarVistaPedidos(data.pedidos);
                if (el) el.textContent = ' Actualizado: ' + new Date().toLocaleTimeString();
            } else {
                console.error('API error:', data.error);
                if (el) el.textContent = ' Error';
            }
        })
        .catch(error => {
            console.error('Error al actualizar:', error);
            const el = document.getElementById('lastUpdate');
            if (el) el.textContent = ' Error de conexión';
        });
}

function actualizarVistaPedidos(pedidos) {
    const container = document.getElementById('pedidosContainer');
    if (!container) return;

    if (pedidos.length === 0) {
        container.innerHTML = '<div class="no-items"><i class="fas fa-check-circle"></i><p>No hay pedidos activos de tacos o postres</p></div>';
        return;
    }

    let html = '';

    pedidos.forEach(pedido => {
        const estado = (pedido.estado || '').toLowerCase();
        const estadoDisplay = estado.toUpperCase().replace('_', ' ');
        const mesa = pedido.mesa_id || '?';
        const fec = pedido.fecha_creacion || '';

        html += `
            <div class="pedido" data-pedido-id="${pedido.id}">
                <div class="pedido-header">
                    <div class="pedido-info">
                        <div class="pedido-title-row">
                            <span class="mesa-badge"><i class="fas fa-chair"></i> Mesa ${mesa}</span>
                            <span class="pedido-num">#${pedido.id}</span>
                        </div>
                        <div class="pedido-meta">
                            <span class="meta-time"><i class="fas fa-clock"></i> <span class="order-time" data-time="${fec}">${fec ? new Date(fec + 'Z').toLocaleTimeString() : '--:--'}</span></span>
                            <button class="btn-todo-listo" onclick="marcarPedidoListo(${pedido.id}, this)"><i class="fas fa-check-double"></i> Todo Listo</button>
                            <span class="estado-pedido estado-${estado}">${estadoDisplay}</span>
                        </div>
                    </div>
                </div>
                <ul class="detalles-lista">
        `;

        (pedido.detalles || []).forEach(detalle => {
            const est = (detalle.estado || '').toLowerCase();
            const estDisplay = est.toUpperCase().replace('_', ' ');
            const notesHTML = detalle.notas ? `<div class="detalle-notas" style="color: #FFA000; font-size: 0.85rem; font-weight: bold; margin-top: 3px;"><i class="fas fa-info-circle"></i> ${detalle.notas}</div>` : '';

            html += `
                <li class="detalle-item">
                    <div class="detalle-info">
                        <span class="detalle-nombre" style="font-weight:600;">${detalle.nombre || ''}</span>
                        ${notesHTML}
                        <span class="detalle-cantidad">x${detalle.cantidad || 0}</span>
                    </div>
                    <div class="detalle-controls">
                        <span class="detalle-estado estado-${est}" data-detalle-id="${detalle.id}" data-current-state="${est}" onclick="cambiarEstado(this)">${estDisplay}</span>
                    </div>
                </li>
            `;
        });

        html += `</ul></div>`;
    });

    container.innerHTML = html;
    actualizarTiempos();
}

function cambiarEstado(element) {
    const detalleId = element.getAttribute('data-detalle-id');
    const currentState = element.getAttribute('data-current-state');

    if (!detalleId || !currentState) return;

    const nextState = ESTADOS_MAP[currentState];
    if (nextState === currentState) return;

    element.textContent = '...';
    element.style.pointerEvents = 'none';
    element.style.opacity = '0.6';

    const formData = new FormData();
    formData.append('detalle_id', detalleId);
    formData.append('estado', nextState);

    fetch(ESTADO_URL, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) actualizarPedidos();
            else location.reload();
        })
        .catch(() => {
            element.textContent = currentState.toUpperCase().replace('_', ' ');
            element.style.pointerEvents = 'auto';
            element.style.opacity = '1';
        });
}

function marcarPedidoListo(pedidoId, btnElement) {
    if (!pedidoId) return;

    btnElement.textContent = '...';
    btnElement.style.pointerEvents = 'none';
    btnElement.style.opacity = '0.6';

    const formData = new FormData();
    formData.append('pedido_id', pedidoId);

    const baseUrl = ESTADO_URL.replace('actualizarDetalle', 'marcarPedidoListo');

    fetch(baseUrl, { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) actualizarPedidos();
            else location.reload();
        })
        .catch(() => {
            btnElement.innerHTML = '<i class="fas fa-check-double"></i> Todo Listo';
            btnElement.style.pointerEvents = 'auto';
            btnElement.style.opacity = '1';
        });
}

function actualizarTiempos() {
    document.querySelectorAll('.order-time').forEach(el => {
        const t = el.getAttribute('data-time');
        if (!t) return;
        const diff = Date.now() - new Date(t + 'Z').getTime();
        if (isNaN(diff)) return;
        const mins = Math.floor(diff / 60000);
        const hrs = Math.floor(mins / 60);
        el.textContent = mins < 60 ? `hace ${mins} min` : `hace ${hrs}h ${mins % 60}min`;
    });
}

setInterval(actualizarPedidos, 10000);

document.addEventListener('DOMContentLoaded', function() {
    actualizarPedidos();
    actualizarTiempos();
    setInterval(actualizarTiempos, 30000);
});
