<?php

namespace Database\Factories;

use App\Models\Article;
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
            'title' => $this->faker->words(2, true),
            'content' => $this->faker->sentences(4, true),
            'stored_words' => false
        ];
    }
}
