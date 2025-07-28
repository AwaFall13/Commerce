# 🛒 Plateforme E-Commerce - Guide d'Installation

## 📋 Prérequis

- PHP 8.0 ou supérieur
- Composer
- Base de données (PostgreSQL ou MySQL)
- Serveur web (Apache/Nginx) ou serveur de développement

## 🚀 Installation Rapide

### Option 1 : Installation Automatique (Windows)
```bash
# Double-cliquez sur le fichier
setup-database.bat
```

### Option 2 : Installation Manuelle

1. **Cloner le projet**
```bash
git clone [URL_DU_REPO]
cd Commerce
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Configurer la base de données**

#### Pour PostgreSQL :
```bash
copy env.postgresql.example .env
```

#### Pour MySQL :
```bash
copy env.mysql.example .env
```

4. **Configurer l'application**
```bash
php artisan key:generate
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

5. **Créer la base de données**
- **PostgreSQL** : Créez une base `commerce`
- **MySQL** : Créez une base `commerce`

6. **Migrer et peupler la base**
```bash
php artisan migrate:fresh --seed
```

7. **Créer le lien symbolique**
```bash
php artisan storage:link
```

## 🔧 Configuration Base de Données

### PostgreSQL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=commerce
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe
```

### MySQL
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=commerce
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

## 🎯 Comptes de Test

- **Administrateur** : `admin@ecommerce.com` / `password`
- **Client** : `client@ecommerce.com` / `password`

## 🚀 Démarrer l'Application

```bash
php artisan serve
```

Puis ouvrez : http://localhost:8000

## 📁 Structure du Projet

```
Commerce/
├── app/
│   ├── Http/Controllers/    # Contrôleurs
│   ├── Models/             # Modèles Eloquent
│   └── Mail/               # Emails
├── database/
│   ├── migrations/         # Migrations
│   └── seeders/           # Seeders
├── resources/views/        # Vues Blade
├── routes/                 # Routes
└── public/                # Fichiers publics
```

## 🔍 Fonctionnalités

### Front-Office
- ✅ Catalogue de produits
- ✅ Panier d'achat
- ✅ Finalisation de commande
- ✅ Historique des commandes
- ✅ Inscription/Connexion

### Back-Office
- ✅ Gestion des produits
- ✅ Gestion des commandes
- ✅ Gestion des utilisateurs
- ✅ Tableau de bord

## 🛠️ Dépannage

### Erreur "could not find driver"
- Installez l'extension PHP pour votre base de données
- PostgreSQL : `pdo_pgsql` et `pgsql`
- MySQL : `pdo_mysql`

### Images ne s'affichent pas
```bash
php artisan storage:link
```

### Erreur de migration
```bash
php artisan migrate:fresh --seed
```

## 📞 Support

Pour toute question, contactez l'équipe de développement. 