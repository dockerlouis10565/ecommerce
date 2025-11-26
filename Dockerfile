FROM composer:2 AS php-deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

FROM node:20 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY public/frontend ./frontend
RUN npm run build

FROM php:8.5-apache AS runtime
WORKDIR /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy PHP dependencies
COPY --from=php-deps /app/vendor ./vendor

# Copy frontend build (adjust path if needed)
COPY --from=frontend /app/dist ./frontend/dist

# Copy backend source
COPY public/backend ./backend
COPY public/index.php ./index.php

EXPOSE 80
CMD ["apache2-foreground"]
