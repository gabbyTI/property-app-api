<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'price',
        'image',
        'bedrooms',
        'toilets',
        'parking_lots',
        'location',
        'term_duration',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
