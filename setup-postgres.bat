@echo off
echo ========================================
echo Configuration PostgreSQL pour vous
echo ========================================

echo.
echo 1. Installation des dependances...
composer install

echo.
echo 2. Creation du fichier .env...
if not exist .env (
    copy .env.example .env
    echo Fichier .env cree !
) else (
    echo Fichier .env existe deja.
)

echo.
echo 3. Configuration de la base de donnees PostgreSQL...
echo Veuillez modifier le fichier .env avec vos parametres PostgreSQL :
echo.
echo DB_CONNECTION=pgsql
echo DB_HOST=127.0.0.1
echo DB_PORT=5432
echo DB_DATABASE=commerce
echo DB_USERNAME=postgres
echo DB_PASSWORD=passer
echo.
echo Appuyez sur une touche pour continuer...
pause

echo.
echo 4. Generation de la cle d'application...
php artisan key:generate

echo.
echo 5. Creation de la base de donnees...
echo Assurez-vous que PostgreSQL est demarre et que la base 'commerce' existe.
echo Si elle n'existe pas, créez-la avec : CREATE DATABASE commerce;

echo.
echo 6. Execution des migrations...
php artisan migrate

echo.
echo 7. Ajout des donnees de test...
php artisan db:seed

echo.
echo 8. Creation du lien symbolique pour le stockage...
php artisan storage:link

echo.
echo 9. Installation de DomPDF...
composer require barryvdh/laravel-dompdf

echo.
echo ========================================
echo Configuration terminee !
echo ========================================
echo.
echo Pour demarrer le serveur :
echo php artisan serve
echo.
echo Puis ouvrez : http://localhost:8000
echo.
echo Comptes de test :
echo - Admin : admin@ecommerce.com / password
echo - Client : client@ecommerce.com / password
echo.
pause 