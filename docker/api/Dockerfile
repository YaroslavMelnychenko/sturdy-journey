FROM php:8.1.0-fpm AS base

# Install system packages
RUN apt-get update -y && apt-get install -y \
    zip unzip zlib1g-dev jpegoptim optipng \
    pngquant gifsicle webp libzip-dev git \
    libicu-dev libpng-dev libjpeg-dev libfreetype-dev 

# Install php extensions
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install zip

# Intall GD for image processing
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install imagick
RUN apt-get update \
    && apt-get install -y libmagickwand-dev ghostscript --no-install-recommends 

RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Install intl for currencies
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Prepare for local dev work
FROM base AS local

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

USER www

# Containerize application for production usage
FROM local AS production

USER root

COPY ./ /var/www/html/

RUN cp .env.example .env

RUN chown www:www ./bootstrap/cache -R && chmod 775 ./bootstrap/cache -R
RUN chown www:www ./storage -R && chmod 775 ./storage -R

RUN composer i

USER www