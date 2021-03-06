<?php

namespace App\Jobs;

use App\Facades\Crawler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Class ProcessArticle
 *
 * @author Bram Raaijmakers
 *
 * @package App\Jobs
 */
class ProcessArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @param string $url
     * @param int $id
     *
     * @return void
     */
    public function __construct(string $url, int $id)
    {
        $this->url = $url;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Crawler::getArticle($this->url, $this->id);
        } catch (\Exception $exception) {
            Log::error('Could not execute job', [
                'id' => $this->id,
                'url' => $this->url,
                'exception' => $exception
            ]);
        }
    }
}
