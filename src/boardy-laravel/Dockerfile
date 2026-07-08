# сборка фронтенда (Vite)
FROM node:20 AS frontend
WORKDIR /app

# Копируем package-файлы и устанавливаем зависимости
COPY package*.json ./
RUN npm ci

# Копируем весь проект и собираем фронтенд
COPY . .
RUN npm run build

FROM php:8.3-fpm

# Системные пакеты
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP-расширения
RUN docker-php-ext-install pdo_mysql mbstring zip opcache

# Redis через pecl
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Сначала только composer.json и .lock – для кеширования зависимостей
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Затем весь код
COPY . .

# Догружаем автозагрузку
RUN composer dump-autoload --optimize

# Копируем собранный фронтенд из стадии frontend
COPY --from=frontend /app/public/build /var/www/html/public/build

# Права
RUN chown -R www-data:www-data storage bootstrap/cache public/build
RUN chmod -R 775 storage bootstrap/cache public/build

EXPOSE 9000
CMD ["php-fpm"]
