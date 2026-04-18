#!/usr/bin/env sh
set -e

cd /var/www/html

if [ -f artisan ]; then
  php artisan migrate --force || true
  php artisan config:clear || true
  php artisan route:clear || true
  php artisan view:clear || true
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
fi

exec apache2-foreground
