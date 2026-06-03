FROM php:8.3-fpm

WORKDIR /app

# Install sistem dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev \
    libxml2-dev libcurl4-openssl-dev libicu-dev \
    && docker-php-ext-install zip pdo pdo_mysql mbstring gd bcmath intl curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy semua project
COPY . .

# Install PHP dependencies di folder core
RUN cd core && composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Permission Laravel
RUN chmod -R 775 core/storage core/bootstrap/cache

EXPOSE 8000

CMD php -S 0.0.0.0:$PORT index.php
