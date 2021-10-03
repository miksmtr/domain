<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\GitDomain;
use App\Models\Log;
use App\Models\ServerSetting;
use Illuminate\Console\Command;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Route;

class CheckDomains extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:checkDomains';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1Command description';

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


        $server_settings = ServerSetting::all()->first();
        $domains = Domain::where('domain_status', 1)->where('used', 1)->where('status', 3)->where('movable', 1)->get();
        $newDomain = Domain::where('domain_status', 0)->where('used', 0)->where('status', 0)->where('movable', 1)->get()->first();
        $continueProccess = true;
        if ($server_settings->is_server_busy) {
            return 0;
        }
        $newDomainName = '';
           $server_settings->is_server_busy = true;
        $server_settings->save(); 
$oldDomainName='';
        foreach ($domains as $oldDomain) {

            if (!$newDomain) {
                $log = new Log();
                $log->type = 1;
                $log->title = "Uyarı";
                $log->description = "Domainler bitmiş, yeni domain eklenmeli";
                $log->save();

                $continueProccess = false;
            } else {
                $newDomainName = $newDomain->name;
            }

            if ($continueProccess) {
                $oldDomainName = $oldDomain->name;
                $oldGitDomain =  GitDomain::where("domain_id", $oldDomain->id)->get()->first();
                $newGitDomain = new GitDomain();
                $newGitDomain->git_id = $oldGitDomain->git_id;
                $newGitDomain->domain_id = $newDomain->id;
                $oldGitDomain->delete();
                $newGitDomain->save();
                $continueProccess = true;
            }

            if ($continueProccess) {
                $new_add_and_old_deleteResponse = HttpRequest::create('/new_add_and_old_delete_request?new_domain_name=' . $newDomainName . '&old_domain_name=' . $oldDomainName, 'GET');
                $res = app()->handle($new_add_and_old_deleteResponse);
                $deleteProccessResponse = $res->getContent();

                if (!$deleteProccessResponse)
                    $continueProccess = false;
            }

            if ($continueProccess) {
                $newDomain->used = 1;
                $newDomain->save();
                $oldDomain->domain_status = 2;
                $oldDomain->save(); // TAŞINDI.
                $log = new Log();
                $log->type = 0;
                $log->title = "Başarılı";
                $log->description = $oldDomain->name . " tamamen taşındı ve ." . $newDomain->name . " yeni domain kullanıma hazır.";
            }
        }


        $server_settings->is_server_busy = false;
        $server_settings->save();
        return 0;
    }
}
