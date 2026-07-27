<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'slug' => \Illuminate\Support\Str::slug($name),
            'icon' => fake()->randomElement(['laptop_mac', 'precision_manufacturing', 'local_shipping', 'build', 'router', 'biotech', 'chair']),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
