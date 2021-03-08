<?php

namespace App\Console;

use App\Console\Commands\ArticleToWords;
use App\Console\Commands\CheckRssFeeds;
use App\Console\Commands\CrawlArticles;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CrawlArticles::class,
        ArticleToWords::class,
        CheckRssFeeds::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call('articles:toWords')->everyFiveMinutes()->name('article_to_words')->withoutOverlapping();
        $schedule->call('rssFeeds:check')->everyFifteenMinutes()->name('check_feeds')->withoutOverlapping();
        // $schedule->call('articles:checkNewWords')->everyTenMinutes()->name('check_new_words')->withoutOverlapping();
    }
//php /home/forge/default/artisan schedule:run


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
