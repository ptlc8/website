ARG PHP_VERSION=8
FROM php:${PHP_VERSION}-fpm-alpine

# Use the default production configuration
ARG PHP_ENV=production # can be "development"
RUN mv "$PHP_INI_DIR/php.ini-"${PHP_ENV} "$PHP_INI_DIR/php.ini"

# Install the mysqli extension
RUN docker-php-ext-install mysqli

# Install the socat package
RUN apk add --no-cache socat

# Copy the source files
COPY src /var/www/html
WORKDIR /var/www/html

# Create entrypoint that connects mail socket
RUN echo -e "#!/bin/sh \n\
    socat TCP4-LISTEN:25,fork UNIX-CONNECT:/run/mail.sock & \n\
    exec \"\$@\"" > /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

# Start the server
CMD ["php-fpm"]
