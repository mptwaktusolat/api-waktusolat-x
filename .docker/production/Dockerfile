FROM serversideup/php:8.3-fpm-nginx

USER root

RUN curl -sL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install -y nodejs

# Copy the existing application directory contents to the working directory
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www

USER www-data

RUN npm ci
RUN npm run build

RUN composer install --no-interaction --optimize-autoloader --no-dev