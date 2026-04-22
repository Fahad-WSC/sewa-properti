FROM php:8.2-apache

# Instal ekstensi mysqli
RUN docker-php-ext-install mysqli

# Memastikan hanya satu MPM yang aktif
# Kita matikan dulu semuanya, lalu paksa aktifkan prefork
RUN a2dismod mpm_event mpm_worker mpm_itk && a2enmod mpm_prefork

# Copy file ke lokasi web server
COPY . /var/www/html/

# Pastikan folder bisa diakses
RUN chown -R www-data:www-data /var/www/html