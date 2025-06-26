<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'image_link',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image_link && Storage::disk('public')->exists($this->image_link)) {
            return Storage::url($this->image_link);
        }
        return Storage::url('menu/default-image.jpg');
    }

    public function getPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_menu');
    }

    public function getCategoryNamesAttribute()
    {
        return $this->categories->pluck('name')->implode(', ');
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }
}