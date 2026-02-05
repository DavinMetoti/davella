<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    protected $fillable = [
        'cluster_id',
        'name',
        'block',
        'number',
        'house_type',
        'land_area',
        'building_area',
        'progress',
        'status',
        'coordinates',
    ];

    protected $casts = [
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
        'progress' => 'integer',
    ];

    // Relationships
    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    // Accessors
    public function getFormattedLandAreaAttribute(): string
    {
        return number_format($this->land_area, 2) . ' m²';
    }

    public function getFormattedBuildingAreaAttribute(): string
    {
        return number_format($this->building_area, 2) . ' m²';
    }

    public function getProgressPercentageAttribute(): string
    {
        return $this->progress . '%';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'available' => '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Available</span>',
            'reserved' => '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Reserved</span>',
            'booked' => '<span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Booked</span>',
            default => '<span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Unknown</span>',
        };
    }

    public function getCoordinatesAttribute(): ?string
    {
        return $this->attributes['coordinates'] ?? null;
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }

    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    public function scopeByCluster($query, $clusterId)
    {
        return $query->where('cluster_id', $clusterId);
    }
}
