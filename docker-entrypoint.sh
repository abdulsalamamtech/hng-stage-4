#!/bin/sh
set -e

# Entrypoint for production container. Responsible for runtime tasks that should
# happen on container start, not at image build time.
#
# Controls via environment variables:
#  RUN_MIGRATIONS=true            - run php artisan migrate --force
#  RUN_OPTIMIZE_CLEAR=true        - run php artisan optimize:clear
#  RUN_OPTIMIZE=true              - run php artisan optimize
#  GENERATE_APP_KEY=true          - run php artisan key:generate if APP_KEY is empty

APP_HOME=/var/www/html

echo "[entrypoint] ensuring storage & cache permissions..."
chown -R www-data:www-data "$APP_HOME/storage" "$APP_HOME/bootstrap/cache" || true
chmod -R 775 "$APP_HOME/storage" "$APP_HOME/bootstrap/cache" || true

cd "$APP_HOME" || exit 1

if [ "${GENERATE_APP_KEY:-false}" = "true" ]; then
  if [ -z "${APP_KEY:-}" ]; then
    if [ -f .env ]; then
      echo "[entrypoint] Generating APP_KEY..."
      php artisan key:generate --no-interaction || true
    else
      echo "[entrypoint] .env not found; skip key generation"
    fi
  else
    echo "[entrypoint] APP_KEY already set; skipping generation"
  fi
fi

if [ "${RUN_OPTIMIZE_CLEAR:-false}" = "true" ]; then
  echo "[entrypoint] Running artisan optimize:clear"
  php artisan optimize:clear || true
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "[entrypoint] Running migrations"
  php artisan migrate --force || true
fi

if [ "${RUN_OPTIMIZE:-true}" = "true" ]; then
  echo "[entrypoint] Running artisan optimize"
  php artisan optimize || true
fi

echo "[entrypoint] executing command: $@"
exec "$@"
