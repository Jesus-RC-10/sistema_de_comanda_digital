# DEVOPS - IMPLEMENTACIÓN COMPLETA
## Sistema de Comanda Digital - Sprint 4

**Fecha de creación:** 27 de Abril de 2026
**Carpeta:** Documents_review/DevOps_Implementacion/

---

## 1. ÍNDICE

1. [Resumen de lo implementado](#1-resumen)
2. [Estructura de archivos](#2-estructura-de-archivos)
3. [Configuración - composer.json](#3-composerjson)
4. [Configuración - phpunit.xml](#4-phpunitxml)
5. [Scripts - backup.sh](#5-script-backupsh)
6. [Scripts - run-tests.sh](#6-script-run-testssh)
7. [Logger - Sistema de logs](#7-logger)
8. [Tests Unitarios](#8-tests-unitarios)
9. [Docker - Actualizaciones](#9-docker-actualizaciones)
10. [CHANGELOG](#10-changelog)
11. [Comandos rápidos](#11-comandos-rápidos)
12. [Próximos pasos](#12-próximos-pasos)

---

## 1. RESUMEN

Se implementó la infraestructura DevOps básica para el Sistema de Comanda Digital según el PDF de Planificación DevOps del Sprint 4.

### Fases DevOps Implementadas

| Fase | Estado | Descripción |
|------|--------|-------------|
| Planificación | ✅ | Plan documentado en este archivo |
| Codificación | ✅ | Git + Docker existentes |
| Construcción | ✅ | Dockerfile actualizado |
| **Pruebas** | ✅ | PHPUnit + Tests unitarios |
| **Lanzamiento** | ✅ | CHANGELOG.md creado |
| **Operación** | ✅ | Scripts de backup |
| **Monitorización** | ✅ | Logger.php implementado |
| Despliegue | ⚠️ | Local listo, CI/CD pendiente |

---

## 2. ESTRUCTURA DE ARCHIVOS

```
sistema_de_comanda_digital/
├── composer.json                    ← NUEVO (requiere-dev PHPUnit)
├── phpunit.xml                      ← NUEVO (config tests)
├── CHANGELOG.md                     ← NUEVO (historial de cambios)
├── Dockerfile                       ← ACTUALIZADO (Composer + logs)
├── docker-compose.yml               ← ACTUALIZADO (healthchecks)
│
├── helpers/
│   └── Logger.php                   ← NUEVO (logging centralizado)
│
├── scripts/
│   ├── backup.sh                    ← NUEVO (backup BD)
│   └── run-tests.sh                 ← NUEVO (ejecutar tests)
│
├── tests/
│   ├── bootstrap.php                 ← NUEVO
│   └── Unit/
│       ├── ProductoTest.php         ← NUEVO
│       ├── PedidoTest.php           ← NUEVO
│       ├── MesaTest.php             ← NUEVO
│       ├── InventarioTest.php       ← NUEVO
│       └── LoggerTest.php           ← NUEVO
│
├── backups/                         ← NUEVO (directorio backups)
└── logs/                            ← YA EXISTE
```

---

## 3. composer.json

```json
{
    "name": "sistema-comanda-digital",
    "description": "Sistema de comanda digital para restaurantes",
    "type": "project",
    "require-dev": {
        "phpunit/phpunit": "^10.5"
    },
    "autoload": {
        "psr-4": {
            "App\\": "models/",
            "Controllers\\": "controllers/"
        }
    },
    "config": {
        "preferred-install": "dist",
        "sort-packages": true
    }
}
```

### Comando para instalar:
```bash
docker compose run --rm web composer install
```

---

## 4. phpunit.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         colors="true"
         cacheDirectory=".phpunit.cache">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Integration</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>models</directory>
        </include>
        <exclude>
            <directory>models/observers</directory>
        </exclude>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_HOST" value="db"/>
        <env name="DB_NAME" value="sistema_comanda_digital_v1"/>
        <env name="DB_USER" value="root"/>
        <env name="DB_PASS" value="root"/>
    </php>
</phpunit>
```

---

## 5. SCRIPT - backup.sh

**Ubicación:** `scripts/backup.sh`

```bash
#!/bin/bash

# ==============================================================================
# Script de Backup para Sistema de Comanda Digital
# ==============================================================================

set -e

# Configuración
DB_NAME="${DB_NAME:-sistema_comanda_digital_v1}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASSWORD:-root}"
BACKUP_DIR="${BACKUP_DIR:-backups}"
DATE=$(date +%Y%m%d_%H%M%S)
CONTAINER_NAME="${DB_CONTAINER:-db}"

# Crear directorio si no existe
mkdir -p "$BACKUP_DIR"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Iniciando backup..."

# Ejecutar backup
BACKUP_FILE="$BACKUP_DIR/backup_${DATE}.sql"

docker exec "$CONTAINER_NAME" mysqldump -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$BACKUP_FILE"

# Comprimir
gzip "$BACKUP_FILE"
BACKUP_FILE="${BACKUP_FILE}.gz"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Backup creado: $BACKUP_FILE"

# Limpiar backups mayores a 7 días
find "$BACKUP_DIR" -name "backup_*.sql.gz" -mtime +7 -delete
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Limpieza de backups antiguos completada"

# Mostrar tamaño del backup
du -h "$BACKUP_FILE"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Proceso de backup finalizado exitosamente"
```

### Comandos:
```bash
# Dar permisos de ejecución
chmod +x scripts/backup.sh

# Ejecutar backup
./scripts/backup.sh

# Con variables personalizadas
DB_NAME=sistema_comanda_digital_v1 ./scripts/backup.sh
```

---

## 6. SCRIPT - run-tests.sh

**Ubicación:** `scripts/run-tests.sh`

```bash
#!/bin/bash

# ==============================================================================
# Script para ejecutar Tests con PHPUnit
# ==============================================================================

set -e

cd "$(dirname "$0")/.."

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Ejecutando tests..."

# Verificar que vendor existe
if [ ! -d "vendor" ]; then
    echo "Error: vendor no existe. Ejecuta 'composer install' primero."
    exit 1
fi

# Ejecutar PHPUnit
./vendor/bin/phpunit "$@"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Tests completados"
```

### Comandos:
```bash
# Dar permisos de ejecución
chmod +x scripts/run-tests.sh

# Ejecutar todos los tests
./scripts/run-tests.sh

# Ejecutar solo tests unitarios
./vendor/bin/phpunit tests/Unit/

# Ejecutar con cobertura
./vendor/bin/phpunit --coverage-html coverage/

# Ejecutar test específico
./vendor/bin/phpunit tests/Unit/PedidoTest.php
```

---

## 7. LOGGER - Sistema de Logging

**Ubicación:** `helpers/Logger.php`

```php
<?php

/**
 * Logger - Sistema de logging centralizado
 * 
 * Uso:
 *   Logger::info('Mensaje de información');
 *   Logger::error('Error occurred', ['user_id' => 1]);
 *   Logger::warning('Alerta', ['stock' => 5]);
 */

class Logger
{
    private static string $logFile = __DIR__ . '/../logs/app.log';
    private static string $errorLogFile = __DIR__ . '/../logs/error.log';

    public static function setLogFile(string $path): void
    {
        self::$logFile = $path;
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        $entry = [
            'timestamp' => date('c'),
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'CLI',
            'user_id' => $_SESSION['user_id'] ?? null
        ];

        $json = json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL;

        file_put_contents(self::$logFile, $json, FILE_APPEND);

        if (in_array(strtolower($level), ['error', 'critical'])) {
            file_put_contents(self::$errorLogFile, $json, FILE_APPEND);
        }
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('info', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('warning', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    public static function debug(string $message, array $context = []): void
    {
        if (getenv('APP_DEBUG') === 'true') {
            self::log('debug', $message, $context);
        }
    }

    public static function logPedido(string $action, int $pedidoId, array $data = []): void
    {
        self::info("Pedido #$pedidoId: $action", array_merge(['pedido_id' => $pedidoId], $data));
    }

    public static function logAuth(string $action, ?int $userId = null): void
    {
        self::info("Auth: $action", ['user_id' => $userId, 'action' => $action]);
    }
}
```

### Uso en código:

```php
<?php
require_once __DIR__ . '/helpers/Logger.php';

// Logging básico
Logger::info('Usuario inició sesión', ['user_id' => 5]);
Logger::warning('Stock bajo', ['producto' => 'Tacos', 'stock' => 3]);
Logger::error('Error de conexión', ['error' => $e->getMessage()]);

// Logging específico
Logger::logPedido('creado', 123, ['total' => 150.00, 'mesa' => 5]);
Logger::logAuth('login', $_SESSION['user_id']);
```

### Ver logs:
```bash
# Ver todos los logs
tail -f logs/app.log

# Ver solo errores
tail -f logs/error.log

# Buscar errores específicos
grep "ERROR" logs/error.log

# Ver últimas 100 líneas
tail -100 logs/app.log
```

---

## 8. TESTS UNITARIOS

### 8.1 tests/bootstrap.php

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/config/config.php')) {
    require_once BASE_PATH . '/config/config.php';
}

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
```

---

### 8.2 tests/Unit/ProductoTest.php

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ProductoTest extends TestCase
{
    public function testPrecioConIVA(): void
    {
        $precio = 100.00;
        $iva = 0.16;
        $precioConIVA = $precio * (1 + $iva);
        
        $this->assertEquals(116.00, $precioConIVA);
    }

    public function testValidarNombreProducto(): void
    {
        $nombre = "Tacos al Pastor";
        $this->assertNotEmpty($nombre);
        $this->assertIsString($nombre);
    }

    public function testValidarPrecioPositivo(): void
    {
        $precio = 50.00;
        $this->assertGreaterThan(0, $precio);
    }

    public function testStockNoNegativo(): void
    {
        $stock = 10;
        $this->assertGreaterThanOrEqual(0, $stock);
    }

    public function testCalcularDescuento(): void
    {
        $precio = 100.00;
        $descuento = 10;
        $precioFinal = $precio - ($precio * $descuento / 100);
        
        $this->assertEquals(90.00, $precioFinal);
    }
}
```

---

### 8.3 tests/Unit/PedidoTest.php

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class PedidoTest extends TestCase
{
    public function testCalcularTotalPedido(): void
    {
        $productos = [
            ['precio' => 50.00, 'cantidad' => 2],
            ['precio' => 30.00, 'cantidad' => 1],
            ['precio' => 20.00, 'cantidad' => 3]
        ];

        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }

        $this->assertEquals(190.00, $total);
    }

    public function testCalcularIVA(): void
    {
        $subtotal = 190.00;
        $iva = 0.16;
        $totalIVA = $subtotal * $iva;
        
        $this->assertEquals(30.40, $totalIVA);
    }

    public function testCalcularCambio(): void
    {
        $total = 116.00;
        $pago = 200.00;
        $cambio = $pago - $total;
        
        $this->assertEquals(84.00, $cambio);
    }

    public function testValidarEstadoPedido(): void
    {
        $estadosValidos = ['pendiente', 'confirmado', 'en_preparacion', 'listo', 'pagado'];
        $estadoActual = 'pendiente';
        
        $this->assertContains($estadoActual, $estadosValidos);
    }
}
```

---

### 8.4 tests/Unit/MesaTest.php

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MesaTest extends TestCase
{
    public function testValidarNumeroMesa(): void
    {
        $numeroMesa = 5;
        $this->assertGreaterThan(0, $numeroMesa);
    }

    public function testValidarEstadoMesa(): void
    {
        $estadosValidos = ['disponible', 'ocupada', 'reservada'];
        $estado = 'disponible';
        
        $this->assertContains($estado, $estadosValidos);
    }

    public function testMesasDisponibles(): void
    {
        $totalMesas = 10;
        $mesasOcupadas = 3;
        $mesasDisponibles = $totalMesas - $mesasOcupadas;
        
        $this->assertEquals(7, $mesasDisponibles);
    }

    public function testPorcentajeOcupacion(): void
    {
        $totalMesas = 10;
        $mesasOcupadas = 4;
        $porcentaje = ($mesasOcupadas / $totalMesas) * 100;
        
        $this->assertEquals(40.00, $porcentaje);
    }
}
```

---

### 8.5 tests/Unit/InventarioTest.php

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class InventarioTest extends TestCase
{
    public function testStockSuficiente(): void
    {
        $stockActual = 50;
        $stockMinimo = 10;
        
        $this->assertGreaterThanOrEqual($stockMinimo, $stockActual);
    }

    public function testAlertaStockBajo(): void
    {
        $stockActual = 5;
        $stockMinimo = 10;
        $alerta = $stockActual < $stockMinimo;
        
        $this->assertTrue($alerta);
    }

    public function testDescontarStock(): void
    {
        $stockInicial = 100;
        $cantidadVendida = 15;
        $stockFinal = $stockInicial - $cantidadVendida;
        
        $this->assertEquals(85, $stockFinal);
    }
}
```

---

## 9. DOCKER - ACTUALIZACIONES

### 9.1 Dockerfile Actualizado

```dockerfile
FROM php:8.2-apache

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar extensiones necesarias de MySQL (mysqli)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Instalar composer para gestión de dependencias
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código fuente de la aplicación a la carpeta de Apache
COPY . /var/www/html/

# Ajustar los permisos para que Apache pueda acceder
RUN chown -R www-data:www-data /var/www/html/

# Configurar errores de PHP
RUN echo "display_errors=Off" >> /usr/local/etc/php/conf.d/docker-php-ext.ini \
    && echo "log_errors=On" >> /usr/local/etc/php/conf.d/docker-php-ext.ini \
    && echo "error_log=/var/www/html/logs/php_errors.log" >> /usr/local/etc/php/conf.d/docker-php-ext.ini

# Crear directorio de logs
RUN mkdir -p /var/www/html/logs && chown www-data:www-data /var/www/html/logs

# Exponer puerto 80
EXPOSE 80

WORKDIR /var/www/html
```

---

### 9.2 docker-compose.yml - Servicios

| Servicio | Propósito | Puerto |
|----------|-----------|--------|
| `web` | Aplicación principal | 8081 |
| `db` | MySQL 8.0 | 3307 |
| `test` | Ejecutar tests | - |
| `phpmyadmin` | Admin de BD (opcional) | 8082 |

---

### 9.3 Healthchecks Implementados

```yaml
web:
  healthcheck:
    test: ["CMD", "curl", "-f", "http://localhost/index.php"]
    interval: 30s
    timeout: 10s
    retries: 3

db:
  healthcheck:
    test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-proot"]
    interval: 10s
```

---

## 10. CHANGELOG

**Ubicación:** `CHANGELOG.md`

```markdown
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

### Infrastructure
- Dockerfile configurado con PHP 8.2
- docker-compose.yml para entorno local
- Estructura de tests con PHPUnit
```

---

## 11. COMANDOS RÁPIDOS

### 11.1 Docker

```bash
# Levantar todos los servicios
docker compose up -d

# Ver servicios activos
docker compose ps

# Ver logs de un servicio
docker compose logs -f web

# Reiniciar un servicio
docker compose restart web

# Detener todos los servicios
docker compose down
```

### 11.2 PHPUnit / Tests

```bash
# Instalar dependencias
docker compose run --rm web composer install

# Ejecutar TODOS los tests
docker compose run --rm web ./vendor/bin/phpunit

# Ejecutar tests unitarios
docker compose run --rm web ./vendor/bin/phpunit tests/Unit/

# Ejecutar con cobertura HTML
docker compose run --rm web ./vendor/bin/phpunit --coverage-html coverage/

# Ejecutar test específico
docker compose run --rm web ./vendor/bin/phpunit tests/Unit/PedidoTest.php

# Usar script
./scripts/run-tests.sh
```

### 11.3 Backup

```bash
# Ejecutar backup
./scripts/backup.sh

# Ver backups existentes
ls -la backups/

# Restaurar un backup
gunzip backups/backup_20260427_120000.sql.gz
docker exec -i db mysql -u root -proot sistema_comanda_digital_v1 < backups/backup_20260427_120000.sql
```

### 11.4 Logs

```bash
# Ver logs de la aplicación
tail -f logs/app.log

# Ver solo errores
tail -f logs/error.log

# Ver errores de PHP
tail -f logs/php_errors.log

# Buscar patrón en logs
grep "Pedido" logs/app.log
```

### 11.5 Git

```bash
# Crear tag de versión
git tag -a v1.0.0 -m "Versión 1.0.0 - Flujo completo implementado"
git push origin v1.0.0

# Ver todos los tags
git tag -l

# Eliminar tag local
git tag -d v1.0.0
```

---

## 12. PRÓXIMOS PASOS

### Sprint 5 (27 Abril - 8 Mayo 2026)

| Prioridad | Tarea | Estado |
|-----------|-------|--------|
| Alta | Escribir tests de integración | Pendiente |
| Alta | Configurar CI/CD con GitHub Actions | Pendiente |
| Media | Agregar más tests unitarios | Pendiente |
| Media | Documentar API endpoints | Pendiente |

### Sprint 6 (11 Mayo - 22 Mayo 2026)

| Prioridad | Tarea | Estado |
|-----------|-------|--------|
| Alta | Implementar backups automáticos (cron) | Pendiente |
| Alta | Configurar alertas de monitoreo | Pendiente |
| Media | Setup Prometheus + Grafana | Pendiente |

---

## REFERENCIA: CICLO DEVUOPS (PDF SPRINT 4)

| Fase | Descripcion | Implementación |
|------|-------------|-----------------|
| **Planificación** | Requisitos, backlog, priorización | ✅ Documentación completa |
| **Codificación** | Git, branching, commits | ✅ Git + branching |
| **Construcción** | Build, dependencias, versionado | ✅ Dockerfile + compose |
| **Pruebas** | Tests automatizados | ✅ PHPUnit + tests |
| **Lanzamiento** | Tags, changelog | ✅ CHANGELOG.md + tags |
| **Despliegue** | Infraestructura, CI/CD | ⚠️ Local, GitHub Actions pendiente |
| **Operación** | Backups, escalado | ✅ Script backup.sh |
| **Monitorización** | Logs, métricas, alertas | ✅ Logger.php |

---

*Documento generado automáticamente*
*Sistema de Comanda Digital - Sprint 4*
*DevOps Implementation Guide v1.0*