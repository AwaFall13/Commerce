<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer des catégories typiques
        $categories = [
            ['name' => 'Boissons', 'description' => 'Jus et boissons locales'],
            ['name' => 'Textile', 'description' => 'Pagnes, vêtements traditionnels'],
            ['name' => 'Beauté', 'description' => 'Soins naturels et artisanaux'],
            ['name' => 'Maison', 'description' => 'Objets artisanaux pour la maison'],
        ];
        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat['name'],
                'description' => $cat['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $catIds = DB::table('categories')->pluck('id', 'name');
        // Produits sénégalais
        $produits = [
            [
                'name' => 'Bissap',
                'description' => 'Jus de fleurs d’hibiscus, boisson rafraîchissante typique du Sénégal.',
                'price' => 1000,
                'stock' => 50,
                'image' => 'https://i.imgur.com/8XZqY2L.jpg',
                'category_id' => $catIds['Boissons'] ?? 1,
            ],
            [
                'name' => 'Pagnes tissés',
                'description' => 'Tissu traditionnel sénégalais, idéal pour vêtements et accessoires.',
                'price' => 5000,
                'stock' => 30,
                'image' => 'https://i.imgur.com/JK9LmNq.jpg',
                'category_id' => $catIds['Textile'] ?? 2,
            ],
            [
                'name' => 'Savon noir africain',
                'description' => 'Savon naturel à base de cendres végétales, pour la beauté de la peau.',
                'price' => 1500,
                'stock' => 40,
                'image' => 'https://i.imgur.com/QW3Rf5p.jpg',
                'category_id' => $catIds['Beauté'] ?? 3,
            ],
            [
                'name' => 'Thiouraye',
                'description' => 'Encens traditionnel sénégalais pour parfumer la maison.',
                'price' => 800,
                'stock' => 60,
                'image' => 'https://i.imgur.com/Vn7XqY8.jpg',
                'category_id' => $catIds['Maison'] ?? 4,
            ],
            [
                'name' => 'Sandales artisanales',
                'description' => 'Sandales faites main en cuir, confortables et élégantes.',
                'price' => 3500,
                'stock' => 25,
                'image' => 'https://i.imgur.com/Lm2KpQ9.jpg',
                'category_id' => $catIds['Textile'] ?? 2,
            ],
            [
                'name' => 'Baobab en poudre',
                'description' => 'Super-aliment riche en vitamines, issu du fruit du baobab.',
                'price' => 2000,
                'stock' => 35,
                'image' => 'https://i.imgur.com/Ht5NqR2.jpg',
                'category_id' => $catIds['Boissons'] ?? 1,
            ],
            [
                'name' => 'Boucles d’oreilles wax',
                'description' => 'Accessoire de mode coloré, fabriqué à la main.',
                'price' => 1200,
                'stock' => 45,
                'image' => 'https://i.imgur.com/Pq8MkL4.jpg',
                'category_id' => $catIds['Textile'] ?? 2,
            ],
            [
                'name' => 'Panier en osier',
                'description' => 'Panier tressé à la main, idéal pour le marché ou la déco.',
                'price' => 2500,
                'stock' => 20,
                'image' => 'https://i.imgur.com/Xy7NqW5.jpg',
                'category_id' => $catIds['Maison'] ?? 4,
            ],
        ];
        foreach ($produits as $prod) {
            DB::table('products')->insert(array_merge($prod, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
