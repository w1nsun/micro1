FROM php:7.4.19-fpm-alpine

RUN apk add --update --no-cache \
    $PHPIZE_DEPS \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    curl \
    && rm -rf /var/cache/apk/*

RUN docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-install pcntl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install opcache \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install sockets \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-source delete \
    && rm -rf /tmp/* /var/tmp/* /var/cache/*

RUN ln -s /usr/local/bin/php /usr/bin/php
COPY ./conf/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./conf/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./conf/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

#install DEV dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
