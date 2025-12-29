FROM php:8.4-fpm

# Instalar dependencias para intl y activar la extensión
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Usar la configuración de producción por defecto
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
