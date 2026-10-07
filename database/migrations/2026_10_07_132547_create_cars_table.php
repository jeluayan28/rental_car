<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->unsignedSmallInteger('year');
            $table->string('category')->index();
            $table->text('description')->nullable();
            $table->decimal('price_per_day', 10, 2);
            $table->unsignedTinyInteger('seats');
            $table->string('transmission');
            $table->string('fuel_type');
            $table->string('image')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
