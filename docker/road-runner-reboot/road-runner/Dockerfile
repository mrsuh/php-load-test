FROM golang:1.11 as build

RUN apt-get update && apt-get install -y git
RUN git clone https://github.com/spiral/roadrunner.git /roadrunner
RUN cd /roadrunner && make

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
        sockets \
    && docker-php-ext-enable \
        intl \
        opcache \
        pcntl \
        iconv \
        mbstring \
        zip \
        bcmath \
        sockets

RUN usermod -u 1000 www-data

COPY --from=build /roadrunner/rr /roadrunner/rr
COPY ./php.ini /usr/local/etc/php/php.ini
COPY ./.rr.yaml /roadrunner

EXPOSE 9000

CMD ["/roadrunner/rr", "serve"]


