<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            "shipment_id" => Shipment::factory(),
            "name" => fake()->sentence(3, false),
            "phone" => fake()->phoneNumber(),
            "address" => fake()->address(),
        ];
    }
}