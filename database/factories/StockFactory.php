<?php

namespace Database\Factories;

use App\Models\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stock>
 */
class StockFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        // $type = ['depart', 'stock',];
        $stock = mt_rand(1, 99);
        return [
            "goods_id" => Goods::factory(),
            "amount" => $stock,
            "note" => "note/note.png",
            "stock" => $stock,
            "type" => 'stock',
            // "weight" => fake(),
            "desc" => fake()->paragraph(1, false)
        ];
    }
}
