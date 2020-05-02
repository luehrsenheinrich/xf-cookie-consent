FROM php:7.4-apache

RUN apt-get update && apt-get install -y libmcrypt-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev libzip-dev \
    default-mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install pdo_mysql mysqli gd \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install zip

VOLUME /var/www/html

CMD ["apache2-foreground"]
