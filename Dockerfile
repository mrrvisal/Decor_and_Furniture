# syntax=docker/dockerfile:1

FROM php:8.2-apache

# Install required PHP extensions for common PDO/MySQL usage
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    libzip-dev \
  && docker-php-ext-install pdo_mysql \
  && rm -rf /var/lib/apt/lists/*

# Enable mod_rewrite (used by public/.htaccess in Apache environments)
RUN a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer manifests first for better layer caching
COPY composer.json composer.lock ./

# Install dependencies (no-dev) and optimize autoloader
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Copy the rest of the app
COPY . .

# Configure Apache to serve from /public
# (DocumentRoot is Apache config, not a Dockerfile instruction)
RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
  && sed -i 's#/var/www/html/index.html#/var/www/html/public/index.php#' /etc/apache2/sites-available/000-default.conf

# Rewrite/Front controller handling

# - public/.htaccess routes requests to public/index.php
# - Ensure AllowOverride so htaccess is honored
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf \
  && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

EXPOSE 80

# Keep apache in foreground
CMD ["apache2-foreground"]

