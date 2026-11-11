# ==========================================
# Stage 1: Composer Dependencies
# ==========================================
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs \
    --no-scripts

# ==========================================
# Stage 2: Frontend Build (Node.js & Vite)
# ==========================================
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

# Copy Ziggy assets for vite.config.ts alias
COPY --from=composer /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy

COPY resources ./resources
COPY public ./public
COPY vite.config.ts tsconfig.json components.json eslint.config.js ./

RUN npm run build

# ==========================================
# Stage 3: PHP 8.3 FPM + Nginx Application
# ==========================================
FROM php:8.3-fpm-bookworm

WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    sqlite3 \
    libsqlite3-dev \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_sqlite \
        gd \
        zip \
        bcmath \
        pcntl \
        intl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer CLI
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Remove default Nginx site configuration
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Normalize line endings and set execution permissions
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Copy application files
COPY . .

# Copy composer dependencies
COPY --from=composer /app/vendor ./vendor

# Copy compiled frontend assets
COPY --from=frontend /app/public/build ./public/build

# Re-dump autoloader
RUN composer dump-autoload --optimize --no-scripts

# Create necessary directories and set proper permissions
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    database \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=15s --retries=3 \
    CMD curl -f http://127.0.0.1/up || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
