<?php

namespace App\Http\Requests\Admin;

use App\Enums\CarStatus;
use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'brand' => ['required', 'string', 'max:60'],
            'model' => ['required', 'string', 'max:80'],
            'year' => ['required', 'integer', 'between:1990,'.(now()->year + 1)],
            'category' => ['required', Rule::in(Car::CATEGORIES)],
            'description' => ['nullable', 'string', 'max:2000'],
            'price_per_day' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'seats' => ['required', 'integer', 'between:1,20'],
            'transmission' => ['required', Rule::in(Car::TRANSMISSIONS)],
            'fuel_type' => ['required', Rule::in(Car::FUEL_TYPES)],
            'status' => ['required', Rule::enum(CarStatus::class)],
            // Either upload a file or paste an external URL.
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_url' => ['nullable', 'url:http,https', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return ['price_per_day' => 'price per day', 'fuel_type' => 'fuel type', 'image_file' => 'image', 'image_url' => 'image URL'];
    }
}
