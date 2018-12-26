FROM php:7.2-cli

RUN apt-get update && apt-get install -y \  
        libmcrypt-dev \
        zlib1g-dev \
        libicu-dev \
    && docker-php-ext-install \
        intl \
        opcache \
        pcntl \
        iconv \
        mbstring \
        zip \
        bcmath \
    && docker-php-ext-enable \
        intl \
        opcache \
        pcntl \
        iconv \
        mbstring \
        zip \
        bcmath

RUN usermod -u 1000 www-data

COPY ./php.ini /usr/local/etc/php/php.ini