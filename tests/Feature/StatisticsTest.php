<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * İstatistik sayfasında bakımda + kirada + boşta toplamı kesinlikle toplam araç sayısına eşittir.
     */
    public function test_statistics_maintenance_plus_rented_plus_idle_equals_total_vehicles(): void
    {
        $branch = Branch::factory()->create();

        // 2 Bakımda araç
        Vehicle::factory()->count(2)->create([
            'branch_id' => $branch->id,
            'status' => 'maintenance',
        ]);

        // 3 Kirada olacak araç
        $rentedCars = Vehicle::factory()->count(3)->create([
            'branch_id' => $branch->id,
            'status' => 'available',
        ]);

        $customer = Customer::factory()->create();
        foreach ($rentedCars as $car) {
            Rental::factory()->create([
                'vehicle_id' => $car->id,
                'customer_id' => $customer->id,
                'pickup_branch_id' => $branch->id,
                'return_branch_id' => $branch->id,
                'start_date' => '2026-10-10',
                'end_date' => '2026-10-20',
                'status' => 'active',
                'total_price' => 2000.00,
            ]);
        }

        // 4 Boşta (müsait) araç
        Vehicle::factory()->count(4)->create([
            'branch_id' => $branch->id,
            'status' => 'available',
        ]);

        // Toplam 2 + 3 + 4 = 9 araç

        $response = $this->get(route('statistics.index', [
            'start_date' => '2026-10-12',
            'end_date' => '2026-10-18',
        ]));

        $response->assertStatus(200);
        $stats = $response->viewData('stats');

        $total = $stats['summary']['total_vehicles'];
        $maintenance = $stats['summary']['maintenance_count'];
        $rented = $stats['summary']['rented_count'];
        $idle = $stats['summary']['idle_count'];

        // Eşitlik doğrulaması:
        $this->assertEquals(9, $total);
        $this->assertEquals(2, $maintenance);
        $this->assertEquals(3, $rented);
        $this->assertEquals(4, $idle);
        $this->assertEquals($total, $maintenance + $rented + $idle);
    }

    /**
     * İstatistik özet sayıları bilinen factory verisiyle tam eşleşir (ciro, sözleşme, iptal).
     */
    public function test_statistics_counts_and_revenue_match_known_factory_data(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create(['branch_id' => $branch->id, 'status' => 'available']);
        $customer = Customer::factory()->create();

        // 1 Adet Aktif kiralama: 3000 TL
        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-15',
            'status' => 'active',
            'total_price' => 3000.00,
        ]);

        // 1 Adet İptal kiralama: 1500 TL
        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-12',
            'end_date' => '2026-10-16',
            'status' => 'cancelled',
            'total_price' => 1500.00,
        ]);

        $response = $this->get(route('statistics.index', [
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-31',
        ]));

        $response->assertStatus(200);
        $stats = $response->viewData('stats');

        // İptal edilen hariç aktif kiralama sayısı = 1
        $this->assertEquals(1, $stats['summary']['rentals_count']);
        // Ciro (iptal edilen hariç) = 3000.00 TL
        $this->assertEquals(3000.00, $stats['summary']['total_revenue']);
        // İptal edilen sayısı = 1
        $this->assertEquals(1, $stats['summary']['cancelled_rentals_count']);
    }

    /**
     * İstatistik sayfasında geçersiz tarih (bitiş < başlangıç) assertSessionHasErrors verir.
     */
    public function test_statistics_invalid_date_range_asserts_session_has_errors(): void
    {
        $response = $this->get(route('statistics.index', [
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-10', // Hatalı!
        ]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['end_date']);
    }
}
