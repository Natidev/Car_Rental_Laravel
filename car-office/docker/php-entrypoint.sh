#!/usr/bin/env sh
set -eu

echo "[entrypoint] Starting container bootstrap..."
if [ ! -L public/storage ]; then
	echo "[entrypoint] Ensuring storage symlink exists..."
	php artisan storage:link || true
fi

echo "[entrypoint] Caching framework artifacts..."
php artisan config:cache
php artisan event:cache || true
php artisan view:cache

# Route caching can fail when closure routes are present.
if php artisan route:cache; then
	echo "[entrypoint] Route cache built."
else
	echo "[entrypoint] Route cache skipped (closure routes detected)."
	php artisan route:clear
fi

if [ -n "${PORT:-}" ]; then
	echo "[entrypoint] Render/HTTP mode detected. Starting Laravel server on 0.0.0.0:${PORT}."
	exec php artisan serve --host=0.0.0.0 --port="${PORT}"
fi

echo "[entrypoint] Bootstrap complete. Starting php-fpm."
exec php-fpm
