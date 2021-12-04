<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class ArticleFactory
 *
 * @author Bram Raaijmakers
 *
 * @package Database\Factories
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'article_id' => $this->faker->numberBetween(1, 100),
            'url' => $this->faker->url,
            'title' => $this->faker->words(2),
            'content' => $this->faker->sentences(4),
            'stored_words' => false
        ];
    }
}
