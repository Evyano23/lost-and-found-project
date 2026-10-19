#!/bin/sh
set -e

# Railway provides PORT (typically 8080)
TARGET_PORT="${PORT:-8080}"
echo "==> Configuring Apache for Railway on port ${TARGET_PORT}..."

# Ensure only mpm_prefork is loaded to prevent AH00534 error
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.* 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Configure Apache to listen ONLY on TARGET_PORT (avoids duplicate listeners)
cat <<EOF > /etc/apache2/ports.conf
Listen ${TARGET_PORT}
EOF

# Ensure VirtualHost accepts traffic on TARGET_PORT
cat <<EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:${TARGET_PORT}>
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

echo "==> Running database migrations..."
php artisan migrate --force || echo "==> Migrations will be retried once database is ready."

echo "==> Starting Apache in foreground on port ${TARGET_PORT}..."
exec apache2-foreground
