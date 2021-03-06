<?php

namespace App\Console\Commands;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Jobs\SendTweet;
use App\Models\UniqueWord;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class Tweet
 *
 * @author Bram Raaijmakers
 *
 * @package App\Console\Commands
 */
class CheckNewWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:checkNewWords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new words and dispatch job to tweet them';

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
            $now = Carbon::now();

            $this->withProgressBar(
                UniqueWord::where(['should_be_tweeted' => true, 'tweeted' => false])->get(),
                function ($uniqueWord) use ($now) {
                    $now = $now->addMinutes(2);
                    SendTweet::dispatch($uniqueWord->value)->delay($now);

                    $uniqueWord->tweeted = true;
                    $uniqueWord->save();
                }
            );

            return 0;
        } catch (\Exception $exception) {
            Log::error('Could not check for new words', ['exception' => $exception]);

            $this->output->error($exception->getMessage());
            return 1;
        }
    }
}
