<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 14.10'da biten kiralaması olan araç 15.10 aramasında müsait, 14.10 aramasında değil.
     */
    public function test_vehicle_ending_on_14th_is_available_on_15th_but_not_on_14th(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'branch_id' => $branch->id,
            'status' => 'available',
        ]);

        // 10.10.2026 - 14.10.2026 arası aktif kiralama
        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-14',
            'status' => 'active',
        ]);

        // Arama 1: 14.10.2026 - 18.10.2026 (14'ünde halen kirada, MÜSAİT OLMAMALI)
        $response1 = $this->get(route('availability.index', [
            'start_date' => '2026-10-14',
            'end_date' => '2026-10-18',
        ]));
        $response1->assertStatus(200);
        $vehicles1 = $response1->viewData('vehicles');
        $this->assertFalse($vehicles1->contains('id', $vehicle->id));

        // Arama 2: 15.10.2026 - 18.10.2026 (14'ünde bittiği için 15'inde MÜSAİT OLMALI)
        $response2 = $this->get(route('availability.index', [
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-18',
        ]));
        $response2->assertStatus(200);
        $vehicles2 = $response2->viewData('vehicles');
        $this->assertTrue($vehicles2->contains('id', $vehicle->id));
    }

    /**
     * İptal edilmiş kiralaması olan araç aynı tarihlerde müsait görünür.
     */
    public function test_vehicle_with_cancelled_rental_is_available_for_same_dates(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'branch_id' => $branch->id,
            'status' => 'available',
        ]);

        // İptal edilmiş rezervasyon
        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-25',
            'status' => 'cancelled',
        ]);

        $response = $this->get(route('availability.index', [
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-25',
        ]));

        $response->assertStatus(200);
        $vehicles = $response->viewData('vehicles');
        $this->assertTrue($vehicles->contains('id', $vehicle->id));
    }

    /**
     * Bakımdaki araç aramada görünmez.
     */
    public function test_vehicle_under_maintenance_does_not_appear_in_availability_search(): void
    {
        $branch = Branch::factory()->create();
        $maintenanceVehicle = Vehicle::factory()->create([
            'branch_id' => $branch->id,
            'status' => 'maintenance',
        ]);

        $response = $this->get(route('availability.index', [
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-25',
        ]));

        $response->assertStatus(200);
        $vehicles = $response->viewData('vehicles');
        $this->assertFalse($vehicles->contains('id', $maintenanceVehicle->id));
    }

    /**
     * Şube filtresi sadece o şubenin araçlarını getirir.
     */
    public function test_branch_filter_only_returns_vehicles_of_that_branch(): void
    {
        $branchA = Branch::factory()->create(['name' => 'Kadıköy Şubesi']);
        $branchB = Branch::factory()->create(['name' => 'Beşiktaş Şubesi']);

        $vehicleA = Vehicle::factory()->create(['branch_id' => $branchA->id, 'status' => 'available']);
        $vehicleB = Vehicle::factory()->create(['branch_id' => $branchB->id, 'status' => 'available']);

        $response = $this->get(route('availability.index', [
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-25',
            'branch_id' => $branchA->id,
        ]));

        $response->assertStatus(200);
        $vehicles = $response->viewData('vehicles');
        $this->assertTrue($vehicles->contains('id', $vehicleA->id));
        $this->assertFalse($vehicles->contains('id', $vehicleB->id));
    }

    /**
     * Geçersiz tarih (bitiş < başlangıç) assertSessionHasErrors ile hata verir.
     */
    public function test_invalid_dates_assert_session_has_errors(): void
    {
        $response = $this->get(route('availability.index', [
            'start_date' => '2026-10-25',
            'end_date' => '2026-10-20', // Bitiş başlangıçtan önce!
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['end_date']);
    }

    /**
     * Alış tarihi geçmişte ise assertSessionHasErrors ile hata verir.
     */
    public function test_past_start_date_asserts_session_has_errors(): void
    {
        $pastDate = Carbon::yesterday()->format('Y-m-d');

        $response = $this->get(route('availability.index', [
            'start_date' => $pastDate,
            'end_date' => Carbon::tomorrow()->format('Y-m-d'),
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['start_date']);
    }
}
