# Laravel 5.5 butuh PHP 7.x. Kalau ada dependency yang rewel, ganti 7.4 -> 7.3 atau 7.2.
FROM php:7.4-cli

# Debian bullseye sudah EOL: deb.debian.org tidak lagi menyimpan paketnya (404).
# archive.debian.org tidak punya suite "bullseye-security" terpisah, jadi buang baris
# security & updates dan hanya pakai arsip "bullseye/main" (cukup untuk build image ini).
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
        git unzip \
        libpng-dev libjpeg-dev libfreetype6-dev \
        libonig-dev libzip-dev libpq-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install pdo_mysql pdo_pgsql mbstring gd zip bcmath exif opcache; \
    rm -rf /var/lib/apt/lists/*

COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html
EXPOSE 8000
ENTRYPOINT ["entrypoint.sh"]
