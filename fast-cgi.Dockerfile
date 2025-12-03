ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-fpm-alpine

# Install the mysqli extension
RUN docker-php-ext-install mysqli

# Use the default production configuration
ARG PHP_ENV=production # can be "development"
RUN mv "$PHP_INI_DIR/php.ini-"${PHP_ENV} "$PHP_INI_DIR/php.ini"

# Setup mail server connection
ARG MAIL_HOST=mail
RUN sed -i "s|^;sendmail_path =|sendmail_path = \"busybox sendmail -S ${MAIL_HOST}:25 -t -i\"|" "$PHP_INI_DIR/php.ini"

# Copy the source files
COPY src /var/www/html
WORKDIR /var/www/html

# Start the server
CMD ["php-fpm"]
