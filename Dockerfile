FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod php8.2

# Configure Apache PHP handler
RUN echo "LoadModule php_module /usr/lib/apache2/modules/libphp.so" > /etc/apache2/mods-available/php.load
RUN echo "AddHandler application/x-httpd-php .php" > /etc/apache2/mods-available/php.conf

# Copy all services to web root
COPY api-gateway /var/www/html/api-gateway
COPY auth-service /var/www/html/auth-service
COPY inventory-service /var/www/html/inventory-service
COPY warehouse-service /var/www/html/warehouse-service
COPY frontend /var/www/html/frontend

# Create main entry point that routes to services
COPY index.php /var/www/html/index.php

# Copy .htaccess for routing
COPY .htaccess /var/www/html/.htaccess

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Configure Apache to serve from the main entry point
RUN echo "DocumentRoot /var/www/html" > /etc/apache2/sites-available/000-default.conf
RUN echo "<Directory /var/www/html>" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    AllowOverride All" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    Require all granted" >> /etc/apache2/sites-available/000-default.conf
RUN echo "    DirectoryIndex index.php index.html" >> /etc/apache2/sites-available/000-default.conf
RUN echo "</Directory>" >> /etc/apache2/sites-available/000-default.conf

EXPOSE 80
