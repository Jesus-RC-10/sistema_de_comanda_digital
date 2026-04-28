# Carpeta DevOps - Implementación Sprint 4

## Contenido

| Archivo | Descripción |
|---------|-------------|
| `DEVOPS_IMPLEMENTACION_COMPLETA.md` | Documento principal con toda la implementación |

## Archivos del proyecto relacionados

Estos archivos están en la raíz del proyecto:

```
├── composer.json                    # PHPUnit dependencies
├── phpunit.xml                      # Test configuration
├── CHANGELOG.md                     # Version history
├── Dockerfile                        # Docker image
├── docker-compose.yml               # Docker services
│
├── helpers/Logger.php               # Logging system
├── scripts/
│   ├── backup.sh                    # Database backup
│   └── run-tests.sh                 # Run tests
├── tests/
│   ├── bootstrap.php
│   └── Unit/
│       ├── ProductoTest.php
│       ├── PedidoTest.php
│       ├── MesaTest.php
│       ├── InventarioTest.php
│       └── LoggerTest.php
└── logs/                             # Application logs
```

## Comandos rápidos

```bash
# Instalar PHPUnit
docker compose run --rm web composer install

# Ejecutar tests
docker compose run --rm web ./vendor/bin/phpunit

# Crear backup
./scripts/backup.sh

# Ver logs
tail -f logs/app.log
```

## Estado DevOps

| Fase | Estado |
|------|--------|
| Pruebas | ✅ Implementado |
| Lanzamiento | ✅ Implementado |
| Operación | ✅ Implementado |
| Monitorización | ✅ Implementado |
| Despliegue CI/CD | ⏳ Pendiente (Sprint 5) |

---

*Última actualización: 27 Abril 2026*