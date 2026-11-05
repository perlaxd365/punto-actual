#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
echo "== Punto Actual: instalación =="
command -v php >/dev/null || { echo "PHP no está instalado"; exit 1; }
command -v composer >/dev/null || { echo "Composer no está instalado"; exit 1; }
composer install --no-dev --optimize-autoloader
[ -f .env ] || cp .env.example .env
php artisan key:generate --force
echo "Configura las credenciales DB_* en .env y vuelve a ejecutar:"
echo "php artisan migrate --seed --force"
echo "npm install && npm run build"
echo "php artisan storage:link && php artisan optimize"
