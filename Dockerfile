FROM serversideup/php:8.2-fpm-nginx

# Set working directory
WORKDIR /var/www/html

# Fix apt permissions and install Node.js using nodesource
USER root
RUN mkdir -p /var/lib/apt/lists/partial && \
    apt-get update && \
    apt-get install -y curl && \
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copy composer.json and composer.lock files
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy the rest of the application
COPY . .

# Copy .env.example to .env if it doesn't exist
COPY .env.example .env

# Set proper permissions using the www-data user (standard for Nginx)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm install
RUN npm run build

# Generate API documentation
RUN php artisan scribe:generate

# Final optimizations for production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Set the proper environment variables for the serversideup/php image
ENV SSL_MODE=off \
    AUTORUN_LARAVEL_MIGRATION=false \
    AUTORUN_LARAVEL_STORAGE_LINK=true \
    PHP_POOL_NAME=www\
    PHP_MAX_EXECUTION_TIME=30 \
    PHP_MEMORY_LIMIT=128M \
    PHP_DISPLAY_ERRORS=Off \
    NGINX_ROOT=/var/www/html/public

# Expose port 80
EXPOSE 80

# Add custom startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Keep as root - the init script will handle running processes with appropriate users
# USER www-data - this was causing nginx to fail

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]