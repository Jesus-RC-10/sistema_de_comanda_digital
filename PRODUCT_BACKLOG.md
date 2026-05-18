# Product Backlog - Sistema de Comanda Digital (SCD)

**Documento:** PRODUCT-BACKLOG-2026-001  
**Fecha:** 28 de abril de 2026  
**Versión:** 2.0 (Actualizado)  
**Metodología:** Scrum  

---

## 📋 Estado del Product Backlog

| Módulo | Total Historias | ✅ Implementadas | ⚠️ Parcial | ❌ Pendientes |
|--------|----------------|-----------------|-------------|---------------|
| Autenticación | 5 | 5 | 0 | 0 |
| Pedidos (Mesa) | 8 | 7 | 1 | 0 |
| Cocina | 6 | 5 | 0 | 1 |
| Caja | 7 | 7 | 0 | 0 |
| Administración | 10 | 9 | 0 | 1 |
| Inventario | 6 | 5 | 0 | 1 |
| Reportes | 4 | 3 | 0 | 1 |
| Seguridad | 7 | 6 | 0 | 1 |
| DevOps | 5 | 3 | 0 | 2 |
| **TOTAL** | **58** | **50** | **1** | **7** |

---

## 🎯 Módulo 1: Autenticación y Seguridad

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **AUTH-001** | Como usuario, quiero iniciar sesión con mi usuario y contraseña | Alta | Sprint 1 | ✅ Done | Login funcional con roles |
| **AUTH-002** | Como usuario, quiero ser redirigido a mi interfaz según mi rol | Alta | Sprint 1 | ✅ Done | 4 roles: admin, mesero, cocina, caja |
| **AUTH-003** | Como usuario, quiero cerrar sesión de forma segura | Alta | Sprint 1 | ✅ Done | Session destroy implementado |
| **AUTH-004** | Como usuario, quiero sesiones seguras con regeneración de ID | Media | Sprint 1 | ✅ Done | session_regenerate_id() |
| **AUTH-005** | Como usuario, quiero que mis credenciales estén encriptadas | Alta | Sprint 1 | ✅ Done | password_hash() con bcrypt |
| **SEG-006** | Como admin, quiero protección CSRF en formularios | Media | - | ❌ Pendiente | Implementar tokens CSRF |
| **SEG-007** | Como admin, quiero rate limiting en login | Media | - | ❌ Pendiente | Prevenir fuerza bruta |

**Credenciales de Prueba:**
```
admin / 123456 (Administrador)
mesero1 / 123456 (Mesero)
cocina1 / 123456 (Cocinero)
caja / 123456 (Cajero)
```

---

## 🍽️ Módulo 2: Pedidos (Cliente/Mesa)

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **PED-001** | Como cliente, quiero ver el menú organizado por categorías | Alta | Sprint 1 | ✅ Done | Tacos, Bebidas, Postres |
| **PED-002** | Como cliente, quiero agregar productos al carrito | Alta | Sprint 1 | ✅ Done | Carrito flotante con badge |
| **PED-003** | Como cliente, quiero personalizar platillos (ingredientes) | Media | Sprint 2 | ⚠️ Parcial | Solo visual, no afecta receta |
| **PED-004** | Como cliente, quiero ver el total en tiempo real | Alta | Sprint 1 | ✅ Done | Cálculo automático en JS |
| **PED-005** | Como cliente, quiero recibir un ticket digital | Alta | Sprint 2 | ✅ Done | Generación de ticket en confirmación |
| **PED-006** | Como cliente, quiero solicitar ayuda al mesero | Media | Sprint 3 | ✅ Done | Botón de ayuda implementado |
| **PED-007** | Como cliente, quiero validación de pedido no vacío | Alta | Sprint 1 | ✅ Done | Validación en servidor |
| **PED-008** | Como cliente, quiero que el sistema identifique mi mesa | Alta | Sprint 1 | ✅ Done | Parámetro mesa_id en URL |

**Flujo Implementado:**
1. Cliente selecciona mesa → 2. Ve menú por categorías → 3. Agrega al carrito → 4. Confirma pedido → 5. Recibe ticket → 6. Cocina recibe en tiempo real

---

## 👨‍🍳 Módulo 3: Cocina

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **COC-001** | Como cocinero, quiero ver pedidos en tiempo real | Alta | Sprint 2 | ✅ Done | Auto-refresh cada 30s |
| **COC-002** | Como cocinero, quiero actualizar el estado de cada item | Alta | Sprint 2 | ✅ Done | 4 estados: pendiente, en_preparacion, listo, entregado |
| **COC-003** | Como cocinero, quiero filtrar pedidos por tipo | Media | Sprint 2 | ✅ Done | Filtra tacos y postres |
| **COC-004** | Como cocinero, quiero ver detalles completos del pedido | Alta | Sprint 2 | ✅ Done | Mesa, items, notas, precios |
| **COC-005** | Como cocinero, quiero actualización automática de pedidos | Alta | Sprint 2 | ✅ Done | AJAX polling implementado |
| **COC-006** | Como cocinero, quiero verificar disponibilidad de ingredientes | Media | Sprint 4 | ❌ Pendiente | Validación pre-pedido |

**Estados de Pedido:**
- `pendiente` → `en_preparacion` → `listo` → `entregado`

---

## 💰 Módulo 4: Caja

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **CAJ-001** | Como cajero, quiero ver ventas pendientes de pago | Alta | Sprint 3 | ✅ Done | Lista con detalles completos |
| **CAJ-002** | Como cajero, quiero ver historial de ventas pagadas | Alta | Sprint 3 | ✅ Done | Historial completo |
| **CAJ-003** | Como cajero, quiero procesar pagos con múltiples métodos | Alta | Sprint 3 | ✅ Done | Efectivo, tarjeta, transferencia, mixto |
| **CAJ-004** | Como cajero, quiero cálculo automático de cambio | Alta | Sprint 3 | ✅ Done | Para pagos en efectivo |
| **CAJ-005** | Como cajero, quiero ver detalles completos de venta | Alta | Sprint 3 | ✅ Done | Items, totales, método pago |
| **CAJ-006** | Como cajero, quiero cancelar ventas pendientes | Media | Sprint 3 | ✅ Done | Cancelación individual |
| **CAJ-007** | Como cajero, quiero marcar pedido como entregado al pagar | Alta | Sprint 3 | ✅ Done | Actualiza estado automático |

**Métodos de Pago Disponibles:**
- Efectivo (con cálculo de cambio)
- Tarjeta
- Transferencia
- Mixto

---

## 🔧 Módulo 5: Administración

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **ADM-001** | Como admin, quiero un dashboard con métricas clave | Alta | Sprint 4 | ✅ Done | Ventas hoy, pedidos activos, mesas ocupadas |
| **ADM-002** | Como admin, quiero gestionar menú (CRUD platillos) | Alta | Sprint 2 | ✅ Done | Con imagen y categoría |
| **ADM-003** | Como admin, quiero gestionar categorías de menú | Alta | Sprint 2 | ✅ Done | Crear/eliminar categorías |
| **ADM-004** | Como admin, quiero gestionar mesas (alta/baja/mod) | Alta | Sprint 1 | ✅ Done | Con ubicación y estado |
| **ADM-005** | Como admin, quiero gestionar usuarios y roles | Alta | Sprint 1 | ✅ Done | CRUD usuarios con roles |
| **ADM-006** | Como admin, quiero gestionar inventario de ingredientes | Alta | Sprint 4 | ✅ Done | Cantidad, mínimos, proveedor |
| **ADM-007** | Como admin, quiero alertas de stock bajo | Alta | Sprint 4 | ✅ Done | Notificaciones automáticas |
| **ADM-008** | Como admin, quiero generar reportes de ventas | Media | Sprint 5 | ⚠️ Parcial | Reportes HTML, pendiente PDF real |
| **ADM-009** | Como admin, quiero asociar recetas a platillos | Media | Sprint 4 | ✅ Done | Ingredientes por producto |
| **ADM-010** | Como admin, quiero notificaciones de eventos del sistema | Media | Sprint 4 | ✅ Done | Observer pattern implementado |

**Secciones del Admin:**
- Dashboard (`/admin&seccion=dashboard`)
- Gestión de Mesas (`/admin&seccion=mesas`)
- Gestión de Menú (`/admin&seccion=menu`)
- Gestión de Usuarios (`/admin&seccion=usuarios`)
- Control de Inventario (`/admin&seccion=inventario`)
- Reportes (`/admin&seccion=reportes`)

---

## 📦 Módulo 6: Inventario

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **INV-001** | Como admin, quiero agregar nuevos ingredientes | Alta | Sprint 4 | ✅ Done | Con categoría y unidad |
| **INV-002** | Como admin, quiero actualizar cantidades de ingredientes | Alta | Sprint 4 | ✅ Done | Edición inline en tabla |
| **INV-003** | Como admin, quiero validación de categorías y unidades (ENUM) | Alta | Sprint 4 | ✅ Done | 7 categorías, 6 unidades |
| **INV-004** | Como admin, quiero alertas cuando ingrediente < mínimo | Alta | Sprint 4 | ✅ Done | Observer + alertas_sistema |
| **INV-005** | Como admin, quiero descuento automático de ingredientes al confirmar pedido | Media | Sprint 5 | ❌ Pendiente | Integrar con recetas_producto |
| **INV-006** | Como admin, quiero establecer mínimos de stock por ingrediente | Alta | Sprint 4 | ✅ Done | Campo cantidad_minima |

**Categorías de Ingredientes:**
- vegetales, carnes, lacteos, granos, especias, bebidas, otros

**Unidades de Medida:**
- kg, gr, lt, ml, unidad, paquete

---

## 📊 Módulo 7: Reportes

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **REP-001** | Como admin, quiero reporte diario de ventas | Media | Sprint 5 | ✅ Done | Desglose por producto |
| **REP-002** | Como admin, quiero reporte mensual de ventas | Media | Sprint 5 | ✅ Done | Agrupado por fecha |
| **REP-003** | Como admin, quiero historial completo de pedidos | Media | Sprint 5 | ✅ Done | Con detalles y estado |
| **REP-004** | Como admin, quiero exportar reportes a PDF | Media | Sprint 5 | ⚠️ Parcial | Genera HTML, falta PDF real |

**Reportes Implementados:**
- Reporte Diario (ventas del día con productos)
- Reporte Mensual (agrupado por fecha)
- Historial Completo (todos los pedidos)

---

## 🔧 Módulo 8: Mesero

### Historias de Usuario

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **MES-001** | Como mesero, quiero ver pedidos activos | Media | Sprint 3 | ✅ Done | Vista con detalles |
| **MES-002** | Como mesero, quiero actualizar estado de pedidos | Media | Sprint 3 | ✅ Done | Dropdown de estados |
| **MES-003** | Como mesero, quiero cerrar pedidos completados | Media | Sprint 3 | ✅ Done | Botón cerrar pedido |
| **MES-004** | Como mesero, quiero recibir solicitudes de ayuda | Media | Sprint 3 | ✅ Done | Popup de ayuda |

---

## 🏗️ Módulo 9: Arquitectura y Patrones

### Historias Técnicas

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **ARC-001** | Como dev, quiero arquitectura MVC implementada | Alta | Sprint 1 | ✅ Done | Controllers, Models, Views |
| **ARC-002** | Como dev, quiero patrón Singleton para DB | Alta | Sprint 1 | ✅ Done | Database.php |
| **ARC-003** | Como dev, quiero patrón Observer para notificaciones | Media | Sprint 4 | ✅ Done | NotificationManager + StockObserver |
| **ARC-004** | Como dev, quiero usar PDO para nuevos modelos | Media | Sprint 3 | ✅ Done | VentaModel, PedidoModel |
| **ARC-005** | Como dev, quiero mysqli para modelos legacy | Media | Sprint 1 | ✅ Done | User, Mesa, Producto |

---

## 🐳 Módulo 10: DevOps y Despliegue

### Historias Técnicas

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **DEV-001** | Como dev, quiero Docker para desarrollo | Alta | Sprint 1 | ✅ Done | docker-compose.yml |
| **DEV-002** | Como dev, quiero Dockerfile para la app | Alta | Sprint 1 | ✅ Done | PHP 8.2 + Apache |
| **DEV-003** | Como dev, quiero script de inicialización de BD | Alta | Sprint 1 | ✅ Done | init-db/ scripts |
| **DEV-004** | Como dev, quiero pipeline CI/CD | Media | - | ❌ Pendiente | GitHub Actions |
| **DEV-005** | Como dev, quiero monitoreo de salud (health check) | Media | - | ❌ Pendiente | Endpoint /health.php |

---

## 🧪 Módulo 11: Pruebas y Calidad

### Historias Técnicas

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **TST-001** | Como dev, quiero pruebas unitarias con PHPUnit | Alta | - | ❌ Pendiente | Configurado pero sin tests |
| **TST-002** | Como dev, quiero pruebas de integración | Media | - | ❌ Pendiente | Flujos completos |
| **TST-003** | Como dev, quiero pruebas funcionales manuales | Alta | Sprint 6 | ✅ Done | Todos los módulos probados |
| **TST-004** | Como dev, quiero pruebas de rendimiento | Media | - | ❌ Pendiente | Apache JMeter |
| **TST-005** | Como dev, quiero pruebas de seguridad | Media | - | ❌ Pendiente | OWASP ZAP |

---

## 📝 Módulo 12: Documentación

### Historias Técnicas

| ID | Historia de Usuario | Prioridad | Sprint | Estado | Notas |
|----|---------------------|-----------|--------|--------|-------|
| **DOC-001** | Como dev, quiero README del proyecto | Alta | Sprint 1 | ✅ Done | Instrucciones básicas |
| **DOC-002** | Como dev, quiero PRD actualizado | Alta | Sprint 7 | ✅ Done | v2.0 completo |
| **DOC-003** | Como dev, quiero CHANGELOG | Media | Sprint 7 | ✅ Done | Historial de cambios |
| **DOC-004** | Como dev, quiero documentación de API | Media | - | ❌ Pendiente | Endpoints documentados |
| **DOC-005** | Como dev, quiero diagramas de arquitectura | Media | Sprint 7 | ✅ Done | PDF en Documents_review |

---

## 🚀 Nuevas Funcionalidades Sugeridas (Backlog)

### Prioridad Alta

| ID | Historia de Usuario | Módulo | Justificación |
|----|---------------------|--------|---------------|
| **NF-001** | Como admin, quiero descuento automático de inventario al confirmar pedido | Inventario | Automatizar flujo completo |
| **NF-002** | Como admin, quiero reportes en PDF real (no solo HTML) | Reportes | Exportación profesional |
| **NF-003** | Como dev, quiero pruebas automatizadas con PHPUnit | Calidad | Cobertura de código |

### Prioridad Media

| ID | Historia de Usuario | Módulo | Justificación |
|----|---------------------|--------|---------------|
| **NF-004** | Como cliente, quiero personalización real de platillos (afecte precio/receta) | Pedidos | Mejorar experiencia |
| **NF-005** | Como cocinero, quiero verificación de ingredientes antes de aceptar pedido | Cocina | Evitar pedidos sin stock |
| **NF-006** | Como admin, quiero protección CSRF en formularios | Seguridad | Prevenir ataques |
| **NF-007** | Como dev, quiero pipeline CI/CD con GitHub Actions | DevOps | Despliegue automático |
| **NF-008** | Como admin, quiero dashboard con gráficas (Chart.js) | Admin | Visualización de datos |

### Prioridad Baja

| ID | Historia de Usuario | Módulo | Justificación |
|----|---------------------|--------|---------------|
| **NF-009** | Como cliente, quiero ver tiempo estimado de preparación | Pedidos | Expectativa clara |
| **NF-010** | Como admin, quiero sistema de propinas | Caja | Funcionalidad común |
| **NF-011** | Como admin, quiero múltiples idiomas (EN/ES) | Global | Internacionalización |
| **NF-012** | Como admin, quiero API REST para integraciones externas | API | Escalabilidad |
| **NF-013** | Como cliente, quiero pagar desde la mesa (pasarela pago) | Pagos | Modernización |
| **NF-014** | Como admin, quiero app móvil nativa (iOS/Android) | Móvil | Acceso móvil |

---

## 📊 Resumen por Sprint

### Sprint 1 (Completado ✅)
- Autenticación básica
- Gestión de mesas
- Gestión de usuarios
- Estructura MVC
- Docker básico

### Sprint 2 (Completado ✅)
- Menú digital para cliente
- Carrito de compras
- Gestión de menú (CRUD)
- Cocina - visualización de pedidos
- Filtros en cocina

### Sprint 3 (Completado ✅)
- Módulo de Caja
- Procesamiento de pagos
- Módulo de Mesero
- Solicitud de ayuda
- Modelos con PDO

### Sprint 4 (Completado ✅)
- Dashboard de administración
- Control de inventario
- Alertas de stock
- Recetas de productos
- Patrón Observer

### Sprint 5 (Completado ✅)
- Reportes básicos (diario, mensual, historial)
- Mejoras de UI/UX
- Optimizaciones de rendimiento

### Sprint 6 (Completado ✅)
- Pruebas funcionales
- Documentación (PRD v2.0, CHANGELOG)
- Refinamiento de código

### Sprint 7+ (Pendiente ⚠️)
- Pruebas automatizadas (PHPUnit)
- PDF real en reportes
- Descuento automático inventario
- CSRF protection
- CI/CD Pipeline
- Health check endpoint

---

## 🎯 Criterios de Aceptación Generales

1. ✅ **Código organizado:** Estructura MVC clara
2. ✅ **Seguridad básica:** Password hash, SQLi prevention, XSS prevention
3. ✅ **Roles funcionando:** 4 roles con acceso restringido
4. ✅ **Base de datos:** 11 tablas con relaciones correctas
5. ✅ **Docker:** Entorno completo con docker-compose
6. ⚠️ **Pruebas:** PHPUnit configurado, pendiente escribir tests
7. ⚠️ **Documentación:** Completa, falta documentación de API
8. ❌ **CI/CD:** Pendiente implementar

---

## 📈 Métricas del Product Backlog

- **Total de Historias:** 58
- **Implementadas:** 50 (86%)
- **Parciales:** 1 (2%)
- **Pendientes:** 7 (12%)

**Porcentaje de Completitud:** 86% ✅

---

**Última actualización:** 28 de abril de 2026  
**Próxima revisión:** Al finalizar Sprint 7
