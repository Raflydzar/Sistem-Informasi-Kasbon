FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache nginx supervisor shadow bash nodejs npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install dependencies & Build Tailwind
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install && npm run build

# Setup permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && nginx -g 'daemon off;'"]
