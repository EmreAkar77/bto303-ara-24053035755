<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: Araç listeleme, sayfalama ve arama çalışır.
     */
    public function test_vehicle_index_lists_vehicles_with_pagination(): void
    {
        $branch = Branch::factory()->create();
        Vehicle::factory()->count(10)->create(['branch_id' => $branch->id]);

        $response = $this->get(route('vehicles.index'));

        $response->assertStatus(200);
        $response->assertViewHas('vehicles');
    }

    /**
     * Test 2: Araç CRUD - Geçerli verilerle yeni araç oluşturulabilir.
     */
    public function test_vehicle_can_be_created_with_valid_data(): void
    {
        $branch = Branch::factory()->create();

        $vehicleData = [
            'branch_id' => $branch->id,
            'plate_number' => '34 TEST 999',
            'brand' => 'Toyota',
            'model' => 'Yaris',
            'model_year' => 2023,
            'daily_rate' => 1400.00,
            'status' => 'available',
        ];

        $response = $this->post(route('vehicles.store'), $vehicleData);

        $response->assertStatus(302);
        $response->assertRedirect(route('vehicles.index'));
        $this->assertDatabaseHas('vehicles', [
            'plate_number' => '34 TEST 999',
            'brand' => 'Toyota',
        ]);
    }

    /**
     * Test 3: Araç Doğrulama Hatası - Eksik alanlar ve mükerrer plaka assertSessionHasErrors ile doğrulanır.
     */
    public function test_vehicle_store_validation_errors_asserted_with_session(): void
    {
        $branch = Branch::factory()->create();
        Vehicle::factory()->create([
            'branch_id' => $branch->id,
            'plate_number' => '34 VAR 123',
        ]);

        // Eksik alanlar ve mükerrer plaka gönderimi
        $response = $this->post(route('vehicles.store'), [
            'plate_number' => '34 VAR 123', // Mükerrer
            'brand' => '',                  // Zorunlu alan boş
            'daily_rate' => -50,            // Geçersiz negatif tutar
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['plate_number', 'brand', 'daily_rate', 'branch_id', 'model', 'model_year', 'status']);
    }

    /**
     * Test 4: Araç CRUD - Araç bilgileri güncellenebilir.
     */
    public function test_vehicle_can_be_updated_successfully(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'branch_id' => $branch->id,
            'daily_rate' => 1200.00,
        ]);

        $updateData = [
            'branch_id' => $branch->id,
            'plate_number' => $vehicle->plate_number,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'model_year' => $vehicle->model_year,
            'daily_rate' => 1800.00,
            'status' => 'maintenance',
        ];

        $response = $this->put(route('vehicles.update', $vehicle), $updateData);

        $response->assertStatus(302);
        $response->assertRedirect(route('vehicles.index'));
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'daily_rate' => 1800.00,
            'status' => 'maintenance',
        ]);
    }

    /**
     * Test 5: Araç CRUD - Aktif kiralaması olmayan araç başarıyla silinebilir.
     */
    public function test_vehicle_can_be_deleted_when_no_active_rental_exists(): void
    {
        $branch = Branch::factory()->create();
        $vehicle = Vehicle::factory()->create(['branch_id' => $branch->id]);

        $response = $this->delete(route('vehicles.destroy', $vehicle));

        $response->assertStatus(302);
        $response->assertRedirect(route('vehicles.index'));
        $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
    }
}
