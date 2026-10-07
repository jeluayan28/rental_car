<?php

namespace App\Models;

use App\Enums\CarStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand', 'model', 'year', 'category', 'description', 'price_per_day',
        'seats', 'transmission', 'fuel_type', 'image', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
            'status' => CarStatus::class,
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
