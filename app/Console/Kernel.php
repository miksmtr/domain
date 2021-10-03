<?php

namespace App\Console;

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
        Commands\DeleteExpiredActivations::class,
        Commands\DailyQuote::class,
        Commands\CheckDomains::class,
        Commands\AddNewDomain::class,
        Commands\WebsitePicker::class,
        Commands\WebsitePickerSecond::class,
       

    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('quote:everyMinute')->everyMinute();
        $schedule->command('quote:addNewDomain')->everyMinute();
        $schedule->command('quote:checkDomains')->everyMinute();
        $schedule->command('quote:websitePicker')->everyMinute();
        $schedule->command('quote:websitePickerSecond')->everyMinute();
        $schedule->command('activations:clean')->daily();
 
   
    
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
