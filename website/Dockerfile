# Use PHP with Alpine as the base image
FROM php:8.3-fpm-alpine as build

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    zip \
    libzip-dev \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql soap \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd

# Install composer
COPY --from=composer:2.7.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy necessary files and change permissions
COPY laravel-app/. .

RUN ls -la .
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Install PHP and Node.js dependencies
RUN composer install --no-dev --prefer-dist \
    && npm install \
    && npm install vue daisyui --save \
    && npm run build

# If you're using Tailwind CSS, add DaisyUI as a plugin
RUN echo 'module.exports = { plugins: [require("daisyui")], }' > tailwind.config.js

# Set permissions for the vendor directory
RUN chown -R www-data:www-data /var/www/html/vendor \
    && chmod -R 775 /var/www/html/vendor

CMD ["php", "artisan", "serve", "--host=0.0.0.0"]