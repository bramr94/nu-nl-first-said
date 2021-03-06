<?php

namespace App\Console\Commands;

use App\Facades\Crawler;
use App\Jobs\ProcessArticle;
use App\Models\Article;
use App\Models\NotFoundArticle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class CrawlOldArticles
 *
 * @author Bram Raaijmakers
 *
 * @package App\Console\Commands
 */
class CrawlArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl the articles.';

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
            for ($i = config('articles.start_id'); $i <= config('articles.end_id'); $i++) {
                // Check if we've already crawled this article.
                if (NotFoundArticle::where('article_id', $i)->count() || Article::where('article_id', $i)->count()) {
                    $this->info('Already crawled: ' . $i);
                    continue;
                }

                $url = config('articles.url') . $i;
                if (Crawler::getStatusCode($url) == 404) {
                    NotFoundArticle::create(['article_id' => $i]);

                    $this->info('Not found:' . $i);
                    continue;
                }

                ProcessArticle::dispatch($url, $i);
                $this->info('Job dispatched: ' . $i);
            }

            return 0;
        } catch (\Exception $exception) {
            Log::error('Could not crawl articles', ['exception' => $exception]);
            $this->error('Something went wrong: ' . $exception->getMessage());

            return 1;
        }
    }
}
