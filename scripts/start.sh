#!/usr/bin/env sh
set -eu

cd /var/www/html

if [ -z "${APP_KEY:-}" ]; then
  echo "APP_KEY is required in environment variables"
  exit 1
fi

# Ensure framework caches and package manifest are generated with prod env.
php artisan package:discover --ansi
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan storage:link || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
