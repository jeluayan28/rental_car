<?php

namespace App\Http\Controllers;

use App\Enums\CarStatus;
use App\Models\Car;
use Illuminate\View\View;

class HomeController extends Controller
{
    /** Categories shown in the landing page car selector, in display order. */
    private const SELECTOR_CATEGORIES = ['Sport', 'SUV', 'Sedan', 'Luxury', 'Adventure'];

    public function index(): View
    {
        $cars = Car::query()
            ->where('status', CarStatus::Available)
            ->orderBy('category')
            ->orderByDesc('price_per_day')
            ->get();

        // One headline car per category is "featured"; picking a vibe reveals the rest.
        $featuredIds = $cars->unique('category')->pluck('id')->all();

        // The selector shows the top-priced available car of each category.
        $showcase = collect(self::SELECTOR_CATEGORIES)
            ->map(fn (string $category) => $cars->firstWhere('category', $category))
            ->filter()
            ->map(fn (Car $car) => [
                'id' => $car->id,
                'name' => "{$car->brand} {$car->model}",
                'year' => $car->year,
                'category' => $car->category,
                'seats' => $car->seats,
                'transmission' => $car->transmission,
                'fuel' => $car->fuel_type,
                'price' => (int) $car->price_per_day,
                'image' => $car->image_url,
            ])
            ->values();

        return view('home', compact('cars', 'featuredIds', 'showcase'));
    }
}
