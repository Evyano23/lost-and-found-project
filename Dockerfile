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

# Ensure Apache listens on port 80 and enable rewrite module
RUN echo "Listen 80" >> /etc/apache2/ports.conf
RUN a2enmod rewrite

EXPOSE 80

# Fix permissions on every container boot and start Apache
CMD chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && php artisan config:clear && apache2-foreground
