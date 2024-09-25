# Use the base image of PHP-FPM with Alpine
FROM php:8.3-fpm-alpine

# Set the working directory
WORKDIR /var/www

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    jpeg-dev \
    bash \
    postgresql-dev \
    autoconf \
    build-base

# Install PHP extensions required for Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd intl zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy the Laravel application to the container
COPY . /var/www/html

# Change permissions for storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Set PHP memory limit
ARG PHP_MEMORY_LIMIT=512M
RUN echo "memory_limit = ${PHP_MEMORY_LIMIT}" > /usr/local/etc/php/conf.d/memory-limit.ini

# Expose port 9000 (default for php-fpm)
EXPOSE 9000

# Default command to start php-fpm
CMD ["php-fpm"]
