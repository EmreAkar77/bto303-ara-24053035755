<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalRequest;
use App\Http\Requests\UpdateRentalRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RentalController extends Controller
{
    /**
     * Display a listing of the resource with pagination, sorting, search, and status filter.
     */
    public function index(Request $request): View
    {
        $query = Rental::with(['vehicle', 'customer', 'pickupBranch', 'returnBranch']);

        // Durum filtresi
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Araç filtresi
        if ($vehicleId = $request->input('vehicle_id')) {
            $query->where('vehicle_id', $vehicleId);
        }

        // Arama (Müşteri adı/soyadı veya Araç plakası)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })->orWhereHas('vehicle', function ($vq) use ($search) {
                    $vq->where('plate_number', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%");
                });
            });
        }

        // Sıralama
        $sort = $request->input('sort', 'start_date');
        $direction = $request->input('direction', 'desc');
        $allowedSorts = ['start_date', 'end_date', 'total_price', 'created_at'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest('start_date');
        }

        $rentals = $query->paginate(8)->withQueryString();
        $vehicles = Vehicle::orderBy('brand')->get();

        return view('rentals.index', compact('rentals', 'vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $selectedVehicleId = $request->query('vehicle_id');
        $vehicles = Vehicle::where('status', '!=', 'maintenance')->orderBy('brand')->get();
        $customers = Customer::orderBy('first_name')->get();
        $branches = Branch::orderBy('name')->get();

        return view('rentals.create', compact('vehicles', 'customers', 'branches', 'selectedVehicleId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRentalRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);

        // Sunucu taraflı toplam fiyat hesabı
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $days = max(1, $start->diffInDays($end));
        $data['total_price'] = $days * $vehicle->daily_rate;

        $rental = Rental::create($data);

        // Araç durumunu güncelle (Bugün veya geçmişte başlayıp hala aktifse rented yap)
        if ($data['status'] === 'active' && $start->lte(Carbon::today()) && $end->gte(Carbon::today())) {
            $vehicle->update(['status' => 'rented']);
        }

        return redirect()->route('rentals.index')
            ->with('success', "Kiralama sözleşmesi (#{$rental->id}) başarıyla oluşturuldu. Toplam Tutar: ".number_format($rental->total_price, 2, ',', '.').' TL');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rental $rental): View
    {
        $rental->load(['vehicle.branch', 'customer', 'pickupBranch', 'returnBranch']);

        return view('rentals.show', compact('rental'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rental $rental): View
    {
        $vehicles = Vehicle::orderBy('brand')->get();
        $customers = Customer::orderBy('first_name')->get();
        $branches = Branch::orderBy('name')->get();

        return view('rentals.edit', compact('rental', 'vehicles', 'customers', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRentalRequest $request, Rental $rental): RedirectResponse
    {
        $data = $request->validated();
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);

        // Fiyat yeniden hesaplanır
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $days = max(1, $start->diffInDays($end));
        $data['total_price'] = $days * $vehicle->daily_rate;

        $rental->update($data);

        // Kiralama tamamlandıysa veya iptal edildiyse aracın durumunu müsait yap (başka aktif kiralama yoksa)
        if (in_array($data['status'], ['completed', 'cancelled'])) {
            $hasOtherActive = Rental::where('vehicle_id', $vehicle->id)
                ->where('id', '!=', $rental->id)
                ->where('status', 'active')
                ->where('start_date', '<=', Carbon::today())
                ->where('end_date', '>=', Carbon::today())
                ->exists();

            if (! $hasOtherActive && $vehicle->status === 'rented') {
                $vehicle->update(['status' => 'available']);
            }
        }

        return redirect()->route('rentals.index')
            ->with('success', "Kiralama kaydı (#{$rental->id}) başarıyla güncellendi.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rental $rental): RedirectResponse
    {
        $id = $rental->id;
        $vehicle = $rental->vehicle;

        $rental->delete();

        // Eğer araç rented ise ve başka aktif kiralama kalmadıysa available yap
        if ($vehicle && $vehicle->status === 'rented') {
            $hasActive = Rental::where('vehicle_id', $vehicle->id)
                ->where('status', 'active')
                ->where('start_date', '<=', Carbon::today())
                ->where('end_date', '>=', Carbon::today())
                ->exists();

            if (! $hasActive) {
                $vehicle->update(['status' => 'available']);
            }
        }

        return redirect()->route('rentals.index')
            ->with('success', "Kiralama kaydı (#{$id}) silindi.");
    }
}
