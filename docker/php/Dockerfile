FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libicu-dev libonig-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip intl opcache

RUN pecl install pcov && docker-php-ext-enable pcov

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html