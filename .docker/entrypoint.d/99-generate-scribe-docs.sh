#!/bin/sh

# Check if the artisan file exists
if [ ! -f "$APP_BASE_DIR/artisan" ]; then
    echo "❌ Artisan file not found in $APP_BASE_DIR"
    exit 1
fi

# Run scribe docs generation https://scribe.knuckles.wtf/laravel/tasks/generating
echo "📂 Generating swagger documentation..."
php "$APP_BASE_DIR/artisan" scribe:generate
exit 0