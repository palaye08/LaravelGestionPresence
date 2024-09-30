# Utilisation de l'image PHP officielle
FROM php:8.1-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Installation des extensions PHP requises
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd mbstring zip xml

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet dans le conteneur
COPY . .

# Installation des dépendances PHP avec Composer
RUN composer install --no-dev --optimize-autoloader

# Créer les répertoires nécessaires et ajuster les permissions
RUN mkdir -p storage/logs \
    && mkdir -p storage/framework/sessions \
    && mkdir -p storage/framework/views \
    && mkdir -p storage/framework/cache \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Créer le fichier de log et ajuster ses permissions
RUN touch storage/logs/laravel.log \
    && chown www-data:www-data storage/logs/laravel.log \
    && chmod 664 storage/logs/laravel.log

# Copier le fichier .env et générer la clé
COPY .env.example .env
RUN php artisan key:generate

# Exposer le port 8080
EXPOSE 8080


# Commande pour démarrer l'application
CMD php artisan serve --host=0.0.0.0 --port=$PORT