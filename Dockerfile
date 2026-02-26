FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Fix MPM conflict
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork

RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY . /var/www/html/

CMD ["apache2-foreground"]