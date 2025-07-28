@echo off
echo ========================================
echo   CONFIGURATION BASE DE DONNEES
echo ========================================
echo.
echo Choisissez votre base de données :
echo 1. PostgreSQL (recommandé)
echo 2. MySQL
echo.
set /p choice="Votre choix (1 ou 2): "

if "%choice%"=="1" (
    echo.
    echo Configuration PostgreSQL...
    copy env.postgresql.example .env
    echo ✅ Fichier .env configuré pour PostgreSQL
) else if "%choice%"=="2" (
    echo.
    echo Configuration MySQL...
    copy env.mysql.example .env
    echo ✅ Fichier .env configuré pour MySQL
) else (
    echo ❌ Choix invalide
    pause
    exit /b 1
)

echo.
echo Génération de la clé d'application...
php artisan key:generate

echo.
echo Nettoyage des caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo Migration et seeding de la base de données...
php artisan migrate:fresh --seed

echo.
echo Création du lien symbolique pour les images...
php artisan storage:link

echo.
echo ========================================
echo   CONFIGURATION TERMINÉE !
echo ========================================
echo.
echo Comptes de test :
echo - Admin : admin@ecommerce.com / password
echo - Client : client@ecommerce.com / password
echo.
echo Pour démarrer le serveur :
echo php artisan serve
echo.
pause 