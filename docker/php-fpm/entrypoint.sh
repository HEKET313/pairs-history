#!/usr/bin/env bash

composer install
php bin/console doctrine:migrations:migrate --no-interaction

supercronic /etc/cron.d/import-job > /proc/1/fd/1 &
php /var/www/pairs/bin/console pairs:import > /proc/1/fd/1 &
tail -f /var/log/php.log > /proc/1/fd/1 &

php-fpm -F
