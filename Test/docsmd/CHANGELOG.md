# Changelog - Sistema de Comanda Digital

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog,
and this project adheres to Semantic Versioning.

---

## [1.0.0] - 2026-04-27

### Added
- Sistema de pedidos completo (mesero → cocina → caja)
- Gestión de mesas con selección y liberación automática
- Menú digital con categorías y productos
- Interfaz de mesero para crear pedidos
- Interfaz de cocina para ver pedidos en preparación
- Interfaz de caja para procesar pagos
- Sistema de autenticación con roles
- Cálculo de cambio automático
- Estados del pedido: pendiente, en proceso, listo, pagado
- Búsqueda de pedidos
- Impresión de tickets
- Docker y Docker Compose para desarrollo

### Security
- Control de acceso por roles (mesero, cocinero, cajero, admin)
- Sesiones seguras con PHP

### Infrastructure
- Dockerfile configurado con PHP 8.2
- docker-compose.yml para entorno local
- Estructura de tests con PHPUnit

---

## Cambios realizados por Oswaldo Ramírez

### Fecha: 23 de abril de 2026

#### Rutas y Configuración del Sistema
- Implementación de base URL dinámica para evitar errores 404.

#### Correcciones en Panel de Administración
- Validaciones en usuarios y mesas para evitar sobrescritura.
- Inventario con selects en lugar de texto libre.

#### Mejoras
- Caja, tickets y UX mejorados.

---

### Fecha: 4 de abril de 2026

#### Correcciones
- Error en carrito.js corregido
- Archivo CSS faltante agregado

#### Mejoras
- Rediseño completo del ticket
- Mejores estilos CSS

---

### Fecha: 27 de marzo de 2026

#### Cambios
- Mejora en visualización de pedidos en caja
- Eliminada redirección automática del carrito

---

## [0.1.0] - 2026-03-02

### Added
- Análisis inicial
- Arquitectura base
- Configuración inicial