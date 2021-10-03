<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Content;

class BanBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:BanBot';

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       

        $this->browse(function ($browser) {
            $ihbar_web_site = "https://www.ihbarweb.org.tr/ihbar.php?subject=9";
            $domain_link = 'https://aaaa.com';
            $detail = 'Şerefsiz bunlar ';

            $browser->visit($ihbar_web_site)
                ->pause(3000);

            $browser->value('#adres', $domain_link);
            $browser->pause(1000);

            $browser->value('#detay', $domain_link);
            $browser->pause(1000);

            $browser->script("document.getElementById('gonder').click();");
            $browser->pause(1000);


            return 1;
        });
    }

}
