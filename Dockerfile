FROM php:8.2-apache

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Debug and fix MPM configuration
RUN set -ex \
    && echo "=== BEFORE: Checking all MPM modules ===" \
    && ls -la /etc/apache2/mods-enabled/mpm* || true \
    && ls -la /etc/apache2/mods-available/mpm* || true \
    \
    && echo "=== Disabling all MPMs ===" \
    && a2dismod -f mpm_event || true \
    && a2dismod -f mpm_worker || true \
    && a2dismod -f mpm_prefork || true \
    \
    && echo "=== Removing symlinks ===" \
    && rm -vf /etc/apache2/mods-enabled/mpm*.load \
    && rm -vf /etc/apache2/mods-enabled/mpm*.conf \
    \
    && echo "=== Enabling prefork ===" \
    && a2enmod mpm_prefork \
    && a2enmod rewrite \
    \
    && echo "=== AFTER: Checking enabled MPMs ===" \
    && ls -la /etc/apache2/mods-enabled/mpm* \
    \
    && echo "=== Testing Apache config ===" \
    && apache2ctl -t

COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]