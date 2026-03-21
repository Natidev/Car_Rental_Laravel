FROM php:8.4-fpm-alpine

# Path prefix inside the Docker build context. Use default "car-office" when the context is the
# repo root (Render, `docker build -f car-office/Dockerfile .`). Use "."
# when the context is the app folder (`docker-compose` context ./car-office, or:
# docker build --build-arg APP_ROOT=. -f Dockerfile .).
ARG APP_ROOT=car-office

WORKDIR /var/www

RUN set -eux; \
    for i in 1 2 3; do \
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
    zip && break; \
    echo "apk install failed (attempt $i), retrying..."; \
    sleep 5; \
    done

RUN docker-php-ext-install pdo pdo_pgsql bcmath intl zip mbstring curl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY ${APP_ROOT}/composer.json ${APP_ROOT}/composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY ${APP_ROOT}/ .

# Remove local/dev framework caches so production container boots with fresh metadata.
RUN rm -f bootstrap/cache/*.php

RUN chmod +x docker/php-entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache public

USER www-data

EXPOSE 9000
EXPOSE 8000
ENTRYPOINT ["/var/www/docker/php-entrypoint.sh"]