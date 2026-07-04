FROM php:8.2-apache

# 1. Install system dependencies dan PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install Node.js 20 (LTS) untuk Vite build
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Enable Apache rewrite module
RUN a2enmod rewrite

# 4. Configure Apache DocumentRoot ke Laravel's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set working directory
WORKDIR /var/www/html

# 7. Copy seluruh source code
COPY . .

# 8. Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Install Node dependencies dan build Vite assets untuk production
RUN npm install && npm run build

# 10. Set proper permissions untuk Laravel storage dan bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Expose port 80
EXPOSE 80

# 12. Start Apache server
CMD ["apache2-foreground"]
