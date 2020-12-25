#!/bin/bash
if [ $(php -r "echo version_compare(PHP_VERSION, '7.4', '>=');") -eq 1 ]
then
    apt -yqq install libffi-dev;
    docker-php-ext-install ffi;
fi