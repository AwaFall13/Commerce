<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    // Relation avec Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation avec OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessor pour les images
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://via.placeholder.com/300x300?text='.urlencode($this->name);
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (file_exists(public_path('storage/'.$this->image))) {
            return asset('storage/'.$this->image);
        }
        
        return asset('images/'.$this->image); // Fallback
    }
}
