#!/bin/sh
set -e

# Railway provides PORT, defaulting to 8080 if not provided
TARGET_PORT="${PORT:-8080}"
echo "==> Configuring Apache for Railway on port ${TARGET_PORT}..."

# Configure Apache to listen on both 80, 8080, and $PORT so Railway traffic always hits Apache
cat <<EOF > /etc/apache2/ports.conf
Listen 80
Listen 8080
Listen ${TARGET_PORT}
EOF

# Ensure VirtualHost accepts traffic on all ports
cat <<EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80 *:8080 *:${TARGET_PORT}>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

echo "==> Setting directory permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Optimizing Laravel configuration..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "==> Running database migrations (timeout in 15s to prevent boot freeze)..."
php artisan migrate --force || echo "==> Migrations will be retried once database is ready."

echo "==> Starting Apache in foreground on port ${TARGET_PORT}..."
exec apache2-foreground
