FROM php:5.6-fpm-alpine
MAINTAINER mdouchement

RUN apk upgrade
RUN apk add --update --no-cache postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql

COPY . /var/www/html
VOLUME ["/var/www/html"]

COPY dockerfiles/nginx.conf /etc/nginx/conf.d/default.conf
VOLUME ["/etc/nginx/conf.d"]
