FROM php:8.2-apache

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Configure Apache to listen on the Railway port
RUN echo "Listen 0.0.0.0:${PORT}" > /etc/apache2/ports.conf

# Copy your application files
COPY . /var/www/html/