# Changelog - Sistema de Comanda Digital

## Cambios realizados por Oswaldo Ramírez

### Fecha: 23 de abril de 2026

#### Rutas y Configuración del Sistema
- **index.php / js / vistas**: Implementación de base URL de forma dinámica para resolver redirecciones 404 y conflictos con carpetas estáticas.

#### Correcciones de Errores en Panel de Administración
- **Gestión de Usuarios (User.php)**: Se solucionó el problema donde se sobrescribía un usuario existente y activo al intentar insertar un usuario bajo el mismo identificador o nombre. 
- **Gestión de Mesas (Mesa.php)**: Se aplicó una validación para evitar sobrescribir mesas ya existentes, permitiendo la reactivación segura si se encontraban "eliminadas" (inactivas).
- **Control de Inventario (inventario.php)**: Cambiados los campos de "Categoría" y "Unidad" de texto libre a menús desplegables (selects) estrictos. Esto previene que MySQL rechace combinaciones que no forman parte de las reglas ENUM.

#### Mejoras de Diseño y Funcionalidades
- **Caja**: Múltiples mejoras de diseño y nuevas funcionalidades operativas dentro de caja.
- **Ticket**: Mejoras de diseño de tickets.
- **Interfaces e Interactividad Ux**: Modificación del diseño en varias interfaces implementando animaciones y respuestas de movimiento dinámico para enriquecer la fluidez.

### Fecha: 4 de abril de 2026

#### Correcciones de Errores
- **carrito.js**: Corregido error de sintaxis que impedía el funcionamiento del carrito (missing } after function body).
- **help-buttons.css**: Creado archivo faltante que causaba error de MIME type en el navegador.

#### Mejoras de Diseño y UX
- **Ticket de Pedido**: Rediseñado completamente para un aspecto más profesional y atractivo.
  - Encabezado con emoji y colores temáticos.
  - Formato mejorado con líneas separadoras punteadas.
  - Estilos coherentes con el tema rojo vino del sistema.
  - Mensaje de agradecimiento y nota para recoger en caja.
- **Modal del Ticket**: Eliminado el botón "Ir a Caja" para acceso manual a la caja.
- **Estilos CSS**: Añadidos estilos personalizados para el ticket en `estilos.css` con bordes redondeados, sombras y tipografía monospace.

#### Optimizaciones Técnicas
- Añadidos parámetros de versión (?v=X) a archivos CSS y JS para forzar recarga y evitar problemas de caché.
- Mejorada la estructura del código JavaScript para mayor mantenibilidad.

### Descripción de cambios
- El carrito ahora funciona correctamente sin errores de sintaxis.
- El ticket de pedido tiene un diseño elegante y profesional que concuerda con la identidad visual del sistema.
- Eliminado el acceso automático a caja; el usuario accede manualmente cuando lo desee.

### Fecha: 27 de marzo de 2026

#### Modificaciones en Caja
- **CajaController.php**: Agregado código para obtener detalles de pedidos en ventas pendientes y pagadas.
- **caja.php (vista)**: Modificado para mostrar la lista de productos en cada venta pendiente y pagada.
- **caja.css**: Agregados estilos para la sección de detalles del pedido.

#### Modificaciones en Carrito
- **carrito.js**: Eliminada la redirección automática a caja al finalizar un pedido, para que la caja se abra manualmente.

### Descripción de cambios
- Ahora en la sección de caja se muestran los detalles completos de cada pedido (productos, cantidades y subtotales) tanto para ventas pendientes como pagadas.
- La finalización de un pedido ya no redirige automáticamente a la caja; el acceso a caja es manual.