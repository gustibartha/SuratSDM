# Production image for InsForge compute (Fly.io). Unlike docker/php/Dockerfile
# (local dev: bind-mounts the repo, runs composer install at container start),
# this bakes the app code and vendor/ into the image itself.
FROM php:7.4-cli

RUN set -eux; \
    sed -i \
        -e 's|http://deb.debian.org/debian|http://archive.debian.org/debian|g' \
        -e '/bullseye-security/d' \
        -e '/bullseye-updates/d' \
        /etc/apt/sources.list; \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until; \
    rm -rf /var/lib/apt/lists/*; \
    apt-get -o Acquire::Retries=5 update; \
    apt-get -o Acquire::Retries=5 install -y --no-install-recommends \
        git unzip ca-certificates gnupg curl \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libonig-dev libzip-dev; \
    # bullseye's own libpq-dev (13.16) hits "SSL connection has been closed
    # unexpectedly" against Postgres providers that front the DB with a
    # modern connection proxy (a known libpq <14 handshake bug) — pull a
    # current libpq from PGDG instead of the archived Debian one.
    install -d /usr/share/postgresql-common/pgdg; \
    curl -o /usr/share/postgresql-common/pgdg/apt.postgresql.org.asc --fail https://www.postgresql.org/media/keys/ACCC4CF8.asc; \
    echo "deb [signed-by=/usr/share/postgresql-common/pgdg/apt.postgresql.org.asc] https://apt.postgresql.org/pub/repos/apt bullseye-pgdg main" > /etc/apt/sources.list.d/pgdg.list; \
    apt-get -o Acquire::Retries=5 update; \
    apt-get -o Acquire::Retries=5 install -y --no-install-recommends libpq-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install pdo_mysql pdo_pgsql mbstring gd zip bcmath exif opcache; \
    rm -rf /var/lib/apt/lists/*

COPY docker/php/opcache.prod.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# composer.json's autoload classmap points at database/seeds and
# database/factories, so composer install needs the full tree present
# (not just composer.json/lock) to generate the classmap successfully.
COPY . .
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader --no-scripts \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/php/entrypoint.prod.sh /usr/local/bin/entrypoint.prod.sh
RUN chmod +x /usr/local/bin/entrypoint.prod.sh

EXPOSE 8080
ENTRYPOINT ["entrypoint.prod.sh"]
