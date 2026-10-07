<?php

namespace Database\Seeders;

use App\Enums\CarStatus;
use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            ['BMW', 'M4 Competition', 2023, 'Sport', 'Twin-turbo inline-six coupe with razor-sharp handling. Built for weekend drives up to Tagaytay and beyond.', 4500, 4, 'Automatic', 'Gasoline', CarStatus::Available],
            ['Ford', 'Mustang GT', 2022, 'Sport', 'Classic V8 muscle with a modern cabin. Loud, fast and impossible to ignore.', 4200, 4, 'Automatic', 'Gasoline', CarStatus::Available],
            ['Toyota', 'Fortuner 2.8 LTD', 2023, 'SUV', 'Rugged and comfortable 7-seater with strong diesel torque, ideal for provincial roads and long trips.', 3500, 7, 'Automatic', 'Diesel', CarStatus::Available],
            ['Mitsubishi', 'Montero Sport GLS', 2022, 'SUV', 'Smooth, well-equipped SUV with generous cargo space and a quiet ride.', 3200, 7, 'Automatic', 'Diesel', CarStatus::Rented],
            ['Honda', 'Civic RS Turbo', 2023, 'Sedan', 'Sporty turbocharged sedan with excellent fuel economy. Great for city driving and business trips.', 2800, 5, 'Automatic', 'Gasoline', CarStatus::Available],
            ['Toyota', 'Vios XLE', 2023, 'Sedan', 'Reliable, economical and easy to park. The practical choice for daily rentals.', 1800, 5, 'Automatic', 'Gasoline', CarStatus::Available],
            ['Mercedes-Benz', 'E-Class E300', 2022, 'Luxury', 'Executive comfort with a refined leather interior and advanced driver assistance.', 7500, 5, 'Automatic', 'Gasoline', CarStatus::Available],
            ['Lexus', 'LM 350', 2023, 'Luxury', 'Ultra-premium luxury van with lounge-style seating. Perfect for VIP transfers and weddings.', 9500, 4, 'Automatic', 'Hybrid', CarStatus::Maintenance],
            ['Toyota', 'Innova 2.8 V', 2023, 'Family', 'Spacious, dependable family hauler with rear air-con vents and room for luggage.', 2600, 7, 'Automatic', 'Diesel', CarStatus::Available],
            ['Toyota', 'Hiace Commuter Deluxe', 2022, 'Family', 'Fits big groups and reunions comfortably, with ample luggage space.', 4000, 15, 'Manual', 'Diesel', CarStatus::Available],
            ['Ford', 'Ranger Raptor', 2023, 'Adventure', 'Off-road ready pickup with long-travel suspension. Built for river crossings and mountain trails.', 4800, 5, 'Automatic', 'Diesel', CarStatus::Available],
            ['Suzuki', 'Jimny GLX', 2023, 'Adventure', 'Compact 4x4 with go-anywhere ability. Small, tough and made for island and mountain adventures.', 3000, 4, 'Manual', 'Gasoline', CarStatus::Available],
        ];

        foreach ($cars as [$brand, $model, $year, $category, $description, $price, $seats, $transmission, $fuel, $status]) {
            Car::updateOrCreate(
                ['brand' => $brand, 'model' => $model],
                [
                    'year' => $year,
                    'category' => $category,
                    'description' => $description,
                    'price_per_day' => $price,
                    'seats' => $seats,
                    'transmission' => $transmission,
                    'fuel_type' => $fuel,
                    // Development placeholder. Swap for a storage path (e.g. "cars/bmw-m4.jpg") later.
                    'image' => 'https://placehold.co/800x500/0f172a/f97316?text='.urlencode("$brand $model"),
                    'status' => $status,
                ],
            );
        }
    }
}
