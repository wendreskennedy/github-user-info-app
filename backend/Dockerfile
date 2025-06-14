FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    zip \
    libzip-dev \
    libonig-dev \
    libxml2-dev

# Instala extensões do PHP
RUN docker-php-ext-install pdo pdo_pgsql zip

# Instala o Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
