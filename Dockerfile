# ============================ 🧱 Stage 1: Frontend Builder ============================
FROM node:22-alpine AS frontend

WORKDIR /var/www/html/frontend

# Copy package files first for caching
COPY package*.json ./

# Install dependencies
RUN npm ci --only=production

# Copy frontend source
COPY ./public/frontend/ ./

# Build static assets (React/Vue/Angular)
RUN npm run build

# ============================ 🧱 Stage 2: Backend Builder ============================
FROM php:8.4.2-fpm AS backend

WORKDIR /var/www/html/backend

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    libcurl4-openssl-dev libssl-dev libpq-dev libicu-dev \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install pdo pdo_mysql zip intl mbstring gd opcache \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer safely
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy composer files first (for caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copy backend source code
COPY ./public/backend/ ./



# ============================ 🧱 Stage 3: Final Runtime ============================
FROM php:8.4.14-apache AS runtime

WORKDIR /var/www/html

# Copy built frontend assets into Apache public dir
COPY --from=frontend /var/www/html/frontend/dist ./public/

# Copy backend (including vendor) into Apache public dir
COPY --from=backend /var/www/html/backend ./public/backend

# Copy index.php explicitly into public root
COPY ./public/index.php ./public/index.php

# Copy compiled PHP extensions from backend stage
COPY --from=backend /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=backend /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Enable Apache modules
RUN a2enmod rewrite headers

# Harden Apache
RUN echo "ServerTokens Prod\nServerSignature Off" >> /etc/apache2/conf-enabled/security.conf \
 && echo 'Header always unset X-Powered-By' >> /etc/apache2/conf-enabled/security.conf \
 && echo 'Header set X-Frame-Options "DENY"\nHeader set X-Content-Type-Options "nosniff"\nHeader set Referrer-Policy "no-referrer"' >> /etc/apache2/conf-enabled/security.conf

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

CMD ["apache2-foreground"]
