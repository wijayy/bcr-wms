<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            // "supplier_id" => Supplier::factory(),
            "shipment_id" => Shipment::factory(),
            "no_job" => fake()->numberBetween(0, 9999999),
            "destination" => fake()->address(),
            "shipping_date" => fake()->date()
        ];
    }
}