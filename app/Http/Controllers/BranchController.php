<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(Request $request): View
    {
        $query = Branch::withCount(['vehicles', 'pickupRentals', 'returnRentals']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $branches = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('branches.index', compact('branches'));
    }

    public function create(): View
    {
        return view('branches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'name.required' => 'Şube adı zorunludur.',
            'address.required' => 'Şube adresi zorunludur.',
            'phone.required' => 'Şube telefon numarası zorunludur.',
        ]);

        $branch = Branch::create($validated);

        return redirect()->route('branches.index')
            ->with('success', "{$branch->name} başarıyla sisteme eklendi.");
    }
}
