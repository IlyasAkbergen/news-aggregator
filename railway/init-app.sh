#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x railway/init-app.sh`

# Exit the script if any command fails
set -e

# Run migrations
php artisan migrate --force

# Clear cache
php artisan optimize:clear

php artisan storage:link --force || true

php artisan l5-swagger:generate

# Cache the various components of the Laravel application
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
