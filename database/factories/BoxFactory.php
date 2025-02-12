<?php

namespace Database\Factories;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Box>
 */
class BoxFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'job_id' => Job::factory(),
            'no_box' => fake()->numberBetween(int2: 100),
            'count' => fake()->numberBetween(1, 5),
            'weight' => fake()->numberBetween(1, 4),
            "length" => mt_rand(1, 100),
            "width" => mt_rand(1, 100),
            "height" => mt_rand(1, 100),
        ];
    }
}
