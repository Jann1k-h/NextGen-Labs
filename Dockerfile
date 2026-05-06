FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

# Dadurch wird die public-Ordnerstruktur in den Container eingebunden, damit Apache direkt darauf zugreifen kann
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Apache-Konfiguration anpassen, damit der DocumentRoot auf den public-Ordner zeigt
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf