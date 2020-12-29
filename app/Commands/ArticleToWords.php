<?php

namespace App\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

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
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'article:toWords';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get all unique words form an article and store them.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->withProgressBar(DB::table('articles')->where('stored_words', false)->get(), function ($article) {
            $content = str_replace(config('articles.strip_from_articles'), ' ',$article->content);
            foreach (explode(' ', $content) as $word) {
                if ($word == '') {
                    continue;
                }

                $now = Carbon::now();

                $uniqueWord = DB::table('unique_words')->where('value', $word)->first();
                if (is_null($uniqueWord)) {
                    DB::table('unique_words')->insert([
                        'value' => $word,
                        'occurrences' => 1,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);

                    continue;
                }

                DB::table('unique_words')->where('id', $uniqueWord->id)->update([
                    'occurrences' => $uniqueWord->occurrences + 1,
                    'updated_at' => $now
                ]);
            }

            DB::table('articles')->where('id', $article->id)->update(['stored_words' => true]);
        });
    }
}
