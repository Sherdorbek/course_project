#!/bin/sh
set -e

if [ -n "$DATABASE_URL" ]; then
    php bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing || true
fi

exec "$@"
