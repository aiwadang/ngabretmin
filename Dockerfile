FROM php:8.3-apache

# Install extensions
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath intl curl

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable mod_rewrite
RUN a2enmod rewrite

# Set document root to /var/www/html (repo root)
ENV APACHE_DOCUMENT_ROOT /var/www/html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy project files
COPY . /var/www/html/

# Install composer dependencies
WORKDIR /var/www/html/core
RUN composer install --no-dev --optimize-autoloader

WORKDIR /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/core/storage /var/www/html/core/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
