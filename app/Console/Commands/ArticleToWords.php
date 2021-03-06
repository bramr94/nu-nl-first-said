<?php

namespace App\Console\Commands;

use App\Jobs\ProcessArticleWords;
use App\Models\Article;
use App\Models\UniqueWord;
use Illuminate\Console\Command;

/**
 * Class ArticleToWords
 *
 * @author Bram Raaijmakers
 *
 * @package App\Console\Commands
 */
class ArticleToWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:toWords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all unique words form an article and store them.';

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
        $this->withProgressBar(Article::where('stored_words', false)->get(), function ($article) {
            ProcessArticleWords::dispatch($article);

            $article->stored_words = true;
            $article->save();
        });

        return 0;
    }
}
