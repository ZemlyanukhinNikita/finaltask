FROM php:7.1-fpm

COPY composer.json /var/www/

RUN apt-get update && apt-get install -y libmcrypt-dev mysql-client \
&& docker-php-ext-install mcrypt pdo_mysql

RUN apt-get install -y git

RUN apt-get install zip unzip php7.1-zip

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www
