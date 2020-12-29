<?php

namespace App\Commands;

use App\Facades\Crawler;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

/**
 * Class ArticleToWords
 *
 * @author Bram Raaijmakers
 *
 * @package App\Console\Commands
 */
class CrawlOldArticles extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'crawl:oldArticles';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Crawl the old articles of nu.nl to build a dictionary.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        for ($i = config('articles.start_id'); $i > 1; $i--) {
            // Check if we've already crawled this article.
            if (Crawler::alreadyCrawled($i)) {
                $this->info('Already crawled: ' . $i);
                continue;
            }

            $url = config('articles.url') . $i;
            if (Crawler::getStatusCode($url) == 404) {
                DB::table('not_found_article')->insert(['article_id' => $i]);

                $this->info('Not found:' . $i);
                continue;
            }

            Crawler::getArticle($url, $i);
            $this->info('Found and stored: ' . $i);
        }

        return 0;
    }
}
