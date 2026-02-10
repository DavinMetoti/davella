<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'reservation_code',
        'reservation_date',
        'expired_at',
        'unit_id',
        'price_snapshot',
        'promo_snapshot',
        'customer_name',
        'customer_phone',
        'ktp_number',
        'sales_id',
        'payment_method',
        'booking_fee',
        'dp_plan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
        'expired_at' => 'datetime',
        'price_snapshot' => 'decimal:2',
        'booking_fee' => 'decimal:2',
        'dp_plan' => 'decimal:2',
        'promo_snapshot' => 'array',
    ];

    // Relationships
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>',
            'confirmed' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Confirmed</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Cancelled</span>',
            'expired' => '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Expired</span>',
            default => '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Unknown</span>',
        };
    }
}
