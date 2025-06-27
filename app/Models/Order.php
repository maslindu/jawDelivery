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
        'driver_id',
        'payment_method',
        'notes',
        'subtotal',
        'shipping_fee',
        'admin_fee',
        'payment_status',
        'status',
        'invoice'
    ];

    // Status constants - sesuai dengan orders.blade.php
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

    // Relasi tetap sama...
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
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

    // Perbaiki accessor buyer name
    public function getBuyerNameAttribute()
    {
        if ($this->user) {
            return $this->user->name ?: ($this->user->fullName ?: $this->user->username);
        }
        return 'User tidak ditemukan';
    }

    public function getBuyerAddressAttribute()
    {
        if ($this->address && $this->address->address) {
            $label = $this->address->label ? "({$this->address->label}) " : '';
            return $label . $this->address->address;
        }
        
        if ($this->user && $this->user->address) {
            return $this->user->address;
        }
        
        return 'Alamat tidak tersedia';
    }

    public function getStatusLabelAttribute()
    {
        $labels = self::getStatusOptions();
        return $labels[$this->status] ?? ucfirst($this->status);
    }
}
