ARG PHP_DOCKER_VERSION
FROM php:${PHP_DOCKER_VERSION}-cli

RUN apt update

RUN apt install unzip

RUN curl -o /usr/bin/composer https://getcomposer.org/composer-1.phar && chmod +x /usr/bin/composer
