# PRD - Sistema de Comanda Digital
## Product Requirements Document (IEEE 16326 + Scrum + DevOps)

---

## TABLA DE CONTENIDOS

1. [Información General](#1-información-general)
2. [Análisis: Código vs Documentación](#2-análisis-código-vs-documentación)
3. [Arquitectura del Sistema](#3-arquitectura-del-sistema)
4. [Requisitos Funcionales](#4-requisitos-funcionales)
5. [Requisitos No Funcionales](#5-requisitos-no-funcionales)
6. [Gestión del Proyecto (Scrum)](#6-gestión-del-proyecto-scrum)
7. [DevOps - Estado Actual](#7-devops---estado-actual)
8. [Distribución de Tareas por Rol](#8-distribución-de-tareas-por-rol)
9. [Críticas y Recomendaciones](#9-críticas-y-recomendaciones)
10. [Product Backlog](#10-product-backlog)
11. [Criterios de Aceptación](#11-criterios-de-aceptación)
12. [Anexos](#12-anexos)

---

## 1. INFORMACIÓN GENERAL

| Campo | Valor |
|-------|-------|
| **Proyecto** | Sistema de Comanda Digital |
| **Versión** | 1.0.0-beta |
| **Fecha** | 28 Abril 2026 |
| **Duración Sprint** | 14 días |
| **Metodología** | Scrum + DevOps |
| **Tipo** | Aplicación Web Multiplataforma (PHP/MySQL) |

### 1.1 Equipo de Proyecto

| Rol | Nombre | Responsabilidad Principal |
|----|--------|-------------------------|
| **Scrum Master** | Jorge Edmundo Osornio Tapia | Facilitación Scrum, removes blockers, ceremonies, gestión sprints |
| **Product Owner** | Yosabeth Pérez Estrada | Priorización backlog, validación con negocio, stakeholder management |
| **Dev 1** | Oswaldo Ramírez García | Backend: Caja, Pedidos, Ventas, PDF/tickets, database |
| **Dev 2** | Uriel Vargas Angeles | Frontend: Mesero, Cocina, UI/UX interfaces |
| **Dev 3** | Jesús Vicente Ríos Carrera | Admin: Dashboard, Reportes, Inventario, métricas |

### 1.2 Propósito del Sistema
Sistema de gestión de pedidos digitales para restaurantes que permite el flujo completo: **Mesero → Cocina → Caja**, con administración centralizada, control de inventario y reportes.

### 1.3 Alcance del Proyecto
- **In-scope**: Gestión de mesas, menú digital, pedidos, cocina, caja, administración, inventario, reportes,observer pattern para alertas.
- **Out-of-scope**: App móviles nativas, pedidos delivery, integración con sistemas externos de pago (Stripe, PayPal), módulo de comandas físicas.

---

## 2. ANÁLISIS: CÓDIGO VS DOCUMENTACIÓN

### 2.1 Lo que SÍ está implementado (verificado en código)

| Módulo | Archivo(s) | Estado Real |
|--------|-----------|-------------|
| **Selección de mesas** | `MesaController.php`, `MesaModel.php` | ✅ Done - cambio de estado libre/ocupada |
| **Menú digital** | `MenuController.php`, `ProductoModel.php`, tabla `productos` | ✅ Done - con categorías, precios |
| **Crear pedidos** | `OrderController.php`, `PedidoModel.php`, `OrderModel.php` | ✅ Done - flujo completo |
| **Interfaz cocina** | `CocinaController.php`, `views/cocina/cocina.php` | ✅ Done - lista pedidos |
| **Cocina preparación** | Actualización estado `pedido_detalles.estado` | ✅ Done - pendiente→en_preparacion→listo→entregado |
| **Interfaz caja** | `CajaController.php`, `VentaModel.php` | ✅ Done - ventas pendientes/pagadas |
| **Cálculo cambio** | `CajaController.php:47` | ✅ Done - `$cambio = $monto_pagado - $total` |
| **Autenticación** | `AuthController.php` | ✅ Done - login con roles |
| **Dashboard admin** | `AdminController.php`, `views/admin/dashboard.php` | ✅ Done - métricas ventas |
| **Gestión inventario** | `Inventario.php`, `views/admin/inventario.php` | ✅ Done - ingredientes CRUD |
| **Reportes PDF** | `ReportController.php`, `generar_pdf.php` | ✅ Done - reportes diarios |
| **Observer pattern** | `NotificationManager.php`, `StockObserver.php` | ✅ Done - alertas stock |
| **Tabla alertas_sistema** | `alertas_sistema` en BD | ✅ Done - integrada en AdminController |
| **Docker** | `Dockerfile`, `docker-compose.yml` | ✅ Done - web + db |

### 2.2 Lo que está CREADO pero NO funciona correctamente

| Componente | Archivo | Problema | Severidad | docker/XAMPP |
|------------|---------|----------|----------|-------------|
| **Tests unitarios** | `tests/Unit/*.php` | ❌ No están listos para usarse | Pendiente |
| **phpunit.xml** | `phpunit.xml` | ⚠️ Incompleto | Por configurar |
| **Logger** | `helpers/Logger.php` | Existe pero **NO se usa** | Pendiente integrar |
| **backups/** | `scripts/backup.sh` | Script existe sin cron | Pendiente |
| **GitHub Actions** | N/A | No configurado | Pendiente |

### 2.3 Código Duplicado - RAZÓN: XAMPP vs Docker

**⚠️ IMPORTANTE: Los archivos duplicados SON INTENCIONALES**

Existieron problemas de compatibilidad entre XAMPP y Docker con las conexiones a la base de datos:

```
PROBLEMA IDENTIFICADO:
├── XAMPP: mysql_connect / mysqli (direto)
└── Docker: PDO (contenedor)
```

**Por eso existen archivos duplicados:**
```
models/Mesa.php         ← Para XAMPP (mysqli directo)
models/MesaModel.php     ← Para Docker (PDO)

models/Producto.php     ← Para XAMPP  
models/ProductoModel.php ← Para Docker

models/Pedido.php        ← Para XAMPP
models/PedidoModel.php  ← Para Docker
models/OrderModel.php   ← Para Docker
```

**SOLUCIÓN ACTUAL:**
- El sistema ya funciona con Docker correctamente
- Los archivos duplicados son **legacy** de la etapa de desarrollo con XAMPP
- **NO eliminar** - quedan como backups para compatibilidad futura
- En producción solo usar versions **Model.php** (Docker)

### 2.4 Lo que NO está implementado (pendiente)

| Requisito | Estado | docker/XAMPP | Impacto |
|-----------|--------|-------------|----------|
| CI/CD (GitHub Actions) | ❌ Pendiente | ❌ No hay pipeline | Alto |
| Tests unitarios funcionales | ❌ Pendiente | No están listos | Alto |
| Backups automáticos (cron) | ❌ Pendiente | Script existe | Medio |
| CRUD usuarios admin completo | ⚠️ Parcial | Medio | Sprint 5 |
| Impresión tickets física | ⚠️ Parcial | Bajo | Sprint 6 |
| Integración con printer ESC/POS | ❌ Pendiente | Bajo | Sprint 6 |
| Métricas avanzadas dashboard | ❌ Pendiente | Medio | Sprint 6 |

### 2.4 Código Duplicado/Problemático - CORREGIR

| Archivo A | Archivo B | Problema | Acción |
|-----------|-----------|----------|--------|
| `models/Mesa.php` | `models/MesaModel.php` | Duplicado - lógica分散a | Unificar en MesaModel |
| `models/Producto.php` | `models/ProductoModel.php` | Duplicado probable | Unificar en ProductoModel |
| `models/Pedido.php` | `models/PedidoModel.php` + `OrderModel.php` | 3 archivos similares | Unificar en uno |

### 2.5 Inconsistencias BD

- Tabla `alertas_sistema` tiene datos pero no hay proceso automático que la llene (solo se inserta manualmente o por triggers no implementados)
- Stock bajo detecta en código PHP pero no genera alertas automáticamente en BD

---

## 3. ARQUITECTURA DEL SISTEMA

### 3.1 Diagrama de Arquitectura

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    CLIENTE (Browser)                      │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────────┐  │
│  │ Mesero  │  │ Cocina  │  │  Caja   │  │    Admin   │  │
│  │  View  │  │  View  │  │  View  │  │   Views    │  │
│  └────┬────┘  └────┬────┘  └────┬────┘  └──────┬────┘  │
└───────┼────────────┼────────────┼──────────────┼───────┘
        │            │            │              │
        └────────────┴────────────┴──────────────┘
                          │
                    index.php (Front Controller)
                          │
         ┌──────────────────┼──────────────────┐
         │                  │                  │
    ┌────┴────┐      ┌─────┴────┐      ┌──────┴──────┐
    │  Mesa   │      │  Order   │      │    Admin    │
    │Controller│     │Controller│     │ Controller  │
    └────┬────┘      └─────┬────┘      └──────┬──────┘
         │                 │                  │
    ┌────┴────┐      ┌─────┴────┐      ┌──────┴──────┐
    │  Mesa   │      │  Order   │      │   Inventario │
    │  Model  │      │  Model   │      │   Observer  │
    └────┬────┘      └─────┬────┘      └──────┬──────┘
         │                 │                  │
         └─────────────────┼──────────────────┘
                           │
                    Database (MySQL)
                    ┌────────────────┐
                    │    ventas      │
                    │    pedidos    │
                    │    inventario │
                    └────────────────┘
```

### 3.2 Stack Tecnológico

| Componente | Tecnología | Versión | Estado |
|------------|-------------|--------|--------|
| Backend | PHP | 8.2 | ✅ Funcionando |
| Base de datos | MySQL (MariaDB) | 8.0 | ✅ Funcionando |
| Frontend | HTML5, CSS3, JavaScript | ES6+ | ✅ Funcionando |
| Contenedores | Docker | Latest | ✅ Funcionando |
| Control de versiones | Git + GitHub | - | ✅ Funcionando |
| Testing | PHPUnit | 10.x | ❌ No funcional |
| Logging | Custom Logger | - | ⚠️ Existe sin usar |
| CI/CD | GitHub Actions | - | ❌ No configurado |
| Version | Git + GitHub | - | ✅ Funcionando |

### 3.3.1 Docker - Estado Actual

```
✅ IMPLEMENTADO Y FUNCIONANDO:
├── Dockerfile                    # PHP 8.2 + Apache
├── docker-compose.yml           # web + db servicios
└── init-db/                   # SQL auto-import en startup
```

**Servicios:**
| Servicio | Puerto | Estado |
|----------|--------|--------|
| web | 8081 | ✅ Funcionando |
| db | 3307 | ✅ Funcionando |

### 3.3.2 XAMPP - Legacy

```
⚠️ LEGACY - Ya no se usa activamente
- Puerto MySQL: 3306
- Puerto Web: 80
- Mantenido para compatibilidad
```

### 3.3.5 Diagrama de Arquitectura del Sistema

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│                           SISTEMA DE COMANDA DIGITAL                              │
│                              (Arquitectura MVC)                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐     │
│  │                      CLIENTE (Browser)                              │     │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────┐ │     │
│  │  │ Mesero   │  │ Cocina   │  │  Caja   │  │  Admin  │  │ Login│ │     │
│  │  │  View   │  │  View   │  │  View   │  │  Views  │  │ View │ │     │
│  │  └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘  └──┬───┘ │     │
│  │       │            │            │            │           │       │       │     │
│  │       └────────────┴────────────┴────────────┴───────────┘       │       │     │
│  └──────────────────────────────────────────────────────────────┬────┘       │
│                                                             │            │
│                                                    index.php ◄────────────┘
│                                                    (Front Controller)
│                                                             │
├─────────────────────────────────────────────────────────────────┴────────────────┤
│                              CORE PHP                                    │
│  ┌──────────────────────────────────────────────────────────────────────┐    │
│  │                    CONTROLLERS (Lógica de negócio)               │    │
│  │  ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────────┐  │    │
│  │  │Mesa       │ │Order      │ │Caja       │ │Admin          │  │    │
│  │  │Controller │ │Controller │ │Controller │ │Controller     │  │    │
│  │  └────┬─────┘ └────┬─────┘ └────┬─────┘ └──────┬───────┘  │    │
│  │       │            │            │               │          │        │    │
│  │       └────────────┴────────────┴───────────────┴──────────┘          │    │
│  │                     │                                          │    │
│  │  ┌──────────────────┴──────────────────────────────────┐       │    │
│  │  │              MODELS (Datos + Lógica)                    │       │    │
│  │  │  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌─────────┐  │       │    │
│  │  │  │ Mesa     │ │ Pedido   │ │ Venta   │ │Inventario│  │       │    │
│  │  │  │ Model   │ │ Model   │ │ Model   │ │  Model  │  │       │    │
│  │  │  └──────────┘ └──────────┘ └──────────┘ └─────────┘  │       │    │
│  │  │  ┌─────────────────────────────────────────────────────┐ │       │    │
│  │  │  │              OBSERVER PATTERN                      │ │       │    │
│  │  │  │  ┌────────────────┐  ┌──────────────────┐   │ │       │    │
│  │  │  │  │Notification  │──│  StockObserver   │   │ │       │    │
│  │  │  │  │ Manager      │  │  (alertas stock) │   │ │       │    │
│  │  │  │  └────────────────┘  └──────────────────┘   │ │       │    │
│  │  │  └─────────────────────────────────────────────────────┘ │       │    │
│  │  └──────────────────────────────────────────────────────┘       │    │
│  └──────────────────────────────────────────────────────────────────┘    │
│                                     │                                      │
│  ┌──────────────────────────────────┴─────────────────────────────────┐  │
│  │                    HELPERS & UTILITIES                               │  │
│  │  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────────┐  │  │
│  │  │  Logger    │  │  Database   │  │  Config (BASE_URL, etc) │  │  │
│  │  │ (logs)     │  │ (PDO)      │  │                    │  ��  │
│  │  └──────────────┘  └──────────────┘  └──────────────────────────┘  │  │
│  └───────────────────────────────────────────────────────────────────┘   │
│                                    │                                       │
│                            DATABASE ◄─────────────────────────────────────────┘
│  ┌───────────────────────────────────────────────────────────────────────┐
│  │                         MySQL / MariaDB                              │
│  │  ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌─────────────────┐  │
│  │  │  mesas   │ │pedidos   │ │ventas    │ │  usuarios     │  │
│  │  │  productos│ │pedido_   │ │inventario│ │categorias_menu   │  │
│  │  │         │ │detalles │ │ingredientes│ │alertas_sistema  │  │
│  │  └────────────┘ └────────────┘ └────────────┘ └─────────────────┘  │
│  └───────────────────────────────────────────────────────────────────────┘
│                                    │
│  ┌─────────────────────────────────┴───────────────────────────────────┐
│  │                      DOCKER ENVIRONMENT                            │
│  │  ┌─────────────────┐  ┌─────────────────┐  ┌──────────────────┐  │
│  │  │  web (PHP 8.2) │  │ db (MySQL 8.0)  │  │ phpmyadmin      │  │
│  │  │  :8081         │  │ :3307          │  │ :8082          │  │
│  │  │  Apache       │  │               │  │               │  │
│  │  └─────────────────┘  └─────────────────┘  └──────────────────┘  │
│  └───────────────────────────────────────────────────────────────────────┘
└─────────────────────────────────────────────────────────────────────────────────────┘
```

### 3.4 Flujo de Datos del Sistema

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        FLUJO: CREAR PEDIDO                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  MESERO                      COCINA                      CAJA        │
│  ======                      ======                      ====        │
│                                                                     │
│  1. Selecciona mesa           4. Ver pedidos         7. Ver ventas    │
│     (MesaController)            pendientes            pendientes      │
│        │                          │                       │          │
│        ▼                          ▼                       ▼          │
│  2. Ve menú digital        5. Actualiza estado       8. Procesa     │
│     (MenuController)          (CocinaController)        pago           │
│        │                          │                       │          │
│        ▼                          ▼                       ▼          │
│  3. Crea pedido           6. Marcar como listo    9. Calcular      │
│     (OrderController)        (PedidoModel)            cambio          │
│     → OrderModel              → actualizar             (CajaController)
│     → PedidoModel              EstadoDetalle           → VentaModel  │
│     → VentaModel                                                        │
│                                                                     │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │                   ESTADOS DEL PEDIDO                         │   │
│  │  pendiente → confirmado → en_preparacion → listo → entregado │   │
│  └─────────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────┘
```

### 3.5 Modelo de Datos (Schema)

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│    mesas     │     │   pedidos   │     │  usuarios   │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id PK       │◄────│ mesa_id FK │     │ id PK       │
│ numero_mesa │     │ usuario_id │◄────│ usuario    │
│ estado      │     │ estado     │     │ password   │
│ ubicacion   │     │ total      │     │ nombre     │
│ activa      │     │ fecha_cre  │     │ rol        │
└──────────────┘     └──────┬─────┘     └──────────────┘
                            │
                     ┌──────┴──────┐
                     │pedido_detalles│
                     ├──────────────┤
                     │ pedido_id FK│◄──┐
                     │ producto_id│◄──┼─┐
                     │ cantidad   │   │ │
                     │ precio     │   │ │
                     │ estado     │   │ │
                     └────────────┘   │ │
                            │      │ │
                     ┌──────┴──────┐   │ │
                     │ productos  │   │ │
                     ├──────────────┤   │ │
                     │ id PK     │───┘ │
                     │ nombre    │     │
                     │ precio   │     │
                     │categoria│    │
                     │ stock   │     │
                     └─────────┘     │
                            │      │
                     ┌──────┴──────┐  │
                     │categorias_  │  │
                     │menu       │  │
                     ├──────────────┤  │
                     │id PK     │────┘
                     │nombre   │
                     │activa   │
                     └─────────┘

┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   ventas    │     │  inventario │     │ingredientes │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id PK      │     │producto_id │◄────│ id PK      │
│ pedido_id  │◄────│ cantidad_  │     │ nombre     │
│total      │     │ actual     │     │ categoria  │
│metodo_pago│     │cantidad_   │     │cantidad_   │
│estado    │     │ minima    │     │actual     │
│fecha_pago│     └───────────┘     │ minima    │
└──────────────┘                   └──────────┘

┌─────────────────┐
│ alertas_sistema │
├─────────────────┤
│ id PK          │
│ tipo          │
│ mensaje      │
│ nivel        │
│ leida        │
│ fecha_creac  │
└─────────────────┘
```

### 3.6 Componentes del Sistema - Detalle de Archivos

```
sistema_de_comanda_digital/
│
├── 📄 index.php                    ← Front Controller (Single Entry Point)
├── 📄 generar_pdf.php              ← Generación reportes/PDF
│
├── 📁 config/
│   ├── config.php                ← Config global (BASE_URL, ASSETS_URL)
│   ├── database.php              ← PDO connection
│   └── db.php                  ← Alias/database helpers
│
├── 📁 controllers/              ← Lógica de negócio (8 archivos)
│   ├── AdminController.php       ← CRUD admin + dashboard
│   ├── AuthController.php      ← Login/logout
│   ├── CajaController.php     ← Pagos + cálculo cambio [OSWALDO]
│   ├── CocinaController.php   ← Kitchen UI + estados [URIEL]
│   ├── MenuController.php      ← Menú digital
│   ├── MesaController.php     ← Mesas
│   ├── MeseroController.php  ← View mesero
│   ├── OrderController.php   ← Pedidos REST API
│   └── ReportController.php  ←PDF reports [OSWALDO]
│
├── 📁 models/                  ← Datos + lógica (15+ archivos)
│   ├── Mesa.php ⚠️           ← DUPLICADO - usar MesaModel
│   ├── MesaModel.php        ← ✅ Modelo mesa activo
│   ├── Pedido.php ⚠️       ← DUPLICADO - usar OrderModel
│   ├── OrderModel.php       ← ✅ Pedidos
│   ├── PedidoModel.php     ← ✅ Detalles pedido
│   ├── Producto.php ⚠️      ← DUPLICADO
│   ├── ProductoModel.php  ← ✅ Productos
│   ├── Inventario.php    ← ✅ Inventario
│   ├── Venta.php         ← ✅ Ventas  
│   ├── VentaModel.php    ← ✅ Pagos [OSWALDO]
│   ├── User.php          ← ✅ Usuarios
│   ├── VentaModel.php   ← ✅ Ventas
│   │
│   └── 📁 observers/
│       ├── NotificationManager.php ← ✅ Observer pattern
│       └── StockObserver.php     ← ✅ Alertas stock
│
├── 📁 views/                  ← UI Templates (18+ archivos)
│   ├── auth/login.php
│   ├── admin/
│   │   ├── dashboard.php     ← Métricas
│   │   ├── inventario.php   ← Stock
│   │   ├── menu.php        ← Productos
│   │   ├── mesas.php      ← CRUD
│   │   ├── reportes.php    ← PDF reports
│   │   └── usuarios.php   ← Users
│   ├── caja/caja.php       ← Interfaz caja [OSWALDO]
│   ├── cocina/cocina.php   ← Cocina UI [URIEL]
│   ├── layout/
│   ├── menu/menu.php
│   └── mesero/mesero.php
│
├── 📁 helpers/
│   └── Logger.php           ← ⚠️ Existe pero NO se usa
│
├── 📁 scripts/
│   ├── backup.sh          ← ⚠️ Sin cron
│   └── run-tests.sh       ← ⚠️ Sin automatizar
│
├── 📁 tests/
│   ├── bootstrap.php      ← ⚠️ Incompleto
│   └── Unit/
│       ├── ProductoTest.php
│       ├── PedidoTest.php
│       ├── MesaTest.php
│       └── InventarioTest.php
│
├── 📁 public/
│   ├── css/
│   ├── js/
│   └── images/platillos/
│
├── 📁 logs/                 ← ✅ app.log, error.log
├── 📁 init-db/              ← SQL schema + seed data
│   └── sistema_comanda_digital_v1.sql
│
├── 📁 Documents_review/       ← Scrum docs (PDFs)
│
├── 📄 Dockerfile          ← PHP 8.2 Apache
├── 📄 docker-compose.yml  ← web + db
├── 📄 composer.json      ← PHPUnit dependency
├── 📄 phpunit.xml       ← ⚠️ Incompleto
└── 📄 CHANGELOG.md       ← Versiones
```

### 3.7 Call Flow - Ejemplo: Crear Pedido

```
┌───────���──────────────────────────────────────────────────────────┐
│              Call Flow: CREAR PEDIDO (Mesero)                    │
├──────────────────────────────────────────────────────────────────┤
│                                                           │
│  1. Browser → index.php?url=order/save (POST JSON)          │
│                         │                                  │
│  2. index.php carga → OrderController::save()            │
│                         │                                  │
│  3. OrderController → OrderModel::saveOrder()           │
│                         │                                  │
│  4. OrderModel → PDO Transaction                         │
│     ┌─────────────────────────────────┐             │
│     │ INSERT pedidos (estado=pendiente) │             │
│     │ INSERT pedido_detalles (foreach) │             │
│     │ UPDATE pedidos SET total        │             │
│     └─────────────────────────────────┘             │
│                         │                                  │
│  5. OrderController → VentaModel::crearVentaPendiente()
│                         │                                  │
│  6. VentaModel → INSERT ventas (estado=pendiente)        │
│                         │                                  │
│  7. Response JSON → {status: success, pedido_id: X} │
│                         │                                  │
│  8. Browser → redirect to cocina view                │
│                                                           │
└──────────────────────────────────────────────────────────────────┘
```

### 3.8 Call Flow - Ejemplo: Pagar (Caja)

```
┌──────────────────────────────────────────────────────────────────┐
│              Call Flow: PAGAR (Caja)                            │
├──────────────────────────────────────────────────────────────────┤
│                                                           │
│  1. Browser → index.php?url=caja/pagar (POST)                │
│                         │                                  │
│  2. CajaController::pagar()                               │
│     a) Obtener venta por ID                                │
│     b) Validar monto_pagado >= total                      │
│     c) Calcular cambio = monto_pagado - total              │
│                                                           │
│  3. VentaModel::pagarVenta()                             │
│     └─ UPDATE ventas SET estado='pagado',                │
│              fecha_pago=NOW(), usuario_id=X                │
│                                                           │
│  4. PedidoModel::cerrarPedido()                            │
│     └─ UPDATE pedidos SET estado='entregado'               │
│                         │                                  │
│  5. Redirect → index.php?url=caja + mensaje              │
│     "Cambio: $XX.XX"                                      │
│                                                           │
│  ⚠️ TODO: Agregar Logger::info('pago', pedido_id)         │
│  ⚠️ TODO: Agregar Logger::error() si falla                │
└──────────────────────────────────────────────────────────────────┘
```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│    mesas     │     │   pedidos   │     │  usuarios   │
├──────────────┤     ├──────────────┤     ├──────────────┤
│ id          │◄────│ mesa_id     │     │ id          │
│ numero_mesa │     │ usuario_id  │◄────│ usuario     │
│ estado      │     │ estado      │     │ password    │
│ ubicacion   │     │ total       │     │ nombre      │
│ activa      │     │ fecha_creac │     │ rol         │
└──────────────┘     └──────┬──────┘     └──────────────┘
                            │
                     ┌──────┴──────┐
                     │pedido_detalles│
                     ├──────────────┤
                     │ pedido_id   │◄──┐
                     │ producto_id │◄──┼─┐
                     │ cantidad   │   │ │
                     │ precio     │   │ │
                     │ estado     │   │ │
                     └────────────┘   │ │
                            │        │ │
                     ┌──────┴──────┐   │ │
                     │ productos  │   │ │
                     ├──────────────┤   │ │
                     │ id          │───┘ │
                     │ nombre      │     │
                     │ precio      │     │
                     │ categoria_id│    │
                     │ stock       │     │
                     └─────────────┘     │
                            │          │
                     ┌──────┴──────┐    │
                     │ categorias_menu│   │
                     ├──────────────┤    │
                     │ id          │────┘
                     │ nombre      │
                     │ activa      │
                     └─────────────┘
                            │
                     ┌──────┴──────┐
                     │  inventario │
                     ├──────────────┤
                     │ producto_id │◄─ FOREIGN KEY
                     │ cantidad_actual
                     │ cantidad_minima
                     └─────────────┘

┌──────────────┐     ┌──────────────┐
│   ventas     │     │  ingredientes│
├──────────────┤     ├──────────────┤
│ id           │     │ id           │
│ pedido_id    │◄────│ nombre       │
│ total        │     │ categoria    │
│ metodo_pago  │     │ cantidad_actual│
│ estado       │     │ cantidad_minima│
│ fecha_pago   │     │ proveedor    │
└──────────────┘     └──────────────┘
```

---

## 4. REQUISITOS FUNCIONALES (IEEE 16326)

### 4.1 Requisitos por Módulo

| ID | Requisito | Prioridad | Estado | Responsable | Sprint |
|----|----------|----------|--------|-------------|--------|
| **RF01** | Selección y cambio de estado de mesas (libre/ocupada) | Alta | ✅ Done | Oswaldo | 2 |
| **RF02** | Menú digital con categorías de productos | Alta | ✅ Done | Oswaldo | 2-3 |
| **RF03** | Crear pedido desde mesa con productos | Alta | ✅ Done | Oswaldo | 2 |
| **RF04** | Ver pedidos pendientes en cocina | Alta | ✅ Done | Uriel | 4 |
| **RF05** | Cambiar estado de preparación (pendiente→en_preparacion→listo→entregado) | Alta | ✅ Done | Uriel | 4 |
| **RF06** | Ver lista de ventas pendientes en caja | Alta | ✅ Done | Oswaldo | 4 |
| **RF07** | Procesar pago en caja (efectivo) | Alta | ✅ Done | Oswaldo | 4 |
| **RF08** | Cálculo de cambio automático | Alta | ✅ Done | Oswaldo | 4 |
| **RF09** | Dashboard admin con métricas (ventas hoy, pedidos activos, mesas ocupadas) | Media | ✅ Done | Jesús | 5 |
| **RF10** | Gestión de inventario (ingredientes CRUD) | Media | ✅ Done | Jesús | 5 |
| **RF11** | Alertas de stock bajo (observer pattern) | Media | ✅ Done | Jesús | 5 |
| **RF12** | Reportes de ventas (generar PDF) | Media | ✅ Done | Jesús | 6 |
| **RF13** | CRUD usuarios admin | Media | ⚠️ Parcial | Jesús | 5 |
| **RF14** | CRUD mesas admin | Media | ✅ Done | Jesús | 5 |
| **RF15** | CRUD productos menú | Media | ✅ Done | Jesús | 5 |
| **RF16** | Impresión tickets | Baja | ⚠️ Parcial | Oswaldo | 6 |
| **RF17** | Búsqueda de pedidos | Baja | ✅ Done | - | 4 |
| **RF18** | Cancelar venta | Baja | ✅ Done | Oswaldo | 4 |

### 4.2 Definición de Estados

#### Mesas
- `libre`: Disponible para asignación
- `ocupada`: Con pedido activo
- `reservada`: Reservada para futuras fechas
- `mantenimiento`: No disponible

#### Pedidos
- `pendiente`: Recién creado, esperando confirmación
- `confirmado`: Confirmado por cocina
- `en_preparacion`: Siendo preparado
- `listo`: Listo para servir
- `entregado`: Entregado al cliente
- `cancelado`: Cancelado

#### Pedido Detalles
- `pendiente`: En cola
- `en_preparacion`: Preparándose
- `listo`: Listo
- `entregado`: Entregado

#### Ventas
- `pendiente`: Esperando pago
- `pagado`: Pagado
- `cancelado`: Cancelado

---

## 5. REQUISITOS NO FUNCIONALES

| ID | Requisito | Estado | Acción |
|----|----------|--------|--------|
| **RNF01** | Tiempo de respuesta < 2s | ✅ Cumple | - |
| **RNF02** | Tests unitarios automatizados | ❌ Pendiente | Implementar en Sprint 5 |
| **RNF03** | Cobertura de tests > 70% | ❌ Pendiente | Implementar en Sprint 5 |
| **RNF04** | Logging centralizado | ⚠️ Existe código sin usar | Conectar Logger en controllers |
| **RNF05** | Backups automáticos | ❌ Pendiente | Configurar cron en Sprint 5 |
| **RNF06** | CI/CD pipeline | ❌ Pendiente | GitHub Actions en Sprint 5 |
| **RNF07** | Deploy automatic staging | ❌ Pendiente | En Sprint 5 |
| **RNF08** | Salud del sistema (healthcheck) | ⚠️ Parcial | Completar en Sprint 5 |

---

## 6. GESTIÓN DEL PROYECTO (SCRUM)

### 6.1 Historial de Sprints

| Sprint | Fechas | Objetivo | Estado | SP Plan | SP Done |
|--------|--------|---------|--------|---------|--------|
| 1 | 02-03 al 13-03 | Análisis requisitos, diseño inicial, configuración | ✅ Done | 7 | 7 |
| 2 | 16-03 al 27-03 | Mesa, menú digital, Login | ✅ Done | 32 | 32 |
| 3 | 30-03 al 10-04 | Lógica de menú completada | ✅ Done | 18 | 0* |
| 4 | 13-04 al 24-04 | Caja, cocina, DevOps | ✅ Done | 24 | 11 |
| **5** | **27-04 al 08-05** | **Administración + Tests DevOps** | 🔄 Planificado | TBD | - |
| 6 | 11-05 al 22-05 | Administración completa + Operaciones | 🔄 Planificado | TBD | - |
| 7 | 25-05 al 05-06 | Pruebas de estrés y validación | 🔄 Planificado | TBD | - |
| 8 | 08-06 al 19-06 | Documentación final y entrega | 🔄 Planificado | TBD | - |

*Nota Sprint 3: SP = 0 por estar en revisión al momento del documento

### 6.1.1 Detalle Sprint 1 (02-13 Marzo) - COMPLETADO

| Fecha | Actividad | Entregable |
|-------|-----------|-----------|
| 02-03 | Reunión inicial, análisis de requisitos | Product Backlog v1 |
| 03-05 | Diseño de arquitectura | Diagrama de clases, DB schema |
| 06-08 | Configuración entorno (Docker) | Dockerfile, docker-compose |
| 09-12 | Implementación básica mesa + menú | Mesas, productos funcionando |
| 13-04 | Sprint Review + Retrospective | Demo working |

**Historias completadas Sprint 1:**
- HU001: Selección de mesa
- HU002: Ver menú digital
- HU003: Login/usuario
- HU004: CRUD básico productos
- HU005: Categorías menú
- HU006: Panel admin básico

### 6.1.2 Detalle Sprint 2 (16-27 Marzo) - COMPLETADO

| Fecha | Actividad | Entregable |
|-------|-----------|-----------|
| 16-03 | Sprint Planning | 32 SP priorizados |
| 17-20 | Implementación mesa complete | Estados mesa |
| 21-24 | Menú digital + pedidos | Order flow |
| 25-26 | Login + auth | Sesiones, roles |
| 27-03 | Sprint Review | Demo flujo completo |

**Historias completadas Sprint 2:**
- Selección de mesa (libre/ocupada)
- Menú con categorías
- Crear pedido desde mesa
- Estados pedido
- Control acceso roles

### 6.1.3 Detalle Sprint 3 (30-10 Abril) - COMPLETADO

| Fecha | Actividad | Entregable |
|-------|-----------|-----------|
| 30-03 | Sprint Planning | 18 SP |
| 01-05 | Mejorar menú | UX improvements |
| 06-09 | Lógica pedidos | Completar OrderModel |
| 10-04 | Revisión | Demo |

**Nota:** Sprint 3 tuvo issues con story points (mostrando 0 en documentación pero funcionalidades completadas en Sprint 4)

### 6.1.4 Detalle Sprint 4 (13-24 Abril) - COMPLETADO

| Fecha | Actividad | Entregable |
|-------|-----------|-----------|
| 13-04 | Sprint Planning | 24 SP |
| 14-17 | Caja - procesar pagos | VentaModel |
| 18-20 | Cocina - UI | Kitchen interface |
| 21-23 | Tests unitarios (iniciales) | PHPUnit setup |
| 24-04 | Sprint Review | Demo flujo completo |

**Historias completadas Sprint 4:**
- HU17: Pedidos a cocina
- HU-UI-02: Interfaz cocina
- HU-UI-03: Interfaz caja
- HU13: Buscar pedidos
- HU14: Imprimir tickets (PDF)
- HU-LOG-01: Cálculo cambio
- Estados pedido: pendiente→en_proceso→listo

### 6.1.5 Sprint 5 (27 Abril - 08 Mayo) - PLANIFICADO

**Objetivo:** Administración + DevOps Tests funcionales

**Daily Scrums sugeridos:** 9:00 AM (15 min max)

| Día | Enfoque |
|-----|--------|
| Lun 27 | Setup PHPUnit, first test |
| Mar 28 | Test caja (Oswaldo) |
| Mié 29 | Test cocina (Uriel) |
| Jue 30 | Test admin (Jesús) |
| Vie 01 | Integrar Logger |
| Sáb 02 | GitHub Actions |
| Dom 03 | Descanso |
| Lun 04 | Code review |
| Mar 05 | Bug fixes tests |
| Mié 06 | CRUD usuarios |
| Jue 07 | Dashboard metrics |
| Vie 08 | Sprint Review + Retrospective |

**Definition of Done (Sprint 5):**
- [ ] Tests unitarios pasan (>80%)
- [ ] Logger escribe en logs/app.log
- [ ] CI/CD ejecuta en cada push
- [ ] CRUD usuarios funciona
- [ ] Code review completado

### 6.1.6 Sprint 6 (11-22 Mayo) - PLANIFICADO

**Objetivo:** Administración completa + Operaciones

| Día | Enfoque |
|-----|--------|
| Lun 11 | Backups cron |
| Mar 12 | Healthchecks |
| Mié 13 | Reportes PDF |
| Jue 14 | Tickets printing |
| Vie 15 | UI improvements |
| Sáb 16 | Descanso |
| Dom 17 | Descanso |
| Lun 18 | WebSocket/polling |
| Mar 19 | Audio alerts |
| Mié 20 | Bug fixes |
| Jue 21 | Testing |
| Vie 22 | Sprint Review |

### 6.1.7 Sprint 7 (25 Mayo - 05 Junio) - PLANIFICADO

**Objetivo:** Testing completo + UAT

| Día | Enfoque |
|-----|--------|
| Lun 25 | Integration tests |
| Mar 26 | E2E tests |
| Mié 27 | Load testing |
| Jue 28 | Security audit |
| Vie 29 | UAT setup |
| Sáb 30 | Descanso |
| Dom 31 | Descanso |
| Lun 01 | UAT stakeholders |
| Mar 02 | Bugs fix |
| Mié 03 | Bugs fix |
| Jue 04 | Regression |
| Vie 05 | Sprint Review |

### 6.1.8 Sprint 8 (08-19 Junio) - PLANIFICADO

**Objetivo:** Documentación + Entrega

| Día | Enfoque |
|-----|--------|
| Lun 08 | README técnico |
| Mar 09 | Manual usuario |
| Mié 10 | Deploy producción |
| Jue 11 | Training equipo |
| Vie 12 | Validación final |
| Sáb 13 | Descanso |
| Dom 14 | Descanso |
| Lun 15 | Ajustes finales |
| Mar 16 | Demo stakeholders |
| Mié 17 | Firma aceptación |
| Jue 18 | Lessons learned |
| Vie 19 | Celebration! 🎉 |

### 6.2 Métricas del Proyecto

| Métrica | Valor |
|--------|-------|
| **Velocity promedio** | ~12.5 SP/sprint |
| **Throughput promedio** | 5.3 historias/sprint |
| **Cycle time promedio** | 3.17 días/historia |
| **Lead time promedio** | 5.7 días/historia |
| **Bug rate promedio** | 0.32 |

### 6.3 Riesgos Identificados

| Riesgo | Impacto | Probabilidad | Plan de Mitigación |
|--------|---------|-------------|-------------------|
| Concurrencia de mesas | Alto | Media | Flag de bloqueo en BD cuando mesa está siendo editada |
| Pérdida de sesión | Medio | Baja | Cookies persistentes + manejo estados servidor |
| Inconsistencia de stock | Alto | Media | Transacciones SQL atómicas |
| Caída servidor local | Alto | Medio | Backups automáticos cada 4 horas |
| Incompatibilidad dispositivos | Medio | Media | Diseño responsivo |

---

## 7. DEVOPS - ESTADO ACTUAL

### 7.1 Fases DevOps

| Fase | Estado | Notas |
|------|--------|-------|
| **Planificación** | ✅ Done | Documentación, backlog, priorización |
| **Codificación** | ✅ Done | Git + GitHub funcionando |
| **Construcción** | ✅ Done | Docker + XAMPP |
| **Pruebas** | ❌ Pendiente | Tests no están listos para usarse |
| **Lanzamiento** | ⚠️ Parcial | Tags existen |
| **Despliegue** | ⚠️ Local | Solo Docker local |
| **Operación** | ❌ Pendiente | Backups manuales |
| **Monitorización** | ❌ Pendiente | Sin alerting |

### 7.2 Docker - Estado Actual

```
✅ IMPLEMENTADO Y FUNCIONANDO:
├── Dockerfile                    # PHP 8.2 + Apache
├── docker-compose.yml           # web + db servicios
└── init-db/                   # SQL auto-import en startup
```

**Servicios Docker:**
| Servicio | Puerto | Imagen | Estado |
|----------|--------|--------|--------|
| web | 8081 | php:8.2-apache | ✅ Funcionando |
| db | 3307 | mysql:8.0 | ✅ Funcionando |
| phpmyadmin | 8082 | phpmyadmin | ❌ No configurado |

### 7.2.1 Comandos Docker

```bash
# Iniciar servicios (primera vez)
docker compose up -d --build

# Ver servicios
docker compose ps

# Logs en tiempo real
docker compose logs -f web

# Reiniciar servicio
docker compose restart web

# Detener todo
docker compose down

# Acceder al contenedor PHP
docker compose exec web bash

# Ejecutar tests (cuando estén listos)
docker compose exec web ./vendor/bin/phpunit

# Backup desde container
docker compose exec db mysqldump -u root -proot sistema_comanda_digital_v1 > backup.sql
```

### 7.3 XAMPP - Estado Legacy

```
⚠️ LEGACY: XAMPP ya no se usa activamente
- Mantenido para compatibilidad
- Archivos duplicados (*.php sin Model) disponibles
- MySQL en puerto 3306
```

**Puertos:**
| Servicio | Docker | XAMPP (legacy) |
|----------|--------|--------------|
| Web | 8081 | 80 |
| MySQL | 3307 | 3306 |

### 7.4 Git/GitHub - Estado Actual

```
✅ IMPLEMENTADO:
├── Git local con commits
├── Remote: GitHub
└── Branch principal: main
```

### 7.5 GitHub Actions - CONFIGURAR EN SPRINT 5

```yaml
# .github/workflows/php.yml
name: PHP CI/CD
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: PHP Setup
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Run Tests
        run: ./vendor/bin/phpunit
```

### 7.6 Archivos DevOps Existentes

```
proyecto/
├── composer.json              ✅ Listo (requiere-dev PHPUnit)
├── phpunit.xml              ⚠️ Incompleto
├── CHANGELOG.md             ✅ Listo
├── Dockerfile               ✅ Listo (actualizar con composer)
├── docker-compose.yml       ⚠️ Falta healthcheck completo
│
├── helpers/
│   └── Logger.php          ⚠️ Existe pero NO se usa
│
├── scripts/
│   ├── backup.sh           ⚠️ Sin cron
│   └── run-tests.sh       ⚠️ Sin CI
│
├── tests/
│   ├── bootstrap.php       ⚠️ Incompleto
│   └── Unit/
│       ├── ProductoTest.php
│       ├── PedidoTest.php
│       ├── MesaTest.php
│       ├── InventarioTest.php
│       └── LoggerTest.php
│
├── backups/               📁 Por crear
└── logs/                 ✅ Existe
```

---

## 8. DISTRIBUCIÓN DE TAREAS POR ROL (Sprint 5-8)

### 8.1 Sprint 5 (27 Abril - 08 Mayo): Administración + DevOps Tests

| Rol | Tarea | Tipo | Prioridad |
|-----|------|------|----------|
| **Jorge (SM)** | Facilitar Daily Scrums, remover blockers | Scrum | Alta |
| **Yosabeth (PO)** | Priorización backlog Sprint 5, validate stories | Producto | Alta |
| **Oswaldo (Dev)** | Tests caja: pagar, cambio, cancelar venta, borrar pendientes | Test | Alta |
| **Uriel (Dev)** | Tests cocina: estados pedido, interface | Test | Alta |
| **Jesús (Dev)** | Tests admin: CRUD usuarios, dashboard | Test | Alta |
| **Equipo** | HACER FUNCIONAR PHPUnit | DevOps | **CRÍTICA** |
| **Equipo** | Conectar Logger en controllers | DevOps | Alta |
| **Equipo** | Configurar GitHub Actions básico | DevOps | Alta |

### 8.2 Sprint 5 - Detalle de Tareas de OSWALDO (Caja + Tickets)

| ID | Tarea | Archivo(s) | Estado Actual | Estado Target |
|----|------|-------------|--------------|----------------|
| **OSW-TST-01** | Test unitario: pagarVenta() | VentaModel.php:23 | ❌ No hay test | ✅ Test funcionando |
| **OSW-TST-02** | Test unitario: cálculo cambio | CajaController.php:47 | ⚠️ Solo validación manual | ✅ Test automatizado |
| **OSW-TST-03** | Test unitario: cancelarVenta() | VentaModel.php:90 | ❌ No hay test | ✅ Test funcionando |
| **OSW-TST-04** | Test unitario: obtenerDetallesPedido (ticket) | VentaModel.php:65 | ❌ No hay test | ✅ Test funcionando |
| **OSW-TST-05** | Test unitario: borrarVentasPendientes() | VentaModel.php:99 | ❌ No hay test | ✅ Test funcionando |
| **OSW-DEV-01** | Agregar Logger::info() en pagar() | CajaController.php:48 | ⚠️ No hay logging | ✅ Conexión Logger |
| **OSW-DEV-02** | Agregar Logger::info() en crearVentaPendiente() | VentaModel.php:14 | ⚠️ No hay logging | ✅ Conexión Logger |
| **OSW-DEV-03** | Agregar Logger::error() en exception payer | CajaController.php:44 | ⚠️ No hay logging | ✅ Conexión Logger |
| **OSW-IMP-01** | Implementar impresión PDF ticket (simplificado) | generar_pdf.php | ⚠️ Solo reportes admin | ✅ Ticket por pedido |
| **OSW-IMP-02** | Mejorar auto-refresh caja (WebSocket/polling) | views/caja/caja.php:104 | ⚠️ 30s fixed | ✅ 5s polling |
| **OSW-BUG-01** | Fix: error en borrarPendientes (line 56) | views/caja/caja.php:56 | ⚠️ Syntax error `;;` | ✅/arreglar |

### 8.2 Sprint 6 (11 Mayo - 22 Mayo): Administración Completa + Operaciones

| Rol | Tarea | Tipo | Prioridad |
|-----|------|------|----------|
| **Jorge (SM)** | Facilitar Daily Scrums, retrospective Sprint 5 | Scrum | Alta |
| **Yosabeth (PO)** | Validar historias sprint 5, priorización sprint 6 | Producto | Alta |
| **Oswaldo (Dev)** | Impresión tickets (PDF/térmica) | Feature | Media |
| **Uriel (Dev)** | Mejoras UI cocina/mesero | Feature | Media |
| **Jesús (Dev)** | Reportes avanzados (PDF charts) | Feature | Media |
| **Equipo** | Backups automáticos (cron) | DevOps | Alta |
| **Equipo** | Healthcheck completo docker | DevOps | Media |

### 8.2.1 Detalle de Tareas de OSWALDO (Caja + Tickets)

| ID | Tarea | Archivo(s) | Estado Actual | Estado Target |
|----|------|-------------|--------------|----------------|
| **OSW-TST-01** | Test: pagarVenta() | VentaModel.php:23 | ❌ No hay test | ✅ Test funcionando |
| **OSW-TST-02** | Test: cálculo cambio | CajaController.php:47 | ⚠️ Solo validación manual | ✅ Test automatizado |
| **OSW-TST-03** | Test: cancelarVenta() | VentaModel.php:90 | ❌ No hay test | ✅ Test funcionando |
| **OSW-TST-04** | Test: obtenerDetallesPedido (ticket) | VentaModel.php:65 | ❌ No hay test | ✅ Test funcionando |
| **OSW-TST-05** | Test: borrarVentasPendientes() | VentaModel.php:99 | ❌ No hay test | ✅ Test funcionando |
| **OSW-DEV-01** | Agregar Logger::info() en pagar() | CajaController.php:48 | ⚠️ No hay logging | ✅ Conexión Logger |
| **OSW-DEV-02** | Agregar Logger::info() en crearVentaPendiente() | VentaModel.php:14 | ⚠️ No hay logging | ✅ Conexión Logger |
| **OSW-DEV-03** | Agregar Logger::error() en exception payer | CajaController.php:44 | ⚠️ No hay logging | ✅ Conexión Logger |
| **OSW-IMP-01** | Implementar impresión PDF ticket | generar_pdf.php, ReportController.php | ⚠️ Solo reportes admin | ✅ Ticket por pedido |
| **OSW-IMP-02** | Mejorar auto-refresh caja | views/caja/caja.php:104 | ⚠️ 30s fixed | ✅ 5s polling |
| **OSW-BUG-01** | Fix: syntax error line 56 | views/caja/caja.php:56 | ⚠️ `;;` extra | ✅ Arreglar |

### 8.2.2 Detalle de Tareas de URIEL (Cocina + Mesero UI)

| ID | Tarea | Archivo(s) | Estado Actual | Estado Target |
|----|------|-------------|--------------|----------------|
| **UR-TST-01** | Test: obtenerPedidosActivosConDetalles() | PedidoModel.php:67 | ❌ No hay test | ✅ Test funcionando |
| **UR-TST-02** | Test: actualizarEstadoDetalle() | PedidoModel.php:89 | ❌ No hay test | ✅ Test funcionando |
| **UR-TST-03** | Test: filtrarTacosYPostres() | CocinaController.php:40 | ❌ No hay test | ✅ Test funcionando |
| **UR-DEV-01** | Agregar Logger en CocinaController | CocinaController.php:72 | ⚠️ No hay logging | ✅ Conexión Logger |
| **UR-IMP-01** | UI timeout visual por tiempo | views/cocina/cocina.php | ⚠️ Sin timeout visual | ✅ Colores por tiempo |
| **UR-IMP-02** | Audio/alerta sonora pedido nuevo | views/cocina/cocina.php | ❌ No funciona | ✅ Audio alert |
| **UR-IMP-03** | Botón batch "marcar todo listo" | CocinaController.php | ❌ No hay feature | ✅ Batch update |

### 8.3 Sprint 7 (25 Mayo - 05 Junio): Pruebas y Validación

| Rol | Tarea | Tipo | Prioridad |
|-----|------|------|----------|
| **Jorge (SM)** | Coordnar pruebas, QA | Scrum | Alta |
| **Yosabeth (PO)** | UAT con stakeholders | Producto | Alta |
| **Oswaldo (Dev)** | Testing integración caja | QA | Alta |
| **Uriel (Dev)** | Testing integración cocina | QA | Alta |
| **Jesús (Dev)** | Testing integración admin | QA | Alta |
| **Equipo** | Pruebas de estrés | QA | Alta |
| **Equipo** | Corrección bugs | Bugfix | Alta |

### 8.4 Sprint 8 (08 Junio - 19 Junio): Documentación y Entrega

| Rol | Tarea | Tipo | Prioridad |
|-----|------|------|----------|
| **Jorge (SM)** | Cierre proyecto, lessons learned | Scrum | Alta |
| **Yosabeth (PO)** | Aceptación final, firmas | Producto | Alta |
| **Oswaldo (Dev)** | Documentación técnica backend | Docs | Alta |
| **Uriel (Dev)** | Documentación UI/UX | Docs | Alta |
| **Jesús (Dev)** | Documentación admin, manual usuario | Docs | Alta |
| **Equipo** | README completo actualizado | Docs | Alta |
| **Equipo** | Deployment producción | DevOps | Alta |

---

## 9. CRÍTICAS Y RECOMENDACIONES

### 9.1 Qué está BIEN

1. **Flujo completo implementado**: Sistema funciona mesero→cocina→caja
2. **Docker**: Entorno funciona correctamente
3. **XAMPP legacy**: Mantenido para compatibilidad
4. **Patrón Observer**: Alertas de stock funcionando
5. **Git/GitHub**: Control de versiones activo
6. **Estructura MVC**: Separación controllers/models/views
7. **Dashboard**: Métricas básicas funcionando
8. **Cálculo de cambio**: Funciona en caja

### 9.2 Estado de Pendientes

| # | Pendiente | Razón | Sprint |
|---|----------|-------|--------|
| 1 | Tests no están listos | Por desarrollar | Sprint 5 |
| 2 | CI/CD | Por configurar | Sprint 5 |
| 3 | Logger no se usa | Por integrar | Sprint 5 |
| 4 | Backups automáticos | Por configurar | Sprint 6 |

### 9.3 ⭐ Código Duplicado - NO ES PROBLEMA

**Los archivos duplicados SON INTENCIONALES para compatibilidad:**

```
models/Mesa.php         ← XAMPP legacy
models/MesaModel.php   ← Docker (en uso)

models/Producto.php   ← XAMPP legacy  
models/ProductoModel.php ← Docker (en uso)

models/Pedido.php      ← XAMPP legacy
models/PedidoModel.php ← Docker
models/OrderModel.php  ← Docker
```

**Decisión: Mantener ambos** - No eliminar, funcionan como backups de compatibilidad.

### 9.4 Recomendaciones por Sprint

**Sprint 5:**
1. ✅ Hacer funcionar PHPUnit
2. ✅ Conectar Logger en controllers
3. ✅ Configurar GitHub Actions

**Sprint 6:**
1. Configurar cron backups
2. Healthchecks Docker
3. WebSocket tiempo real

### 9.5 MétricasObjetivo

| Métrica | Actual | Objetivo |
|--------|--------|---------|
| Tests | 0 | > 20 |
| Cobertura | 0% | > 70% |
| CI/CD | No | Automático |

---

## 10. PRODUCT BACKLOG (Priorizado)

### 10.1 Sprint 5 Backlog

| ID | Historia de Usuario | Prioridad | SP | Responsable |
|----|---------------------|----------|-----|-------------|
| HU-DEV-01 | Tests unitarios funcionan | Alta | 8 | Equipo |
| HU-DEV-02 | Logger integrado en controllers | Alta | 5 | Equipo |
| HU-DEV-03 | GitHub Actions CI/CD | Alta | 8 | Equipo |
| HU-ADM-05 | CRUD usuarios completo | Media | 5 | Jesús |
| HU-ADM-06 | Dashboard métricas avanzadas | Media | 5 | Jesús |
| HU-CAJ-01 | Mejora interfaz caja | Baja | 3 | Oswaldo |
| HU-COC-01 | Mejora UI cocina | Baja | 3 | Uriel |

### 10.2 Sprint 6 Backlog

| ID | Historia de Usuario | Prioridad | SP | Responsable |
|----|---------------------|----------|-----|-------------|
| HU-DEV-04 | Backups automáticos cron | Alta | 5 | Equipo |
| HU-DEV-05 | Healthchecks completos | Alta | 3 | Equipo |
| HU-ADM-07 | Reportes avanzados PDF | Media | 8 | Jesús |
| HU-CAJ-02 | Impresión tickets | Media | 5 | Oswaldo |
| HU-MES-01 | Mejora UI mesero | Baja | 5 | Uriel |

### 10.3 Sprint 7-8 Backlog

| ID | Historia de Usuario | Prioridad | SP | Responsable |
|----|---------------------|----------|-----|-------------|
| HU-QA-01 | Testing completo | Alta | 13 | Equipo |
| HU-QA-02 | Corrección bugs | Alta | 8 | Equipo |
| HU-DOC-01 | Documentación técnica | Alta | 5 | Equipo |
| HU-DOC-02 | Manual usuario | Alta | 3 | Equipo |
| HU-DEV-06 | Deploy producción | Alta | 5 | Equipo |

---

## 11. RECOMENDACIONES ADICIONALES

### 11.1 Mejoras Técnicas Recomendadas

| # | Recomendación | Prioridad |理由 |
|---|--------------|----------|-----|
| 1 | **WebSocket para tiempo real** | Media | Actualizar cocina/mesero sin refresh |
| 2 | **Cache Redis** | Baja | Mejora rendimiento BD |
| 3 | **API REST completa** | Media | Facilita integración futura |
| 4 | **JWT para autenticación** | Media | Más seguro que sesiones |
| 5 | **Notificaciones push** | Baja | Alertas a dispositivos |

### 11.2 Documentos a Generar

| Documento | Estado | Ubicación |
|-----------|--------|------------|
| Manual de Usuario | ❌ Pendiente | Por crear |
| Documento Técnica API | ❌ Pendiente | Por crear |
| Guía de Despliegue | ❌ Pendiente | Por crear |
| **Diagramas UML** | ✅ **EXISTENTES** | Documents_review/ |

### 11.2.1 Diagramas UML Existentes

```
✅ YA INCLUIDOS EN Documents_review/:

├── Documents_review/Documento final/Sistema_de_comanda_digital_FINAL.pdf
├── Documents_review/SCD_Descripcion de arquitectura.pdf
├── Documents_review/Plan de gestion de proyectos.pdf
└── Documents_review/PRODUCT BACKLOG XD.pdf
```

**Contienen:**
- Diagrama de clases completo
- Diagrama de casos de uso
- Diagrama de secuencia
- Modelo de datos ER
- Product Backlog

### 11.3 Siguientes Pasos Inmediatos

```
✅ ACCIONES SPRINT 5:
├── 1. composer install (Docker)
├── 2. Configurar phpunit.xml
├── 3. Primer test funcional
├── 4. GitHub Actions basic
└── 5. Integrar Logger en controllers

📋 PARA REUNIÓN SPRINT:
├── Review: Demo flujo completo
├── Retrospective: Lecciones aprendidas
└── Planning: Priorizar Sprint 5
```

### 11.4 Contactos y Recursos

| Recurso | Enlace |
|---------|-------|
| GitHub | (configurar en Sprint 5) |
| Documentación | Documents_review/ |
| Docker | docker-compose.yml |
| Tests | tests/Unit/ |

---

## 12. CRITERIOS DE ACEPTACIÓN

El sistema es aceptado cuando:

### 11.1 Funcionales
- [ ] Cada interfaz (mesero, cocina, caja, admin) funciona sin errores
- [ ] El flujo completo pedidos funciona (crear → kitchen → pagar)
- [ ] Control de acceso por roles funciona correctamente
- [ ] Inventario actualiza stock al crear pedido
- [ ] Reportes PDF se generan correctamente

### 11.2 DevOps (Sprint 5-6)
- [ ] Tests unitarios pasan con PHPUnit
- [ ] Logger registra acciones en logs/app.log
- [ ] GitHub Actions ejecuta tests en cada push
- [ ] Backups se ejecutan automáticamente
- [ ] Healthcheck retorna status correcto

### 11.3 Calidad
- [ ] Cobertura de tests > 70%
- [ ] 0 errores críticos en producción
- [ ] Tiempo de respuesta < 2s
- [ ] Documentación completa

---

## 12. ANEXOS

### A. Estructura de Archivos Actual

```
sistema_de_comanda_digital/
├── index.php                    # Front controller
├── generar_pdf.php              # Reportes
├── composer.json                 # Dependencias
├── phpunit.xml                   # Config tests
├── Dockerfile                    # Container PHP
├── docker-compose.yml           # Servicios
├── CHANGELOG.md                  # Versiones
│
├── config/
│   ├── config.php                # Config global
│   ├── database.php              # conexión DB
│   └── db.php                     #
│
├── controllers/
│   ├── AdminController.php       # Admin CRUD
│   ├── AuthController.php        # Login/logout
│   ├── CajaController.php         # Pagos
│   ├── CocinaController.php        # Kitchen
│   ├── MenuController.php         # Menú
│   ├── MesaController.php         # Mesas
│   ├── MeseroController.php       # Mesero
│   ├── OrderController.php        # Pedidos
│   └── ReportController.php      # PDF reports
│
├── models/
│   ├── Mesa.php                   # ⚠️ DUPLICADO
│   ├── MesaModel.php              # ✅ Usa esto
│   ├── Pedido.php                  # ⚠️ DUPLICADO
│   ├── PedidoModel.php             # ✅ Usa esto
│   ├── OrderModel.php             # ✅ Usa esto
│   ├── Producto.php               # ⚠️ DUPLICADO probable
│   ├── ProductoModel.php          # ✅ Usa esto
│   ├── Inventario.php             # ✅
│   ├── Venta.php                  # ✅
│   ├── VentaModel.php             # ✅
│   ├── User.php                   # ✅
│   ├── observers/
│   │   ├── NotificationManager.php # ✅
│   │   └── StockObserver.php       # ✅
│
├── views/
│   ├── auth/login.php
│   ├── admin/
│   │   ├── dashboard.php
│   │   ├── inventario.php
│   │   ├── menu.php
│   │   ��─�� mesas.php
│   │   ├── reportes.php
│   │   └── usuarios.php
│   ├── caja/caja.php
│   ├── cocina/cocina.php
│   ├── layout/
│   ├── menu/menu.php
│   ├── mesero/mesero.php
│   └── mesas/mesas.php
│
├── helpers/
│   └── Logger.php                 # ⚠️ NO se usa
│
├── scripts/
│   ├── backup.sh                 # ⚠️ Sin cron
│   └── run-tests.sh              # ⚠️ Sin usar
│
├── tests/
│   ├── bootstrap.php             # ⚠️ Incompleto
│   └── Unit/
│       ├── ProductoTest.php      # ⚠️ No corre
│       ├── PedidoTest.php         # ⚠️ No corre
│       ├── MesaTest.php          # ⚠️ No corre
│       ├── InventarioTest.php    # ⚠️ No corre
│       └── LoggerTest.php         # ⚠️ No corre
│
├── public/
│   ├── css/
│   ├── js/
│   └── images/
│
├── logs/                         # ✅
├── backups/                     # 📁 por crear
├── init-db/
│   └── sistema_comanda_digital_v1.sql
│
└── Documents_review/             # Documentación
    ├── Sprint 1-8/
    ├── DevOps_Implementacion/
    └── *.pdf
```

### B. Endpoints de la API

| Método | URL | Controller | Acción |
|--------|-----|------------|--------|
| GET | /mesa | MesaController | index |
| GET | /menu | MenuController | index |
| POST | /order/save | OrderController | save |
| GET | /order/pending | OrderController | getPendingOrders |
| POST | /order/complete | OrderController | complete |
| GET | /caja | CajaController | index |
| POST | /caja/pagar | CajaController | pagar |
| POST | /caja/cancelar | CajaController | cancelar |
| GET | /cocina | CocinaController | index |
| POST | /cocina/actualizar | CocinaController | actualizarEstado |
| GET | /admin | AdminController | index |
| POST | /admin | AdminController | procesarFormulario |

### C. Roles de Usuario

| Rol | Acceso |
|-----|--------|
| admin | Todas las secciones admin, dashboard, reportes |
| mesero | Crear pedidos, ver estado |
| cocina | Ver y actualizar estado pedidos |
| caja | Procesar pagos, ver ventas |

---

*Documento generado: 28 Abril 2026*
*Versión: 1.0*
*Basado en IEEE 16326 (PMP) + Scrum + DevOps*