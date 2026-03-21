FROM php:8.4-fpm-alpine

WORKDIR /var/www

RUN set -eux; \
    for i in 1 2 3; do \
    apk add --no-cache \
    bash \
    curl \
    icu-dev \
    libpq \
    libzip-dev \
    oniguruma-dev \
    postgresql-client \
    postgresql-dev \
    unzip \
    zip && break; \
    echo "apk install failed (attempt $i), retrying..."; \
    sleep 5; \
    done

RUN docker-php-ext-install pdo pdo_pgsql bcmath intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

USER www-data

EXPOSE 8000
