#!/usr/bin/env sh
set -eu

echo "[entrypoint] Starting container bootstrap..."

# Wait for PostgreSQL before running migrations.
if [ "${DB_CONNECTION:-pgsql}" = "pgsql" ]; then
	db_url="${DB_URL:-${DATABASE_URL:-}}"
	if [ -n "$db_url" ]; then
		echo "[entrypoint] Waiting for PostgreSQL using DB URL..."
	else
		echo "[entrypoint] Waiting for PostgreSQL at ${DB_HOST:-pgsql}:${DB_PORT:-5432}..."
	fi
	tries=0
	until php -r '
		$dbUrl = getenv("DB_URL") ?: getenv("DATABASE_URL") ?: "";
		if ($dbUrl !== "") {
			$parts = parse_url($dbUrl);
			if ($parts === false) {
				exit(1);
			}
			$host = $parts["host"] ?? "127.0.0.1";
			$port = (string)($parts["port"] ?? "5432");
			$db = isset($parts["path"]) ? ltrim($parts["path"], "/") : "postgres";
			$user = $parts["user"] ?? "postgres";
			$pass = $parts["pass"] ?? "";
			$query = [];
			if (isset($parts["query"])) {
				parse_str($parts["query"], $query);
			}
			$sslmode = $query["sslmode"] ?? (getenv("DB_SSLMODE") ?: "prefer");
			$dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode={$sslmode}";
		} else {
			$host = getenv("DB_HOST") ?: "pgsql";
			$port = getenv("DB_PORT") ?: "5432";
			$db = getenv("DB_DATABASE") ?: "postgres";
			$user = getenv("DB_USERNAME") ?: "postgres";
			$pass = getenv("DB_PASSWORD") ?: "";
			$sslmode = getenv("DB_SSLMODE") ?: "prefer";
			$dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode={$sslmode}";
		}
		try {
			new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 3]);
			exit(0);
		} catch (Throwable $e) {
			exit(1);
		}
	'; do
		tries=$((tries + 1))
		if [ "$tries" -ge 30 ]; then
			echo "[entrypoint] Database is not reachable after 30 attempts. Exiting."
			exit 1
		fi
		sleep 2
	done
fi

echo "[entrypoint] Running migrations..."
php artisan migrate --force --no-interaction

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
