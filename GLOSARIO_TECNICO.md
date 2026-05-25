# Glosario Técnico — Sistema de Comanda Digital (SCD)

## Proyecto: Taquería El Informático
## Estándar: IEEE 16326 (PMP) + Scrum + DevOps

---

## A

- **AdminController.php** — Controlador que gestiona el panel de administración: dashboard, CRUD de mesas, menú, usuarios, inventario y reportes.
- **alertas_sistema** — Tabla de base de datos que almacena notificaciones del sistema con nivel (alto/medio/bajo), tipo, mensaje y estado de lectura.
- **Apache mod_rewrite** — Módulo de Apache que permite reescribir URLs limpias (amigables) redirigiendo todas las peticiones a `index.php` como Front Controller.
- **Arquitectura MVC** — Patrón de diseño Modelo-Vista-Controlador que separa la lógica de negocio (Model), la presentación (View) y el flujo de control (Controller).
- **AuthController.php** — Controlador que maneja la autenticación de usuarios: login, logout, redirección por rol y regeneración de ID de sesión.
- **Auto-refresh (polling)** — Mecanismo de actualización periódica mediante peticiones AJAX cada N segundos (5s mesero, 10s cocina, 30s caja) para simular tiempo real sin WebSockets.

## B

- **Backlog** — Lista priorizada de todas las funcionalidades, requisitos, mejoras y correcciones necesarias para el producto, gestionada por el Product Owner.
- **BASE_URL** — Constante definida en `config/config.php` que establece la URL base del sistema para evitar errores 404 en recursos estáticos.
- **bcrypt** — Algoritmo de hash para contraseñas utilizado por `password_hash()` de PHP para el almacenamiento seguro de credenciales.
- **Bug rate** — Tasa de errores: cantidad de bugs encontrados dividida entre el número de historias de usuario entregadas en un sprint.
- **Burn-down Chart** — Gráfica que muestra el trabajo pendiente (story points) a lo largo del tiempo de un sprint.

## C

- **CajaController.php** — Controlador del módulo de caja que procesa pagos, calcula cambio automático, cancela ventas y gestiona métodos de pago.
- **Cálculo de cambio** — Función que resta el total de la venta del monto pagado por el cliente (`$cambio = $monto_pagado - $total`) para devolver el cambio exacto.
- **categorias_menu** — Tabla de base de datos que define las categorías del menú (Tacos, Bebidas, Postres) con nombre, descripción, orden y estado activo.
- **Ciclo DevOps** — Conjunto de 8 fases: Planificación, Codificación, Construcción, Pruebas, Lanzamiento, Despliegue, Operación y Monitorización.
- **CocinaController.php** — Controlador de la interfaz de cocina que muestra pedidos pendientes, permite actualizar estados y filtrar por tipo de platillo.
- **Comanda** — Término español para la nota o ticket de pedido en un restaurante. En el sistema, representa la orden completa de un cliente.
- **composer.json** — Archivo de configuración de dependencias PHP que define PHPUnit como dependencia de desarrollo y el autoloading PSR-4.
- **Cronograma de Sprints** — Calendario que define las fechas de inicio y fin de cada sprint, los objetivos y los entregables principales.
- **CRUD** — Acrónimo de Create, Read, Update, Delete: operaciones básicas de persistencia sobre una entidad (mesas, productos, usuarios, etc.).
- **CSRF (Cross-Site Request Forgery)** — Tipo de ataque de seguridad donde se ejecutan acciones no autorizadas en nombre de un usuario autenticado. Pendiente de implementar tokens CSRF en formularios.
- **Cycle Time** — Tiempo que el equipo trabaja activamente en una tarea desde que se inicia ("In Progress") hasta que se completa ("Done"). Promedio: 3.17 días/historia.

## D

- **Dashboard admin** — Panel de administración que muestra métricas clave: ventas del día, pedidos activos, mesas ocupadas y alertas de stock.
- **Database.php** — Clase Singleton que proporciona una única instancia de conexión PDO a la base de datos MySQL/MariaDB.
- **db.php** — Archivo alternativo de conexión a base de datos, utilizado como alias/helper en algunos modelos legacy.
- **Definition of Done (DoD)** — Lista de criterios que una historia de usuario debe cumplir para considerarse completada (tests pasan, código revisado, documentado, etc.).
- **Diagrama de clases** — Representación UML de las clases del sistema, sus atributos, métodos y relaciones. Incluido en los PDFs de `Documents_review/`.
- **Diagrama de secuencia** — Diagrama UML que muestra la interacción temporal entre objetos para un caso de uso específico (ej: crear pedido, pagar).
- **Docker Compose** — Herramienta para definir y ejecutar aplicaciones multi-contenedor Docker. En este proyecto: servicios web (PHP 8.2 + Apache) y db (MySQL 8.0).
- **Dockerfile** — Archivo de configuración que construye la imagen Docker del servicio web: PHP 8.2 Apache con extensiones pdo, pdo_mysql, mysqli y Composer.

## E

- **Entregable** — Resultado tangible de un sprint: código funcionando, documentación, diagramas, etc.
- **Estado de pedido** — Ciclo de vida: `pendiente → confirmado → en_preparacion → listo → entregado → cancelado`.
- **Estado de pedido_detalles** — Estados por línea de producto: `pendiente → en_preparacion → listo → entregado`.
- **Estado de venta** — Estados financieros: `pendiente → pagado → cancelado`.
- **Estado de mesa** — Estados físicos: `libre → ocupada → reservada → mantenimiento`.

## F

- **Flag de bloqueo** — Mecanismo de concurrencia que marca una mesa como "en edición" en la base de datos para evitar modificaciones simultáneas.
- **Front Controller** — Patrón de diseño implementado en `index.php` que recibe todas las peticiones HTTP y las redirige al controlador correspondiente según el parámetro `?url=`.
- **Frontend** — Capa de presentación del sistema compuesta por HTML5, CSS3 y JavaScript (ES6+) con polling AJAX para actualización en tiempo real.

## G

- **generar_pdf.php** — Punto de entrada para la generación de reportes en formato PDF/HTML. Actualmente genera HTML con estilo de impresión, no PDF nativo.
- **Git** — Sistema de control de versiones distribuido utilizado para el seguimiento de cambios en el código fuente del proyecto.
- **GitHub Actions** — Plataforma de CI/CD integrada en GitHub. Pendiente de configurar para ejecutar tests automáticos en cada push.
- **Glosario Técnico** — Este documento: listado alfabético de términos técnicos del proyecto con sus definiciones.

## H

- **Healthcheck** — Mecanismo de Docker que verifica periódicamente que un servicio responde correctamente (curl a index.php para web, mysqladmin ping para db).
- **helpers/** — Directorio que contiene utilidades transversales del sistema como `Logger.php` para logging centralizado.
- **Historia de Usuario (HU)** — Descripción breve de una funcionalidad desde la perspectiva del usuario final. Formato: "Como [rol], quiero [acción] para [beneficio]".
- **.htaccess** — Archivo de configuración de Apache que habilita la reescritura de URLs para el patrón Front Controller.

## I

- **IEEE 16326** — Estándar internacional para Planes de Gestión de Proyectos (PMP) de software, utilizado como referencia para la documentación del proyecto.
- **index.php** — Front Controller del sistema: archivo PHP único que recibe todas las peticiones web y las enruta al controlador y acción adecuados.
- **ingredientes** — Tabla de base de datos que almacena los ingredientes del inventario con nombre, categoría, unidad de medida, cantidad actual, cantidad mínima y proveedor.
- **init-db/** — Directorio que contiene el script SQL (`sistema_comanda_digital_v1.sql`) con el esquema completo de la base de datos y datos de semilla.
- **Inventario.php** — Modelo que gestiona el inventario de ingredientes con operaciones CRUD, verificación de stock y alertas de nivel bajo.
- **IVA** — Impuesto al Valor Agregado (16%) aplicado al cálculo de precios en los tests unitarios.

## J

- **JavaScript (ES6+)** — Lenguaje de programación del lado del cliente utilizado para la interactividad del sistema: carrito de compras, polling AJAX, notificaciones y actualización dinámica de interfaces.

## K

- **KPI (Key Performance Indicator)** — Indicador clave de rendimiento para medir el éxito del proyecto: Sprint Velocity, Throughput, Cycle Time, Lead Time, Bug Rate.

## L

- **Lead Time** — Tiempo total desde que una necesidad es identificada hasta que se entrega al cliente final. Promedio: 5.7 días/historia.
- **Logger.php** — Clase en `helpers/Logger.php` que implementa logging centralizado en formato JSON con niveles: info, warning, error, debug. Actualmente existe pero no está integrada en los controladores.
- **Login** — Módulo de autenticación que verifica credenciales contra la base de datos usando bcrypt y redirige según el rol del usuario.

## M

- **Mantenimiento (estado mesa)** — Estado que indica que una mesa no está disponible por reparación o mantenimiento.
- **MenuController.php** — Controlador que despliega el menú digital con productos organizados por categorías y gestiona la generación de tickets.
- **MesaController.php** — Controlador que gestiona la selección de mesas y el cambio de estado (libre/ocupada).
- **MeseroController.php** — Controlador de la interfaz del mesero para ver pedidos activos, actualizar estados y recibir solicitudes de ayuda.
- **mesas** — Tabla de base de datos que registra las mesas del restaurante con número único, estado, ubicación y disponibilidad.
- **Método de pago** — Forma de pago aceptada: efectivo (con cambio), tarjeta, transferencia o mixto.
- **Modelo de datos (ER)** — Diagrama Entidad-Relación que muestra las 11 tablas de la base de datos y sus relaciones: mesas, pedidos, pedido_detalles, productos, categorias_menu, ventas, usuarios, inventario, ingredientes, recetas_producto, alertas_sistema.
- **MySQL/MariaDB** — Sistema de gestión de bases de datos relacional utilizado para persistencia del sistema.

## N

- **NotificationManager.php** — Implementación del patrón Observer (sujeto) que gestiona y notifica eventos del sistema como alertas de stock bajo.
- **Nuevas Funcionalidades (NF)** — Historias de usuario sugeridas para el backlog futuro: descuento automático de inventario, PDF real, CSRF, CI/CD, gráficas, propinas, multi-idioma, API REST, app móvil.

## O

- **Observer Pattern** — Patrón de diseño de comportamiento implementado con `NotificationManager` (sujeto) y `StockObserver` (observador) para alertas automáticas de inventario.
- **OrderController.php** — Controlador que expone una API REST para la gestión de pedidos: crear (`POST /order/save`), obtener pendientes (`GET /order/pending`), completar (`POST /order/complete`).
- **OrderModel.php** — Modelo que gestiona las operaciones de pedidos con transacciones PDO atómicas para la base de datos en Docker.

## P

- **password_hash()** — Función de PHP que genera un hash bcrypt seguro para almacenar contraseñas en la base de datos.
- **Patrón Singleton** — Patrón de diseño creacional implementado en `Database.php` que asegura una única instancia de conexión a la base de datos.
- **PDO (PHP Data Objects)** — Extensión de PHP para acceso a bases de datos con soporte de prepared statements, transacciones y múltiples drivers.
- **pedido_detalles** — Tabla que almacena los productos individuales de cada pedido con cantidad, precio unitario, subtotal, notas y estado individual.
- **pedidos** — Tabla principal de órdenes con referencia a mesa, usuario, estado, total, notas y fechas de creación/actualización.
- **PedidoModel.php** — Modelo para la gestión de detalles de pedido y operaciones relacionadas con PDO en entorno Docker.
- **PHP 8.2** — Versión del lenguaje de programación PHP utilizada en el backend, ejecutándose sobre Apache en el contenedor Docker.
- **PHPUnit** — Framework de pruebas unitarias para PHP (versión 10.x) configurado como dependencia de desarrollo en composer.json.
- **phpunit.xml** — Archivo de configuración de PHPUnit que define los suites de tests (Unit, Integration), bootstrap, variables de entorno y configuración de cobertura.
- **Plan de Gestión de Proyectos (PMP)** — Documento basado en IEEE 16326 que cubre: información general, roles, historial de sprints, definiciones, métricas, KPIs, infraestructura, gestión de riesgos, cronograma y criterios de aceptación.
- **Platillo** — Producto individual del menú con nombre, descripción, precio, categoría, imagen, stock y tiempo de preparación.
- **PRD (Product Requirements Document)** — Documento de requisitos del producto que especifica funcionalidades (RF), requisitos no funcionales (RNF), arquitectura, flujo de datos, estado DevOps y backlog priorizado.
- **Prioridad** — Nivel de importancia de una historia de usuario: Alta, Media o Baja, definida por el Product Owner.
- **Product Backlog** — Lista priorizada de 58 historias de usuario organizadas en 12 módulos: Autenticación, Pedidos, Cocina, Caja, Administración, Inventario, Reportes, Mesero, Arquitectura, DevOps, Pruebas, Documentación.
- **Product Owner (PO)** — Rol responsable de priorizar el backlog, validar historias con el negocio y gestionar a los stakeholders. En el proyecto: Yosabeth Pérez Estrada.
- **productos** — Tabla de base de datos que almacena los productos del menú con nombre, descripción, precio, categoría, imagen, stock y tiempo de preparación.
- **ProductoModel.php** — Modelo activo para la gestión de productos con PDO en entorno Docker.
- **Proveedor** — Nombre del proveedor asociado a un ingrediente en el inventario.
- **PSR-4** — Estándar de PHP para autoloading de clases mediante namespaces, configurado en composer.json con los prefijos `App\` (models) y `Controllers\` (controllers).

## R

- **recetas_producto** — Tabla intermedia que asocia productos con ingredientes y sus cantidades para definir la receta de cada platillo.
- **ReportController.php** — Controlador que genera reportes de ventas en formato HTML/PDF: diario, mensual e historial completo.
- **Requisito Funcional (RF)** — Especificación de una funcionalidad concreta que el sistema debe proporcionar (RF01-RF18).
- **Requisito No Funcional (RNF)** — Especificación de calidad del sistema: rendimiento (<2s respuesta), tests, cobertura (>70%), logging, backups, CI/CD, healthchecks (RNF01-RNF08).
- **Riesgo** — Evento potencial que puede afectar negativamente al proyecto. Identificados: concurrencia de mesas, pérdida de sesión, inconsistencia de stock, caída del servidor, incompatibilidad de dispositivos.
- **Rol de usuario** — Nivel de acceso al sistema: admin (todo), mesero (pedidos), cocina (estados), caja (pagos).

## S

- **SCD (Sistema de Comanda Digital)** — Nombre del proyecto: aplicación web para la gestión de pedidos en restaurantes.
- **Scrum** — Marco de trabajo ágil utilizado en el proyecto con sprints de 10-14 días, roles definidos (SM, PO, Dev Team) y ceremonias (Planning, Daily Scrum, Review, Retrospective).
- **Scrum Master (SM)** — Rol que facilita el proceso Scrum, remueve blockers y gestiona las ceremonias. En el proyecto: Jorge Edmundo Osornio Tapia.
- **session_regenerate_id()** — Función de PHP que regenera el ID de sesión después del login para prevenir ataques de fijación de sesión.
- **Singleton (Database.php)** — Patrón que garantiza una única instancia de conexión PDO a la base de datos en toda la aplicación.
- **Sprint** — Bloque de tiempo de duración fija (10-14 días) durante el cual se crea un incremento de software "terminado" y potencialmente desplegable.
- **Sprint Velocity** — Cantidad de story points completados por sprint. Promedio: ~12.5 SP/sprint.
- **Stock bajo** — Alerta generada por el Observer Pattern cuando la cantidad actual de un ingrediente es menor a su cantidad mínima.
- **StockObserver.php** — Implementación del patrón Observer (observador) que reacciona a cambios en el inventario y genera alertas de stock bajo en `alertas_sistema`.
- **Story Points (SP)** — Unidad de medida de esfuerzo relativo basada en la complejidad, riesgo e incertidumbre de una historia de usuario.

## T

- **Throughput** — Cantidad de unidades de trabajo (historias de usuario) completadas por unidad de tiempo (sprint). Promedio: 5.3 historias/sprint.
- **Ticket** — Comprobante digital del pedido generado al confirmar la orden. Actualmente en formato HTML con estilo de impresión.
- **Transacción SQL atómica** — Conjunto de operaciones SQL que se ejecutan como una unidad indivisible (COMMIT/ROLLBACK) para garantizar la consistencia de datos.

## U

- **UAT (User Acceptance Testing)** — Pruebas de aceptación del usuario donde los stakeholders validan que el sistema cumple con los requisitos.
- **Unidad de medida** — Unidades utilizadas para ingredientes: kg (kilogramo), gr (gramo), lt (litro), ml (mililitro), unidad, paquete.
- **User.php** — Modelo para la gestión de usuarios del sistema con autenticación, CRUD y control de acceso por roles.
- **usuarios** — Tabla de base de datos que almacena los usuarios del sistema con usuario único, password hash bcrypt, nombre, rol, estado activo y fechas.

## V

- **Venta.php / VentaModel.php** — Modelos que gestionan las ventas: creación, pago, cancelación, cálculo de cambio y actualización de estados.
- **ventas** — Tabla de base de datos que registra las transacciones de venta con pedido asociado, total, método de pago, estado y fechas.
- **Versionado semántico** — Sistema de versiones con formato `MAJOR.MINOR.PATCH` (ej: v1.0.0) para gestionar releases del software.

## W

- **WebSocket** — Tecnología de comunicación bidireccional en tiempo real entre cliente y servidor. Recomendada como mejora futura para reemplazar el polling actual.

## X

- **XAMPP** — Paquete de servidor local (Apache + MySQL + PHP + Perl) utilizado durante la fase inicial de desarrollo. Actualmente en estado legacy, reemplazado por Docker.

---

*Documento generado el 19 de mayo de 2026*
*Basado en el análisis completo del código fuente, documentación técnica, PRD, Product Backlog, Plan de Gestión de Proyectos (IEEE 16326) y documentos DevOps del Sistema de Comanda Digital.*
