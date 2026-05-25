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