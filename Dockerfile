FROM php:8.1-apache

# Extensions PHP nécessaires pour la connexion MySQL (PDO)
RUN docker-php-ext-install pdo pdo_mysql

# Outils requis par Composer pour récupérer les paquets
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*

# Composer sert uniquement à l'outillage de développement
# (PHPUnit, PHPStan, PHP-CS-Fixer). L'application elle-même n'a
# aucune dépendance à l'exécution : vendor/ n'est jamais requis en production.
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Le code est monté via docker-compose dans /var/www/html
