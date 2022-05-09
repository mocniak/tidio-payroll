FROM php:8.1-fpm

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN apt-get update \
    && apt-get install -y zlib1g-dev libicu-dev libzip-dev zip unzip \
    && docker-php-ext-install intl opcache pdo pdo_mysql bcmath \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && usermod -u $USER_ID www-data --shell /bin/bash \
    && groupmod -g $GROUP_ID www-data \
    && mkdir /payroll && chown -R www-data:www-data /payroll

COPY --chown=www-data:www-data --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /payroll

USER www-data:www-data

COPY --chown=www-data:www-data composer.* ./
RUN composer install \
    --no-scripts \
    --no-interaction
COPY --chown=www-data:www-data . ./
