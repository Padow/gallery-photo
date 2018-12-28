FROM php:5.6-fpm-alpine
MAINTAINER mdouchement

RUN apk update \
    && apk upgrade \
    && apk add --no-cache \
        postgresql-dev \
        freetype \
        libpng \
        libjpeg-turbo \
        freetype-dev \
        libpng-dev \
        jpeg-dev \
        libjpeg \
        libjpeg-turbo-dev \
        postgresql-dev

# Install the PHP GS & PDO libraries
RUN docker-php-ext-configure gd \
  --enable-gd-native-ttf \
  --with-png-dir=/usr/include/ \
  --with-jpeg-dir=/usr/include/ \
  --with-jgif-dir=/usr/include/ \
  --with-freetype-dir=/usr/include/ && \
  docker-php-ext-install gd pdo pdo_pgsql

COPY . /var/www/html
VOLUME ["/var/www/html"]

COPY dockerfiles/nginx.conf /etc/nginx/conf.d/default.conf
VOLUME ["/etc/nginx/conf.d"]

# Override of https://github.com/docker-library/php/blob/master/5.6/alpine3.8/fpm/docker-php-entrypoint
COPY dockerfiles/docker-entrypoint.sh /usr/local/bin/docker-php-entrypoint
