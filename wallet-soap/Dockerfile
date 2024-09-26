# Usar una imagen base de PHP con FPM y Alpine para un entorno ligero
FROM php:8.3-fpm-alpine

# Instalar dependencias necesarias
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
    build-base \
    libxml2-dev

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd intl zip soap

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el directorio de trabajo
WORKDIR /var/www

# Copiar el código fuente de Laravel
COPY . /var/www

# Instalar las dependencias de Composer
#RUN composer install --no-dev --optimize-autoloader

# Asignar los permisos necesarios
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# Set PHP memory limit
ARG PHP_MEMORY_LIMIT=512M
RUN echo "memory_limit = ${PHP_MEMORY_LIMIT}" > /usr/local/etc/php/conf.d/memory-limit.ini

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Default command to start php-fpm
CMD ["php-fpm"]
