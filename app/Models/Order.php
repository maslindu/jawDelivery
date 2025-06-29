<?php
// app/Models/Order.php

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

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_PROCESSING => 'Diproses',
            self::STATUS_SHIPPED => 'Dikirim',
            self::STATUS_DELIVERED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

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

    // Accessor untuk total amount
    public function getTotalAmountAttribute()
    {
        return $this->subtotal + $this->shipping_fee + $this->admin_fee;
    }

    // Accessor untuk nama pembeli (prioritas: fullName, lalu username)
    public function getBuyerNameAttribute()
    {
        if ($this->user) {
            return $this->user->fullName ?: $this->user->username;
        }
        return 'User tidak ditemukan';
    }

    // Accessor untuk alamat pembeli (prioritas: UserAddress, lalu User address)
    public function getBuyerAddressAttribute()
    {
        // Prioritas 1: Alamat dari tabel user_addresses
        if ($this->address && $this->address->address) {
            $label = $this->address->label ? "({$this->address->label}) " : '';
            return $label . $this->address->address;
        }
        
        // Prioritas 2: Alamat dari tabel users
        if ($this->user && $this->user->address) {
            return $this->user->address;
        }
        
        return 'Alamat tidak tersedia';
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
