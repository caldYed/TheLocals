FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Disable ALL MPMs first, then enable ONLY prefork
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2dismod mpm_prefork || true \
    && a2enmod mpm_prefork

# Enable rewrite
RUN a2enmod rewrite

# Copy files
COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]