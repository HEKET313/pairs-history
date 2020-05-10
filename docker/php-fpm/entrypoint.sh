#!/usr/bin/env bash

composer install
php bin/console doctrine:migrations:migrate --no-interaction

tail -f /var/log/php.log > /proc/1/fd/1 &

php-fpm -F
