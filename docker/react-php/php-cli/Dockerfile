FROM php:7.2-cli

RUN apt-get update && apt-get install -y \  
        libicu-dev \
    && docker-php-ext-install \
        intl \
        opcache \
    && docker-php-ext-enable \
        intl \
        opcache

RUN usermod -u 1000 www-data

COPY ./php.ini /usr/local/etc/php/php.ini