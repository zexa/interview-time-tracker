FROM php:7.4.14-fpm-alpine

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN apk update \
    && apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo_pgsql

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions http

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    # replace 172.17.0.1 with host.docker.internal if running on windows or mac
    && echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apk add --no-cache zlib-dev libpng-dev \
    && docker-php-ext-install gd

RUN apk add libzip-dev \
    && docker-php-ext-install zip

RUN rm -rf /var/www/html

RUN mkdir /reports \
    && chown -R www-data:www-data /reports

WORKDIR /var/www
