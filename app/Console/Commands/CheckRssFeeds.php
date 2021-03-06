<?php

namespace App\Console\Commands;

use App\Jobs\ProcessArticle;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
                $feed = (array) simplexml_load_file($url);

                foreach ($feed['channel']->item as $article) {
                    $articleId = $this->getArticleId($article->link);
                    if (Article::where('article_id', $articleId)->count()) {
                        continue;
                    }

                    ProcessArticle::dispatch($article->link, $articleId);
                }
            }

            return 0;
        } catch (\Exception $exception) {
            Log::error('Something went wrong while retrieving new articles', ['exception' => $exception]);

            $this->output->error($exception->getMessage());
            return 1;
        }
    }

    private function getArticleId(string $url): string
    {
        return explode('/', $url)[4];
    }
}
