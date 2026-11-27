
# ==========================================
# Stage 1: Build frontend assets using Node
# ==========================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


# ==========================================
# Stage 2: Laravel application using PHP
# ==========================================
FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd intl zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Configure Apache to serve Laravel's public directory
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf \
    && printf '\n<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy Laravel application
COPY . .

# Install production dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Copy compiled frontend assets
COPY --from=frontend /app/public/build ./public/build

# Prepare Laravel directories
# Fix the Windows storage symlink for Linux Docker
RUN rm -f public/storage \
    && ln -s ../storage/app/public public/storage
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Laravel production environment
ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 80

CMD ["apache2-foreground"]
