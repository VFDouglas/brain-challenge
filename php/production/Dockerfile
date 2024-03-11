FROM php:fpm
RUN docker-php-ext-install opcache
COPY php/production/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get install -y git libjpeg62-turbo-dev libpng-dev libfreetype6-dev libxslt-dev libzip-dev libldap2-dev bash && apt-get clean
RUN apt-get install -y nodejs npm && apt-get clean
RUN docker-php-ext-install intl ldap zip mysqli pdo pdo_mysql gd ftp
RUN mkdir -p /var/www/html
RUN chown -R www-data:www-data . && chmod -R 775 storage/*
COPY . /var/www/html
