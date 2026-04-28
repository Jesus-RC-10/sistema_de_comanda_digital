FROM php:8.2-apache

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Instalar extensiones necesarias de MySQL (mysqli)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copiar el código fuente de la aplicación a la carpeta de Apache
COPY . /var/www/html/

# Ajustar los permisos para que Apache pueda acceder
RUN chown -R www-data:www-data /var/www/html/