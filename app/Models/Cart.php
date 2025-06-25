<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'menu_id',
        'quantity',
    ];

    // Relationship to the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to the menu item
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
