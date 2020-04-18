# Development build
FROM php:7.4.3-fpm-alpine as base_php

ENV WORKPATH "/var/www/snowtricks"

## Installe toutes les dépendances nécessaires, voir si elles le sont toutes
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev postgresql-dev gnupg autoconf git zlib-dev curl go \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl pdo_pgsql opcache json pgsql

## Récupère le fichier php.ini que j'ai dans le dossier pour le mettre dans le container
COPY docker/php/conf/php.ini /usr/local/etc/php/php.ini

# xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Composer
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p ${WORKPATH} \
    && mkdir -p \
       ${WORKPATH}/var/cache \
       ${WORKPATH}/var/logs \
       ${WORKPATH}/var/sessions \
    && chown -R www-data /tmp/ \
    && chown -R www-data ${WORKPATH}/var

WORKDIR ${WORKPATH}

COPY --chown=www-data:www-data . ./