<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\BannedList;
use App\Models\Domain;
use App\Models\Log;
use phpseclib3\Net\SSH2;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use App\Models\ServerSetting;

class DailyQuote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:everyMinute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively send an exclusive quote to everyone daily via email.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $server_settings = ServerSetting::all()->first();
       
        if ($server_settings->is_server_busy) {
            return 0;
        }
        $server_settings->is_server_busy = true;
        $server_settings->save(); 
        $output = shell_exec('php artisan dusk');
        $server_settings->is_server_busy = false;
        $server_settings->save(); 
    }

    
}
