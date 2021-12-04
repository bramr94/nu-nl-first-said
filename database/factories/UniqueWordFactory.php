<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UniqueWordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'value' => $this->faker->word,
            'occurrences' => 1,
            'should_be_tweeted' => true,
            'tweeted' => false,
        ];
    }
}
