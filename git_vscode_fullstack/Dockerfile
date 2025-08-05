# Base image with PHP and Apache
FROM php:8.2-apache



#adding the required metadata
LABEL developer="Louis Mensah"
# ✅ Set working directory inside the container



ONBUILD COPY . /var/www/html
#Automatically copies the child project’s files into the web root.
#ONBUILD RUN composer install --no-dev --optimize-autoloader
#Runs Composer in the child image to install PHP dependencies.
ONBUILD ENV APP_ENV=production
#Ensures consistent environment settings across downstream builds.
ONBUILD EXPOSE 80
#Makes sure the child container exposes the correct port for web traffic.
ONBUILD WORKDIR /var/www/html
#Ensures all subsequent commands in the child image run from the correct path.
#ONBUILD can't be with the following commands:     ONBUILD ONBUILD, ONBUILD FROM, ONBUILD STOPSIGNAL, ONBUILD HEALTHCHECK, ONBUILD ENTRYPOINT





# ✅ Optional: Set environment variables
ENV APP_ENV=production
ENV DB_HOST=db 
#for docker DB_HOST=db, for local development without docker DB_HOST=localhost or 127.0.0.1 , for remote server use the remote server's ip or domain, for ci/cd testing use localhost or a test container alias
ENV DB_NAME=ecommerce_db
#--->  same as DB_DATABASE, but always make sure to use DB_NAME as it is more compatible
ENV DB_USER=root
ENV DB_PASS=secret
#ENV LOG_CHANNEL=stack
ENV ERROR_REPORTING=E_ALL
ENV ENABLE_CACHE=true
ENV FORCE_MIGRATION=false
#ENV STORAGE_PATH=/var/www/html/storage
#ENV MEDIA_URL=/media
#ENV JWT_SECRET=your_long_secret_key
#ENV SESSION_LIFETIME=120
#ENV APP_DEBUG=false
#ENV APP_URL=http://localhost
ENV DB_PORT=3306
#ENV DB_DATABASE=ecommerce_db; mostly used in lavarel
ENV DB_USERNAME=root
#ENV DB_PASSWORD=secret





WORKDIR /var/www/html






COPY composer.json composer.lock ./
#COPY . . 
# OR

COPY --chown=www-data:www-data . /var/www/html






RUN apt-get update && apt-get install -y \
  zip unzip curl git libpng-dev libjpeg-dev libonig-dev libxml2-dev && \
  curl -sS https://getcomposer.org/installer | php && \
  mv composer.phar /usr/local/bin/composer && \
  composer install --no-dev --optimize-autoloader && \
  docker-php-ext-install mysqli pdo_mysql gd mbstring xml && \
  a2enmod rewrite headers && \
  service apache2 restart && \
  apt-get clean && rm -rf /var/lib/apt/lists/* 
RUN echo "ServerTokens Prod\nServerSignature Off" >> /etc/apache2/conf-enabled/security.conf
RUN echo "Header always unset X-Powered-By" >> /etc/apache2/conf-enabled/security.conf

# ✅ Set permissions for media storage folder
RUN mkdir -p /var/www/html/storage /var/www/html/cache storage/media && \
    chown -R www-data:www-data /var/www/html storage/media && \
    chmod -R 755 /var/www/html storage/media

RUN echo "upload_max_filesize = 20M\npost_max_size = 25M" > /usr/local/etc/php/conf.d/uploads.ini





# ✅ Expose port 80 for web traffic
EXPOSE 80









#Absolutely, Louis! The VOLUME instruction in a PHP Dockerfile is used to declare persistent storage locations inside the container — places where data should survive across container restarts or be shared with the host. It’s especially useful for things like media uploads, logs, or cache folders.
# Common VOLUME Examples in PHP Dockerfiles
 #1. Declare a Media Storage Volume
#dockerfile
#VOLUME /var/www/html/storage
#Ensures that uploaded files or generated media are stored persistently.
#Docker will create an anonymous volume unless you mount a named one at runtime.
# 2. Declare Multiple Volumes
#dockerfile
#VOLUME ["/var/www/html/storage", "/var/www/html/cache"]
#Useful for separating media from cached data or logs.
#Each path becomes a mount point managed by Docker.
# 3. Volume for Config or Secrets
#dockerfile
#VOLUME /var/www/html/config
#Lets you inject config files at runtime without baking them into the image.
#Great for .env files or dynamic settings.
# 4. Volume for Composer Cache (optional optimization)
#dockerfile
#VOLUME /root/.composer
#Speeds up builds by caching Composer dependencies across runs.
#How It Works
#Declaring a VOLUME in the Dockerfile tells Docker:
#At runtime, you can mount a host folder or named volume:
#bash
#docker run -v /host/media:/var/www/html/storage my-php-app
# Bonus Tip for Your Backend Projects
#If you're handling media uploads or logs, using VOLUME ensures:
#Data isn’t lost when the container is rebuilt or restarted.
#You can back up or inspect files from the host system.
#You avoid bloating your image with runtime-generated content.




# ✅ Optional: Health check for container
HEALTHCHECK --interval=30s --timeout=10s \
  CMD curl -f http://localhost || exit 1

#Absolutely, Louis! Adding a HEALTHCHECK to your PHP Dockerfile is a smart move — it lets Docker monitor whether your container is actually functioning, not just running. Here are practical examples tailored to PHP environments:
#HEALTHCHECK --interval=30s --timeout=10s --retries=3 \
#  CMD curl -f http://localhost || exit 1
#Checks if your PHP app (served via Apache or PHP’s built-in server) responds to HTTP requests.
#curl -f fails if the response code is 400 or higher.
#Docker marks the container as unhealthy after 3 failed attempts.
#If you're using php:fpm, check if the FPM process is alive:
#dockerfile
#HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
#  CMD pidof php-fpm || exit 1
#✅ Verifies that the PHP-FPM process is running.
#pidof returns the process ID; if missing, the container is unhealthy.
#Create a lightweight script like healthcheck.php:
#php
#<?php
#echo "OK";
#Then in your Dockerfile:
#dockerfile
#COPY healthcheck.php /var/www/html/
#HEALTHCHECK CMD curl -f http://localhost/healthcheck.php || exit 1
#✅ Lets you customize logic — e.g., check DB connection, file access, etc.
#if your container runs PHP scripts (not a web server), use:
#dockerfile
#HEALTHCHECK CMD php -r "exit(0);"
#✅ Always returns healthy unless PHP itself fails to execute.









#CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
#CMD ["php", "/var/www/html/index.php"]

#CMD ["php-fpm"]

#COPY composer.json composer.lock ./
#RUN composer install --no-dev --optimize-autoloader
#COPY . .  # Copy the rest of the app after dependencies

#The ENTRYPOINT instruction defines the default executable that runs when a container starts. In PHP Dockerfiles, it’s often used to launch a web server, run a script, or start a custom process. Here are some practical examples tailored to PHP workflows:

#ENTRYPOINT ["php", "/var/www/html/index.php"]

#Runs a specific PHP file when the container starts.Useful for CLI-based PHP apps or microservices.


#ENTRYPOINT ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

#Keeps Apache running as the main process.Common in official php:apache base images.

#Use CMD when you want a default that users can override.Use ENTRYPOINT when your container should always run a specific executable (like php, apache2, or a custom script).Combine both when you want a fixed command with flexible arguments.
#for examples

#EXAMPLE BELOW:



#FROM php:8.2-cli
#WORKDIR /app
#COPY . .
#ENTRYPOINT ["php"]
#CMD ["index.php"]





#CLI scripts	FROM php:8.2-cli	Lightweight image for running PHP scripts from the command line
#Apache web server	FROM php:8.2-apache	Includes Apache; great for serving web apps via port 80
#Nginx + PHP-FPM combo	FROM php:8.2-fpm	Use with a separate Nginx container; ideal for production setups
#Alpine-based (minimal)	FROM php:8.2-cli-alpine	Smaller footprint; good for fast builds and reduced image size
#Composer-ready	FROM composer:latest	Includes Composer; useful for dependency management
#Custom extensions	FROM php:8.2-cli + RUN docker-php-ext-install	Add extensions like pdo, gd, mysqli, etc.













#The WORKDIR instruction in a PHP Dockerfile sets the default directory inside the container where all subsequent commands (RUN, CMD, COPY, etc.) will execute. It’s like saying, “From here on, this is home base.”

#Here are practical examples tailored to different PHP setups:

#Common WORKDIR Examples for PHP Projects
# 1. Apache Web Server Setup
#dockerfile
#WORKDIR /var/www/html







#The SHELL instruction in a Dockerfile lets you define the default shell used for RUN commands. By default, Docker uses ["/bin/sh", "-c"], but you can switch to something like bash if needed.

#Here are some practical examples you can run in a PHP Dockerfile:

# Basic SHELL Usage
#dockerfile
#SHELL ["/bin/bash", "-c"]
#Switches the shell to bash, allowing advanced scripting features like brace expansion or source.

# Installing PHP Extensions with Bash
#dockerfile
#SHELL ["/bin/bash", "-c"]
#RUN apt-get update && \
  #  apt-get install -y libpng-dev && \
 #   docker-php-ext-install gd
#Useful when chaining commands with && and using bash-specific syntax.

# Composer Setup with Custom Shell
#dockerfile
#SHELL ["/bin/bash", "-c"]
#RUN curl -sS https://getcomposer.org/installer | php && \
  #  mv composer.phar /usr/local/bin/composer
#Ensures the pipe to php works correctly in bash.

# Conditional Logic in RUN
#dockerfile
#SHELL ["/bin/bash", "-c"]
#RUN if [ ! -f /app/.env ]; then cp /app/.env.example /app/.env; fi
#Bash lets you use if statements and other control structures.

# Bonus Tips
#You can switch back to the default shell anytime:

#dockerfile
#SHELL ["/bin/sh", "-c"]
#Use SHELL when you need more scripting flexibility than sh provides.

#Combine with WORKDIR to scope your commands:

#dockerfile
#WORKDIR /usr/src/app
#SHELL ["/bin/bash", "-c"]
#RUN echo "Current dir: $(pwd)"








#Absolutely, Louis — here are practical ONBUILD examples tailored for PHP projects, especially useful when you're crafting a reusable base image for downstream builds:

# Common ONBUILD Commands for PHP Dockerfiles
#These commands will trigger automatically when another Dockerfile uses your image as its base.

#ONBUILD COPY — Add source code
#dockerfile
#ONBUILD COPY . /var/www/html
#Automatically copies the child project’s files into the web root.

# ONBUILD RUN — Install dependencies
#dockerfile
#ONBUILD RUN composer install --no-dev --optimize-autoloader
#Runs Composer in the child image to install PHP dependencies.

#ONBUILD ENV — Set environment variables
#dockerfile
#ONBUILD ENV APP_ENV=production
#Ensures consistent environment settings across downstream builds.

# ONBUILD EXPOSE — Declare ports
#dockerfile
#ONBUILD EXPOSE 80
#Makes sure the child container exposes the correct port for web traffic.

# ONBUILD WORKDIR — Set working directory
#dockerfile
#ONBUILD WORKDIR /var/www/html
#Ensures all subsequent commands in the child image run from the correct path.
# Example: Base Image for PHP Projects
#dockerfile
#FROM php:8.2-apache

#WORKDIR /var/www/html

#ONBUILD COPY . /var/www/html
#ONBUILD RUN composer install --no-dev --optimize-autoloader
#ONBUILD ENV APP_ENV=production
#ONBUILD EXPOSE 80
#Then in your child Dockerfile:

#dockerfile
#FROM my-php-base:latest
#This setup ensures every project using my-php-base gets a consistent build process without repeating boilerplate.