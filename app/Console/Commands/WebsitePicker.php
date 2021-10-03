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

class WebsitePicker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:websitePicker';

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
        $websites = Website::where('status', 0)->get();
        $part = "/wp-json/wp/v2/posts/";
        $category_part = "/wp-json/wp/v2/categories";
        $server_settings = ServerSetting::all()->first();

        if ($server_settings->website_picker_busy) {
            return 0;
        }


        foreach ($websites as $key => $website) {
            $server_settings->website_picker_busy = true;
            $server_settings->save();

            $log = new Log();
            $log->type = 0;
            $log->title = "Başarılı";
            $log->which_worker = "websitePicker";
            $log->description = $website->link . " içerikleri çekilmeye başlandı.";




            try {
                $timer = 0;

                for ($i = 1; $i < 15000; $i++) {



                    $post_id = $i;
                    $rest_api_link = $website->link . $part . $post_id;
                    $curlSession = curl_init();
                    curl_setopt($curlSession, CURLOPT_URL, $rest_api_link);
                    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
                    $jsonData = json_decode(curl_exec($curlSession));

                    curl_close($curlSession);
               
                    if (!isset($jsonData->data->status)) {
                        $save = true;
                        $link = '';
                        try {
                            $link = $jsonData->slug;
                            $save = true;
                        } catch (\Throwable $th) {
                            $save = false;
                            break;
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
                $log->which_worker = "websitePicker";
                $log->description = $website->link . " içerikleri çekildi işlem tamamlandı.";
                $contents_c = Content::where('website_id', $website->id)->get();
                if (count($contents_c) > 0)
                    $website->status = 1;
                else
                    $website->status = -2;

                $website->save();
                $server_settings->website_picker_busy = false;
                $server_settings->save();
            } catch (\Throwable $th) {

                $log = new Log();
                $log->type = -1;
                $log->title = "Hata";
                $log->which_worker = "websitePicker";
                $log->description = $website->link . " içerikleri çekerken hata meydana geldi. Hata: " . "$th";
                $website->status = -1;
                $website->save();
                $log->save();
                //Eski kayıtlarıda siliver 
                //Content::where("website_id",$website->id)->delete();
                $server_settings->website_picker_busy = false;
                $server_settings->save();
            }
        }
    }
}
