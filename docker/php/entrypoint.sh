#!/bin/sh
set -e
cd /var/www/html

# storage/framework/* dipasang sebagai named volume (lihat docker-compose.yml),
# jadi volume kosong di awal butuh subfolder ini dibuat ulang tiap container baru.
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views

# Install dependency PHP kalau folder vendor belum ada (hanya jalan sekali, agak lama)
if [ ! -d vendor ] || [ -z "$(ls -A vendor 2>/dev/null)" ]; then
  echo ">> vendor/ belum ada, menjalankan composer install..."
  composer install --no-interaction --prefer-dist
fi

echo ">> Menjalankan Laravel di http://0.0.0.0:8000"
php artisan serve --host=0.0.0.0 --port=8000
