FROM php:8.2-fpm

# Instala dependências do sistema e extensões PHP necessárias para o Laravel e MySQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpa o cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copia o Composer mais recente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www