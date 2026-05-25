// assets/js/mesero-ayuda.js

document.addEventListener('DOMContentLoaded', function() {
    const botonAyuda = document.getElementById('botonAyuda');
    const ayudaPopup = document.getElementById('ayudaPopup');
    const cerrarAyuda = document.getElementById('cerrarAyuda');
    const listaMesas = document.getElementById('listaMesas');

    let notificacionesPendientes = [];

    // Cambiar estilos del botón de ayuda para que se vea premium
    if (botonAyuda) {
        botonAyuda.style.position = 'relative';
    }

    inicializarNotificaciones();

    // Mostrar popup de ayuda
    if (botonAyuda) {
        botonAyuda.addEventListener('click', function() {
            ayudaPopup.style.display = 'block';
            cargarNotificaciones();
        });
    }

    // Cerrar popup de ayuda
    if (cerrarAyuda) {
        cerrarAyuda.addEventListener('click', function() {
            ayudaPopup.style.display = 'none';
        });
    }

    // Cerrar popup al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (ayudaPopup && ayudaPopup.style.display === 'block' && 
            !ayudaPopup.contains(e.target) && 
            e.target !== botonAyuda) {
            ayudaPopup.style.display = 'none';
        }
    });

    // Inicializar sistema de notificaciones en base de datos
    function inicializarNotificaciones() {
        verificarNuevasNotificaciones();
        setInterval(verificarNuevasNotificaciones, 5000);
    }

    // Sintetizar un tono digital de campana premium usando Web Audio API
    function playChimeSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            if (ctx.state === 'suspended') {
                // El navegador bloquea audio sin interacción, lo ignoramos o reanudamos
                ctx.resume();
            }
            const osc1 = ctx.createOscillator();
            const osc2 = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc1.connect(gain);
            osc2.connect(gain);
            gain.connect(ctx.destination);
            
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(659.25, ctx.currentTime); // E5
            osc1.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5

            osc2.type = 'triangle';
            osc2.frequency.setValueAtTime(440, ctx.currentTime); // A4
            osc2.frequency.exponentialRampToValueAtTime(554.37, ctx.currentTime + 0.15); // C#5
            
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
            
            osc1.start(ctx.currentTime);
            osc2.start(ctx.currentTime);
            osc1.stop(ctx.currentTime + 0.55);
            osc2.stop(ctx.currentTime + 0.55);
        } catch (e) {
            console.warn("Audio Context bloqueado o no soportado por el navegador:", e);
        }
    }

    // Mostrar notificación emergente flotante en pantalla
    function mostrarNotificacionEmergente(notificacion) {
        const notificacionElem = document.createElement('div');
        notificacionElem.className = 'notificacion-emergente';
        notificacionElem.innerHTML = `
            <div class="notificacion-contenido">
                <div class="notificacion-icono">🔔</div>
                <div class="notificacion-texto">
                    <strong>MESA SOLICITA AYUDA</strong>
                    <p style="margin: 3px 0 0 0; font-size: 0.85rem; color:#ffd54f;">${notificacion.mensaje}</p>
                </div>
                <button class="cerrar-notificacion">×</button>
            </div>
        `;

        notificacionElem.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #8B0000;
            color: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 2000;
            min-width: 320px;
            animation: slideInRight 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border-left: 5px solid #FFD700;
            font-family: inherit;
        `;

        document.body.appendChild(notificacionElem);
        
        // Sonar timbre de aviso
        playChimeSound();

        // Eliminar sola en 6 segundos
        setTimeout(() => {
            if (notificacionElem.parentNode) {
                notificacionElem.style.animation = 'slideOutRight 0.3s ease forwards';
                setTimeout(() => notificacionElem.remove(), 300);
            }
        }, 6000);

        const cerrarBtn = notificacionElem.querySelector('.cerrar-notificacion');
        cerrarBtn.addEventListener('click', function() {
            notificacionElem.remove();
        });
    }

    // Consultar alertas de asistencia activas al servidor
    function verificarNuevasNotificaciones() {
        fetch(BASE_URL + 'index.php?url=mesero/obtenerAlertasAyuda')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.alertas) {
                    const nuevas = data.alertas;
                    
                    // Buscar si hay alguna alerta nueva que no teníamos registrada en memoria
                    nuevas.forEach(notif => {
                        const yaExiste = notificacionesPendientes.some(n => n.id === notif.id);
                        if (!yaExiste) {
                            mostrarNotificacionEmergente(notif);
                        }
                    });
                    
                    notificacionesPendientes = nuevas;
                    actualizarBadgeNotificaciones();
                    
                    if (ayudaPopup && ayudaPopup.style.display === 'block') {
                        renderListaPopup();
                    }
                }
            })
            .catch(err => console.error("Error al consultar asistencia de mesas:", err));
    }

    // Cargar y pintar alertas en el popup modal
    function cargarNotificaciones() {
        renderListaPopup();
    }

    function renderListaPopup() {
        if (notificacionesPendientes.length === 0) {
            listaMesas.innerHTML = `
                <div class="mesa-item" style="text-align:center; padding: 20px; color:#aaa; font-style:italic;">
                    <i class="fas fa-check-circle" style="color:#43A047; font-size:1.5rem; margin-bottom:8px; display:block;"></i>
                    No hay mesas solicitando ayuda
                </div>
            `;
            return;
        }

        let html = '';
        notificacionesPendientes.forEach(notif => {
            html += `
                <div class="mesa-item nueva" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(255,255,255,0.06); padding: 12px 0;">
                    <div>
                        <strong style="color: #fff; font-size:0.95rem;">${notif.mensaje}</strong>
                        <span style="font-size: 11px; color: #888; display: block; margin-top: 3px;">
                            <i class="fas fa-clock"></i> ${new Date(notif.fecha_creacion).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </span>
                    </div>
                    <button class="btn-atender-ayuda" data-id="${notif.id}" style="background:#43A047; color:white; border:none; padding:6px 12px; border-radius:8px; font-weight:bold; font-size:0.8rem; cursor:pointer; font-family:inherit;">
                        Atender
                    </button>
                </div>
            `;
        });

        listaMesas.innerHTML = html;

        // Asignar eventos de click a botones Atender
        document.querySelectorAll('.btn-atender-ayuda').forEach(btn => {
            btn.addEventListener('click', function() {
                const alertaId = this.dataset.id;
                this.textContent = '...';
                this.disabled = true;

                const formData = new FormData();
                formData.append('alerta_id', alertaId);

                fetch(BASE_URL + 'index.php?url=mesero/atenderAlerta', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Recargar notificaciones inmediatamente
                        verificarNuevasNotificaciones();
                    }
                })
                .catch(err => console.error("Error al atender alerta:", err));
            });
        });
    }

    // Actualizar el número rojo de alertas en el botón de ayuda
    function actualizarBadgeNotificaciones() {
        const count = notificacionesPendientes.length;
        let badge = document.querySelector('.notificacion-badge');
        
        if (!badge && count > 0 && botonAyuda) {
            badge = document.createElement('span');
            badge.className = 'notificacion-badge';
            botonAyuda.appendChild(badge);
        }
        
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }
});

// Styles for notifications are loaded from mesero-ayuda.css