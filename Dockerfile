## ---------- Base PHP image ----------
FROM php:8.2-fpm AS php-base

# Install system dependencies and PHP extensions
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libpng-dev \
    sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite gd zip \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www

## ---------- Composer deps ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

## ---------- Frontend build (Vite) ----------
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci || npm i
COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./
# Build assets to public/build (Laravel Vite default)
RUN npm run build

## ---------- Final runtime image ----------
FROM php-base AS runtime

# Copy application (only necessary files)
COPY --chown=www-data:www-data . /var/www

# Copy Composer vendor and built assets from previous stages
COPY --from=vendor /app/vendor /var/www/vendor
COPY --from=frontend /app/public/build /var/www/public/build

# Provide composer binary in runtime for installs inside container when bind-mounting
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Ensure storage/bootstrap are writable
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
 && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

# Copy entrypoint and use it to boot the app
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
