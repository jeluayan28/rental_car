<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_id')->constrained()->restrictOnDelete();
            $table->date('pickup_date');
            $table->date('return_date');
            $table->unsignedSmallInteger('total_days');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->index();
            $table->timestamps();

            // Availability lookups: bookings for a car overlapping a date range.
            $table->index(['car_id', 'pickup_date', 'return_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
