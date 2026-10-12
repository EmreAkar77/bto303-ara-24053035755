<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Branch;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource with pagination, sorting, search, and status filter.
     */
    public function index(Request $request): View
    {
        $query = Vehicle::with('branch');

        // Metin araması: Marka, model veya plaka
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        // Durum filtresi
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Şube filtresi
        if ($branchId = $request->input('branch_id')) {
            $query->where('branch_id', $branchId);
        }

        // Sıralama
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['brand', 'model_year', 'daily_rate', 'created_at'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $vehicles = $query->paginate(8)->withQueryString();
        $branches = Branch::orderBy('name')->get();

        // Hero banner canlı sayaçları:
        // status yalnızca maintenance için kontrol edilir.
        // Kirada olma durumu doğrudan bugünün kiralama tarihlerinden gelir.
        $today = Carbon::today()->format('Y-m-d');
        $totalVehicles = Vehicle::count();
        $maintenanceCount = Vehicle::where('status', 'maintenance')->count();
        $rentedCount = Vehicle::where('status', '!=', 'maintenance')
            ->whereHas('rentals', function ($q) use ($today) {
                $q->where('status', '!=', 'cancelled')
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today);
            })->count();
        $availableCount = max(0, $totalVehicles - $maintenanceCount - $rentedCount);

        return view('vehicles.index', compact('vehicles', 'branches', 'totalVehicles', 'maintenanceCount', 'rentedCount', 'availableCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $branches = Branch::orderBy('name')->get();

        return view('vehicles.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $vehicle = Vehicle::create($request->validated());

        return redirect()->route('vehicles.index')
            ->with('success', "{$vehicle->plate_number} plakalı araç başarıyla filoya eklendi.");
    }

    /**
     * Display the specified resource with its related rentals.
     */
    public function show(Vehicle $vehicle): View
    {
        $vehicle->load(['branch', 'rentals.customer', 'rentals.pickupBranch', 'rentals.returnBranch']);

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle): View
    {
        $branches = Branch::orderBy('name')->get();

        return view('vehicles.edit', compact('vehicle', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update($request->validated());

        return redirect()->route('vehicles.index')
            ->with('success', "{$vehicle->plate_number} plakalı araç bilgileri güncellendi.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        if ($vehicle->rentals()->exists()) {
            return back()->with('error', 'Bu araca ait geçmiş veya mevcut kiralama kayıtları bulunmaktadır. Sistem bütünlüğü gereği kiralama kaydı olan araçlar silinemez.');
        }

        $plate = $vehicle->plate_number;
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', "{$plate} plakalı araç başarıyla silindi.");
    }
}
