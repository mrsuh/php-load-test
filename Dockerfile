FROM php:7.2-cli as build

RUN apt-get update && apt-get upgrade -y \
    unzip \
    libmcrypt-dev \
    zlib1g-dev \
    && docker-php-ext-install \
    iconv \
    mbstring \
    zip \
    bcmath

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

COPY . /app
ENV APP_ENV prod
RUN sh /app/bin/build.sh

FROM alpine

COPY --from=build /app /app
RUN chmod -R 777 /app/var