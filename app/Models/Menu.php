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

    public function getImageUrlAttribute()
    {
        // return $this->avatar_link
        //     ? Storage::url('menu/' . $this->image_link)
        //     : Storage::url('menu/default-image.jpg');
        return Storage::url('menu/default-image.jpg');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
