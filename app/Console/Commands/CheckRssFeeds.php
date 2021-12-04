<?php

namespace App\Console\Commands;

use App\Exceptions\InvalidFeedException;
use App\Facades\Feed;
use App\Jobs\ProcessArticle;
use App\Models\Article;
use App\Exceptions\InvalidXmlException;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Class CheckRssFeeds
 *
 * @author Bram Raaijmakers
 *
 * @package App\Console\Commands
 */
class CheckRssFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rssFeeds:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the rss feeds for new articles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            foreach (config('rss-feeds.urls') as $url) {
                $feed = Feed::load($url);

                if (!isset($feed->channel) || !isset($feed->channel->item)) {
                    throw new InvalidFeedException();
                }

                foreach ($feed->channel->item as $article) {
                    if (!isset($article->link)) {
                        throw new InvalidFeedException();
                    }

                    $articleId = $this->getArticleId($article->link);
                    if (Article::where('article_id', $articleId)->count()) {
                        continue;
                    }

                    ProcessArticle::dispatch($article->link, $articleId);
                }
            }

            return 0;
        } catch (InvalidFeedException $exception) {
            Log::error('We received a invalid feed', [
                'feed' => $feed,
                'exception' => $exception
            ]);

            $this->output->error('We received a invalid feed');
            return 1;
        } catch (\Exception $exception) {
            Log::error('Something went wrong while retrieving new articles', ['exception' => $exception]);

            $this->output->error($exception->getMessage());
            return 1;
        }
    }

    /**
     * Retreive the article id from the url string.
     *
     * @param string $url
     *
     * @return string
     */
    private function getArticleId(string $url): string
    {
        return explode('/', $url)[4];
    }
}
