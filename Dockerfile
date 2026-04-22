FROM php:8.2-apache

# Instal ekstensi mysqli
RUN docker-php-ext-install mysqli

# Copy file ke web server
COPY . /var/www/html/

# Set permission
RUN chown -R www-data:www-data /var/www/html