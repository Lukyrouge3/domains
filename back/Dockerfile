# backend/Dockerfile
FROM composer:2 AS vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs

FROM php:8.3-fpm-alpine AS prod

RUN apk add --no-cache \
    sqlite \
    sqlite-dev \
    pkgconfig \
    && docker-php-ext-install pdo pdo_sqlite

WORKDIR /var/www

COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN composer dump-autoload --optimize --no-dev 2>/dev/null || true

RUN mkdir -p database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache database

USER www-data

EXPOSE 9000

COPY entrypoint.sh /entrypoint.sh
CMD ["/entrypoint.sh"]