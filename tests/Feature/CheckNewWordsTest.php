<?php

namespace Tests\Feature;

use App\Jobs\ProcessArticleWords;
use App\Jobs\SendTweet;
use App\Models\UniqueWord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class CheckNewWordsTest
 *
 * @author Bram Raaijmakers
 *
 * @package Tests\Feature
 */
class CheckNewWordsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function we_can_run_the_command()
    {
        $this->artisan('articles:checkNewWords')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function new_words_will_be_processed()
    {
        // We use the Queue fake since the jobs is dispatched with a delay.
        Queue::fake();
        $uniqueWord = UniqueWord::factory()->create();


        $this->artisan('articles:checkNewWords')->assertExitCode(0);
        Queue::assertPushed(SendTweet::class, function ($job) {
            return !is_null($job->delay);
        });

        $this->assertDatabaseHas('unique_words', [
            'id' => $uniqueWord->id,
            'tweeted' => true
        ]);
    }
}
