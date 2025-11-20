# Use an official Node.js image as the base image
FROM node:22-alpine AS frontend

# Set working directory inside the container
WORKDIR /var/www/html/frontend

# Copy package.json and package-lock.json first for better caching
COPY package*.json ./

# Install dependencies including npm packages
RUN npm install

# Copy the rest of the frontend application
COPY ./public/frontend ./


# Expose port 80
EXPOSE 80
CMD ["npm", "start"]

#============================ # 🧱 Stage 1: Backend Builder # ============================
FROM php:8.4-fpm AS backend

LABEL org.opencontainers.image.title="Ecommerce PHP Backend"
LABEL org.opencontainers.image.version="1.0.0"
LABEL org.opencontainers.image.description="Production-grade PHP backend with Composer"
LABEL org.opencontainers.image.authors="Louis"

# Set working directory
WORKDIR /var/www/html/backend
##
# Copy backend source code
COPY ./public/backend/  ./

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev libssl-dev libpq-dev libicu-dev \
 && docker-php-ext-install pdo pdo_mysql zip intl mbstring gd \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Install PHP dependencies
RUN if [ -f composer.json ]; then \
    composer install --no-dev --optimize-autoloader --no-interaction --no-progress; \
    fi

# Set ownership
RUN chown -R www-data:www-data /var/www

#=== api ======

#============================ # 🧱 Stage 2: Final Runtime # ============================
FROM php:8.4.14-apache AS runtime

WORKDIR /var/www/html

# Copy built frontend from previous stage
COPY --from=frontend /var/www/html/frontend/ ./public/

# Copy backend from previous stage
COPY --from=backend /var/www/html/backend/ ./

RUN docker-php-ext-install pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite headers

# Harden Apache (optional)
RUN echo "ServerTokens Prod\nServerSignature Off" >> /etc/apache2/conf-enabled/security.conf \
 && echo 'Header always unset X-Powered-By' >> /etc/apache2/conf-enabled/security.conf \
 && echo 'Header set X-Frame-Options "DENY"\nHeader set X-Content-Type-Options "nosniff"\nHeader set Referrer-Policy "no-referrer"' >> /etc/apache2/conf-enabled/security.conf
# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
