<?php
// app/Models/Driver.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'license_plate',
        'driver_license',
        'vehicle_registration',
        'status',
        'rating',
        'total_deliveries',
        'is_available',
        'working_hours'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'working_hours' => 'array',
        'rating' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Method untuk increment deliveries
    public function incrementDeliveries()
    {
        $this->increment('total_deliveries');
        return $this;
    }

    // Method untuk toggle availability
    public function toggleAvailability()
    {
        $this->is_available = !$this->is_available;
        $this->save();
        return $this;
    }

    // Scope untuk driver yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->where('is_available', true);
    }
}
