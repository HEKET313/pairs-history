FROM php:7.4-fpm

ENV DEBIAN_FRONTEND=noninteractive

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt-get update && apt-get install -y apt-utils gnupg apt-transport-https zip

RUN echo "deb http://ru.archive.ubuntu.com/ubuntu/ bionic main restricted" >> /etc/apt/sources.list && \
    echo "deb http://ru.archive.ubuntu.com/ubuntu/ bionic-updates main restricted" >> /etc/apt/sources.list && \
    echo "deb http://ru.archive.ubuntu.com/ubuntu/ bionic universe" >> /etc/apt/sources.list && \
    echo "deb http://ru.archive.ubuntu.com/ubuntu/ bionic-updates universe" >> /etc/apt/sources.list && \
    echo "deb http://ru.archive.ubuntu.com/ubuntu/ bionic multiverse" >> /etc/apt/sources.list && \
    echo "deb http://ru.archive.ubuntu.com/ubuntu/ bionic-updates multiverse" >> /etc/apt/sources.list

RUN apt-key adv --recv-keys --no-tty --keyserver keyserver.ubuntu.com 40976EAF437D05B5 && \
    apt-key adv --recv-keys --no-tty --keyserver keyserver.ubuntu.com 3B4FE6ACC0B21F32

RUN apt-get upgrade -y && apt-get install -y make wget software-properties-common

RUN apt-get --assume-yes install git zlib1g-dev

# PostgreSQL drivers
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# ZIP
RUN apt-get update -y && \
    apt-get install -y libwebp-dev libjpeg62-turbo-dev libxpm-dev libfreetype6-dev libzip-dev && \
    docker-php-ext-install zip

RUN apt-get update \
  && apt-get install unzip libpq-dev -y

# composer
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O composer-setup.php
RUN php composer-setup.php --version=1.10.5 --install-dir=/usr/bin
RUN php -r "unlink('composer-setup.php');"
RUN mv /usr/bin/composer.phar /usr/bin/composer
RUN chmod +x /usr/bin/composer

#RUN apt-get install -y cron
#COPY import-job /etc/cron.d/import-job
#RUN chmod 0644 /etc/cron.d/import-job
#RUN crontab /etc/cron.d/import-job
#RUN touch /var/log/cron.log
#RUN cron

ENV SUPERCRONIC_URL=https://github.com/aptible/supercronic/releases/download/v0.1.9/supercronic-linux-amd64 \
    SUPERCRONIC=supercronic-linux-amd64 \
    SUPERCRONIC_SHA1SUM=5ddf8ea26b56d4a7ff6faecdd8966610d5cb9d85

RUN curl -fsSLO "$SUPERCRONIC_URL" \
 && echo "${SUPERCRONIC_SHA1SUM}  ${SUPERCRONIC}" | sha1sum -c - \
 && chmod +x "$SUPERCRONIC" \
 && mv "$SUPERCRONIC" "/usr/local/bin/${SUPERCRONIC}" \
 && ln -s "/usr/local/bin/${SUPERCRONIC}" /usr/local/bin/supercronic

COPY import-job /etc/cron.d/import-job
RUN chmod 0644 /etc/cron.d/import-job
#RUN supercronic /etc/cron.d/import-job

# clear trash
RUN docker-php-source delete && \
    rm -rf /var/cache/apt/* /var/tmp/* /tmp/*

RUN chmod 0777 /var/log

CMD php-fpm
EXPOSE 9000

# XDebug
RUN pecl install xdebug-2.9.0 && docker-php-ext-enable xdebug

ADD symfony.ini /etc/php/7.4/php-fpm.d/
ADD symfony.ini /etc/php/7.4/cli/conf.d/

ADD symfony.pool.conf /etc/php/7.4/php-fpm.d/

RUN echo "xdebug.remote_enable=1"        >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_autostart=0"     >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back=0"  >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_host=172.17.0.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN mkdir /home/www-data && chmod 0777 /home/www-data && usermod -u 1000 --home=/home/www-data www-data
RUN touch /var/log/php.log && chmod 0777 -R /var/log
USER www-data

VOLUME /var/www/pairs
WORKDIR /var/www/pairs
