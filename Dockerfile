# Utilisation de l'image PHP officielle
FROM php:8.1-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Configuration des locales
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    locale-gen

# Installation des extensions PHP requises
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_mysql gd mbstring zip xml

# Installation de Composer
COPY --from=composer:2.3 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers du projet dans le conteneur
COPY . /var/www

# Installation des dépendances PHP avec Composer
RUN composer install --no-dev --optimize-autoloader

# Ajuster les permissions des fichiers (spécifique à Laravel)
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Exposer le port pour PHP-FPM
EXPOSE 9000
EXPOSE 80
# Démarrer PHP-FPM
CMD ["php-fpm"]
# À ajouter à la fin de votre Dockerfile
RUN chown www-data:www-data /var/www/firebase_credentials.json && \
    chmod 644 /var/www/firebase_credentials.json
