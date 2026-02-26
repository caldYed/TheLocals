FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Fix Apache port for Railway
RUN sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
RUN sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Fix MPM conflict
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork

RUN a2enmod rewrite

COPY . /var/www/html/

CMD ["apache2-foreground"]