#!/bin/bash
set -e

# Ensure the .env file exists
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env file from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate application key if not already set
if ! grep -q "^APP_KEY=" /var/www/html/.env || grep -q "^APP_KEY=$" /var/www/html/.env; then
    echo "Generating application key..."
    php /var/www/html/artisan key:generate --force
fi

# Apply database migrations if environment variable is set
if [ "${AUTORUN_LARAVEL_MIGRATION}" = "true" ]; then
    echo "Running migrations..."
    php /var/www/html/artisan migrate --force
fi

# Start the queue worker in the background
if [ "${ENABLE_QUEUE_WORKER}" = "true" ]; then
    echo "Starting queue worker..."
    php /var/www/html/artisan queue:work --tries=3 &
fi

# Start the Node.js geojson helper server in the background
if [ "${ENABLE_GEOJSON_HELPER}" = "true" ]; then
    echo "Starting geojson helper server..."
    cd /var/www/html/node-api/geojson-helper && node server.js &
fi

# Execute the original entrypoint (the init script from serversideup/php)
exec /init