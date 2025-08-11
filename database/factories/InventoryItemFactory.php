<?php
namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InventoryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'         => $this->faker->word,
            'sku'          => $this->faker->unique()->word,
            'description'  => $this->faker->sentence,
            'price'        => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
