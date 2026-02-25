FROM php:8.4-apache

# 1. Habilitar mod_rewrite (necesario para las rutas de Laravel)
RUN a2enmod rewrite

# 2. Cambiar la raíz de Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Instalar herramientas del sistema y Node.js (para Vue/Pinia)
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 4. Instalar extensiones de PHP que usa Laravel
RUN docker-php-ext-install pdo_mysql mbstring zip gd

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
