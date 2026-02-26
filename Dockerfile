FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# First, disable all MPMs completely
RUN a2dismod -f mpm_event mpm_worker mpm_prefork || true

# Clean any remaining MPM configs
RUN rm -f /etc/apache2/mods-enabled/mpm*.load || true \
    && rm -f /etc/apache2/mods-enabled/mpm*.conf || true

# Now enable only prefork
RUN a2enmod mpm_prefork

# Enable rewrite
RUN a2enmod rewrite

# Verify only prefork is enabled
RUN ls -la /etc/apache2/mods-enabled/ | grep mpm

# Copy files
COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]