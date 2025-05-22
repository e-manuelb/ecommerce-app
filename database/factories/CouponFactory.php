<?php

namespace Database\Factories;

use App\Constants\DiscountTypes;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid()->toString(),
            'code' => Str::random(10),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'discount_type' => $this->faker->randomElement(DiscountTypes::ALL),
            'min_subtotal_to_apply' => $this->faker->randomFloat(2, 0, 100),
            'expires_at' => $this->faker->dateTimeBetween('-1 year'),
            'active' => $this->faker->boolean,
        ];
    }
}
