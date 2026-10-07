<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CarStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarRequest;
use App\Models\Car;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CarController extends Controller
{
    public function index(Request $request): View
    {
        $status = CarStatus::tryFrom((string) $request->query('status'));

        $cars = Car::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->whereLike('brand', "%{$term}%")->orWhereLike('model', "%{$term}%")))
            ->withCount('bookings')
            ->orderBy('brand')->orderBy('model')
            ->paginate(10)
            ->withQueryString();

        return view('admin.cars.index', compact('cars', 'status'));
    }

    public function create(): View
    {
        return view('admin.cars.form', ['car' => new Car(['status' => CarStatus::Available, 'year' => now()->year, 'seats' => 5])]);
    }

    public function store(CarRequest $request): RedirectResponse
    {
        $car = Car::create($this->attributes($request));

        return redirect()->route('admin.cars.show', $car)->with('status', "{$car->brand} {$car->model} was added.");
    }

    public function show(Car $car): View
    {
        $bookings = $car->bookings()->with('user')->latest('pickup_date')->limit(8)->get();

        return view('admin.cars.show', compact('car', 'bookings'));
    }

    public function edit(Car $car): View
    {
        return view('admin.cars.form', compact('car'));
    }

    public function update(CarRequest $request, Car $car): RedirectResponse
    {
        $oldImage = $car->hasStoredImage() ? $car->image : null;

        $car->update($this->attributes($request, $car));

        // Remove the previous upload if it was replaced.
        if ($oldImage && $oldImage !== $car->image) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.cars.show', $car)->with('status', 'Car updated.');
    }

    public function updateStatus(Request $request, Car $car): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::enum(CarStatus::class)]]);
        $car->update($data);

        return back()->with('status', "{$car->brand} {$car->model} is now {$data['status']}.");
    }

    public function destroy(Car $car): RedirectResponse
    {
        // Bookings keep a reference to the car (history), so a booked car can't be deleted.
        if ($car->bookings()->exists()) {
            return back()->with('error', "{$car->brand} {$car->model} has bookings and can't be deleted. Set it to maintenance to take it off the site instead.");
        }

        if ($car->hasStoredImage()) {
            Storage::disk('public')->delete($car->image);
        }
        $car->delete();

        return redirect()->route('admin.cars.index')->with('status', 'Car deleted.');
    }

    /** @return array<string, mixed> */
    private function attributes(CarRequest $request, ?Car $car = null): array
    {
        $data = $request->safe()->except(['image_file', 'image_url']);

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('cars', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        } elseif ($car) {
            $data['image'] = $car->image; // keep what's there
        }

        return $data;
    }
}
