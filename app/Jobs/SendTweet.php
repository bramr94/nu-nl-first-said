<?php

namespace App\Jobs;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Exceptions\TwitterException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTweet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $word;

    protected TwitterOAuth $twitter;

    /**
     * Create a new job instance.
     *
     * @param string $word
     *
     * @return void
     */
    public function __construct(string $word)
    {
        $this->word = $word;

        $this->twitter = new TwitterOAuth(
            config('services.twitter.consumer_access_token'),
            config('services.twitter.consumer_access_token_secret'),
            config('services.twitter.auth_access_token'),
            config('services.twitter.auth_account_token_secret')
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            dd($this->word);
            $result = $this->twitter->post('statuses/update', ['status' => $this->word]);

            if (isset($result->errors)) {
                throw TwitterException();
            }
        } catch (\Exception $exception) {
            if ($exception instanceof TwitterException) {
                Log::error('Something went wrong while posting tweet', [
                    'word' => $this->word,
                    'result' => $result,
                    'exception' => $exception,
                ]);

                $this->fail($exception);
                return;
            }

            Log::error('Something went wrong', [
                'word' => $this->word,
                'exception' => $exception
            ]);
        }
    }
}
