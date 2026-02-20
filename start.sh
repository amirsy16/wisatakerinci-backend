#!/bin/bash

set -e

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding database..."
php artisan db:seed --force || echo "Seeding skipped or already done."

echo "==> Creating storage symlink..."
php artisan storage:link || true

echo "==> Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
