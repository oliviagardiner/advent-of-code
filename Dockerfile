FROM php:8.2-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && mkdir /var/app

WORKDIR /var/app