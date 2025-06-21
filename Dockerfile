FROM php:8.2-apache

# Install required packages
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev \
    libpng-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working dir
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP deps
RUN composer install --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Apache config to point to /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80

CMD ["apache2-foreground"]
