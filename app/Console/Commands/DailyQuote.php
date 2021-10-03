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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
   {
         // status -1 ise sorun var kodda sorun var.

    // status 0 ise çalışıyor

    // status 1 ise banlandı

    // status 2 ise taşındı.

    // status 3 ise banlandıgı hakkında email gönderildi.
    $TR_SERVER_IP = Config::get('values.TR_SERVER_IP');
    $ACTION_TYPE = Config::get('values.ACTION_TYPE');
    $TR_SERVER_SSH_USERNAME = Config::get('values.TR_SERVER_SSH_USERNAME');
    $TR_SERVER_PASSWORD = Config::get('values.TR_SERVER_PASSWORD');
    $WHICH_MAIL_FOR_BANNED = Config::get('values.WHICH_MAIL_FOR_BANNED');
    $WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM = Config::get('values.WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM');

 $hour = date("H");

    if($hour < 5 ){
    
        return 0;

    }
    if($hour > 18 ){

        return 0;

    }

    $ssh = new SSH2($TR_SERVER_IP);
    if (!$ssh->login($TR_SERVER_SSH_USERNAME, $TR_SERVER_PASSWORD)) {

        Mail::raw(" this server don't connect to " . $TR_SERVER_IP, function ($mail) use ($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM, $TR_SERVER_IP) {
            $mail->from('ex@exaclicks.com');
            $mail->to($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM)
                ->subject(" this server don't connect to " . $TR_SERVER_IP);
        });

        $log = new Log();
        $log->type = -1;
        $log->title = "Hata";
        $log->description = "$TR_SERVER_IP ipli servere bağlanılmıyor.";
        $log->save();
        exit();
    }

    
    $moved_text = "The document has moved ";
    $isMoved = "";


    $domains = Domain::where("used",1)->where("status","!=",3)->orWhereNull('status')->get();
    foreach ($domains as $domain) {
        $status = -1;
        $link = $domain->name;
        $control = explode('http',$link);
        if(count($control) < 2){
            $link = "http://".$link;
        }

        $command = 'curl -s -H "Proxy-Connection: keep-alive"  -H "Cache-Control: max-age=0"   -H "Upgrade-Insecure-Requests: 1" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36" -H "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"  -H "Accept-Language: tr-TR,tr;q=0.9,tr;q=0.8" ' . $link;
        $html = $ssh->exec($command);



        if ($html != '') {
            $isMoved = strpos($html, $moved_text);
            if ($isMoved != '') {
                $status = 2;
            } else {
                $status = 0;
            }
        } else {
            $status = 1;
        }


        // $domain->status = $status;


        if ($domain->status != 1 && $domain->status != 3) {



            if ($status == 1) {

                $bannedItem = new BannedList();
                $getBannedItem = BannedList::where('domain_id', $domain->id);

                $todayDate = Carbon::now();
                $bannedItem->how_many_times = 1;

                if ($getBannedItem->first()) {
                    $bannedItem  = $getBannedItem->first();
                    $bannedItem->how_many_times = $bannedItem->how_many_times + 1;
                    $diff = $todayDate->diffInMinutes($bannedItem->banned_time);
                    if ($diff >= 120) {
                        $bannedItem->how_many_times = 1;
                    }

                    $bannedItem->banned_time = $todayDate;
                    $bannedItem->save();
                } else {
                    $bannedItem->domain_id = $domain->id;
                    $bannedItem->banned_time = $todayDate;

                    $bannedItem->save();
                }




                if ($bannedItem->how_many_times > 30) {

                    if ($ACTION_TYPE == 0) {
                        $domain->save();
                        if($domain->movable==0){
                            Mail::raw($domain->name . " engellendi. <br> " , function ($mail) use ($domain, $WHICH_MAIL_FOR_BANNED) {
                                $mail->from("ex@exaclicks.com");
                                $mail->to($WHICH_MAIL_FOR_BANNED)
                                    ->subject($domain->name);
                            });
                          
                        }
                        $log = new Log();
                        $log->type = 1;
                        $log->title = "Uyarı";
                        $log->description = "$domain->name banlandı.";
                        $log->save();
                        $domain->status = 3;
                        $domain->domain_status = 1; //  1 taşınması gerekiyor. 2 taşındı.
                        $domain->save();
                    }
                }
            }
        }
    }
   }
}
