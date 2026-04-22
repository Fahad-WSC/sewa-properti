FROM php:8.2-apache

# Instal ekstensi mysqli
RUN docker-php-ext-install mysqli

# Cara yang lebih aman untuk menangani konflik MPM di image PHP-Apache
RUN sed -i 's/LoadModule mpm_event_module/#LoadModule mpm_event_module/' /etc/apache2/mods-enabled/mpm_event.conf \
    && sed -i 's/#LoadModule mpm_prefork_module/LoadModule mpm_prefork_module/' /etc/apache2/mods-enabled/mpm_prefork.conf

# Copy file ke lokasi web server
COPY . /var/www/html/

# Pastikan folder bisa diakses
RUN chown -R www-data:www-data /var/www/html