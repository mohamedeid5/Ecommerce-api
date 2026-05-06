<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name'        => ucfirst($name),
            'slug'        => Str::slug($name),
            'description' => fake()->optional()->paragraph(),
            'price'       => fake()->randomFloat(2, 5, 1000),
            'stock'       => fake()->numberBetween(0, 500),
            'is_active'   => fake()->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }
}
