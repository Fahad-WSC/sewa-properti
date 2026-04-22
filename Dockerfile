FROM php:8.2-apache

# 1. Instal ekstensi mysqli untuk database
RUN docker-php-ext-install mysqli

# 2. Mengubah konfigurasi port Apache agar membaca environment variable dari Railway
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 3. Copy semua file proyek ke dalam web server
COPY . /var/www/html/

# 4. Berikan hak akses
RUN chown -R www-data:www-data /var/www/html