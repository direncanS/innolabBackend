FROM php:8.2-fpm

# Gerekli bağımlılıkları yükleme
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    default-mysql-server

# PHP uzantılarını yükleme
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Composer'ı yükleme
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizinini ayarlama
WORKDIR /var/www

# Proje dosyalarını kopyalama
COPY . .

# Bağımlılıkları yükleme
RUN npm install
RUN composer install

# MySQL ve Laravel başlatma scripti
CMD service mysql start && \
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS laravel;"
