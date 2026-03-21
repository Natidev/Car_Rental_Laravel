#!/usr/bin/env sh
set -eu

# Render (and most PaaS) only stream stdout/stderr to the log UI — not storage/logs/laravel.log.
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

echo "[entrypoint] Starting container bootstrap..."

if [ -z "${APP_KEY:-}" ]; then
	echo "[entrypoint] ERROR: APP_KEY is not set. Add it in Render Environment (run: php artisan key:generate --show locally, paste the base64:... value)." >&2
	exit 1
fi

# Ensure DB schema exists before session/cache drivers that use the database (SESSION_DRIVER=database, etc.).
if php artisan migrate --force; then
	echo "[entrypoint] Migrations complete."
else
	echo "[entrypoint] Warning: migrate failed (check DATABASE_URL and network). Web requests may fail if sessions/cache use the database."
fi

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
