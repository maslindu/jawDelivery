<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'payment_method',
        'notes',
        'subtotal',
        'shipping_fee',
        'admin_fee',
        'payment_status',
        'status',
        'invoice'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_order')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
