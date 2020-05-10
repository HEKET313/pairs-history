#!/usr/bin/env bash

composer install

tail -f /var/log/php.log > /proc/1/fd/1 &

php-fpm -F
