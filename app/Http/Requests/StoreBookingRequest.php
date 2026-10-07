<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // Customers must be logged in (the route is also behind `auth`).
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'pickup_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'return_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:pickup_date'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'pickup_date.required' => 'Please choose a pickup date.',
            'pickup_date.after_or_equal' => 'The pickup date cannot be in the past.',
            'return_date.required' => 'Please choose a return date.',
            'return_date.after_or_equal' => 'The return date cannot be earlier than the pickup date.',
        ];
    }

    /** @return array<int, callable> */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                /** @var Car $car */
                $car = $this->route('car');

                if (! $car->isAvailable()) {
                    $validator->errors()->add('car', 'Sorry, this car is not available for booking.');
                } elseif ($car->hasBookingBetween($this->date('pickup_date'), $this->date('return_date'))) {
                    $validator->errors()->add('pickup_date', 'This car is already booked for some of those dates. Please pick different dates.');
                }
            },
        ];
    }
}
