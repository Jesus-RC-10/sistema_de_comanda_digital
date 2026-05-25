
# HERRAMIENTAS DE TRABAJO (WORK TOOLS)

| Campo | Valor |
|-------|-------|
| **Proyecto** | Sistema de Comanda Digital |
| **Versión** | 1.0 |
| **Fecha** | 13 Mayo 2026 |
| **Sistema Operativo** | Fedora 44 (Forty Four) |
| **Metodología** | Scrum + DevOps |

---

## 1. Stack Tecnológico (Tech Stack) Completo

| Herramienta (Tool) | Versión (Version) | Propósito |
|-------------------|------------------|-----------|
| **Sistema Operativo** | Fedora 44 (Forty Four) | Sistema operativo principal |
| **XAMPP** | 8.0.30-0 | Entorno legado (legacy) — PHP 8.0.30, MariaDB 11.8.6 |
| **Docker Engine** | 29.4.2 | Contenedores (containers) de producción |
| **Docker Compose** | 5.1.2 | Orquestación de contenedores |
| **PHP (XAMPP)** | 8.0.30 | Entorno de desarrollo local (legacy) |
| **PHP (Docker)** | 8.2 (Apache + mod_rewrite) | Entorno de producción en contenedor |
| **MySQL/MariaDB (XAMPP)** | MariaDB 11.8.6 (client 15.2) | Base de datos local (legacy) |
| **MySQL (Docker)** | 8.0 | Base de datos en contenedor |
| **Node.js** | 22.22.2 | Entorno de ejecución (runtime) JavaScript |
| **npm** | 10.9.7 | Gestor de paquetes (package manager) |
| **opencode** | 1.14.50 | Asistente AI por línea de comandos (CLI) |
| **cline** | 3.82.0 | Extensión VS Code con AI + MCP (Model Context Protocol) |
| **Playwright** | 1.59.1 | Automatización de navegador (browser automation) |
| **PHPUnit** | ^10.5 | Pruebas unitarias (unit tests) PHP |
| **@testsprite/testsprite-mcp** | 0.0.37 | Servidor MCP de pruebas automatizadas (AI testing) |
| **mcp-markdownify-server** | 1.0.4 | Conversión de HTML/PDF a Markdown |

## 2. Entornos de Ejecución (Environments)

| Puerto | Docker (Producción) | XAMPP (Legacy) |
|--------|--------------------|----------------|
| Web | 8081 → 80 (Apache) | 80 (Apache) |
| MySQL | 3307 → 3306 (MySQL 8.0) | 3306 (MariaDB 11.8.6) |
| phpMyAdmin | No configurado | `/phpmyadmin` |

**Diferencia clave:** Docker usa PDO para conexión a base de datos, mientras que XAMPP legacy usa `mysqli` directo. Por eso existen archivos duplicados (`Mesa.php` vs `MesaModel.php`, etc.).

## 3. opencode — Generación del PRD.md y Asistencias

**opencode** es el asistente AI por línea de comandos (CLI) que se usa para:

- **Generar y actualizar el PRD.md**: Analiza el código fuente y la estructura del proyecto para mantener el PRD actualizado automáticamente
- **Documentar cambios**: Cuando se agregan nuevas funcionalidades, opencode actualiza la documentación
- **Asistencia en desarrollo**: Ayuda con debugging, refactorización, y sugerencias de código

```
# Iniciar opencode
opencode

# Generar comando para actualizar PRD
opencode "Actualiza PRD.md con el estado actual del proyecto"
```

## 4. cline + Servidores MCP (Model Context Protocol)

**cline** es la extensión de VS Code que orquesta 3 servidores MCP. Configuración actual desde `cline_mcp_settings.json`:

```
Archivo: /home/waldo/.config/Code/User/globalStorage/saoudrizwan.claude-dev/settings/cline_mcp_settings.json
```

```
MCP Servers configurados:

1. PLAYWRIGHT MCP (Browser Automation)
   - Comando: npx -y @playwright/mcp@latest
   - Tipo: stdio | Timeout: 30s
   - Funcion: Ejecuta pruebas headless en Chromium contra localhost:8081
   - Estado: Activo

2. TESTSPRITE MCP (AI Testing Engine)
   - Comando: npx @testsprite/testsprite-mcp@latest
   - Tipo: stdio
   - API_KEY: configurada en el archivo original
   - Funcion: Genera planes de prueba, ejecuta en cloud, reporta en dashboard interactivo
   - Estado: Activo

3. MARKDOWNIFY MCP (Conversion a Markdown)
   - Comando: node /home/waldo/Documentos/Cline/MCP/markdownify-mcp/dist/index.js
   - Tipo: stdio
   - Funcion: Convierte HTML/PDF a Markdown para reportes
   - Estado: Activo
```

### 4.1 Playwright MCP — Automatización de Navegador (Browser Automation)

- **Proposito**: Ejecuta pruebas E2E (End-to-End) en un navegador headless Chromium
- **Destino**: Apunta a `http://localhost:8081` (servidor Docker)
- **Capacidades**: Navegacion, llenado de formularios, clics, captura de screenshots, grabacion de video
- **Pruebas que ejecuta**: Login (4 roles), menu digital, creacion de pedidos, cocina, caja/pago

### 4.2 TestSprite MCP — Motor de Pruebas Automatizadas (AI Testing)

- **Version**: 0.0.37
- **API Key**: Configurada en `cline_mcp_settings.json` y en `~/.config/@testsprite/testsprite-mcp-nodejs/config.json`
- **Proposito**: Genera automaticamente planes de prueba, scripts de prueba y los ejecuta en la nube (cloud) de TestSprite
- **Dashboard interactivo**: Los resultados se suben a `testsprite.com` con visualizacion interactiva (screenshots, videos, historico, metricas)
- **Respaldo local (local backup)**:
  - Scripts Python: `testsprite_tests/test-*.py`
  - Planes de prueba: `testsprite_tests/*test_plan.json`
  - Reportes: `testsprite_tests/*-test-report.md` y `testsprite_tests/*-test-report.html`
  - Plantillas de reporte: `node_modules/@testsprite/testsprite-mcp/dist/assets/`

### 4.3 Markdownify MCP — Conversión a Markdown

- **Version**: 1.0.4 (mcp-markdownify-server)
- **Ubicacion**: `/home/waldo/Documentos/Cline/MCP/markdownify-mcp/dist/index.js`
- **Proposito**: Convierte reportes HTML y PDF generados por las pruebas a formato Markdown
- **Uso**: Se integra con cline para transformar automaticamente los reportes de TestSprite y Playwright a `.md`

## 5. TestSprite MCP — Detalle de Funcionamiento

```
Arquitectura de TestSprite:

+--------------------------------------------------------------------+
|                         TESTSPRITE MCP                             |
+--------------------------------------------------------------------+
|                                                                     |
|  cline (VS Code) ---> TestSprite MCP ---> API Cloud (testsprite.com)|
|       |                                                             |
|       | 1. Lee PRD.md y codigo fuente                               |
|       | 2. Genera plan de pruebas (test plan) automatico            |
|       | 3. Crea scripts de prueba (Python/JS)                       |
|       | 4. Ejecuta en cloud de TestSprite                           |
|       | 5. Descarga resultados                                      |
|       |                                                             |
|       v                                                             |
|  +---------------------------------------------------------------+ |
|  |                    RESULTADOS (Results)                        | |
|  |                                                               | |
|  |  testsprite_tests/ (Archivos locales)                        | |
|  |    +-- testsprite_backend_test_plan.json   (plan backend)    | |
|  |    +-- testsprite_frontend_test_plan.json  (plan frontend)   | |
|  |    +-- test-001_*.py                       (script Python)   | |
|  |    +-- test-002_*.py                       (script Python)   | |
|  |    +-- testsprite-mcp-test-report.md       (reporte .md)     | |
|  |    +-- testsprite-mcp-test-report.html     (reporte .html)   | |
|  |                                                               | |
|  |  Dashboard testsprite.com (Interactivo / Cloud)               | |
|  |    +-- Historico de pruebas                                   | |
|  |    +-- Screenshots por paso                                   | |
|  |    +-- Videos de sesiones                                     | |
|  |    +-- Metricas de cobertura                                  | |
|  |    +-- Exportacion a Python                                   | |
|  +---------------------------------------------------------------+ |
+--------------------------------------------------------------------+
```

## 6. Diagrama de Flujo (Flow Diagram)

```
+----------------------------------------------------------------------------------+
|            FLUJO DE TRABAJO INTEGRADO (Integrated Workflow)                      |
+----------------------------------------------------------------------------------+
|                                                                                  |
|  +--------------+     +--------------+     +--------------+     +--------------+ |
|  | DESARROLLADOR|     |   opencode   |     |    cline    |     |   Docker    | |
|  |    (Dev)    |--->|   (CLI)     |--->|  (VS Code)  |--->| (:8081)     | |
|  | Escribe     |     | Analiza y   |     | Orquesta    |     | Servidor    | |
|  | codigo PHP  |     | documenta   |     | 3 MCP       |     | web + db    | |
|  | /JS         |     | PRD.md      |     | servers     |     |             | |
|  +--------------+     +--------------+     +------+-------+     +--------------+ |
|                                                    |                             |
|                            +-----------------------+----------------+           |
|                            |                       |                |           |
|                            v                       v                v           |
|  +----------------------------+  +------------------------+  +--------------+   |
|  |     TESTSPRITE MCP         |  |     PLAYWRIGHT MCP     |  | MARKDOWNIFY  |   |
|  |  (Pruebas Automatizadas)   |  |  (Browser Automation)  |  |    MCP       |   |
|  |                            |  |                        |  | (Reportes)   |   |
|  | 1. Lee PRD.md y codigo     |  | 1. Navega a :8081     |  |              |   |
|  | 2. Genera plan de pruebas  |  | 2. Prueba login       |  | Convierte    |   |
|  |    (test plan)             |  |    (4 roles)          |  | HTML/PDF     |   |
|  | 3. Crea scripts Python     |  | 3. Prueba menu        |  | a Markdown   |   |
|  | 4. Ejecuta en cloud        |  | 4. Prueba crear       |  | (.md)        |   |
|  | 5. Sube resultados a       |  |    pedido             |  |              |   |
|  |    dashboard interactivo   |  | 5. Prueba cocina      |  |              |   |
|  | 6. Genera respaldos        |  | 6. Prueba caja/pago   |  |              |   |
|  |    locales (Python)        |  | 7. Captura screenshots|  |              |   |
|  |                            |  |    y videos           |  |              |   |
|  +-------------+--------------+  +-----------+------------+  +--------------+   |
|                |                              |                                 |
|                +--------------+---------------+                                 |
|                               |                                                 |
|                               v                                                 |
|  +--------------------------------------------------------------------------+   |
|  |                           RESULTADOS (Results)                           |   |
|  |                                                                          |   |
|  |  +---------------------------------------+  +------------------------+  |   |
|  |  |  DASHBOARD TESTSPRITE.COM             |  |  ARCHIVOS LOCALES      |  |   |
|  |  |  (Interactivo / Cloud)                |  |  (Local Files)         |  |   |
|  |  |                                       |  |                        |  |   |
|  |  |  * Historico de pruebas               |  |  * testsprite_tests/   |  |   |
|  |  |  * Screenshots por paso               |  |    +-- *test_plan.json |  |   |
|  |  |  * Videos de sesiones                 |  |    +-- test-*.py       |  |   |
|  |  |  * Metricas de cobertura              |  |    +-- *-report.md    |  |   |
|  |  |  * Respaldo automatico Python         |  |    +-- *-report.html  |  |   |
|  |  |  * Exportacion de resultados          |  +------------------------+  |   |
|  |  +---------------------------------------+                              |   |
|  +--------------------------------------------------------------------------+   |
|                               |                                                 |
|                               v                                                 |
|  +--------------------------------------------------------------------------+   |
|  |              CICLO DE RETROALIMENTACION (Feedback Loop)                  |   |
|  |                                                                          |   |
|  |  Bugs encontrados en pruebas --> opencode analiza y sugiere correcciones |   |
|  |  --> Developer aplica cambios en codigo --> cline + MCP re-ejecutan     |   |
|  |  --> Se repite el ciclo hasta que todas las pruebas pasen                |   |
|  |                                                                          |   |
|  |  +---------+    +----------+    +----------+    +--------------+        |   |
|  |  |  Bug    |--->| opencode |--->|Developer |--->| cline + MCP |        |   |
|  |  |Detectado|    | Analiza  |    | Corrige  |    | Re-ejecuta  |        |   |
|  |  +---------+    +----------+    +----------+    +------+-------+        |   |
|  |                                                         |                |   |
|  |                                                         v                |   |
|  |                                                  +-------------+         |   |
|  |                                                  |  Pruebas   |         |   |
|  |                                                  |  pasan?    |         |   |
|  |                                                  |  SI / NO   |         |   |
|  |                                                  +------+-----+         |   |
|  |                                                    SI / NO              |   |
|  |                                                     |    |              |   |
|  |                                                     v    +----> Vuelve  |   |
|  |                                               Listo          al inicio |   |
|  +--------------------------------------------------------------------------+   |
+----------------------------------------------------------------------------------+
```


#### Fase 1: Desarrollo (Development)

1. Developer escribe codigo PHP/JS en el proyecto
2. opencode analiza el codigo y genera/actualiza PRD.md automaticamente
3. Se confirman los cambios con Git (commit + push)

#### Fase 2: Preparacion de Pruebas (Test Preparation)

4. Se inicia Docker (docker compose up -d --build) para entorno de pruebas
5. Se verifica que http://localhost:8081 este respondiendo
6. Se abre VS Code con cline listo para orquestar pruebas

#### Fase 3: Ejecucion de Pruebas (Test Execution)

7. cline ejecuta TestSprite MCP:
   - Lee PRD.md y codigo fuente
   - Genera plan de pruebas (test plan) automatico
   - Crea scripts Python de prueba
   - Ejecuta pruebas en cloud de TestSprite
   - Sube resultados al dashboard interactivo testsprite.com

8. cline ejecuta Playwright MCP (Browser Automation):
   - Navega a http://localhost:8081
   - Prueba login con 4 roles (admin, mesero, cocina, caja)
   - Prueba seleccion de mesas
   - Prueba menu digital y carrito
   - Prueba creacion de pedidos
   - Prueba interfaz de cocina (cambios de estado)
   - Prueba caja (pago y calculo de cambio)
   - Captura screenshots y videos de cada paso

9. Markdownify MCP convierte reportes HTML a Markdown

#### Fase 4: Revision de Resultados (Results Review)

10. Resultados disponibles en:
    - Dashboard interactivo: testsprite.com (screenshots, videos, historico)
    - Archivos locales: testsprite_tests/*.md, *.html, *.py

11. Developer revisa bugs y fallos encontrados

#### Fase 5: Correccion y Retroalimentacion (Fix & Feedback)

12. opencode analiza los reportes de prueba y sugiere correcciones
13. Developer aplica cambios en el codigo
14. Se confirman cambios con Git (commit + push)
15. Se repite el ciclo desde la Fase 3 hasta que todas las pruebas pasen

## 7. Comandos de Operación (Operation Commands)

#### Docker (Entorno de Produccion)

```bash
# Construir e iniciar servicios (primera vez o con cambios)
docker compose up -d --build

# Iniciar servicios existentes
docker compose up -d

# Ver estado de servicios
docker compose ps

# Ver logs en vivo de un servicio
docker compose logs -f web

# Ver logs de base de datos
docker compose logs -f db

# Detener todos los servicios
docker compose down

# Detener y eliminar volumenes (borra datos BD)
docker compose down -v

# Reiniciar un servicio especifico
docker compose restart web

# Entrar al contenedor PHP
docker compose exec web bash

# Entrar al contenedor MySQL
docker compose exec db mysql -u root -proot sistema_comanda_digital_v1

# Backup de base de datos desde contenedor
docker compose exec db mysqldump -u root -proot sistema_comanda_digital_v1 > backup.sql

# Restaurar base de datos desde backup
cat backup.sql | docker compose exec -T db mysql -u root -proot sistema_comanda_digital_v1

# Ejecutar comandos PHP dentro del contenedor
docker compose exec web php -v
docker compose exec web php index.php

# Ejecutar PHPUnit dentro del contenedor
docker compose exec web ./vendor/bin/phpunit
```

#### XAMPP (Entorno Legacy / Desarrollo Local)

```bash
# Iniciar TODOS los servicios (Apache + MySQL + ProFTPD)
sudo /opt/lampp/ctlscript.sh start

# Iniciar solo Apache (puerto 80)
sudo /opt/lampp/ctlscript.sh start apache

# Iniciar solo MySQL (puerto 3306)
sudo /opt/lampp/ctlscript.sh start mysql

# Detener todos los servicios
sudo /opt/lampp/ctlscript.sh stop

# Detener un servicio especifico
sudo /opt/lampp/ctlscript.sh stop apache

# Reiniciar servicios
sudo /opt/lampp/ctlscript.sh restart

# Ver estado de servicios
sudo /opt/lampp/ctlscript.sh status

# Iniciar XAMPP con interfaz grafica (GUI)
sudo /opt/lampp/manager-linux-x64.run

# Acceder a phpMyAdmin (web)
# http://localhost/phpmyadmin
```

#### Git — Control de Cambios (Version Control)

```bash
# Verificar estado actual del repositorio
git status

# Ver diferencias sin confirmar (unstaged changes)
git diff

# Ver diferencias del area de preparacion (staged changes)
git diff --staged

# Agregar archivo especifico al area de preparacion (staging)
git add archivo.php

# Agregar todos los archivos modificados y nuevos
git add .

# Crear confirmacion (commit) con mensaje
git commit -m "Descripcion clara del cambio realizado"

# Subir cambios al repositorio remoto (GitHub)
git push origin main

# Bajar cambios del repositorio remoto
git pull origin main

# Ver historial de confirmaciones (commit history)
git log --oneline

# Ver historial detallado
git log --oneline --graph --all

# Crear una nueva rama (branch)
git checkout -b nombre-rama

# Cambiar de rama
git checkout main

# Fusionar rama (merge)
git merge nombre-rama

# Ejemplo de ciclo completo de cambio:
# git status
# git add .
# git commit -m "Fix: corrige error CORS en cocina.js"
# git push origin main
```

#### opencode (CLI)

```bash
# Iniciar opencode
opencode

# Generar/actualizar PRD.md con estado actual del proyecto
opencode "Actualiza PRD.md con el estado actual del codigo y las pruebas"

# Ver version de opencode
opencode --version

# Obtener ayuda
opencode --help
```

#### cline + MCP (dentro de VS Code)

```bash
# Comando para iniciar el flujo completo de testing (escribir en cline chat):
# "Help me test this project with TestSprite"

# Comando para pruebas especificas de Playwright:
# "Ejecuta pruebas de login con Playwright en localhost:8081"

# Comando para convertir reportes:
# "Convierte el reporte HTML a Markdown con Markdownify"
```

#### Pruebas (Testing)

```bash
# Ejecutar PHPUnit (dentro del contenedor Docker)
docker compose exec web ./vendor/bin/phpunit

# Ejecutar PHPUnit con cobertura
docker compose exec web ./vendor/bin/phpunit --coverage-html=coverage

# Ejecutar tests especificos
docker compose exec web ./vendor/bin/phpunit tests/Unit/CajaTest.php

# Ejecutar script de pruebas local
./scripts/run-tests.sh

# Ver reportes de TestSprite (locales)
cat testsprite_tests/testsprite-mcp-test-report.md

# Ver reporte detallado de Playwright (MCP)
cat PruebasIntentotestsprint-mcp-test-report.md
```

#### Respaldo (Backup)

```bash
# Backup manual de base de datos (Docker)
docker compose exec db mysqldump -u root -proot sistema_comanda_digital_v1 > backups/backup_$(date +%Y%m%d_%H%M%S).sql

# Backup con script automatizado
./scripts/backup.sh

# Backup de base de datos (XAMPP legacy)
/opt/lampp/mysql/bin/mysqldump -u root sistema_comanda_digital_v1 > backups/backup_xampp.sql
```

---

*Documento generado: 13 Mayo 2026*
*Version: 1.0*
*Sistema Operativo: Fedora 44*
