<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    public function definition(): array
    {
        $categories = Category::all();
        $brands = ['Caterpillar', 'Dell', 'Bosch', 'Honeywell', 'Cisco', 'Zebra', 'Snap-on', 'Komatsu', 'Agilent', 'Herman Miller'];
        $statuses = ['active', 'active', 'active', 'archived', 'checked_out'];

        return [
            'name' => fake()->words(3, true) . ' ' . fake()->numerify('###'),
            'asset_tag' => 'AST-' . date('Y') . '-' . str_pad(fake()->unique()->numberBetween(100, 9999), 4, '0', STR_PAD_LEFT),
            'serial' => strtoupper(fake()->bothify('####-??-###')),
            'category_id' => $categories->random()->id ?? Category::factory(),
            'status' => fake()->randomElement($statuses),
            'brand' => fake()->randomElement($brands),
            'model' => fake()->numerify('Model ###') . ' ' . fake()->randomLetter(),
            'purchase_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'purchase_price' => fake()->randomFloat(2, 100, 50000),
            'supplier' => fake()->company(),
            'location' => 'Warehouse ' . fake()->randomLetter() . ', Bay ' . fake()->numberBetween(1, 20),
            'description' => fake()->sentence(),
            'created_by' => User::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }

    public function archived(): static
    {
        return $this->state(fn () => [
            'status' => 'archived',
            'archived_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'archived_reason' => fake()->randomElement(['Irreparable Damage', 'Decommissioned', 'End of Life Cycle']),
        ]);
    }

    public function checkedOut(): static
    {
        return $this->state(fn () => ['status' => 'checked_out']);
    }
}
