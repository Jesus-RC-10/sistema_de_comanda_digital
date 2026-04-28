# PLAN DE GESTIÓN DE PROYECTOS
## Sistema de Comanda Digital

---

## 1. INFORMACIÓN GENERAL

### 1.1 Datos del Proyecto

| Campo | Valor |
|-------|-------|
| **Proyecto** | Comanda Digital |
| **Fecha** | 07-04-2026 |
| **Sprint Actual** | 4 (En curso) |
| **Duración Sprint** | 10 días |
| **Producto** | Aplicación Web Multiplataforma |

### 1.2 Roles del Equipo

| Rol | Nombre |
|-----|--------|
| **Scrum Master** | Osornio Tapia Jorge Edmundo |
| **Product Owner** | Guadalupe Yosabeth Pérez Estrada |
| **Development Team** | Ramírez García Oswaldo, Uriel Vargas Angeles, Jesús Vicente Ríos Carrera |

---

## 2. HISTORIAL DE SPRINTS

### 2.1 Sprints Completados y Planeados

| Sprint | Fecha Inicio | Fecha Fin | Objetivo | Estado |
|--------|--------------|-----------|----------|--------|
| SPRINT 1 | 02-03-2026 | 13-03-2026 | Análisis de requisitos, diseño inicial del sistema, configuración de equipos de desarrollo | Completado |
| SPRINT 2 | 16-03-2026 | 27-03-2026 | Implementación de mesa, menú digital y lógica de Login | Completado |
| SPRINT 3 | 30-03-2026 | 10-04-2026 | Implementación de lógica de menú y finalización de lógica de Login | Completado |
| **SPRINT 4** | 13-04-2026 | 24-04-2026 | Implementación completa de caja, cocina y DevOps | **Completado** |
| SPRINT 5 | 27-04-2026 | 08-05-2026 | Implementación de Administración | Planeado |
| SPRINT 6 | 11-05-2026 | 22-05-2026 | Implementación completa de Administración | Planeado |
| SPRINT 7 | 25-05-2026 | 05-06-2026 | Pruebas | Planeado |
| SPRINT 8 | 08-06-2026 | 19-06-2026 | Preparación final, documentación y entrega del sistema | Planeado |

### 2.2 Resumen de Presupuesto

El presupuesto no se detalla monetariamente, ya que se trata de un proyecto académico con recursos internos, por lo cual el costo principal es el recurso humano (horas Hombre) y la infraestructura de hardware.

---

## 3. DEFINICIONES Y ACRÓNIMOS

| Término | Definición |
|---------|------------|
| **Backlog** | Lista priorizada de todas las funcionalidades, requisitos y mejoras necesarias para el producto. |
| **Sprint** | Bloque de tiempo de duración fija (10 días) durante el cual se crea un incremento de software "terminado". |
| **Story Points** | Unidad de medida de esfuerzo basada en la complejidad, riesgo e incertidumbre. |
| **Lead Time** | Tiempo total desde que una necesidad es identificada hasta que se entrega al cliente final. |
| **Cycle Time** | Tiempo que el equipo trabaja activamente en una tarea (desde "In Progress" hasta "Done"). |
| **Throughput** | Cantidad de unidades de trabajo completadas por unidad de tiempo. |
| **Scrum** | Marco de trabajo ágil para la entrega iterativa de software. |
| **KPI** | Indicadores métricos para medir el éxito del proceso. |
| **Burn-down Chart** | Gráfica de trabajo pendiente vs. tiempo. |

---

## 4. SPRINT 4 - PLANIFICACIÓN Y RESULTADOS

### 4.1 Objetivo del Sprint 4

> Desarrollar e integrar las funcionalidades principales del sistema de comanda digital, permitiendo gestionar pedidos desde la interfaz de mesero hasta cocina y caja.

### 4.2 Historias de Usuario Planeadas

| ID | Historia de Usuario | Prioridad | Puntos | Responsable |
|----|---------------------|-----------|--------|-------------|
| HU17 | Como sistema quiero enviar pedidos a cocina para su preparación | Alta | 8 | Oswaldo Ramírez García |
| HU-UI-02 | Como cocinero quiero ver los pedidos en una interfaz clara | Alta | 5 | Oswaldo Ramírez García |
| HU-UI-03 | Como cajero quiero ver pedidos en caja para procesar pagos | Alta | 5 | Oswaldo Ramírez García |
| HU13 | Como usuario quiero buscar pedidos fácilmente | Media | 5 | Jesús Vicente Ríos Carrera |
| HU14 | Como usuario quiero imprimir tickets de los pedidos | Media | 5 | Jesús Vicente Ríos Carrera |

### 4.3 Funcionalidades Entregadas

| Historia | Descripción | Estado |
|----------|-------------|--------|
| HU12 | Ver pedidos en sistema | Completado |
| HU13 | Buscar pedidos | Completado |
| HU14 | Imprimir ticket | Completado |
| HU16 | Marcar pedido como pagado | Completado |
| HU17 | Enviar pedidos a cocina | Completado |
| HU22 | Estados del pedido (pendiente, en proceso, listo) | Completado |
| HU-UI-01 | Interfaz de mesero | Completado |
| HU-UI-02 | Interfaz de cocina | Completado |
| HU-UI-03 | Interfaz de caja | Completado |
| HU-LOG-01 | Cálculo de cambio | Completado |
| HU-CONF-01 | Cálculo de cambio | Completado |

### 4.4 Retroalimentación de Stakeholders

**Aspectos Positivos:**
- El sistema cumple con el flujo básico esperado: mesero → cocina → caja
- La interfaz es clara y fácil de entender para usuarios nuevos
- La funcionalidad de estados de pedidos mejora la organización en cocina
- El cálculo de cambio funciona correctamente
- Se logró una solución funcional lista para pruebas reales

---

## 5. SPRINT 4 - RETROSPECTIVA

### 5.1 ¿Qué salió bien?

- Se completaron todas las historias de usuario planeadas dentro del sprint
- Se logró integrar correctamente el flujo completo del sistema (mesero → cocina → caja)
- Buena colaboración entre los integrantes del equipo
- Las interfaces desarrolladas (mesero, cocina y caja) son funcionales
- El cálculo de cambio y los estados de pedido funcionan correctamente
- Se logró una solución funcional lista para pruebas reales

### 5.2 ¿Qué se puede mejorar?

- Mejor organización del tiempo en algunas tareas
- Mayor consistencia en el diseño de interfaces (UI)
- Mejor comunicación en momentos clave del desarrollo
- Validaciones más robustas en formularios
- Implementar pruebas automatizadas (DevOps)

### 5.3 Acciones de Mejora Identificadas

| Problema | Causa | Acción de Mejora | Responsable | Sprint |
|----------|-------|------------------|-------------|--------|
| Tests no implementados | No se priorizó | Implementar PHPUnit con tests unitarios | Equipo | Sprint 5 |
| Consistencia UI | Sin guía de estilos | Crear sistema de diseño unificado | Jesús Vicente | Sprint 5 |
| Validaciones | Lógica dispersa | Centralizar validaciones en helpers | Oswaldo | Sprint 5 |

---

## 6. MÉTRICAS Y KPIs

### 6.1 Sprint Velocity

| Sprint | SP Comprometidos | SP Terminados | Eficiencia (Done/Planned) | Observaciones |
|--------|------------------|---------------|---------------------------|---------------|
| Sprint 1 | 7 | 7 | 1.00 | Incentivar el trabajo en equipo |
| Sprint 2 | 32 | 32 | 1.00 | Modificación de SP por observaciones del PO |
| Sprint 3 | 18 | 0 | 0 | En revisión |
| Sprint 4 | 24 | 11 | 0.46 | En curso - funcionalidades completas |

### 6.2 Throughput (Promedio)

| Sprint | Historias Completadas |
|--------|----------------------|
| Sprint 1 | 7 |
| Sprint 2 | 6 |
| Sprint 3 | 3 |
| **Promedio** | **5.3 historias por sprint** |

### 6.3 Cycle Time (Tiempo de Desarrollo)

| Historia | Días |
|----------|------|
| HU001 | 5 |
| HU002 | 2 |
| HU003 | 2 |
| HU004 | 1 |
| HU006 | 1 |
| HU007 | 1 |
| HU01 | 2 |
| HU02 | 4 |
| HU04 | 3 |
| HU06 | 2 |
| HU39 | 6 |
| HU40 | 4 |
| **Promedio** | **3.17 días por historia** |

### 6.4 Lead Time (Tiempo Total)

| Historia | Días |
|----------|------|
| HU001 | 6 |
| HU002 | 3 |
| HU003 | 3 |
| HU004 | 2 |
| HU005 | 3 |
| HU006 | 2 |
| HU007 | 2 |
| HU01 | 2 |
| HU02 | 7 |
| HU04 | 11 |
| HU06 | 2 |
| HU39 | 6 |
| HU40 | 14 |
| HU07 | 18 |
| **Promedio** | **5.7 días por historia** |

### 6.5 Calidad (Bug Rate)

| Sprint | HU Entregadas | Bugs (QA) | Tasa de Error |
|--------|---------------|-----------|---------------|
| Sprint 1 | 7 | 2 | 0.28 |
| Sprint 2 | 8 | 3 | 0.37 |

---

## 7. INFRAESTRUCTURA Y HERRAMIENTAS

### 7.1 Stack Tecnológico

| Componente | Tecnología |
|------------|------------|
| **Backend** | PHP 8.x con arquitectura modular |
| **Base de Datos** | MySQL (MariaDB) |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+) |
| **Control de Versiones** | Git + GitHub |
| **Contenedores** | Docker + Docker Compose |

### 7.2 Herramientas de Desarrollo

- **IDE**: Visual Studio Code
- **Servidor Local**: XAMPP / Docker
- **Diagramación**: Lucidchart
- **Gestión de Proyecto**: Trello
- **Ofimática**: Microsoft Office / Google Docs

---

## 8. GESTIÓN DE RIESGOS

| Riesgo | Impacto | Probabilidad | Plan de Mitigación |
|--------|--------|---------------|-------------------|
| Concurrencia de Mesas | Alto | Medio | Implementar "Flag" de bloqueo en BD cuando una mesa está siendo editada |
| Pérdida de Sesión | Medio | Baja | Uso de cookies persistentes y manejo de estados en servidor |
| Inconsistencia de Stock | Alto | Medio | Transacciones SQL atómicas |
| Caída del servidor local | Alto | Medio | Backups automáticos cada 4 horas |
| Incompatibilidad de tablets | Medio | Medio | Desarrollo basado en diseño responsivo |

---

## 9. PLAN DEV OPS - IMPLEMENTACIÓN

*(Ver documento separado: `PLAN_DEVOPS_IMPLEMENTACION.md`)*

### 9.1 Estado Actual DevOps

| Fase | Estado | Descripción |
|------|--------|-------------|
| Planificación | ✅ Completo | Documentación, backlog, priorización |
| Codificación | ✅ Completo | Git, branching, commits |
| Construcción | ✅ Completo | Dockerfile + docker-compose |
| **Pruebas** | ❌ Pendiente | PHPUnit - Implementar en Sprint 5 |
| **Lanzamiento** | ❌ Pendiente | Tags, changelog - Implementar en Sprint 5 |
| **Despliegue** | ⚠️ Parcial | CI/CD local - Completar en Sprint 5 |
| **Operación** | ❌ Pendiente | Backups, escalado - Implementar en Sprint 6 |
| **Monitorización** | ❌ Pendiente | Logs, alertas - Implementar en Sprint 6 |

---

## 10. CRONOGRAMA GENERAL

### 10.1 Roadmap de Sprints

```
Sprint 1 ████████████ 100%  (Completado)
Sprint 2 ████████████ 100%  (Completado)
Sprint 3 ████████████ 100%  (Completado)
Sprint 4 ████████████ 100%  (Completado)
Sprint 5 ░░░░░░░░░░░░  0%   (Pendiente - Abril 27)
Sprint 6 ░░░░░░░░░░░░  0%   (Pendiente - Mayo 11)
Sprint 7 ░░░░░░░░░░░░  0%   (Pendiente - Mayo 25)
Sprint 8 ░░░░░░░░░░░░  0%   (Pendiente - Junio 8)
```

### 10.2 Entregables por Sprint

| Sprint | Entregable Principal |
|--------|---------------------|
| Sprint 1 | Análisis de requisitos y diseño inicial |
| Sprint 2 | Módulos de mesa, menú y login |
| Sprint 3 | Lógica de menú completada |
| Sprint 4 | Flujo completo: mesero → cocina → caja |
| **Sprint 5** | **Administración + Tests DevOps** |
| Sprint 6 | Administración completa + Operaciones |
| Sprint 7 | Pruebas de estrés y validación |
| Sprint 8 | Documentación final y entrega |

---

## 11. CRITERIOS DE ACEPTACIÓN

El sistema será aceptado cuando:

- [ ] Cada Historia de Usuario pase las pruebas unitarias
- [ ] Permita seleccionar mesas correctamente
- [ ] Muestre el menú sin errores
- [ ] Evite duplicidad de mesas
- [ ] Controle accesos por roles
- [ ] Funcione correctamente la comunicación frontend-backend
- [ ] Se completen las fases DevOps (Pruebas, Monitorización, etc.)

---

## 12. ORGANIZACIÓN DEL EQUIPO

### 12.1 Reglas de Trabajo

- **Commit obligatorio** al final de cada jornada
- **Daily Scrum** máximo 15 minutos a las 9:00 AM
- **Revisión de código** cruzada entre integrantes
- **Comunicación** mediante canal de WhatsApp grupal

### 12.2 Distribución de Carga

Ningún desarrollador debe tener más del 40% de la carga total del Sprint para evitar cuellos de botella.

---

## 13. ANEXOS

- Anexo A – Product Backlog
- Anexo B – Sprint Backlog
- Anexo C – Sprint Review (este documento)
- Anexo D – Daily Scrum
- Anexo E – Sprint Retrospective
- Anexo F – KPIs
- Anexo G – Kanban

---

*Documento actualizado: Sprint 4 - Abril 2026*
*Ubicación: Documents_review/Sprint 4/*