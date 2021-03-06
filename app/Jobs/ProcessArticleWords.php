<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\UniqueWord;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class ProcessArticleWords
 *
 * @author Bram Raaijmakers
 *
 * @package App\Jobs
 */
class ProcessArticleWords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $article;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $content = str_replace(config('articles.strip_from_articles'), ' ',$this->article->content);
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
        } catch (\Exception $exception) {
            Log::error('Could not execute article to words jobs', ['exception' => $exception]);
        }
    }
}
