<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CarController extends Controller
{
    public function index(): View
    {
        return view('cars.index');
    }
}
