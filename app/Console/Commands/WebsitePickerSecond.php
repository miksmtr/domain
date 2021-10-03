<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Models\BannedList;
use App\Models\Content;
use App\Models\Domain;
use App\Models\Log;
use App\Models\ServerSetting;
use App\Models\Website;
use phpseclib3\Net\SSH2;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class WebsitePickerSecond extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:websitePickerSecond';

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
        $TR_SERVER_IP = Config::get('values.TR_SERVER_IP');
    $TR_SERVER_SSH_USERNAME = Config::get('values.TR_SERVER_SSH_USERNAME');
    $TR_SERVER_PASSWORD = Config::get('values.TR_SERVER_PASSWORD');
  
        $websites = Website::where('status',-2)->get();
        $part = "/wp-json/wp/v2/posts/";
        $category_part = "/wp-json/wp/v2/categories";
        $server_settings = ServerSetting::all()->first();

 
        if ($server_settings->website_picker_second_busy) {
            return 0;
        }

        $ssh = new SSH2($TR_SERVER_IP);
        if (!$ssh->login($TR_SERVER_SSH_USERNAME, $TR_SERVER_PASSWORD)) {
    
            
           return 0;
        }

        foreach ($websites as $key => $website) {
            $server_settings->website_picker_second_busy = true;
            $server_settings->save();

            $log = new Log();
            $log->type = 0;
            $log->title = "Başarılı";
            $log->which_worker = "websitePickerSecond";
            $log->description = $website->link . " içerikleri çekilmeye başlandı.";

          
            try {
                $timer = 0;
                for ($i = 1; $i < 15000; $i++) {



                    $post_id = $i;
                    $rest_api_link = $website->link . $part . $post_id;
                    $command = 'curl -s -H "Proxy-Connection: keep-alive"  -H "Cache-Control: max-age=0"   -H "Upgrade-Insecure-Requests: 1" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36" -H "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"  -H "Accept-Language: tr-TR,tr;q=0.9,tr;q=0.8" ' . $rest_api_link;
                    $html = $ssh->exec($command);
                    sleep(3);
                    $jsonData = json_decode($html);

                    if($timer==500){
                        break;
                    }
                    if (!isset($jsonData->data->status)) {
                        $save = true;
                        $link = '';
                        try {
                            $link = $jsonData->slug;
                            $save = true;
                        } catch (\Throwable $th) {
                            $save = false;
                        }


                        $description = '';
                        if ($save) {
                            $save = true;
                            $description = $jsonData->excerpt->rendered;
                        } else {
                            $save = false;
                        }


                        $title = '';
                        if ($save) {
                            $save = true;
                            $title =  $jsonData->title->rendered;
                        } else {
                            $save = false;
                        }

                        $wp_content = '';
                        if ($save) {
                            $save = true;
                            $wp_content  = $jsonData->content->rendered;
                        } else {
                            $save = false;
                        }

                        if ($save) {
                            $content = new Content();
                            $content->first_link = $link;
                            $content->first_title = $title;
                            $content->first_description = $description;
                            $content->first_content = $wp_content;
                            $content->first_category = '';
                            $content->rewriter_title = $title;
                            $content->rewriter_description =  $description;
                            $content->website_id =  $website->id;
                            $content->save();
                            $timer == 0;
                        }
                    }
                    $timer++;
                }

                $log = new Log();
                $log->type = 0;
                $log->title = "Başarılı";
                $log->which_worker = "websitePickerSecond";
                $log->description = $website->link . " içerikleri çekildi işlem tamamlandı.";
                $contents_c = Content::where('website_id', $website->id)->get();
                if (count($contents_c) > 0)
                    $website->status = 1;
                else
                    $website->status = -1;

                $website->save();
                $server_settings->website_picker_second_busy = false;
                $server_settings->save();
            } catch (\Throwable $th) {

                $log = new Log();
                $log->type = -1;
                $log->title = "Hata";
                $log->which_worker = "websitePickerSecond";
                $log->description = $website->link . " içerikleri çekerken hata meydana geldi. Hata: " . "$th";
                $website->status = -1;
                $website->save();
                $log->save();
                //Eski kayıtlarıda siliver 
                //Content::where("website_id",$website->id)->delete();
                $server_settings->website_picker_second_busy = false;
                $server_settings->save();
            }
        }
    }
}
