<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\CarStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Car extends Model
{
    use HasFactory;

    public const CATEGORIES = ['Sport', 'SUV', 'Sedan', 'Luxury', 'Family', 'Adventure'];

    public const TRANSMISSIONS = ['Automatic', 'Manual'];

    public const FUEL_TYPES = ['Gasoline', 'Diesel', 'Hybrid', 'Electric'];

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

    /**
     * Displayable image URL. `image` holds either an absolute URL (dev placeholders)
     * or a path on the public disk (uploaded files).
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => match (true) {
            blank($this->image) => null,
            str_starts_with($this->image, 'http') => $this->image,
            default => Storage::url($this->image),
        });
    }

    /** Whether `image` points at a file we stored (as opposed to an external URL). */
    public function hasStoredImage(): bool
    {
        return filled($this->image) && ! str_starts_with($this->image, 'http');
    }

    public function isAvailable(): bool
    {
        return $this->status === CarStatus::Available;
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where('status', CarStatus::Available);
    }

    /**
     * Whether an active (pending/confirmed) booking overlaps the given dates, both ends inclusive.
     */
    public function hasBookingBetween(CarbonInterface $pickup, CarbonInterface $return): bool
    {
        return $this->bookings()
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->whereDate('pickup_date', '<=', $return)
            ->whereDate('return_date', '>=', $pickup)
            ->exists();
    }

    /**
     * Rental length and price. A same-day return counts as one day.
     *
     * @return array{days: int, total: float}
     */
    public function quote(CarbonInterface $pickup, CarbonInterface $return): array
    {
        $days = max(1, (int) $pickup->copy()->startOfDay()->diffInDays($return->copy()->startOfDay()));

        return ['days' => $days, 'total' => round((float) $this->price_per_day * $days, 2)];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
