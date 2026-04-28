FROM php:8.2-apache

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar extensiones necesarias de PDO para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Instalar composer para gestión de dependencias
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el código fuente de la aplicación a la carpeta de Apache
COPY . /var/www/html/

# Ajustar los permisos para que Apache pueda acceder
RUN chown -R www-data:www-data /var/www/html/

# Configurar errores de PHP
RUN echo "display_errors=Off" >> /usr/local/etc/php/conf.d/docker-php-ext.ini \
    && echo "log_errors=On" >> /usr/local/etc/php/conf.d/docker-php-ext.ini \
    && echo "error_log=/var/www/html/logs/php_errors.log" >> /usr/local/etc/php/conf.d/docker-php-ext.ini

# Crear directorio de logs
RUN mkdir -p /var/www/html/logs && chown www-data:www-data /var/www/html/logs

# Exponer puerto 80
EXPOSE 80

WORKDIR /var/www/html
