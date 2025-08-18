#!/usr/bin/env sh
set -e

cd /var/www

# Ensure correct permissions
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwx storage bootstrap/cache || true

# Install composer deps if vendor is missing or empty
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor 2>/dev/null)" ]; then
  echo "[entrypoint] Installing composer dependencies..."
  composer install --no-dev --prefer-dist --no-interaction --no-progress
fi

# Copy .env if not present (use .env.example as baseline)
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
  php artisan key:generate --force || true
fi

# Storage link
php artisan storage:link || true

# Optimize caches (don't fail the container if artisan fails)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"
