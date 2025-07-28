#!/bin/bash

echo "🚀 Installation du projet E-Commerce Laravel"
echo "=============================================="

# Vérifier si Composer est installé
if ! command -v composer &> /dev/null; then
    echo "❌ Composer n'est pas installé. Veuillez l'installer d'abord."
    exit 1
fi

# Installer les dépendances
echo "📦 Installation des dépendances Composer..."
composer install

# Installer les dépendances npm
echo "📦 Installation des dépendances npm..."
npm install

# Créer le fichier .env s'il n'existe pas
if [ ! -f .env ]; then
    echo "⚙️  Création du fichier .env..."
    cp .env.example .env
    echo "✅ Fichier .env créé. Veuillez le configurer avec vos paramètres de base de données."
fi

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
php artisan key:generate

# Créer la base de données SQLite (optionnel)
if [ ! -f database/database.sqlite ]; then
    echo "🗄️  Création de la base de données SQLite..."
    echo "" > database/database.sqlite
fi

# Exécuter les migrations
echo "🗃️  Exécution des migrations..."
php artisan migrate

# Créer le lien symbolique pour le stockage
echo "🔗 Création du lien symbolique pour le stockage..."
php artisan storage:link

# Installer DomPDF
echo "📄 Installation de DomPDF pour les factures..."
composer require barryvdh/laravel-dompdf

echo ""
echo "✅ Installation terminée !"
echo ""
echo "📋 Prochaines étapes :"
echo "1. Configurez votre fichier .env avec vos paramètres de base de données"
echo "2. Si vous utilisez MySQL, créez une base de données et mettez à jour .env"
echo "3. Lancez le serveur avec : php artisan serve"
echo "4. Accédez au site : http://localhost:8000"
echo ""
echo "📖 Consultez README_INSTALLATION.md pour plus de détails." 