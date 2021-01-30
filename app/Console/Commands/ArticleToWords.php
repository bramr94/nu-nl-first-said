<?php

namespace App\Console\Commands;

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
    public function handle()
    {
        $this->withProgressBar(Article::where('stored_words', false)->get(), function ($article) {
            $content = str_replace(config('articles.strip_from_articles'), ' ',$article->content);
            foreach (explode(' ', $content) as $word) {
                if ($word == '') {
                    continue;
                }

                $uniqueWord = UniqueWord::firstOrNew(['value' =>  html_entity_decode($word)]);
                if (is_null($uniqueWord->occurrences)) {
                    $uniqueWord->occurrences = 0;
                }

                $uniqueWord->occurrences = bcadd($uniqueWord->occurrences, 1);
                $uniqueWord->save();
            }

            $article->stored_words = true;
            $article->save();
        });

        return 0;
    }
}
