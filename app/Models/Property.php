<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'upload_successful',
        'disk'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImagesAttribute()
    {
        return [
            "thumbnail" => $this->getImagePath("thumbnail"),
            "original" => $this->getImagePath("original"),
            "large" => $this->getImagePath("large"),
        ];
    }

    public function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/properties/{$size}/" . $this->image);
    }
}
