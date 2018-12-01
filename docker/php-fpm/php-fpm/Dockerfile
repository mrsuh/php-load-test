FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \  
        libicu-dev \
    && docker-php-ext-install \
        intl \
        opcache \
    && docker-php-ext-enable \
        intl \
        opcache

RUN usermod -u 1000 www-data

COPY ./php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./php.ini /usr/local/etc/php/php.ini