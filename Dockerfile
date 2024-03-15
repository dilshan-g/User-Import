# Always a good practice to keep the Dockerfile as shorter as possible.
# PHP8.1 image with apache
FROM php:8.1-apache

# Install PHP extensions and other dependencies
RUN apt-get update && \
    apt-get install -y libpng-dev && \
    docker-php-ext-install pdo pdo_mysql gd
