<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\GitDomain;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request as HttpRequest;


class ActionController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function new_add_and_old_delete_request(Request $request)
    {
     
        $newDomainName = $request->get('new_domain_name');
        $oldDomainName = $request->get('old_domain_name');
        $newDomainName = Domain::where('name', $newDomainName)->get()->first()->name;
        $continueProccess = true;

        $continueProccess =  $this->add_new($newDomainName, $oldDomainName);
        if ($continueProccess) {
            $continueProccess =  $this->old_delete($newDomainName, $oldDomainName);
        }

        return $continueProccess;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function new_add_request(Request $request)
    {
        
        $newDomainName = $request->get('new_domain_name');
        $newDomainName = Domain::where('name', $newDomainName)->get()->first()->name;
        return $this->add_new($newDomainName);
    }






    public function add_new($newDomainName, $oldDomainName=null)
    {
        $continueProccess = true;
        $add_new_domain_server_records = HttpRequest::create('/add_new_domain_server_records?new_domain_name=' . $newDomainName, 'GET');
        $res = app()->handle($add_new_domain_server_records);
        $responseNewDomainRecords = $res->getContent();

        if ($responseNewDomainRecords) {
            $log = new Log();
            $log->type = 0;
            $log->title = "Başarılı";
            $log->description = "$newDomainName için droplet oluşturuldu ve dns kayıtları işlemi başarıyla tamamlandı..";
            $log->save();
            $continueProccess = true;
        } else {

            $log = new Log();
            $log->type = -1;
            $log->title = "Hata";
            $log->description = "$newDomainName için droplet ve dns kayıtları işlemini yaparken bir hata meydana geldi..";
            $log->save();
            $continueProccess = false;
            $this->deleteErrorProcces($newDomainName);
        }
        return $continueProccess;
    }




    public function old_delete($newDomainName, $oldDomainName)
    {
        $continueProccess = true;
        $old_domain_move_redirect_server = HttpRequest::create('/old_domain_move_redirect_server?new_domain_name=' . $newDomainName . '&old_domain_name=' . $oldDomainName, 'GET');
        $res = app()->handle($old_domain_move_redirect_server);
        $responseOldDomainMove = $res->getContent();

        if ($responseOldDomainMove) {
            $log = new Log();
            $log->type = 0;
            $log->title = "Başarılı";
            $log->description = "$oldDomainName yönlendirme ve dns kayıtları işlemi başarıyla tamamlandı..";
            $log->save();
            $continueProccess = true;
        } else {
            $log = new Log();
            $log->type = -1;
            $log->title = "Hata";
            $log->description = "$oldDomainName yönlendirme ve dns kayıtları işlemini yaparken bir hata  meydana geldi..";
            $log->save();
            $continueProccess = false;
            $this->deleteErrorProcces($newDomainName, $oldDomainName, true);
        }
        return $continueProccess;
    }




    public function deleteErrorProcces($newDomainName, $oldDomainName = null, $step2 = false)
    {
        $deleteProccess = HttpRequest::create('/error_new_domain_server_records_delete?new_domain_name=' . $newDomainName, 'GET');
        $res = app()->handle($deleteProccess);
        $deleteProccessResponse = $res->getContent();

        if ($deleteProccessResponse) {

            $log = new Log();
            $log->type = 1;
            $log->title = "Uyarı";
            $log->description = "$newDomainName droplet oluşturma ve yeni dns recordsları sırasında bir hata meydana geldiği için yapılan işlemler geri alındı.";
            $log->save();

        } else {
            $log = new Log();
            $log->type = -1;
            $log->title = "Hata";
            $log->description = "$newDomainName droplet ve dns recordsları silmeyi denedi ama başarılı olamadı .";
            $log->save();
        }

        if ($step2) {
            $deleteProccessRedirect = HttpRequest::create('/error_old_domain_move_redirect_server?old_domain_name=' . $oldDomainName, 'GET');
            $res = app()->handle($deleteProccessRedirect);
            $deleteProccessRedirectResponse = $res->getContent();

            if ($deleteProccessRedirectResponse) {
                $log = new Log();
                $log->type = 1;
                $log->title = "Uyarı";
                $log->description = "$newDomainName droplet oluşturma ve yeni dns recordsları sırasında bir hata meydana geldiği için yapılan işlemler geri alındı.";
                $log->save();
            } else {
                $log = new Log();
                $log->type = -1;
                $log->title = "Hata";
                $log->description = "$newDomainName redirect dns recordsları silmeyi denedi ama başarılı olamadı.";
                $log->save();
            }
        }
    }
}
