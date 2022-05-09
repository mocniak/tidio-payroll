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

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /payroll

USER www-data:www-data
RUN docker-compose exec payroll-php bash -c "bin/console doctrine:schema:create --env=dev" \
    && docker-compose exec payroll-php bash -c "bin/console doctrine:schema:update --env=dev --force" \
    && bin/console doctrine:database:create --env=test \
    && bin/console doctrine:schema:create --env=test \
    && bin/console doctrine:schema:update --env=test --force \
    && bin/console app:import-example-data
