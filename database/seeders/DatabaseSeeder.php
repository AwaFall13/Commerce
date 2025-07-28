<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un utilisateur admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@ecommerce.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Créer un utilisateur normal
        User::create([
            'name' => 'Client Test',
            'email' => 'client@ecommerce.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);

        // Créer des catégories
        $categories = [
            'Bijoux africains',
            'Tissus traditionnels',
            'Cosmétiques naturels',
            'Artisanat',
            'Boissons traditionnelles',
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }

        // Créer des produits de test
        $products = [
            [
                'name' => 'Boucles d\'oreilles wax',
                'description' => 'Magnifiques boucles d\'oreilles en wax africain',
                'price' => 15000,
                'stock' => 50,
                'category_id' => 1,
                'image' => 'boucles d\'oreilles.jpg',
            ],
            [
                'name' => 'Bissap',
                'description' => 'Boisson traditionnelle à base d\'hibiscus',
                'price' => 2000,
                'stock' => 100,
                'category_id' => 5,
                'image' => 'bissap.webp',
            ],
            [
                'name' => 'Pagnes tissés',
                'description' => 'Tissus traditionnels colorés',
                'price' => 25000,
                'stock' => 30,
                'category_id' => 2,
                'image' => 'Pagnes tissés.webp',
            ],
            [
                'name' => 'Savon noir africain',
                'description' => 'Savon naturel pour la peau',
                'price' => 5000,
                'stock' => 75,
                'category_id' => 3,
                'image' => 'Savon noir africain.webp',
            ],
            [
                'name' => 'Thiouraye',
                'description' => 'Encens traditionnel parfumé',
                'price' => 3000,
                'stock' => 60,
                'category_id' => 4,
                'image' => 'thiouraye.webp',
            ],
            [
                'name' => 'Sandales artisanales',
                'description' => 'Sandales faites main en cuir',
                'price' => 18000,
                'stock' => 25,
                'category_id' => 4,
                'image' => 'Sandales artisanales.webp',
            ],
            [
                'name' => 'Baobab en poudre',
                'description' => 'Poudre de baobab riche en vitamines',
                'price' => 8000,
                'stock' => 40,
                'category_id' => 3,
                'image' => 'Baobab en poudre.webp',
            ],
            [
                'name' => 'Panier en osier',
                'description' => 'Panier traditionnel tressé à la main',
                'price' => 12000,
                'stock' => 20,
                'category_id' => 4,
                'image' => 'panier en osier.jpg',
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('✅ Données de test créées avec succès !');
        $this->command->info('👤 Compte admin : admin@ecommerce.com / password');
        $this->command->info('👤 Compte client : client@ecommerce.com / password');
    }
}
