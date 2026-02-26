#!/bin/bash
# entrypoint.sh

# Fix Apache MPM
a2dismod mpm_event
a2dismod mpm_worker
a2enmod mpm_prefork

# Set Apache to listen on Railway PORT
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost *:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Start Apache in foreground
exec apache2-foreground