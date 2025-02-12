<?php

namespace Database\Factories;

use App\Models\Box;
use App\Models\Shipment;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goods>
 */
class GoodsFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $unit = ['pcs', 'set', 'unit', 'prs'];
        return [
            "supplier_id" => Supplier::factory(),
            "code" => "gd" . mt_rand(1, 99999),
            "image" => "goods/goods.jpg",
            "material" => "wood",
            "desc" => fake()->paragraph(1, false),
            "weight" => mt_rand(1, 50000),
            "stock" => mt_rand(1, 99),
            "us_price" => mt_rand(1, 5000),
            "id_price" => mt_rand(1, 10000000),
            "unit" => $unit[mt_rand(0, 3)],
        ];
    }
}
