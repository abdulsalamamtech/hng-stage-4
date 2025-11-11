# Dockerfile - Development image for Laravel (PHP-FPM)
# Used for local development with SQLite database

FROM php:8.2-fpm-alpine

# System deps for typical Laravel apps (adjust as needed)
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    zlib-dev \
    oniguruma-dev \
    tzdata \
    sqlite \
    sqlite-dev

# PHP extensions required by Laravel & common packages
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_sqlite gd zip intl opcache

# Install Node.js for asset building
RUN apk add --no-cache nodejs npm

# Create app directory
ENV APP_HOME=/var/www
WORKDIR $APP_HOME

# Copy application code
COPY --chown=www-data:www-data . $APP_HOME

# Install PHP dependencies
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-interaction

# Install frontend dependencies and build assets
RUN npm install \
 && npm run build

# Set runtime permissions (storage & bootstrap/cache)
RUN chown -R www-data:www-data $APP_HOME/storage $APP_HOME/bootstrap/cache \
 && chmod -R 775 $APP_HOME/storage $APP_HOME/bootstrap/cache

# Environment defaults (override at runtime)
ENV APP_ENV=local \
    APP_DEBUG=true \
    LOG_CHANNEL=stderr \
    PHP_FPM_CLEAR_ENV=0

# Run as www-data
USER www-data

# Expose php-fpm port
EXPOSE 9000

# Use the built-in php-fpm process
CMD ["php-fpm"]
