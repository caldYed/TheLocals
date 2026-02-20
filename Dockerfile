FROM php:8.2-apache

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Copy your application files to the container
COPY . /var/www/html/