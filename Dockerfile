# Imagen base: PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para PDO MySQL y mbstring
RUN docker-php-ext-install pdo pdo_mysql mbstring

# Habilitar mod_rewrite de Apache (por si necesitas .htaccess en el futuro)
RUN a2enmod rewrite

# Copiar todo el proyecto al directorio de Apache
COPY . /var/www/html/

# Dar permisos a la carpeta de imágenes para subidas
RUN chown -R www-data:www-data /var/www/html/assets/img \
    && chmod -R 775 /var/www/html/assets/img

# Exponer el puerto 80
EXPOSE 80
