#!/bin/bash
set -e

echo "==> Initializing Laravel application..."

# Create .env if missing
if [ ! -f /var/www/html/.env ]; then
    echo "==> Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Sync APP_URL from environment if provided
if [ -n "$APP_URL" ]; then
    sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|g" /var/www/html/.env
fi

# Ensure APP_KEY exists
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# SQLite database setup if SQLite is used
DB_CONNECTION=$(grep -E "^DB_CONNECTION=" /var/www/html/.env | cut -d '=' -f2 | tr -d ' "\r\n' || echo "sqlite")
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p /var/www/html/database
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        echo "==> Creating database.sqlite..."
        touch /var/www/html/database/database.sqlite
    fi
    chown -R www-data:www-data /var/www/html/database
    chmod 775 /var/www/html/database
    chmod 664 /var/www/html/database/database.sqlite
fi

# Storage symlink
echo "==> Ensuring storage link..."
php artisan storage:link --no-interaction || true

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force --no-interaction || true

# Run seed if database is new and empty
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "==> Seeding initial test data..."
    php artisan db:seed --force --no-interaction || true
fi

# Clear any cached configs so runtime env takes effect
php artisan optimize:clear --no-interaction || true

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Laravel application initialized successfully."

exec "$@"
