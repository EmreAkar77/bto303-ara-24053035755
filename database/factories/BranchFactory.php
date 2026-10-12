<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $districts = ['Kadıköy', 'Beşiktaş', 'Şişli', 'Bakırköy', 'Üsküdar', 'Çankaya', 'Konak', 'Muratpaşa', 'Nilüfer', 'Karşıyaka', 'Seyhan', 'Bornova'];
        $district = fake()->randomElement($districts);

        return [
            'name' => "{$district} Şubesi",
            'address' => fake()->streetAddress().", {$district}",
            'phone' => '+90 '.fake()->numerify('5## ### ## ##'),
        ];
    }
}
