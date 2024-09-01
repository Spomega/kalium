# Use the official PHP image with Apache
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install necessary PHP extensions and tools
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Symfony project files to the container
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/var

# Expose port 80
EXPOSE 80

# Set the entry point for the container
CMD ["apache2-foreground"]