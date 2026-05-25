#!/bin/bash

# ==============================================================================
# Script de Backup para Sistema de Comanda Digital
# ==============================================================================

set -e

# Configuración
DB_NAME="${DB_NAME:-comanda_db}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASSWORD:-password}"
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