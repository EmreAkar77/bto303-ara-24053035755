<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Rental;
use App\Models\Vehicle;

class FleetStatistics
{
    /**
     * Compute fleet statistics for a given date range and optional branch.
     *
     * Ensures every vehicle belongs to exactly one mutually exclusive group:
     * 1. Maintenance (status === 'maintenance')
     * 2. Rented (has overlapping non-cancelled rental)
     * 3. Idle / Available (remaining)
     *
     * Total vehicles === Maintenance + Rented + Idle.
     */
    public function getStatistics(string $startDate, string $endDate, ?int $branchId = null): array
    {
        // 1. Verimli tekil sorgu: Araçlar, bağlı şube ve aralıkla çakışan iptal edilmemiş kiralamalar
        $vehiclesQuery = Vehicle::with([
            'branch',
            'rentals' => function ($q) use ($startDate, $endDate) {
                $q->where('status', '!=', 'cancelled')
                    ->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate)
                    ->with('customer');
            },
        ]);

        if ($branchId) {
            $vehiclesQuery->where('branch_id', $branchId);
        }

        $allVehicles = $vehiclesQuery->orderBy('brand')->get();

        $maintenanceVehicles = collect();
        $rentedVehicles = collect();
        $idleVehicles = collect();

        foreach ($allVehicles as $vehicle) {
            if ($vehicle->status === 'maintenance') {
                $maintenanceVehicles->push($vehicle);
            } elseif ($vehicle->rentals->isNotEmpty()) {
                // Aktif çakışan ilk kiralama kaydını araç nesnesine iliştir
                $vehicle->active_rental = $vehicle->rentals->first();
                $rentedVehicles->push($vehicle);
            } else {
                $idleVehicles->push($vehicle);
            }
        }

        $totalVehicles = $allVehicles->count();
        $maintenanceCount = $maintenanceVehicles->count();
        $rentedCount = $rentedVehicles->count();
        $idleCount = $idleVehicles->count();

        // Matematiksel doğrulama: toplam her zaman üçünün toplamına eşittir
        $occupancyRate = $totalVehicles > 0 ? round(($rentedCount / $totalVehicles) * 100, 1) : 0.0;

        // 2. Aralıktaki kiralamalar ve mali özet
        $rentalsQuery = Rental::where(function ($q) use ($startDate, $endDate) {
            $q->where('start_date', '<=', $endDate)
                ->where('end_date', '>=', $startDate);
        });

        if ($branchId) {
            $rentalsQuery->where(function ($q) use ($branchId) {
                $q->where('pickup_branch_id', $branchId)
                    ->orWhere('return_branch_id', $branchId);
            });
        }

        $allRentals = $rentalsQuery->get();
        $nonCancelledRentals = $allRentals->where('status', '!=', 'cancelled');
        $cancelledRentals = $allRentals->where('status', '===', 'cancelled');

        $rentalsCount = $nonCancelledRentals->count();
        $totalRevenue = (float) $nonCancelledRentals->sum('total_price');
        $cancelledRentalsCount = $cancelledRentals->count();

        // 3. Şube Bazlı Dağılım Tablosu (N+1 engellenerek collection üzerinden hesaplanır)
        $branches = Branch::orderBy('name')->get();
        if ($branchId) {
            $branches = $branches->where('id', $branchId);
        }

        $branchStats = [];
        foreach ($branches as $branch) {
            $bVehicles = $allVehicles->where('branch_id', $branch->id);
            $bTotal = $bVehicles->count();
            $bMaintenance = $bVehicles->where('status', 'maintenance')->count();
            $bRented = $bVehicles->where('status', '!=', 'maintenance')->filter(fn ($v) => $v->rentals->isNotEmpty())->count();
            $bIdle = max(0, $bTotal - $bMaintenance - $bRented);
            $bOccupancy = $bTotal > 0 ? round(($bRented / $bTotal) * 100, 1) : 0.0;

            $branchStats[] = [
                'branch' => $branch,
                'total' => $bTotal,
                'maintenance' => $bMaintenance,
                'rented' => $bRented,
                'idle' => $bIdle,
                'occupancy_rate' => $bOccupancy,
            ];
        }

        return [
            'summary' => [
                'total_vehicles' => $totalVehicles,
                'maintenance_count' => $maintenanceCount,
                'rented_count' => $rentedCount,
                'idle_count' => $idleCount,
                'occupancy_rate' => $occupancyRate,
                'rentals_count' => $rentalsCount,
                'total_revenue' => $totalRevenue,
                'cancelled_rentals_count' => $cancelledRentalsCount,
            ],
            'branch_stats' => $branchStats,
            'maintenance_vehicles' => $maintenanceVehicles,
            'rented_vehicles' => $rentedVehicles,
            'idle_vehicles' => $idleVehicles,
        ];
    }
}
