ARG PHP_DOCKER_VERSION
FROM php:${PHP_DOCKER_VERSION}-cli

RUN apt update

RUN apt -yqq install unzip libsqlite3-dev

RUN curl -o /usr/bin/composer https://getcomposer.org/composer-1.phar && chmod +x /usr/bin/composer

RUN docker-php-ext-install bcmath mbstring pdo_sqlite > /dev/null

COPY bin/install-ffi.sh install-ffi.sh

RUN chmod +x install-ffi.sh && ./install-ffi.sh
