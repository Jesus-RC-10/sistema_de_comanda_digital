# PLAN DETALLADO: Implementación DevOps para Sistema de Comandas Digitales

---

## FASE 1: PRUEBAS (PHPUnit)

### Paso 1.1 - Crear `composer.json`

```json
{
    "require-dev": {
        "phpunit/phpunit": "^10.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "models/",
            "Controllers\\": "controllers/"
        }
    }
}
```

### Paso 1.2 - Crear estructura de tests

```
tests/
├── Unit/
│   ├── ProductoTest.php        # Probar ProductoModel
│   ├── PedidoTest.php          # Probar PedidoModel
│   └── MesaTest.php            # Probar MesaModel
└── bootstrap.php               # Configuracion de tests
```

### Paso 1.3 - Tests recomendados por modelo

| Modelo | Tests a crear |
|--------|---------------|
| **ProductoModel** | CRUD, busqueda, calculo de precio |
| **PedidoModel** | Crear pedido, agregar productos, calcular total |
| **MesaModel** | Abrir/cerrar mesa, cambiar estado |
| **Inventario** | Actualizar stock, alertas de stock bajo |

### Ejemplo de test basico

```php
// tests/Unit/ProductoTest.php
<?php

use PHPUnit\Framework\TestCase;

class ProductoTest extends TestCase
{
    public function testCalcularPrecioConIVA()
    {
        $producto = new Producto(100.00);
        $this->assertEquals(116.00, $producto->precioConIVA());
    }
}
```

### Configuracion PHPUnit (`phpunit.xml`)

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         colors="true">
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
    </source>
</phpunit>
```

---

## FASE 2: LANZAMIENTO (Release)

### Paso 2.1 - Crear archivo CHANGELOG.md

```markdown
# Changelog

## [1.0.0] - 2026-04-27

### Added
- Sistema de pedidos completo
- Gestion de mesas
- Modulo de cocina
- Modulo de caja

### Fixed
- Bug en calculo de total
- Error al cerrar mesa
```

### Paso 2.2 - Versionado semantico

Formatos de tags:
- `v1.0.0` - Primera version productiva
- `v1.1.0` - Nuevas funcionalidades
- `v1.0.1` - Bug fixes

Comandos:
```bash
git tag -a v1.0.0 -m "Primera version productiva"
git push origin v1.0.0
```

---

## FASE 3: DESPLIEGUE (CI/CD Local)

### Paso 3.1 - Actualizar docker-compose.yml

```yaml
services:
  php:
    build: .
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    ports:
      - "8000:8000"
    command: ["php", "-S", "0.0.0.0:8000"]

  test:
    build: .
    volumes:
      - .:/var/www/html
    command: ["php", "vendor/bin/phpunit"]
```

### Paso 3.2 - Crear script de tests

```bash
#!/bin/bash
# scripts/run-tests.sh
docker compose run --rm php ./vendor/bin/phpunit --colors
```

---

## FASE 4: OPERACION

### Paso 4.1 - Scripts de backup

```bash
#!/bin/bash
# scripts/backup.sh

# Configuracion
DB_NAME="comanda_db"
DB_USER="root"
DB_PASS="${DB_PASSWORD:-password}"
BACKUP_DIR="backups"
DATE=$(date +%Y%m%d_%H%M%S)

# Crear directorio si no existe
mkdir -p $BACKUP_DIR

# Ejecutar backup
docker exec db mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/backup_$DATE.sql

# Comprimir
gzip $BACKUP_DIR/backup_$DATE.sql

# Limpiar backups mayores a 7 dias
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

echo "Backup completado: $BACKUP_DIR/backup_$DATE.sql.gz"
```

### Paso 4.2 - Health check en docker-compose.yml

```yaml
services:
  php:
    # ... config existente
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8000/index.php"]
      interval: 30s
      timeout: 10s
      retries: 3
    depends_on:
      db:
        condition: service_healthy
```

---

## FASE 5: MONITORIZACION (Basica)

### Paso 5.1 - Configurar logs de errores PHP

En `.htaccess` o al inicio de `index.php`:

```php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');
ini_set('display_errors', 0);
```

### Paso 5.2 - Logging centralizado

```php
// helpers/Logger.php

<?php

class Logger
{
    private static $logFile = __DIR__ . '/../logs/app.log';

    public static function log($level, $message, array $context = [])
    {
        $entry = [
            'timestamp' => date('c'),
            'level' => strtoupper($level),
            'message' => $message,
            'context' => $context
        ];

        file_put_contents(
            self::$logFile,
            json_encode($entry) . PHP_EOL,
            FILE_APPEND
        );
    }

    public static function info($message, array $context = [])
    {
        self::log('info', $message, $context);
    }

    public static function error($message, array $context = [])
    {
        self::log('error', $message, $context);
    }

    public static function warning($message, array $context = [])
    {
        self::log('warning', $message, $context);
    }
}
```

### Uso del Logger

```php
// En tus controllers
Logger::info('Pedido creado', ['pedido_id' => $pedidoId, 'usuario' => $userId]);
Logger::error('Error de conexion BD', ['error' => $e->getMessage()]);
```

---

## RESUMEN DE ARCHIVOS A CREAR

```
proyecto/
├── composer.json                    NUEVO
├── phpunit.xml                      NUEVO
├── CHANGELOG.md                     NUEVO
├── scripts/
│   ├── run-tests.sh                 NUEVO
│   └── backup.sh                    NUEVO
├── tests/
│   ├── bootstrap.php                NUEVO
│   ├── Unit/
│   │   ├── ProductoTest.php         NUEVO
│   │   ├── PedidoTest.php           NUEVO
│   │   └── MesaTest.php             NUEVO
│   └── Integration/
│       └── PedidoCompletoTest.php   NUEVO
├── helpers/
│   └── Logger.php                   NUEVO
└── logs/                           ya existe
```

---

## ORDEN RECOMENDADO DE IMPLEMENTACION

```
1. composer.json + PHPUnit
2. Estructura tests/ + bootstrap
3. Primer test basico (ProductoTest)
4. Scripts de backup
5. Logging basico
6. CHANGELOG.md
```

---

## REFERENCIA: CICLO DEVUOPS (Del PDF)

| Fase | Descripcion | Estado Actual |
|------|-------------|---------------|
| **Planificacion** | Requisitos, backlog, priorizacion | Completo |
| **Codificacion** | Git, branching, commits, code review | Completo |
| **Construccion** | Build, dependencias, versionado | Parcial |
| **Pruebas** | Tests automatizados | **FALTA** |
| **Lanzamiento** | Tags, changelog | **FALTA** |
| **Despliegue** | Infraestructura, configuracion | Parcial |
| **Operacion** | Backups, escalado, incidentes | **FALTA** |
| **Monitorizacion** | Logs, metricas, alertas | **FALTA** |

---

## COMANDOS UTILES

```bash
# Instalar dependencias
composer install

# Ejecutar tests
./vendor/bin/phpunit

# Ejecutar tests con cobertura
./vendor/bin/phpunit --coverage-html coverage

# Crear backup manual
./scripts/backup.sh

# Ver logs
tail -f logs/app.log

# Crear tag de version
git tag -a v1.0.0 -m "Primera version"
git push origin v1.0.0
```

---

*Documento generado automaticamente para el proyecto Sistema de Comandas Digitales*
*Basado en el PDF de planification DevOps - Sprint 4*