FROM serversideup/php:8.4-cli-alpine AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist

FROM node:24-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./

RUN npm ci

COPY vite.config.ts tsconfig.json ./
COPY resources ./resources
COPY public ./public

# Build frontend assets
RUN npm run build

FROM serversideup/php:8.4-fpm-nginx-alpine AS final

USER root

# Install php extensions: https://serversideup.net/open-source/docker-php/docs/customizing-the-image/installing-additional-php-extensions
RUN install-php-extensions intl gd

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor

COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build

# Set permissions for runtime folders
RUN chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

USER www-data

ARG APP_COMMITSHA=local
ARG APP_REFNAME=local
ARG APP_RELEASETAG=
ARG APP_REPOSITORYURL=
ENV APP_COMMITSHA=$APP_COMMITSHA \
    APP_REFNAME=$APP_REFNAME \
    APP_RELEASETAG=$APP_RELEASETAG \
    APP_REPOSITORYURL=$APP_REPOSITORYURL
