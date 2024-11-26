ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-fpm-alpine

# Use the default production configuration
ARG PHP_ENV=production # can be "development"
RUN mv "$PHP_INI_DIR/php.ini-"${PHP_ENV} "$PHP_INI_DIR/php.ini"

# Install the mysqli extension
RUN apk add --no-cache libxml2-dev && \
    docker-php-ext-install mysqli

# Copy the source files
COPY src /var/www/html
WORKDIR /var/www/html

# Set up the database connection and hCaptcha secret
RUN echo "<?php \
    define('DB_HOST', getenv('DB_HOST')); \
    define('DB_USER', getenv('DB_USER')); \
    define('DB_PASS', getenv('DB_PASS')); \
    define('DB_NAME', getenv('DB_NAME')); \
    define('HCAPTCHA_SECRET', getenv('HCAPTCHA_SECRET')); \
    define('HCAPTCHA_SITEKEY', getenv('HCAPTCHA_SITEKEY')); \
?>" > /var/www/html/api/credentials.php

# Start the server
CMD ["php-fpm"]