<?php

namespace Database\Factories;

use App\Models\Box;
use App\Models\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BoxDetail>
 */
class BoxDetailFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'box_id' => Box::factory(),
            'goods_id' => Goods::factory(),
            'amount' => fake()->numberBetween(-1,  10),
        ];
    }
}