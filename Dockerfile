FROM php:8.4-apache

# Install system dependencies and required PHP extensions
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files over
COPY . .

# Run composer install with --no-scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Configure Apache document root for Laravel's public folder
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache rewrite module
RUN a2enmod rewrite

EXPOSE 80

# Create a start script to fix permissions and launch Apache cleanly
RUN echo '#!/bin/sh' > /start.sh && \
    echo 'chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache' >> /start.sh && \
    echo 'chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache' >> /start.sh && \
    echo 'php artisan config:clear' >> /start.sh && \
    echo 'exec apache2-foreground' >> /start.sh && \
    chmod +x /start.sh

CMD ["/start.sh"]
