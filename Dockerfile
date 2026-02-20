FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (common for PHP apps)
RUN a2enmod rewrite

# Copy project files into the container
COPY . /var/www/html/

# Apache already listens correctly inside Railway
# No need to modify ports.conf

# Railway sets PORT; Apache expects port 80 inside container
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]