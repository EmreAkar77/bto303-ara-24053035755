<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    protected $model = Rental::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::today()->addDays(fake()->numberBetween(-30, 10));
        $days = fake()->numberBetween(1, 10);
        $endDate = (clone $startDate)->addDays($days);
        $dailyRate = fake()->numberBetween(1000, 3000);
        $totalPrice = $dailyRate * $days;

        return [
            'vehicle_id' => Vehicle::factory(),
            'customer_id' => Customer::factory(),
            'pickup_branch_id' => Branch::factory(),
            'return_branch_id' => Branch::factory(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_price' => $totalPrice,
            'status' => fake()->randomElement(['active', 'completed', 'cancelled']),
            'notes' => fake()->boolean(60) ? fake()->sentence() : null,
        ];
    }
}
