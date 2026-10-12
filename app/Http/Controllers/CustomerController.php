<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Customer::withCount('rentals');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('driving_license_number', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'driving_license_number' => ['required', 'string', 'max:30', 'unique:customers,driving_license_number'],
        ], [
            'first_name.required' => 'Müşteri adı zorunludur.',
            'last_name.required' => 'Müşteri soyadı zorunludur.',
            'phone.required' => 'Telefon numarası zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.unique' => 'Bu e-posta adresi zaten kayıtlı.',
            'driving_license_number.required' => 'Ehliyet numarası zorunludur.',
            'driving_license_number.unique' => 'Bu ehliyet numarası zaten kayıtlı.',
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', "Müşteri {$customer->full_name} başarıyla sisteme eklendi.");
    }
}
