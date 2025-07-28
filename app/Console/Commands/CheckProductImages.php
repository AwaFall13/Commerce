<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class CheckProductImages extends Command
{
    protected $signature = 'products:check-images {--fix : Copier les images manquantes si possible}';
    protected $description = 'Vérifie la présence des images produits et tente de corriger les chemins manquants';

    public function handle()
    {
        $missing = [];
        $fixed = 0;

        foreach (Product::all() as $product) {
            $image = $product->image;
            if (!$image) {
                $this->warn("Produit sans image : {$product->name}");
                continue;
            }

            $storagePath = public_path('storage/' . ltrim($image, '/'));
            $imagesPath = public_path('images/' . ltrim($image, '/'));

            if (file_exists($storagePath) || file_exists($imagesPath) || filter_var($image, FILTER_VALIDATE_URL)) {
                $this->info("OK : {$product->name} => $image");
            } else {
                $this->error("Image manquante : {$product->name} => $image");
                $missing[] = [$product, $image];
            }
        }

        if ($this->option('fix') && count($missing)) {
            foreach ($missing as [$product, $image]) {
                $basename = basename($image);
                $possible = [
                    public_path('images/' . $basename),
                    public_path('storage/' . $basename),
                ];
                foreach ($possible as $src) {
                    if (file_exists($src)) {
                        $dest = public_path('images/' . $basename);
                        if (!file_exists($dest)) {
                            copy($src, $dest);
                            $this->info("Copié $src vers $dest");
                            $fixed++;
                        }
                    }
                }
            }
            $this->info("Correction terminée. $fixed images copiées.");
        }
    }
} 