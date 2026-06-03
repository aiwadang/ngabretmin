FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libonig-dev libxml2-dev \
    libcurl4-openssl-dev libicu-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd bcmath intl curl \
    && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

RUN cd /var/www/html/core && \
    composer install --no-dev --ignore-platform-reqs --no-scripts 2>/dev/null || \
    composer update --no-dev --ignore-platform-reqs --no-scripts

RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html/core/storage \
    /var/www/html/core/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
