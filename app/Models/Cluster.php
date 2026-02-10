<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cluster extends Model
{
    protected $fillable = [
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'site_plan_path',
        'area_size',
        'total_units',
        'available_units',
        'price_range_min',
        'price_range_max',
        'developer_id',
        'year_built',
        'facilities',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'is_active' => 'boolean',
        'year_built' => 'integer',
        'facilities' => 'array',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor for total units
    public function getTotalUnitsAttribute()
    {
        return $this->units()->count();
    }

    // Accessor for available units
    public function getAvailableUnitsAttribute()
    {
        return $this->units()->where('status', 'available')->count();
    }

    // Accessor for formatted price range
    public function getFormattedPriceRangeAttribute()
    {
        if ($this->price_range_min && $this->price_range_max) {
            return 'Rp ' . number_format($this->price_range_min, 0, ',', '.') . ' - Rp ' . number_format($this->price_range_max, 0, ',', '.');
        } elseif ($this->price_range_min) {
            return 'Starting from Rp ' . number_format($this->price_range_min, 0, ',', '.');
        } elseif ($this->price_range_max) {
            return 'Up to Rp ' . number_format($this->price_range_max, 0, ',', '.');
        }
        return 'Price not set';
    }

    // Accessor for coordinates
    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude . ', ' . $this->longitude;
        }
        return null;
    }

    // Accessor for facilities to ensure it's always an array
    public function getFacilitiesAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($value) ? $value : [];
    }
}
