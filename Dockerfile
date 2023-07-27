FROM php:8.1.12 as php

RUN apt-get update -y
RUN apt-get install -y unzip libonig-dev libpq-dev libcurl4-gnutls-dev zlib1g-dev
RUN docker-php-ext-install pdo pdo_mysql bcmath mbstring gd

# Install Node.js
# RUN curl -sL https://deb.nodesource.com/setup_19.x | bash -
# RUN apt-get install -y nodejs

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#  it's copying the Composer binary from the official Composer image (version 2.4.4) to the new image being built, making Composer available inside that new image.
# COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

# Set working directory
WORKDIR /var/www
# Copy existing application directory contents
COPY . .

# Install Composer dependencies
RUN composer install

# Install Node dependencies and build assets
# RUN npm install && npm run build

# copying project files into www
# WORKDIR /var/www
# COPY . .



ENV PORT=8001

# Make the entrypoint script executable
RUN chmod +x ./Docker/entrypoint.sh

ENTRYPOINT [ "docker/entrypoint.sh" ]

#======================================================================
# Node
FROM node:20-alpine as node

# Set working directory
WORKDIR /var/www
# Copy existing application directory contents
COPY . .

RUN npm install --global

RUN npm install

VOLUME /var/www/node_modules
