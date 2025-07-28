<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email', 
        'message',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
} 