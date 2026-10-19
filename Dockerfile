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

# Run composer install with --no-scripts to completely bypass package:discover crash
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Set correct storage permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configure Apache document root for Laravel's public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conff
RUN a2enmod rewrite

RUN sed -i 's/80/80/g' /etc/apache2/ports.conf
EXPOSE 80

# Start Apache and run post-deploy commands safely when the container boots up
CMD php artisan config:clear && php artisan config:cache && php artisan route:cache && php artisan migrate --force && apache2-foreground
