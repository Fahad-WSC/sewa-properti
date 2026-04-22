FROM php:8.2-apache

# Install mysqli
RUN docker-php-ext-install mysqli

# FIX MPM conflict
RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2enmod mpm_prefork

# Set port dari Railway
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Copy project
COPY . /var/www/html/

# Permission
RUN chown -R www-data:www-data /var/www/html