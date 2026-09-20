FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite

# Copy all services to web root
COPY api-gateway /var/www/html/api-gateway
COPY auth-service /var/www/html/auth-service
COPY inventory-service /var/www/html/inventory-service
COPY warehouse-service /var/www/html/warehouse-service
COPY frontend /var/www/html

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Configure Apache
RUN echo "DocumentRoot /var/www/html" > /etc/apache2/sites-available/000-default.conf
RUN echo "<Directory /var/www/html>" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    DirectoryIndex index.php index.html" >> /etc/apache2/sites-available/000-default.conf
RUN echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

EXPOSE 80
