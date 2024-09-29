#!/bin/sh
set -e

# Attendre que la base de données soit prête
until php artisan db:monitor --timeout=60; do
  echo "Waiting for database connection..."
  sleep 5
done

# Exécuter les migrations
php artisan migrate --force

# Démarrer l'application
php artisan serve --host=0.0.0.0 --port=$PORT