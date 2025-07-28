@echo off
echo ========================================
echo   NETTOYAGE POUR GIT
echo ========================================
echo.

echo Suppression des fichiers temporaires...
if exist "check_images.php" del "check_images.php"
if exist "check_orders.php" del "check_orders.php"
if exist "test_order.php" del "test_order.php"

echo.
echo Nettoyage des caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo Suppression des logs...
if exist "storage\logs\laravel.log" del "storage\logs\laravel.log"

echo.
echo ✅ Nettoyage terminé !
echo.
echo Vous pouvez maintenant pousser sur GitHub :
echo git add .
echo git commit -m "Projet e-commerce complet"
echo git push origin main
echo.
pause 