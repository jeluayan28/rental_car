<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $role = UserRole::tryFrom((string) $request->query('role'));

        $users = User::query()
            ->when($role, fn ($q) => $q->where('role', $role))
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($q) => $q
                ->whereLike('name', "%{$term}%")->orWhereLike('email', "%{$term}%")))
            ->withCount('bookings')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'all' => User::count(),
            'customer' => User::where('role', UserRole::Customer)->count(),
            'admin' => User::where('role', UserRole::Admin)->count(),
        ];

        return view('admin.users.index', compact('users', 'role', 'counts'));
    }
}
