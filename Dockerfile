FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev \
    libpng-dev sqlite3 libsqlite3-dev \
    gnupg2 ca-certificates lsb-release \
    && docker-php-ext-install pdo pdo_sqlite zip gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js 18 (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache database \
 && chmod -R 775 storage bootstrap/cache database

# Fix Apache root
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80

# Create the SQLite file if it doesn't exist
RUN mkdir -p database && touch database/database.sqlite && chmod -R 775 database

# Laravel entrypoint
ENTRYPOINT ["sh", "-c", "php artisan storage:link && php artisan config:cache && php artisan migrate --force && apache2-foreground"]

