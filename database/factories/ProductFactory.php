<?php

namespace Database\Factories;

use App\Constants\DiscountType;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'name' => $this->faker->name,
            'slug' => Str::random(16),
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
