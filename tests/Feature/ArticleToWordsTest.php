<?php

namespace Tests\Feature;

use App\Jobs\ProcessArticleWords;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * Class ArticleToWordsTest
 *
 * @author Bram Raaijmakers
 *
 * @package Tests\Feature
 */
class ArticleToWordsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function we_can_run_the_command()
    {
        $this->artisan('articles:toWords')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function a_job_is_dispatched_if_the_are_unprocessed_articles()
    {
        Bus::fake();
        $article = Article::factory()->create(['stored_words' => false]);
        $this->artisan('articles:toWords')->assertExitCode(0);

        Bus::assertDispatched(ProcessArticleWords::class);
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'stored_words' => true
        ]);
    }

    /**
     * @test
     */
    public function article_is_processed_correctly()
    {
        Config::set('twitter.tweet_words', true);
        $article = Article::factory()->create(['content' => 'Dit is een een test! w00rd']);
        $job = new ProcessArticleWords($article);

        $job->handle();

        $this->assertDatabaseCount('unique_words', 3);
        $this->assertDatabaseMissing('unique_words', ['value' => 'Dit']);
        $this->assertDatabaseMissing('unique_words', ['value' => 'w00rd']);
        $this->assertDatabaseHas('unique_words', [
            'value' => 'is',
            'occurrences' => 1,
            'should_be_tweeted' => true
        ]);
        $this->assertDatabaseHas('unique_words', [
            'value' => 'een',
            'occurrences' => 2,
            'should_be_tweeted' => true
        ]);
        $this->assertDatabaseHas('unique_words', [
            'value' => 'test',
            'occurrences' => 1,
            'should_be_tweeted' => true
        ]);
    }
}
