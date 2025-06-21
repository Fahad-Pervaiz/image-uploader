FROM php:8.2-apache

# Install system packages
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev \
    libpng-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Laravel storage/cache permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Change Apache root to Laravel's public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

# Laravel startup: config cache, migrate, then start apache
ENTRYPOINT ["sh", "-c", "php artisan config:cache && php artisan migrate --force && apache2-foreground"]
