FROM ubuntu:18.04

RUN apt-get -y update && apt-get -y install software-properties-common

RUN DEBIAN_FRONTEND=noninteractive && apt-get install -y tzdata

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
			unit \
			unit-php

COPY ./conf.json /var/lib/unit/
COPY ./php.ini /usr/local/etc/php/php.ini

CMD ["/usr/sbin/unitd", "--no-daemon", "--control", "0.0.0.0:8080", "--log", "/dev/stdout"]