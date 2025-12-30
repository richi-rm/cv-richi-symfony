FROM php:8.4-fpm

# Instalar dependencias para intl y activar la extensión
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Log de errores del servidor
RUN echo "display_errors=Off" >> "$PHP_INI_DIR/conf.d/error-logging.ini" && \
    echo "log_errors=On" >> "$PHP_INI_DIR/conf.d/error-logging.ini" && \
    echo "error_log=/var/log/php_errors.log" >> "$PHP_INI_DIR/conf.d/error-logging.ini"

# Usar la configuración de producción por defecto
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Instalamos composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
