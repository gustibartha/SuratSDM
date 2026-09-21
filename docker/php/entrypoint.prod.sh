#!/bin/sh
set -e
cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/testing storage/framework/views storage/logs
chmod -R 775 storage bootstrap/cache

# Config cache must be built here (not at image build time) since the real
# DB/API secrets only exist as env vars once Fly injects them at runtime.
# route:cache is skipped: routes/web.php has a couple of closure-based routes,
# which Laravel cannot serialize — route:cache would fail on those and (with
# set -e) crash-loop the container.
php artisan config:cache

echo ">> Menjalankan Laravel di http://0.0.0.0:${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
