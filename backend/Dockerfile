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
    libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instala extensões do PHP
RUN docker-php-ext-install pdo pdo_pgsql zip

# Instala o Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instala o Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000

# Comando simples que verifica vendor e inicia o servidor
CMD ["sh", "-c", "if [ ! -d 'vendor' ]; then composer install --no-interaction --optimize-autoloader; fi && php artisan serve --host=0.0.0.0 --port=8000"]