Write-Host "🚀 Installation du projet E-Commerce Laravel" -ForegroundColor Green
Write-Host "==============================================" -ForegroundColor Green

# Vérifier si Composer est installé
try {
    $composerVersion = composer --version
    Write-Host "✅ Composer détecté" -ForegroundColor Green
} catch {
    Write-Host "❌ Composer n'est pas installé. Veuillez l'installer d'abord." -ForegroundColor Red
    exit 1
}

# Installer les dépendances
Write-Host "📦 Installation des dépendances Composer..." -ForegroundColor Yellow
composer install

# Installer les dépendances npm
Write-Host "📦 Installation des dépendances npm..." -ForegroundColor Yellow
npm install

# Créer le fichier .env s'il n'existe pas
if (-not (Test-Path ".env")) {
    Write-Host "⚙️  Création du fichier .env..." -ForegroundColor Yellow
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "✅ Fichier .env créé. Veuillez le configurer avec vos paramètres de base de données." -ForegroundColor Green
    } else {
        Write-Host "⚠️  Fichier .env.example non trouvé. Créez manuellement le fichier .env" -ForegroundColor Yellow
    }
}

# Générer la clé d'application
Write-Host "🔑 Génération de la clé d'application..." -ForegroundColor Yellow
php artisan key:generate

# Créer la base de données SQLite (optionnel)
if (-not (Test-Path "database/database.sqlite")) {
    Write-Host "🗄️  Création de la base de données SQLite..." -ForegroundColor Yellow
    New-Item -Path "database/database.sqlite" -ItemType File -Force
}

# Exécuter les migrations
Write-Host "🗃️  Exécution des migrations..." -ForegroundColor Yellow
php artisan migrate

# Créer le lien symbolique pour le stockage
Write-Host "🔗 Création du lien symbolique pour le stockage..." -ForegroundColor Yellow
php artisan storage:link

# Installer DomPDF
Write-Host "📄 Installation de DomPDF pour les factures..." -ForegroundColor Yellow
composer require barryvdh/laravel-dompdf

Write-Host ""
Write-Host "✅ Installation terminée !" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Prochaines étapes :" -ForegroundColor Cyan
Write-Host "1. Configurez votre fichier .env avec vos paramètres de base de données" -ForegroundColor White
Write-Host "2. Si vous utilisez MySQL, créez une base de données et mettez à jour .env" -ForegroundColor White
Write-Host "3. Lancez le serveur avec : php artisan serve" -ForegroundColor White
Write-Host "4. Accédez au site : http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "📖 Consultez README_INSTALLATION.md pour plus de détails." -ForegroundColor Cyan 