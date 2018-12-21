FROM ubuntu:16.04

ARG VERSION=1.4-1~xenial

RUN apt-get -y update && apt-get -y install software-properties-common

RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

RUN apt-get -y update && \
    apt-get -y install php7.2 \
        php7.2-cli \
        php7.2-xml \
        php7.2-mbstring \
        php7.2-common \
        php7.2-opcache

COPY ./unit.list /etc/apt/sources.list.d/

ADD http://nginx.org/keys/nginx_signing.key .

RUN apt-key add nginx_signing.key && \
        apt-get -y update && \
        apt-get -y install \
			unit=$VERSION \
			unit-php=$VERSION

COPY ./conf.json /var/lib/unit/
COPY ./php.ini /usr/local/etc/php/php.ini

CMD ["/usr/sbin/unitd", "--no-daemon", "--control", "0.0.0.0:8080", "--log", "/dev/stdout"]