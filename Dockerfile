FROM php:8.1-apache

# Extensions PHP nécessaires pour la connexion MySQL (PDO)
RUN docker-php-ext-install pdo pdo_mysql

# Le code est monté via docker-compose dans /var/www/html
