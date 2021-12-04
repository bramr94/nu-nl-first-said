<?php

namespace Tests\Feature;

use App\Facades\Feed;
use App\Jobs\ProcessArticle;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * Class RssFeedTest
 *
 * @author Bram Raaijmakers
 *
 * @package Tests\Feature
 */
class RssFeedTest extends TestCase
{
    use RefreshDatabase;

    private string $url = 'https://www.nu.nl/tech/1/veel-bekende-routers-bevatten-beveiligingslekken.html';

    /**
     * @test
     */
    public function we_can_run_the_command()
    {
        Bus::fake();
        Config::set('rss-feeds.urls', ['www.-bestaat-dit-niet-.nl']);

        Feed::shouldReceive('load')->once()->andReturn($this->createFeedResponse());

        $this->artisan('rssFeeds:check')->assertExitCode(0);
        Bus::assertDispatched(ProcessArticle::class);
    }

    /**
     * @test
     */
    public function job_will_not_dispatch_if_article_exists()
    {
        Bus::fake();
        Config::set('rss-feeds.urls', ['www.-bestaat-dit-niet-.nl']);

        // Create a new article with the same article_id as in the URL.
        Article::factory()->create(['article_id' => 1]);
        $this->assertDatabaseCount('articles', 1);

        Feed::shouldReceive('load')->once()->andReturn($this->createFeedResponse());

        $this->artisan('rssFeeds:check')->assertExitCode(0);
        $this->assertDatabaseCount('articles', 1);
        Bus::assertNotDispatched(ProcessArticle::class);
    }

    /**
     * @test
     */
    public function command_will_fail_with_invalid_feed()
    {
        Bus::fake();
        Config::set('rss-feeds.urls', ['www.-bestaat-dit-niet-.nl']);

        Feed::shouldReceive('load')->once()->andReturn((object) 'invalid feed object');

        $this->artisan('rssFeeds:check')->assertFailed();
        Bus::assertNotDispatched(ProcessArticle::class);
    }

    private function createFeedResponse(): object
    {
        $mockData = new \stdClass();
        $mockData->channel = new \stdClass();
        $mockData->channel->item = array();
        $mockData->channel->item[0] = new \stdClass();
        $mockData->channel->item[0]->link = $this->url;
        $mockData->channel->item[0]->title = 'test';

        return $mockData;
    }
}
