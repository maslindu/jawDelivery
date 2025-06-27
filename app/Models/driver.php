<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'working_hours',
        'is_available',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'is_available' => 'boolean',
        'rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('status', 'active');
    }

    public function getFullInfoAttribute()
    {
        return [
            'id' => $this->id,
            'name' => $this->user->fullName ?? $this->user->username,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'avatar_url' => $this->user->avatar_url,
            'vehicle_type' => $this->vehicle_type,
            'license_plate' => $this->license_plate,
            'status' => $this->status,
            'rating' => $this->rating,
            'total_deliveries' => $this->total_deliveries,
            'is_available' => $this->is_available,
        ];
    }

    public function toggleAvailability()
    {
        $this->update(['is_available' => !$this->is_available]);
        return $this;
    }

    public function incrementDeliveries()
    {
        $this->increment('total_deliveries');
        return $this;
    }
}
