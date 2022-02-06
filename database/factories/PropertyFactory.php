<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = 'The ' . $this->faker->lastName() . ' House';
        $slug = Str::slug($title);
        return [
            'title' => $title,
            'slug' => $slug,
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(10000, 60000),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'toilets' => $this->faker->numberBetween(1, 3),
            'parking_lots' => $this->faker->numberBetween(1, 2),
            'location' => $this->faker->address(),
            'term_duration' => $this->faker->numberBetween(2, 12) . ' months',
            'is_active' => $this->faker->boolean(),
        ];
    }
}
