FROM php:8.4-fpm-alpine

WORKDIR /var/www

RUN set -eux; \
    apk add --no-cache \
    bash \
    curl \
    curl-dev \
    icu-dev \
    libpq \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    unzip \
    zip

RUN docker-php-ext-install pdo pdo_pgsql bcmath intl zip mbstring curl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

# Remove local/dev framework caches so production container boots with fresh metadata.
RUN rm -f bootstrap/cache/*.php

RUN if [ -f docker/php-entrypoint.sh ]; then chmod +x docker/php-entrypoint.sh; fi \
    && mkdir -p storage bootstrap/cache public database \
    && chown -R www-data:www-data storage bootstrap/cache public database

USER www-data

EXPOSE 8000

ENTRYPOINT ["/var/www/docker/php-entrypoint.sh"]