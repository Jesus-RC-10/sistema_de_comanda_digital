class OrderManager {
    constructor(cartManager) {
        this.cartManager = cartManager;
    }

    async saveOrder() {
        const cart = this.cartManager.getCart();
        const mesaNumero = this.cartManager.getMesaNumero();
        
        // Preparar datos para enviar al servidor
        const orderData = {
            mesa: mesaNumero,
            items: cart.map(item => ({
                nombre: item.name,
                cantidad: item.quantity,
                precio: item.price
            }))
        };

        try {
            const response = await fetch('index.php?action=order/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();
            
            if (result.status === 'success') {
                // Guardar también en localStorage como respaldo (opcional)
                const pedidos = JSON.parse(localStorage.getItem("pedidos")) || {};
                const mesaKey = "mesa_" + mesaNumero;
                pedidos[mesaKey] = orderData.items;
                localStorage.setItem("pedidos", JSON.stringify(pedidos));
                
                return { success: true, pedido_id: result.pedido_id };
            } else {
                throw new Error(result.message || 'Error al guardar el pedido');
            }
        } catch (error) {
            console.error('Error al guardar pedido:', error);
            // Como respaldo, guardar en localStorage si falla la conexión
            const pedidos = JSON.parse(localStorage.getItem("pedidos")) || {};
            const mesaKey = "mesa_" + mesaNumero;
            pedidos[mesaKey] = orderData.items;
            localStorage.setItem("pedidos", JSON.stringify(pedidos));
            
            throw error;
        }
    }

    async generateTicket() {
        const cart = this.cartManager.getCart();
        const total = this.cartManager.getTotal();
        const mesaNumero = this.cartManager.getMesaNumero();
        
        // Obtener la fecha y hora del servidor
        let fecha = '';
        let hora = '';
        
        try {
            const response = await fetch('index.php?action=menu/getServerDateTime');
            const data = await response.json();
            if (data.success) {
                fecha = data.fecha;
                hora = data.hora;
            }
        } catch (error) {
            console.error('Error al obtener fecha del servidor:', error);
            // Fallback a fecha local si falla
            const now = new Date();
            fecha = now.toLocaleDateString();
            hora = now.toLocaleTimeString();
        }

        return `
TAQUERÍA EL INFORMÁTICO
----------------------------------------
Mesa: ${mesaNumero}
Fecha: ${fecha}   Hora: ${hora}
----------------------------------------
${cart.map(i => `${i.name.padEnd(20)} x${i.quantity}  $${(i.price * i.quantity).toFixed(2)}`).join('\n')}
----------------------------------------
TOTAL: $${total.toFixed(2)}
----------------------------------------
¡Gracias por tu pedido!
`;
    }

    downloadTicket() {
        const ticketContent = this.generateTicket();
        const blob = new Blob([ticketContent], { type: "text/plain" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = `Ticket_Mesa_${this.cartManager.getMesaNumero()}.txt`;
        link.click();
    }
}