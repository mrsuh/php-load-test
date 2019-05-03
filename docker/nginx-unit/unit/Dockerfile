FROM centos:7

RUN yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm

RUN yum -y update
RUN yum -y install net-tools

RUN yum -y install unit php-unit php72-unit-php
RUN yum -y install php72 \
php72-php-cli \
php72-php-xml \
php72-php-intl \
php72-php-iconv \
php72-php-zip \
php72-php-bcmath \
php72-php-mbstring \
php72-php-common \
php72-php-opcache

COPY ./conf.json /var/lib/unit/
COPY ./php.ini /usr/local/etc/php/php.ini

RUN mkdir /run/unit
RUN touch /run/unit/unit.pid

CMD ["/usr/sbin/unitd", "--no-daemon", "--control", "0.0.0.0:8080", "--log", "/var/log/unit/unit.log", "--state", "/var/lib/unit"]