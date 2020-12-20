<?php

namespace App\Console\Commands;

use App\Facades\Crawler;
use App\Models\NotFoundArticle;
use Illuminate\Console\Command;

/**
 * Class CrawlOldArticles
 *
 * @author Bram Raaijmakers
 *
 * @package App\Console\Commands
 */
class CrawlOldArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:oldArticles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl the old articles.';

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
    public function handle()
    {
        for ($i = config('articles.start_id'); $i > 1; $i--) {
            // Check we've already crawled this article.
            if (NotFoundArticle::where('article_id', $i)->count()) {
                continue;
            }

            $url = config('articles.url') . $i;

            if (Crawler::getStatusCode($url) == 404) {
                NotFoundArticle::create(['article_id' => $i]);

                $this->info('Not found:' . $i);
                continue;
            }

            $this->info('Found: ' . $i);
        }
        return 0;
    }
}
