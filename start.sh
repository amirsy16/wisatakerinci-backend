#!/bin/bash

set -e

echo "==> Creating storage symlink..."
php artisan storage:link || true

echo "==> Starting server..."
php artisan serve --host=0.0.0.0 --port=$PORT
