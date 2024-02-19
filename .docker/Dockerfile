FROM php:7.2-fpm

RUN apt-get update  \
    && apt-get -y install git zip libpq-dev nano bash

RUN apt-get -y install  libxml2-dev

RUN docker-php-ext-install mbstring xml pdo pdo_pgsql

RUN curl 'http://pecl.php.net/get/xdebug-3.1.5.tgz' -o xdebug-3.1.5.tgz \
    && pecl install xdebug-3.1.5.tgz \
    && docker-php-ext-enable xdebug

WORKDIR /var/www/project

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN mv ~/.symfony5/bin/symfony /usr/local/bin/symfony
