<?php

namespace App\Console\Commands;

use App\Models\Code;
use App\Models\Domain;
use App\Models\GitDomain;
use App\Models\Log;
use App\Models\ServerSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request as HttpRequest;

class AddNewDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:addNewDomain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '2Command description';

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

        $continueProccess = true;
        $codes = Code::all();
        $git_id = 0;
        $newDomain = Domain::where('domain_status', 0)->where('used', 0)->where('status', 0)->where('movable', 1)->get()->first();
        $server_settings = ServerSetting::all()->first();
     
        if ($server_settings->is_server_busy) {
            return 0;
        }

        $server_settings->is_server_busy = true;
        $server_settings->save();

        foreach ($codes as $key => $code) {

            $git_domains = GitDomain::where("git_id", $code->id)->get();
            $git_domains_lenght = (count($git_domains));
            if ($git_id == 0 && $git_domains_lenght < $code->limit) {
                $git_id = $code->id;
                break;
            }
        }


        if ($git_id == 0) {
            $continueProccess = false;
            $log = new Log();
            $log->type = 1;
            $log->title = "Uyarı";
            $log->description = "Kodlarımızda tanımladığımız limitler dolu.";
            $log->save();
        }


        if ($git_id != 0) {
            if (!$newDomain) {
                $log = new Log();
                $log->type = 1;
                $log->title = "Uyarı";
                $log->description = "Domainler bitmiş, yeni domain eklenmeli";
                $log->save();
                $continueProccess = false;
            }
        }

       if ($continueProccess) {

            $oldGitDomain =  GitDomain::where("domain_id", $newDomain->id)->get()->first();
            if ($oldGitDomain)
                $oldGitDomain->delete();
            $newGitDomain = new GitDomain();
            $newGitDomain->git_id = $git_id;
            $newGitDomain->domain_id = $newDomain->id;
            $newGitDomain->save();
            $continueProccess = true;
        } 

        if ($continueProccess) {


            $request = HttpRequest::create('/new_add_request?new_domain_name=' . $newDomain->name, 'GET');
            $res = app()->handle($request);
            $response = $res->getContent();

         
        

            
            if (!$response)
                $continueProccess = false;
        }


        if ($continueProccess) {
            $newDomain->used = 1;
            $newDomain->save();
            $log = new Log();
            $log->type = 0;
            $log->title = "Başarılı";
            $log->description = $newDomain->name. "'domaini kullanıma hazır!";
            $log->save();
        }



        $server_settings->is_server_busy = false;
        $server_settings->save();

        return 0;
    }
}
