<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cars = [
            ['brand' => 'Renault', 'model' => 'Clio', 'rate' => 1200],
            ['brand' => 'Renault', 'model' => 'Megane', 'rate' => 1600],
            ['brand' => 'Fiat', 'model' => 'Egea', 'rate' => 1100],
            ['brand' => 'Toyota', 'model' => 'Corolla', 'rate' => 1750],
            ['brand' => 'Volkswagen', 'model' => 'Golf', 'rate' => 2000],
            ['brand' => 'Volkswagen', 'model' => 'Passat', 'rate' => 2400],
            ['brand' => 'Ford', 'model' => 'Focus', 'rate' => 1700],
            ['brand' => 'Hyundai', 'model' => 'i20', 'rate' => 1150],
            ['brand' => 'Peugeot', 'model' => '3008', 'rate' => 2600],
            ['brand' => 'BMW', 'model' => '320i', 'rate' => 3800],
            ['brand' => 'Mercedes-Benz', 'model' => 'C200', 'rate' => 4200],
            ['brand' => 'Audi', 'model' => 'A3', 'rate' => 3200],
        ];

        $car = fake()->randomElement($cars);
        $cities = ['34', '06', '35', '07', '16', '01'];
        $city = fake()->randomElement($cities);
        $plate = "{$city} ".strtoupper(fake()->lexify('???')).' '.fake()->numerify('###');

        return [
            'branch_id' => Branch::factory(),
            'plate_number' => $plate,
            'brand' => $car['brand'],
            'model' => $car['model'],
            'model_year' => fake()->numberBetween(2020, 2024),
            'daily_rate' => $car['rate'],
            'status' => fake()->randomElement(['available', 'rented', 'maintenance']),
        ];
    }
}
