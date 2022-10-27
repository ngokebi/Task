<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$OATm2cQK6NFnl2qWpvg3oOswAU.6oSWDpqvQvTK54uQEdBkliYoWO', // password
            'status' => $this->faker->randomElement([true, false]),
        ];
    }
}
