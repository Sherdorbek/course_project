FROM dunglas/frankenphp:1-php8.4-alpine

RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    git \
    unzip \
    && install-php-extensions \
    pdo_pgsql \
    intl \
    zip \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APP_ENV=prod \
    APP_DEBUG=0 \
    CADDY_GLOBAL_OPTIONS="auto_https off" \
    FRANKENPHP_CONFIG="worker ./public/index.php"

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --no-dev --classmap-authoritative \
    && php bin/console importmap:install \
    && php bin/console asset-map:compile \
    && php bin/console cache:clear

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80 443

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
