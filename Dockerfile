# Sử dụng PHP với Apache
FROM php:7.4-apache

RUN apt-get update && apt-get install -y \    
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    postgis \
    nodejs \
    npm 
COPY php/php.ini /usr/local/etc/php/php.ini
RUN docker-php-ext-install pdo pdo_pgsql

# Cài đặt mở rộng PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

# Sao chép composer
COPY --from=composer:2.4.4 /usr/bin/composer /usr/bin/composer

# Sao chép file cấu hình Apache
COPY apache.conf /etc/apache2/sites-enabled/000-default.conf
RUN a2enmod rewrite
WORKDIR /var/www

COPY . .

# Cài đặt phụ thuộc PHP
RUN composer install --no-dev --optimize-autoloader

# Cài đặt Yarn
RUN npm install -g yarn

# Cài đặt phụ thuộc Node.js
RUN npm install

# Build ứng dụng
RUN npm run prod

# Cấp quyền cho thư mục
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public

# Expose cổng 80 cho Apache
EXPOSE 80
CMD ["apache2-foreground"]
