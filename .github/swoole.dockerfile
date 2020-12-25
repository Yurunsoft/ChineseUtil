ARG SWOOLE_DOCKER_VERSION
FROM phpswoole/swoole:${SWOOLE_DOCKER_VERSION}

RUN apt update

RUN apt -yqq install unzip libsqlite3-dev

RUN docker-php-ext-install bcmath mbstring pdo_sqlite > /dev/null

COPY bin/install-ffi.sh install-ffi.sh

RUN chmod +x install-ffi.sh && ./install-ffi.sh
