ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-alpine

# Install the mysqli extension
RUN apk add --no-cache libxml2-dev && \
    docker-php-ext-install mysqli

# Copy the source files
COPY src /var/www/html
WORKDIR /var/www/html

# Set up the database connection and hCaptcha secret
RUN echo "<?php \
    define('DB_HOST', \$_ENV['DB_HOST']); \
    define('DB_USER', \$_ENV['DB_USER']); \
    define('DB_PASS', \$_ENV['DB_PASS']); \
    define('DB_NAME', \$_ENV['DB_NAME']); \
    define('HCAPTCHA_SECRET', \$_ENV['HCAPTCHA_SECRET']); \
    define('HCAPTCHA_SITEKEY', \$_ENV['HCAPTCHA_SITEKEY']); \
?>" > /var/www/html/api/credentials.php

# Start the server
CMD ["php", "-S", "0.0.0.0:80"]