<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'amount', 'payment_status', 'payment_method', 'paid_at'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
