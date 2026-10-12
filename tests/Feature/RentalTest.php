<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 6: Teslim tarihi başlangıç tarihinden önce olamaz (assertSessionHasErrors).
     */
    public function test_rental_cannot_have_end_date_before_start_date_using_session_errors(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create(['branch_id' => $branch->id, 'status' => 'available']);
        $customer = Customer::factory()->create();

        $data = [
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-15', // Başlangıçtan önce!
            'status' => 'active',
        ];

        $response = $this->post(route('rentals.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['end_date']);
        $this->assertDatabaseMissing('rentals', [
            'vehicle_id' => $vehicle->id,
            'start_date' => '2026-10-20',
        ]);
    }

    /**
     * Test 7: Çakışma Engeli - Aynı araca aktif kiralamayla çakışan tarihte yeni kiralama açılamaz.
     */
    public function test_active_rental_blocks_overlapping_dates_for_same_vehicle(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create(['branch_id' => $branch->id, 'status' => 'available']);
        $customer = Customer::factory()->create();

        // 10 Ekim - 20 Ekim arası aktif kiralama mevcut
        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-20',
            'status' => 'active',
        ]);

        // Çakışan aralık: 15 Ekim - 25 Ekim
        $conflictingData = [
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-15',
            'end_date' => '2026-10-25',
            'status' => 'active',
        ];

        $response = $this->post(route('rentals.store'), $conflictingData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['start_date']);
    }

    /**
     * Test 8: İptal Edilenin Engel Olmaması - Çakışan tarihteki kiralama 'cancelled' ise yeni kiralama yapılabilir.
     */
    public function test_cancelled_rental_does_not_block_overlapping_dates(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create(['branch_id' => $branch->id, 'status' => 'available']);
        $customer = Customer::factory()->create();

        // İptal edilmiş rezervasyon
        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-20',
            'status' => 'cancelled', // İptal!
        ]);

        // Aynı tarihlerde yeni kiralama deneniyor
        $newData = [
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-12',
            'end_date' => '2026-10-18',
            'status' => 'active',
        ];

        $response = $this->post(route('rentals.store'), $newData);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('rentals', [
            'vehicle_id' => $vehicle->id,
            'status' => 'active',
        ]);
        $createdRental = Rental::where('vehicle_id', $vehicle->id)->where('status', 'active')->first();
        $this->assertEquals('2026-10-12', $createdRental->start_date->format('Y-m-d'));
    }

    /**
     * Test 9: Güncellemede Kendi Kaydının Sayılmaması - Kiralama güncellenirken kendi tarih aralığı çakışma sayılmaz.
     */
    public function test_rental_update_ignores_current_record_in_date_conflict_check(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create(['branch_id' => $branch->id, 'status' => 'available']);
        $customer = Customer::factory()->create();

        $rental = Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ]);

        // Kendi tarihini 1 gün uzatarak güncelliyoruz (10 Ekim - 16 Ekim)
        $updateData = [
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-16',
            'status' => 'active',
        ];

        $response = $this->put(route('rentals.update', $rental), $updateData);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();
        $this->assertEquals('2026-10-16', $rental->fresh()->end_date->format('Y-m-d'));
    }

    /**
     * Test 10: Bakımdaki Araç Kuralı - status: maintenance olan araca yeni kiralama açılamaz.
     */
    public function test_vehicle_under_maintenance_cannot_be_rented(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'branch_id' => $branch->id,
            'status' => 'maintenance', // Bakımda!
        ]);
        $customer = Customer::factory()->create();

        $data = [
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-15',
            'status' => 'active',
        ];

        $response = $this->post(route('rentals.store'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['vehicle_id']);
    }

    /**
     * Test 11: Toplam Tutarın Sunucuda Hesaplanması - (Gün Sayısı * Günlük Ücret) sunucu tarafında doğrulanır.
     */
    public function test_rental_total_price_is_calculated_automatically_on_server_side(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'branch_id' => $branch->id,
            'daily_rate' => 1500.00,
            'status' => 'available',
        ]);
        $customer = Customer::factory()->create();

        // 4 Günlük kiralama (10 Ekim - 14 Ekim)
        $data = [
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'pickup_branch_id' => $branch->id,
            'return_branch_id' => $branch->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-14',
            'status' => 'active',
        ];

        $response = $this->post(route('rentals.store'), $data);

        $response->assertStatus(302);

        // Beklenen tutar: 4 gün * 1500 TL = 6000.00 TL
        $this->assertDatabaseHas('rentals', [
            'vehicle_id' => $vehicle->id,
            'total_price' => 6000.00,
        ]);
    }

    /**
     * Test 12: Kiralama CRUD - Kiralama kaydı başarıyla silinebilir.
     */
    public function test_rental_can_be_deleted_successfully(): void
    {
        $rental = Rental::factory()->create();

        $response = $this->delete(route('rentals.destroy', $rental));

        $response->assertStatus(302);
        $response->assertRedirect(route('rentals.index'));
        $this->assertDatabaseMissing('rentals', ['id' => $rental->id]);
    }
}
