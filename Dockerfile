FROM php:8.2-apache

# Install PHP extensions in a single layer
RUN docker-php-ext-install pdo pdo_mysql && \
    a2enmod rewrite

# Copy only essential files
COPY api-gateway /var/www/html/api-gateway
COPY auth-service /var/www/html/auth-service
COPY inventory-service /var/www/html/inventory-service
COPY warehouse-service /var/www/html/warehouse-service
COPY frontend /var/www/html/frontend
COPY index.php /var/www/html/index.php
COPY api.php /var/www/html/api.php

# Set permissions and configure Apache in single layer
RUN chown -R www-data:www-data /var/www/html && \
    echo "DocumentRoot /var/www/html" > /etc/apache2/sites-available/000-default.conf && \
    echo "<Directory /var/www/html>" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf && \
    echo "    DirectoryIndex index.php index.html" >> /etc/apache2/sites-available/000-default.conf && \
    echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

EXPOSE 80
