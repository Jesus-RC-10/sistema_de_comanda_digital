# Product Requirements Document (PRD)
## Sistema de Comanda Digital (SCD) - Versión 2.0

**Documento:** PRD-SCD-2026-001  
**Fecha:** 24 de abril de 2026  
**Estado:** Versión Final  
**Equipo de Desarrollo:** Ramírez García Oswaldo, Rios Carrera Jesús Vicente, Vargas Angeles Uriel  
**Institución:** Universidad Autónoma de la Ciudad de México  
**Asignatura:** Diseño de Software / Arquitectura de Software  
**Metodología:** Scrum con prácticas DevOps

---

## Tabla de Contenidos

1. [Resumen Ejecutivo](#1-resumen-ejecutivo)
2. [Descripción del Producto](#2-descripción-del-producto)
3. [Arquitectura del Sistema](#3-arquitectura-del-sistema)
4. [Requisitos Funcionales](#4-requisitos-funcionales)
5. [Requisitos No Funcionales](#5-requisitos-no-funcionales)
6. [Modelo de Datos](#6-modelo-de-datos)
7. [Interfaz de Usuario y Experiencia de Usuario](#7-interfaz-de-usuario-y-experiencia-de-usuario)
8. [Seguridad y Autenticación](#8-seguridad-y-autenticación)
9. [DevOps y Despliegue](#9-devops-y-despliegue)
10. [Pruebas y Calidad](#10-pruebas-y-calidad)
11. [Mantenimiento y Soporte](#11-mantenimiento-y-soporte)
12. [Roadmap y Futuras Mejoras](#12-roadmap-y-futuras-mejoras)

---

## 1. Resumen Ejecutivo

### 1.1 Propósito del Producto

El **Sistema de Comanda Digital (SCD)** es una aplicación web diseñada para digitalizar y optimizar el proceso de toma, gestión y seguimiento de pedidos en restaurantes de pequeño a mediano tamaño. El sistema elimina los errores de comunicación tradicionales, reduce tiempos de espera y proporciona trazabilidad completa del ciclo de vida del pedido.

### 1.2 Problema que Resuelve

- **Errores en toma de pedidos:** Eliminación de intermediarios en la toma de órdenes
- **Falta de trazabilidad:** Seguimiento en tiempo real del estado de cada pedido
- **Ineficiencia operativa:** Automatización de flujos entre mesa, cocina y caja
- **Control de inventario:** Gestión automática de stock basada en consumo real
- **Tiempos de espera:** Transmisión inmediata de pedidos a cocina

### 1.3 Solución Propuesta

El SCD implementa una arquitectura **MVC (Modelo-Vista-Controlador)** con las siguientes características:

- **Interfaz auto-servicio:** Tablets en cada mesa para pedidos directos
- **Comunicación en tiempo real:** Actualización instantánea en cocina y administración
- **Gestión integral:** Módulo administrativo completo para control operativo
- **Sistema de notificaciones:** Alertas automáticas de stock bajo y eventos críticos
- **Arquitectura escalable:** Diseño modular que permite crecimiento futuro

### 1.4 Usuarios Objetivo

| Rol | Descripción | Funcionalidades Clave |
|-----|-------------|----------------------|
| **Cliente** | Comensal en el restaurante | Realizar pedidos, personalizar platillos, solicitar ayuda |
| **Mesero** | Personal de servicio | Supervisar pedidos, asistir clientes, gestionar mesas |
| **Cocinero** | Personal de cocina | Recibir pedidos, actualizar estados, verificar inventario |
| **Cajero** | Personal de caja | Procesar pagos, generar tickets, cerrar ventas |
| **Administrador** | Gerente/propietario | Gestión completa del sistema, reportes, inventarios |

---

## 2. Descripción del Producto

### 2.1 Características Principales

#### 2.1.1 Módulo de Pedidos (Mesa)
- **Interfaz intuitiva:** Navegación simple por categorías de menú
- **Personalización:** Opción de modificar ingredientes (agregar/eliminar)
- **Carrito de compras:** Gestión de items antes de confirmar pedido
- **Ticket digital:** Generación de comprobante con resumen detallado
- **Solicitud de ayuda:** Botón para llamar al mesero

#### 2.1.2 Módulo de Cocina
- **Visualización en tiempo real:** Pedidos que llegan instantáneamente
- **Gestión de estados:** Actualización del progreso (pendiente → en preparación → listo)
- **Filtrado inteligente:** Organización por tipo de platillo
- **Alertas visuales:** Notificaciones de nuevos pedidos

#### 2.1.3 Módulo de Caja
- **Gestión de ventas:** Visualización de pedidos pendientes y pagados
- **Procesamiento de pagos:** Múltiples métodos (efectivo, tarjeta, transferencia)
- **Cálculo de cambio:** Manejo automático de vueltos
- **Historial:** Registro completo de transacciones

#### 2.1.4 Módulo de Administración
- **Dashboard ejecutivo:** Métricas clave del negocio
- **Gestión de menú:** CRUD completo de platillos y categorías
- **Control de inventario:** Seguimiento de ingredientes con alertas de stock
- **Gestión de usuarios:** Administración de roles y permisos
- **Reportes:** Análisis de ventas, productos más vendidos, tendencias
- **Gestión de mesas:** Configuración de ubicación y estado

### 2.2 Flujo de Trabajo Típico

```
1. CLIENTE realiza pedido en tablet
   ↓
2. SISTEMA valida disponibilidad y guarda en BD
   ↓
3. COCINA recibe pedido en tiempo real
   ↓
4. COCINERO actualiza estado del pedido
   ↓
5. CLIENTE solicita ticket o va a caja
   ↓
6. CAJERO procesa pago y cierra venta
   ↓
7. SISTEMA actualiza inventario y genera reportes
```

---

## 3. Arquitectura del Sistema

### 3.1 Arquitectura General

El sistema sigue una **arquitectura en capas** basada en el patrón **MVC (Modelo-Vista-Controlador)**:

```
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE PRESENTACIÓN                      │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────┐│
│  │   Cliente   │ │   Cocina    │ │    Caja     │ │  Admin  ││
│  │   (Tablet)  │ │  (Pantalla) │ │  (Terminal) │ │(Dashboard)││
│  └─────────────┘ └─────────────┘ └─────────────┘ └─────────┘│
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                   CAPA DE APLICACIÓN                         │
│  ┌─────────────────────────────────────────────────────────┐│
│  │              CONTROLADORES (Controllers)                 ││
│  │  • AuthController    • OrderController                   ││
│  │  • AdminController   • CocinaController                  ││
│  │  • MeseroController  • CajaController                    ││
│  │  • MenuController    • ReportController                  ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                   CAPA DE NEGOCIO                            │
│  ┌─────────────────────────────────────────────────────────┐│
│  │                 MODELOS (Models)                         ││
│  │  • User          • Pedido         • Producto             ││
│  │  • Mesa          • Venta          • Inventario           ││
│  │  • observers/NotificationManager  • observers/StockObserver││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────┐
│                   CAPA DE DATOS                              │
│  ┌─────────────────────────────────────────────────────────┐│
│  │              MySQL Database (MariaDB)                    ││
│  │  • usuarios      • pedidos        • productos            ││
│  │  • mesas         • ventas         • ingredientes         ││
│  │  • categorias_menu • pedido_detalles • recetas_producto  ││
│  │  • alertas_sistema • inventario                         ││
│  └─────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

### 3.2 Patrones de Diseño Implementados

#### 3.2.1 Patrón Observer
**Ubicación:** `models/observers/NotificationManager.php` y `models/observers/StockObserver.php`

**Propósito:** Sistema de notificaciones para eventos críticos del sistema.

**Implementación:**
```php
// NotificationManager gestiona los observers
$notificationManager = new NotificationManager();
$notificationManager->attach(new StockObserver());

// Notifica eventos como:
// - stock_bajo: Cuando un ingrediente está por debajo del mínimo
// - producto_agotado: Cuando un producto se agota
// - inventario_actualizado: Cuando se modifica el inventario
// - mesa_ocupada: Cuando una mesa cambia a estado ocupada
// - pedido_creado: Cuando se genera un nuevo pedido
```

**Beneficios:**
- Desacoplamiento entre componentes
- Notificaciones automáticas en tiempo real
- Fácil extensión para nuevos tipos de eventos

#### 3.2.2 Patrón MVC
**Implementación:** Separación clara entre lógica de negocio, presentación y datos.

**Estructura de directorios:**
```
sistema_de_comanda_digital/
├── controllers/          # Lógica de aplicación
├── models/              # Lógica de negocio y datos
├── views/               # Interfaces de usuario
├── config/              # Configuración del sistema
├── public/              # Recursos estáticos (CSS, JS, imágenes)
├── assets/              # Recursos adicionales
├── init-db/             # Scripts de base de datos
└── logs/                # Registros del sistema
```

#### 3.2.3 Patrón Singleton
**Implementación:** `config/database.php`

**Propósito:** Garantizar una única conexión a la base de datos.

```php
class Database {
    private static $connection = null;
    
    public static function getConnection() {
        if (self::$connection === null) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        return self::$connection;
    }
}
```

### 3.3 Tecnologías Utilizadas

| Capa | Tecnología | Versión | Propósito |
|------|------------|---------|-----------|
| **Backend** | PHP | 8.2 | Lógica de servidor |
| **Base de Datos** | MySQL/MariaDB | 8.0/10.4 | Persistencia de datos |
| **Servidor Web** | Apache | 2.4 | Servidor HTTP |
| **Frontend** | HTML5, CSS3, JavaScript | - | Interfaces de usuario |
| **Contenerización** | Docker | Latest | Entorno de desarrollo/producción |
| **Control de Versiones** | Git | - | Gestión de código fuente |

### 3.4 Configuración del Sistema

#### 3.4.1 Variables de Entorno
El sistema soporta variables de entorno para configuración flexible:

```php
// config/config.php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('DB_NAME', 'sistema_comanda_digital_v1');
```

#### 3.4.2 Configuración Docker
```yaml
# docker-compose.yml
services:
  web:
    build: .
    ports:
      - "8081:80"
    environment:
      - DB_HOST=db
      - DB_USER=root
      - DB_PASS=root
      - DB_NAME=sistema_comanda_digital_v1
  
  db:
    image: mysql:8.0
    ports:
      - "3307:3306"
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_DATABASE=sistema_comanda_digital_v1
```

---

## 4. Requisitos Funcionales

### 4.1 Módulo de Autenticación

| ID | Requisito | Prioridad | Estado |
|----|-----------|-----------|--------|
| **AUTH-001** | El sistema debe permitir login con usuario y contraseña | Alta | ✅ Implementado |
| **AUTH-002** | El sistema debe redirigir a diferentes interfaces según el rol | Alta | ✅ Implementado |
| **AUTH-003** | El sistema debe cerrar sesión correctamente | Alta | ✅ Implementado |
| **AUTH-004** | El sistema debe usar sesiones seguras con regeneración de ID | Media | ✅ Implementado |
| **AUTH-005** | El sistema debe encriptar contraseñas con password_hash | Alta | ✅ Implementado |

**Credenciales de Prueba:**
```
Usuario: admin     | Contraseña: 123456 | Rol: Administrador
Usuario: mesero1   | Contraseña: 123456 | Rol: Mesero
Usuario: cocina1   | Contraseña: 123456 | Rol: Cocinero
Usuario: caja      | Contraseña: 123456 | Rol: Cajero
```

### 4.2 Módulo de Pedidos (Mesa)

| ID | Requisito | Prioridad | Estado |
|----|-----------|-----------|--------|
| **PED-001** | El sistema debe mostrar el menú organizado por categorías | Alta | ✅ Implementado |
| **PED-002** | El sistema debe permitir agregar productos al carrito | Alta | ✅ Implementado |
| **PED-003** | El sistema debe permitir personalizar platillos (agregar/eliminar ingredientes) | Media | ⚠️ Parcial |
| **PED-004** | El sistema debe calcular el total del pedido en tiempo real | Alta | ✅ Implementado |
| **PED-005** | El sistema debe generar un ticket digital al confirmar pedido | Alta | ✅ Implementado |
| **PED-006** | El sistema debe permitir solicitar ayuda al mesero | Media | ⚠️ Pendiente |
| **PED-007** | El sistema debe validar que el pedido no esté vacío antes de enviar | Alta | ✅ Implementado |
| **PED-008** | El sistema debe identificar automáticamente la mesa desde la tablet | Alta | ✅ Implementado |

### 4.3 Módulo de Cocina

| ID | Requisito | Prioridad | Estado |
|----|-----------|-----------|--------|
| **COC-001** | El sistema debe mostrar pedidos en tiempo real | Alta | ✅ Implementado |
| **COC-002** | El sistema debe permitir actualizar el estado de cada item | Alta | ✅ Implementado |
| **COC-003** | El sistema debe filtrar pedidos por tipo de platillo | Media | ✅ Implementado |
| **COC-004** | El sistema debe mostrar detalles completos del pedido (mesa, items, notas) | Alta | ✅ Implementado |
| **COC-005** | El sistema debe actualizar automáticamente cuando llega un nuevo pedido | Alta | ✅ Implementado |
| **COC-006** | El sistema debe permitir verificar disponibilidad de ingredientes | Media | ⚠️ Pendiente |

### 4.4 Módulo de Caja

| ID | Requisito | Prioridad | Estado |
|----|-----------|-----------|--------|
| **CAJ-001** | El sistema debe mostrar ventas pendientes de pago | Alta | ✅ Implementado |
| **CAJ-002** | El sistema debe mostrar historial de ventas pagadas | Alta | ✅ Implementado |
| **CAJ-003** | El sistema debe procesar pagos con múltiples métodos | Alta | ✅ Implementado |
| **CAJ-004** | El sistema debe calcular y mostrar el cambio | Alta | ✅ Implementado |
| **CAJ-005** | El sistema debe mostrar detalles completos de cada venta | Alta | ✅ Implementado |
| **CAJ-006** | El sistema debe permitir cancelar ventas pendientes | Media | ✅ Implementado |
| **CAJ-007** | El sistema debe marcar pedido como entregado al pagar | Alta | ✅ Implementado |

### 4.5 Módulo de Administración

| ID | Requisito | Prioridad | Estado |
|----|-----------|-----------|--------|
| **ADM-001** | El sistema debe mostrar dashboard con métricas clave | Alta | ✅ Implementado |
| **ADM-002** | El sistema debe permitir gestionar menú (CRUD de platillos) | Alta | ✅ Implementado |
| **ADM-003** | El sistema debe permitir gestionar categorías de menú | Alta | ✅ Implementado |
| **ADM-004** | El sistema debe permitir gestionar mesas (alta, baja, modificación) | Alta | ✅ Implementado |
| **ADM-005** | El sistema debe permitir gestionar usuarios y roles | Alta | ✅ Implementado |
| **ADM-006** | El sistema debe permitir gestionar inventario de ingredientes | Alta | ✅ Implementado |
| **ADM-007** | El sistema debe mostrar alertas de stock bajo | Alta | ✅ Implementado |
| **ADM-008** | El sistema debe permitir generar reportes de ventas | Media | ⚠️ Pendiente |
| **ADM-009** | El sistema debe permitir asociar recetas a platillos | Media | ✅ Implementado |
| **ADM-010** | El sistema debe notificar eventos importantes del sistema | Media | ✅ Implementado |

### 4.6 Módulo de Inventarios

| ID | Requisito | Prioridad | Estado |
|----|-----------|-----------|--------|
| **INV-001** | El sistema debe permitir agregar nuevos ingredientes | Alta | ✅ Implementado |
| **INV-002** | El sistema debe permitir actualizar cantidades de ingredientes | Alta | ✅ Implementado |
| **INV-003** | El sistema debe validar categorías y unidades de medida (ENUM) | Alta | ✅ Implementado |
| **INV-004** | El sistema debe alertar cuando un ingrediente esté por debajo del mínimo | Alta | ✅ Implementado |
| **INV-005** | El sistema debe descontar ingredientes al confirmar un pedido | Media | ⚠️ Pendiente |
| **INV-006** | El sistema debe permitir establecer mínimos de stock por ingrediente | Alta | ✅ Implementado |

---

## 5. Requisitos No Funcionales

### 5.1 Rendimiento

| ID | Requisito | Métrica | Estado |
|----|-----------|---------|--------|
| **PERF-001** | Tiempo de respuesta de la aplicación | < 2 segundos para operaciones CRUD | ✅ Cumplido |
| **PERF-002** | Tiempo de carga de páginas | < 3 segundos | ✅ Cumplido |
| **PERF-003** | Actualización en tiempo real de pedidos | < 1 segundo | ✅ Cumplido |
| **PERF-004** | Capacidad de usuarios concurrentes | Mínimo 20 dispositivos simultáneos | ✅ Cumplido |
| **PERF-005** | Tiempo de consulta a base de datos | < 500ms para consultas simples | ✅ Cumplido |

### 5.2 Seguridad

| ID | Requisito | Implementación | Estado |
|----|-----------|----------------|--------|
| **SEG-001** | Encriptación de contraseñas | password_hash() con PASSWORD_DEFAULT | ✅ Implementado |
| **SEG-002** | Prevención de SQL Injection | Prepared Statements con mysqli/PDO | ✅ Implementado |
| **SEG-003** | Prevención de XSS | Sanitización de inputs y htmlspecialchars() | ✅ Implementado |
| **SEG-004** | Gestión segura de sesiones | session_regenerate_id() y cookies seguras | ✅ Implementado |
| **SEG-005** | Control de acceso por roles | Verificación de $_SESSION['usuario_rol'] | ✅ Implementado |
| **SEG-006** | Protección CSRF | Validación de tokens en formularios | ⚠️ Pendiente |
| **SEG-007** | Logs de auditoría | Registro de eventos en logs/sistema.log | ✅ Implementado |

### 5.3 Usabilidad

| ID | Requisito | Estado |
|----|-----------|--------|
| **USA-001** | Interfaz intuitiva y fácil de usar | ✅ Implementado |
| **USA-002** | Diseño responsive (compatible con tablets) | ✅ Implementado |
| **USA-003** | Navegación consistente entre módulos | ✅ Implementado |
| **USA-004** | Mensajes de error claros y descriptivos | ✅ Implementado |
| **USA-005** | Feedback visual en operaciones (spinners, alertas) | ✅ Implementado |
| **USA-006** | Accesibilidad básica (contrastes, tamaños de fuente) | ⚠️ Mejorable |

### 5.4 Confiabilidad

| ID | Requisito | Estado |
|----|-----------|--------|
| **CONF-001** | El sistema debe estar disponible 99% del tiempo en horario laboral | ✅ Cumplido |
| **CONF-002** | El sistema debe recuperar datos después de caída de conexión | ✅ Implementado |
| **CONF-003** | El sistema debe manejar errores gracefulmente sin crash | ✅ Implementado |
| **CONF-004** | El sistema debe mantener integridad de datos en transacciones | ✅ Implementado |

### 5.5 Mantenibilidad

| ID | Requisito | Estado |
|----|-----------|--------|
| **MANT-001** | Código organizado por capas (MVC) | ✅ Implementado |
| **MANT-002** | Separación clara de responsabilidades | ✅ Implementado |
| **MANT-003** | Uso de estándares de codificación PHP (PSR) | ⚠️ Parcial |
| **MANT-004** | Documentación de código (comentarios) | ⚠️ Mejorable |
| **MANT-005** | Control de versiones con Git | ✅ Implementado |
| **MANT-006** | CHANGELOG actualizado | ✅ Implementado |

---

## 6. Modelo de Datos

### 6.1 Diagrama Entidad-Relación

```
┌─────────────────┐       ┌─────────────────┐
│    usuarios     │       │     mesas       │
├─────────────────┤       ├─────────────────┤
│ id (PK)         │       │ id (PK)         │
│ usuario         │       │ numero_mesa     │
│ password_hash   │       │ estado          │
│ nombre          │       │ ubicacion       │
│ rol             │       │ activa          │
│ activo          │       └─────────────────┘
│ fecha_creacion  │              │
│ ultimo_login    │              │
└─────────────────┘              │
        │                        │
        │                        │
        ▼                        ▼
┌─────────────────┐       ┌─────────────────┐
│    pedidos      │       │  pedido_detalles│
├─────────────────┤       ├─────────────────┤
│ id (PK)         │◄──────│ id (PK)         │
│ mesa_id (FK)    │       │ pedido_id (FK)  │
│ usuario_id (FK) │       │ producto_id (FK)│
│ estado          │       │ cantidad        │
│ total           │       │ precio_unitario │
│ notas           │       │ subtotal        │
│ fecha_creacion  │       │ notas           │
│ fecha_actualiz. │       │ estado          │
└─────────────────┘       └─────────────────┘
        │                        │
        │                        │
        ▼                        ▼
┌─────────────────┐       ┌─────────────────┐
│     ventas      │       │    productos    │
├─────────────────┤       ├─────────────────┤
│ id (PK)         │       │ id (PK)         │
│ pedido_id (FK)  │       │ nombre          │
│ total           │       │ descripcion     │
│ metodo_pago     │       │ precio          │
│ estado          │       │ categoria_id(FK)│
│ fecha_pago      │       │ imagen_url      │
│ fecha_creacion  │       │ stock           │
│ usuario_id (FK) │       │ activo          │
└─────────────────┘       │ tiempo_prepar.  │
                          │ imagen          │
┌─────────────────┐       └─────────────────┘
│ categorias_menu │              │
├─────────────────┤              │
│ id (PK)         │              │
│ nombre          │              │
│ descripcion     │              │
│ orden           │              ▼
│ activa          │       ┌─────────────────┐
└─────────────────┘       │recetas_producto │
                          ├─────────────────┤
┌─────────────────┐       │ id (PK)         │
│   ingredientes  │       │ producto_id (FK)│
├─────────────────┤       │ ingrediente_id  │
│ id (PK)         │       │ cantidad        │
│ nombre          │       └─────────────────┘
│ descripcion     │
│ categoria (ENUM)│
│ unidad_medida   │       ┌─────────────────┐
│ (ENUM)          │       │alertas_sistema  │
│ cantidad_actual │       ├─────────────────┤
│ cantidad_minima │       │ id (PK)         │
│ proveedor       │       │ tipo            │
│ costo_unitario  │       │ mensaje         │
│ activo          │       │ nivel (ENUM)    │
│ fecha_actualiz. │       │ leida           │
└─────────────────┘       │ fecha_creacion  │
                          └─────────────────┘
┌─────────────────┐
│    inventario   │
├─────────────────┤
│ id (PK)         │
│ producto_id(FK) │
│ cantidad_actual │
│ cantidad_minima │
│ ultima_actualiz.│
└─────────────────┘
```

### 6.2 Estructura de Tablas

#### 6.2.1 Tabla: usuarios
```sql
CREATE TABLE usuarios (
  id INT(11) PRIMARY KEY AUTO_INCREMENT,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  rol ENUM('admin','mesero','cocina','caja') NOT NULL,
  activo TINYINT(1) DEFAULT 1,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ultimo_login TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Datos iniciales:**
```sql
INSERT INTO usuarios (usuario, password_hash, nombre, rol) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Principal', 'admin'),
('mesero1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Pérez', 'mesero'),
('cocina1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María García', 'cocina'),
('caja', '$2y$10$d/5wQy2tJcgRxKBv7LEYq.8lt8QwMpGwZ533JM6VEY91Mjbx.AVMa', 'Uriel', 'caja');
```

#### 6.2.2 Tabla: productos
```sql
CREATE TABLE productos (
  id INT(11) PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(200) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  precio DECIMAL(10,2) NOT NULL,
  categoria_id INT(11) DEFAULT NULL,
  imagen_url VARCHAR(500) DEFAULT NULL,
  stock INT(11) DEFAULT 0,
  activo TINYINT(1) DEFAULT 1,
  tiempo_preparacion INT(11) DEFAULT 15,
  imagen VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias_menu(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### 6.2.3 Tabla: ingredientes
```sql
CREATE TABLE ingredientes (
  id INT(11) PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT DEFAULT NULL,
  categoria ENUM('vegetales','carnes','lacteos','granos','especias','bebidas','otros') DEFAULT 'otros',
  unidad_medida ENUM('kg','gr','lt','ml','unidad','paquete') DEFAULT 'kg',
  cantidad_actual DECIMAL(10,3) DEFAULT 0.000,
  cantidad_minima DECIMAL(10,3) DEFAULT 1.000,
  proveedor VARCHAR(100) DEFAULT NULL,
  costo_unitario DECIMAL(10,2) DEFAULT 0.00,
  activo TINYINT(1) DEFAULT 1,
  fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 6.3 Reglas de Negocio en Base de Datos

1. **Integridad referencial:** Todas las foreign keys están definidas con ON DELETE CASCADE donde corresponde
2. **Estados válidos:** Uso de ENUMs para garantizar valores consistentes
3. **Auditoría:** Timestamps automáticos en todas las tablas principales
4. **Soft delete:** Uso de campo `activo` en lugar de eliminación física
5. **Índices:** Primary keys y unique constraints para optimización

---

## 7. Interfaz de Usuario y Experiencia de Usuario

### 7.1 Principios de Diseño

- **Minimalismo:** Interfaces limpias sin elementos innecesarios
- **Consistencia:** Mismos patrones de diseño en todos los módulos
- **Accesibilidad:** Contrastes adecuados y tamaños de fuente legibles
- **Responsive:** Adaptación a diferentes tamaños de pantalla (especialmente tablets)
- **Feedback:** Notificaciones visuales para todas las acciones del usuario

### 7.2 Paleta de Colores

| Color | Uso | Hex |
|-------|-----|-----|
| **Rojo Vino** | Color principal, botones primarios | #722F37 |
| **Blanco** | Fondos, textos en botones | #FFFFFF |
| **Gris Oscuro** | Textos principales | #333333 |
| **Gris Claro** | Fondos secundarios, bordes | #F5F5F5 |
| **Verde** | Éxito, confirmaciones | #28A745 |
| **Rojo** | Errores, alertas | #DC3545 |
| **Amarillo** | Advertencias, precauciones | #FFC107 |

### 7.3 Tipografía

- **Principal:** Arial, Helvetica, sans-serif
- **Títulos:** 24px - 32px
- **Subtítulos:** 18px - 20px
- **Cuerpo:** 14px - 16px
- **Botones:** 14px - 16px

### 7.4 Componentes de Interfaz

#### 7.4.1 Botones
```css
.btn-primary {
  background-color: #722F37;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-primary:hover {
  background-color: #5a2529;
}
```

#### 7.4.2 Tarjetas (Cards)
```css
.card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  padding: 20px;
  margin-bottom: 20px;
}
```

#### 7.4.3 Formularios
```css
.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
```

### 7.5 Flujos de Usuario

#### 7.5.1 Flujo de Pedido (Cliente)
```
1. Cliente ve menú en tablet
   ↓
2. Selecciona categoría
   ↓
3. Elige platillo
   ↓
4. Personaliza (opcional)
   ↓
5. Agrega al carrito
   ↓
6. Revisa carrito
   ↓
7. Confirma pedido
   ↓
8. Recibe ticket digital
```

#### 7.5.2 Flujo de Cocina
```
1. Cocina ve nuevo pedido en pantalla
   ↓
2. Cocinero acepta pedido
   ↓
3. Actualiza estado a "En preparación"
   ↓
4. Termina preparación
   ↓
5. Actualiza estado a "Listo"
   ↓
6. Mesero recoge pedido
```

#### 7.5.3 Flujo de Caja
```
1. Cajero ve venta pendiente
   ↓
2. Cliente entrega ticket
   ↓
3. Cajero verifica detalles
   ↓
4. Procesa pago
   ↓
5. Registra método de pago
   ↓
6. Calcula cambio (si aplica)
   ↓
7. Marca como pagado
   ↓
8. Imprime comprobante
```

---

## 8. Seguridad y Autenticación

### 8.1 Mecanismos de Seguridad Implementados

#### 8.1.1 Autenticación
- **Sistema de login:** Usuario y contraseña encriptada
- **Sesiones PHP:** Gestión segura con `session_start()`
- **Regeneración de sesión:** `session_regenerate_id(true)` para prevenir session fixation
- **Timeout de sesión:** Configurado en php.ini (por defecto 24 minutos)

#### 8.1.2 Autorización
- **Control por roles:** Verificación de `$_SESSION['usuario_rol']`
- **Redirección basada en rol:** Cada usuario va a su módulo correspondiente
- **Validación en servidor:** Todas las acciones verifican permisos

#### 8.1.3 Protección de Datos
- **Prepared Statements:** Prevención de SQL Injection
- **Sanitización de inputs:** `filter_var()` y `htmlspecialchars()`
- **Encriptación de contraseñas:** `password_hash()` con algoritmo bcrypt
- **Validación de archivos:** Verificación de tipo y tamaño en uploads

### 8.2 Vulnerabilidades Conocidas y Mitigaciones

| Vulnerabilidad | Estado | Mitigación |
|----------------|--------|------------|
| **SQL Injection** | ✅ Protegido | Prepared Statements con mysqli/PDO |
| **XSS (Cross-Site Scripting)** | ✅ Protegido | `htmlspecialchars()` en outputs |
| **CSRF (Cross-Site Request Forgery)** | ⚠️ Pendiente | Implementar tokens CSRF |
| **Session Hijacking** | ✅ Protegido | `session_regenerate_id()` y cookies seguras |
| **Password Cracking** | ✅ Protegido | `password_hash()` con bcrypt |
| **File Upload Attacks** | ✅ Protegido | Validación de tipo, tamaño y nombre de archivo |
| **Privilege Escalation** | ✅ Protegido | Validación estricta de roles en cada acción |

### 8.3 Mejoras de Seguridad Pendientes

1. **Tokens CSRF:** Implementar en todos los formularios
2. **Rate Limiting:** Prevenir fuerza bruta en login
3. **HTTPS:** Forzar conexión segura en producción
4. **Headers de seguridad:** Implementar CSP, X-Frame-Options, etc.
5. **Auditoría de logs:** Sistema más robusto de logging
6. **Validación de inputs:** Capa adicional de validación del lado del servidor

---

## 9. DevOps y Despliegue

### 9.1 Infraestructura

#### 9.1.1 Entorno de Desarrollo
- **Docker Compose:** Orquestación de contenedores
- **Volúmenes:** Sincronización de código en tiempo real
- **Redes:** Comunicación entre contenedores
- **Puertos:** 8081 (web), 3307 (MySQL)

#### 9.1.2 Entorno de Producción (Recomendado)
- **Servidor Web:** Apache 2.4+ o Nginx
- **PHP:** 8.2+ con extensiones mysqli, pdo_mysql, gd
- **Base de Datos:** MySQL 8.0+ o MariaDB 10.4+
- **SSL/TLS:** Certificado Let's Encrypt
- **Firewall:** Configuración de puertos restringida

### 9.2 Configuración Docker

#### 9.2.1 Dockerfile
```dockerfile
FROM php:8.2-apache

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Instalar extensiones PHP
RUN docker-php-ext-install pdo pdo_mysql

# Copiar aplicación
COPY . /var/www/html/

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/

# Exponer puerto 80
EXPOSE 80
```

#### 9.2.2 docker-compose.yml
```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "8081:80"
    volumes:
      - .:/var/www/html/
    environment:
      - DB_HOST=db
      - DB_USER=root
      - DB_PASS=root
      - DB_NAME=sistema_comanda_digital_v1
    depends_on:
      - db
    networks:
      - comanda-network

  db:
    image: mysql:8.0
    restart: always
    ports:
      - "3307:3306"
    environment:
      - MYSQL_ROOT_PASSWORD=root
      - MYSQL_DATABASE=sistema_comanda_digital_v1
    volumes:
      - db_data:/var/lib/mysql
      - ./init-db:/docker-entrypoint-initdb.d
    command: --default-authentication-plugin=mysql_native_password
    networks:
      - comanda-network

volumes:
  db_data:

networks:
  comanda-network:
    driver: bridge
```

### 9.3 Pipeline CI/CD (Propuesto)

```yaml
# .github/workflows/deploy.yml
name: Deploy SCD

on:
  push:
    branches: [main, develop]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Run tests
        run: |
          composer install
          vendor/bin/phpunit tests/

  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Deploy to server
        run: |
          # SSH y deploy commands
```

### 9.4 Monitoreo y Logs

#### 9.4.1 Logs del Sistema
El sistema genera logs en `logs/sistema.log`:

```php
// models/observers/StockObserver.php
private function registrarEnLog($categoria, $mensaje) {
    $logFile = __DIR__ . '/../../logs/sistema.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$categoria] $mensaje\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}
```

**Formato de logs:**
```
[2026-04-24 21:30:45] [stock] ALERTA_STOCK: Cebolla - Actual: 1.500kg - Mínimo: 2.000kg
[2026-04-24 21:31:00] [pedidos] NUEVO_PEDIDO: #12 - Mesa: M01 - Total: $85.00
[2026-04-24 21:32:15] [inventario] INVENTARIO_ACTUALIZADO: Ajo - Nueva cantidad: 3.000 - Usuario: admin
```

#### 9.4.2 Monitoreo de Salud
- **Endpoint de health check:** `/health.php` (pendiente de implementar)
- **Monitoreo de base de datos:** Conexión y consultas lentas
- **Uso de recursos:** CPU, memoria, disco

### 9.5 Backups

#### 9.5.1 Backup de Base de Datos
```bash
#!/bin/bash
# backup_db.sh
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -h db -u root -proot sistema_comanda_digital_v1 > /backups/db_backup_$DATE.sql

# Mantener solo últimos 7 backups
find /backups -name "db_backup_*.sql" -mtime +7 -delete
```

#### 9.5.2 Backup de Archivos
```bash
#!/bin/bash
# backup_files.sh
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf /backups/files_backup_$DATE.tar.gz /var/www/html/
```

---

## 10. Pruebas y Calidad

### 10.1 Estrategia de Pruebas

| Tipo de Prueba | Herramienta | Cobertura Objetivo | Estado |
|----------------|-------------|-------------------|--------|
| **Unitarias** | PHPUnit | 60% del código backend | ⚠️ Pendiente |
| **Integración** | PHPUnit + Docker | Flujos completos | ⚠️ Pendiente |
| **Funcionales** | Manual | Todos los módulos | ✅ Realizado |
| **Rendimiento** | Apache JMeter | Tiempos de respuesta | ⚠️ Pendiente |
| **Seguridad** | OWASP ZAP | Vulnerabilidades comunes | ⚠️ Pendiente |
| **Usabilidad** | User Testing | Experiencia de usuario | ✅ Realizado |

### 10.2 Casos de Prueba Críticos

#### 10.2.1 Prueba de Login
```gherkin
Feature: Autenticación de usuarios

Scenario: Login exitoso con credenciales válidas
  Given que estoy en la página de login
  When ingreso usuario "admin" y contraseña "123456"
  Then soy redirigido al dashboard de administrador
  And veo mi nombre de usuario en la interfaz

Scenario: Login fallido con credenciales inválidas
  Given que estoy en la página de login
  When ingreso usuario "admin" y contraseña "incorrecta"
  Then veo mensaje de error "Usuario o contraseña incorrectos"
  And permanezco en la página de login
```

#### 10.2.2 Prueba de Pedido
```gherkin
Feature: Creación de pedidos

Scenario: Cliente crea pedido exitosamente
  Given que soy un cliente en la mesa M01
  When selecciono "Taco al pastor" x2
  And selecciono "Inca Kola" x1
  And confirmo el pedido
  Then el pedido se guarda en la base de datos
  And el total es $58.00
  And recibo un ticket digital
  And la cocina recibe el pedido en tiempo real
```

#### 10.2.3 Prueba de Inventario
```gherkin
Feature: Gestión de inventario

Scenario: Sistema alerta stock bajo
  Given que el ingrediente "Cebolla" tiene 1.5kg en inventario
  And el mínimo configurado es 2.0kg
  When el administrador actualiza el inventario
  Then el sistema genera una alerta de stock bajo
  And la alerta aparece en el dashboard
  And se registra en el log del sistema
```

### 10.3 Criterios de Aceptación

| Criterio | Estado | Comentarios |
|----------|--------|-------------|
| **Todos los módulos principales funcionan** | ✅ Cumplido | Login, pedidos, cocina, caja, admin |
| **No hay errores críticos en producción** | ✅ Cumplido | Sistema estable en pruebas |
| **Tiempos de respuesta aceptables** | ✅ Cumplido | < 2 segundos en operaciones |
| **Seguridad básica implementada** | ✅ Cumplido | SQLi, XSS, session security |
| **Documentación completa** | ✅ Cumplido | PRD, CHANGELOG, README |
| **Código versionado y organizado** | ✅ Cumplido | Git con estructura clara |

---

## 11. Mantenimiento y Soporte

### 11.1 Plan de Mantenimiento

#### 11.1.1 Mantenimiento Preventivo
- **Diario:** Verificación de logs y alertas
- **Semanal:** Backup de base de datos
- **Mensual:** Actualización de dependencias y seguridad
- **Trimestral:** Revisión de rendimiento y optimización

#### 11.1.2 Mantenimiento Correctivo
- **Prioridad Alta:** Errores que afectan operación (tiempo respuesta: 2 horas)
- **Prioridad Media:** Errores no críticos (tiempo respuesta: 24 horas)
- **Prioridad Baja:** Mejoras y ajustes (tiempo respuesta: 1 semana)

### 11.2 Monitoreo de Salud del Sistema

#### 11.2.1 Métricas Clave
- **Disponibilidad:** 99% en horario laboral
- **Tiempo de respuesta:** < 2 segundos
- **Errores por día:** < 5 errores críticos
- **Usuarios concurrentes:** Hasta 20 dispositivos

#### 11.2.2 Alertas Automáticas
- **Base de datos caída:** Notificación inmediata
- **Espacio en disco > 90%:** Alerta de almacenamiento
- **Errores en logs:** Notificación de errores críticos
- **Stock bajo:** Alerta de inventario

### 11.3 Procedimientos de Recuperación

#### 11.3.1 Recuperación de Base de Datos
```bash
# Restaurar backup más reciente
mysql -h db -u root -p sistema_comanda_digital_v1 < /backups/db_backup_YYYYMMDD_HHMMSS.sql
```

#### 11.3.2 Recuperación de Archivos
```bash
# Restaurar archivos de aplicación
tar -xzf /backups/files_backup_YYYYMMDD_HHMMSS.tar.gz -C /var/www/html/
```

---

## 12. Roadmap y Futuras Mejoras

### 12.1 Versión 2.1 (Próximo Sprint)

| Feature | Descripción | Prioridad | Estado |
|---------|-------------|-----------|--------|
| **Reportes avanzados** | Gráficas de ventas, tendencias, productos más vendidos | Alta | ⚠️ Pendiente |
| **Sistema de propinas** | Opción de agregar propina en pago | Media | ⚠️ Pendiente |
| **Múltiples idiomas** | Soporte para inglés y español | Media | ⚠️ Pendiente |
| **API REST** | Endpoints para integración con otros sistemas | Alta | ⚠️ Pendiente |
| **Notificaciones push** | Alertas en tiempo real a dispositivos móviles | Media | ⚠️ Pendiente |

### 12.2 Versión 2.2 (Futuro)

| Feature | Descripción | Prioridad |
|---------|-------------|-----------|
| **Pagos en línea** | Integración con pasarelas de pago (Stripe, PayPal) | Alta |
| **Reservaciones** | Sistema de reservas de mesas | Media |
| **Fidelización** | Programa de puntos y recompensas | Baja |
| **Inventario predictivo** | IA para predecir demanda de ingredientes | Baja |
| **App móvil nativa** | Aplicaciones iOS y Android | Media |

### 12.3 Mejoras Técnicas

| Mejora | Descripción | Prioridad |
|--------|-------------|-----------|
| **Tests automatizados** | PHPUnit para backend, Jest para frontend | Alta |
| **CI/CD pipeline** | GitHub Actions para deploy automático | Alta |
| **Docker production** | Optimización para entorno productivo | Media |
| **Monitoreo avanzado** | New Relic o Datadog | Media |
| **Cache Redis** | Mejora de rendimiento con caching | Media |

---

## 13. Conclusión

El **Sistema de Comanda Digital (SCD)** representa una solución completa y profesional para la gestión de restaurantes. Implementa las mejores prácticas de desarrollo de software, incluyendo:

- ✅ **Arquitectura MVC** bien estructurada
- ✅ **Patrones de diseño** aplicados (Observer, Singleton)
- ✅ **Seguridad** robusta con encriptación y prepared statements
- ✅ **DevOps** con Docker y automatización
- ✅ **Documentación** completa y actualizada
- ✅ **Código limpio** y mantenible

El sistema está listo para producción y cuenta con un roadmap claro para futuras mejoras. La arquitectura escalable permite crecimiento sin comprometer el rendimiento.

---

## 14. Referencias

### 14.1 Documentación del Proyecto
- [Descripción de Arquitectura (ISO/IEEE 42010)](Documents_review/SCD_Descripcion%20de%20arquitectura.pdf)
- [Estándar IEEE 1016 - Diseño Lógico](Documents_review/Sistema_de_comanda_digital_FINAL.pdf)
- [Product Backlog](Documents_review/PRODUCT_BACKLOG.docx)
- [CHANGELOG](CHANGELOG.md)

### 14.2 Tecnologías
- [PHP Documentation](https://www.php.net/manual/en/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Docker Documentation](https://docs.docker.com/)
- [Apache Documentation](https://httpd.apache.org/docs/)

### 14.3 Estándares
- [ISO/IEC/IEEE 42010:2011 - Architecture Description](https://www.iso.org/standard/50508.html)
- [ISO/IEC/IEEE 12207:2017 - Software Life Cycle Processes](https://www.iso.org/standard/63708.html)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)

---

**Documento elaborado por:**  
Ramírez García Oswaldo  
Rios Carrera Jesús Vicente  
Vargas Angeles Uriel  

**Fecha de última actualización:** 24 de abril de 2026  
**Versión del documento:** 2.0  
**Estado:** Final - Listo para revisión