FROM php:5.6-fpm
ENV PHPREDIS_VERSION 2.2.8


RUN mkdir -p /home/seacontact
WORKDIR /home/seacontact

ADD https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz /tmp/redis.tar.gz
RUN tar xzf /tmp/redis.tar.gz -C /tmp \
    && mkdir -p /usr/src/php/ext \
    && mv /tmp/phpredis-$PHPREDIS_VERSION /usr/src/php/ext/redis \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis \
    && rm -rf /usr/src/php/ext/redis \
    && apt-get update \
    && apt-get install -y  libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install mysqli pdo pdo_mysql

COPY ./php.ini /usr/local/etc/php/php.ini
COPY . /home/seacontact
