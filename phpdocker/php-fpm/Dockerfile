FROM php:8.2-fpm
WORKDIR "/ec0607"

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y \
       unzip \
       zip \
       git \
       libzip-dev \
       libicu-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install intl

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

