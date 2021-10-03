<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use DigitalOceanV2\Client;
use App\Models\BannedList;
use App\Models\Code;
use App\Models\GitDomain;
use phpseclib3\Net\SSH2;
use Illuminate\Support\Facades\Mail;


class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domains = Domain::orderByDesc('status')->where('used', 1)->where('movable', 0)->get();
        return view('domains.index', compact('domains'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function un_used_domain_index()
    {
        $domains = Domain::orderByDesc('status')->where('used', 0)->where('movable', 1)->get();
        return view('domains.un_used_domain_index', compact('domains'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function movable_and_used_domain_index()
    {
        $domains = Domain::orderByDesc('status')->where('movable', 1)->where('used', 1)->get();
        return view('domains.used_and_movable_index', compact('domains'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('domains.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function un_used_domain_create()
    {
        return view('domains.un_used_domain_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'hosting' => 'required',
        ]);

        $domain = Domain::create([
            'name' => $request->get('name'),
            'hosting' => $request->get('hosting'),
            'start_time' => $request->get('start_time'),
            'finish_time' => $request->get('finish_time'),
            'status' => 0,
            'movable' =>  0,
            'used' => 1,
            'doain_status' => 0,
        ]);
        

        $domain->save();

        return redirect()->route('domains.index')
            ->with('success', 'Domain oluşturma başarıyla tamamlandı');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function un_used_domain_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'hosting' => 'required',
            'movable' => 'required',
        ]);

        $used = 0;
        if($request->get('movable')==0){
            $used = 1;
        }
        $domain = Domain::create([
            'name' => $request->get('name'),
            'hosting' => $request->get('hosting'),
            'start_time' => $request->get('start_time'),
            'finish_time' => $request->get('finish_time'),
            'status' => 0,
            'movable' =>  $request->get('movable'),
            'used' => $used,
            'doain_status' => 0,
        ]);

        $domain->save();

        return redirect()->route('un_used_domain_index')
            ->with('success', 'Domain oluşturma başarıyla tamamlandı');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        return view('domains.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {
        return view('domains.edit', compact('domain'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        $request->validate([
            'name' => 'required',
            'hosting' => 'required',
        ]);


        $domain->update($request->all());

        return redirect()->route('domains.index')
            ->with('success', 'Domain güncellemesi başarıyla tamamlandı');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {
        $domain->delete();

        return redirect()->route('domains.index')
            ->with('success', 'Domain kaldırma başarıyla tamamlandı');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function un_used_destroy(Request $request, Domain $domain)
    {
        echo $domain->id . "<br>";
        echo $request;
        exit();
        $domain->delete();

        return redirect()->route('un_used_domain_index')
            ->with('success', 'Domain kaldırma başarıyla tamamlandı');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function banlanmalogu(Request $request)
    {
        $bannedItem =  BannedList::all();
        foreach ($bannedItem  as $key => $value) {
            echo $value->id . "--" . $value->domain_id . "---" . $value->how_many_times . "---" . $value->banned_time . "<br>";
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_new_domain_server_records(Request $request)
    {
        $request->validate([
            'new_domain_name' => 'required',
        ]);
        $newDomainName = $request->get('new_domain_name');
        $token = Config::get('values.DIGITALOCEAN_ACCESS_TOKEN');
        $client = new Client();
        $client->authenticate($token);
        $domainRecord = $client->domainRecord();
        $dropletClient = $client->droplet();
        $WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM = Config::get('values.WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM');
        $public_key_root = Config::get('values.PUBLIC_KEY_ROOT');
        $private_key_root = Config::get('values.PRIVATE_KEY_ROOT');
        $addNewGitDomain = Request::create('/add_new_git_domain?new_domain_name=' . $newDomainName, 'GET');
        $res = app()->handle($addNewGitDomain);
        $new_git_domain_id = $res->getContent();
        $newGitDomain = GitDomain::where('id', $new_git_domain_id)->get()->first();

        $return = false;

        try {


            ////////////////STEP 0 CREATE NEW DROPLET
            $code = Code::where("id", $newGitDomain->git_id)->get()->first();
            $code_link = $code->git_address;
            $explode_array = explode("/", $code_link);
            $code_document_root = $explode_array[count($explode_array) - 1];
            $newGitDomain->setup = 1;
            $newGitDomain->save();
            ////////////////


            ////////////////STEP 1 CREATE NEW DROPLET
            $addNewDropletRequest = Request::create('/add_new_droplet?new_domain_name=' . $newDomainName, 'GET');
            $res = app()->handle($addNewDropletRequest);
            $dropletId = $res->getContent();

            ////////////////







            //////////////STEP 2 CREATE NEW DOMAİN DNS RECORDS 
            sleep(10);
            comeBack:
            $droplet123 = $dropletClient->getById($dropletId);
            if (count($droplet123->networks) == 0) {
                sleep(5);
                goto comeBack;
            }


            sleep(100);

            $dropletIpAdress = $droplet123->networks[1]->ipAddress;
            $hostingIp = $dropletIpAdress;
            $digitalocean_nameservers_ipies = ["173.245.58.51", "173.245.59.41", "198.41.222.173"];
            $new_nameservers = ['ns1.' . $newDomainName, 'ns2.' . $newDomainName, 'ns3.' . $newDomainName];
            $domainRecordInfos = $domainRecord->getAll($newDomainName);
            //CREATE NEW DOMAİN RECORDS
            $domainRecordInfos = $domainRecord->getAll($newDomainName);

            //Delete old dns
            foreach ($domainRecordInfos as $value) {
                if ($value->type != "SOA") {
                    $domainRecord->remove($newDomainName, $value->id);
                }
            }

            //create new dns and nameservers;
            $created = $domainRecord->create($newDomainName, 'A', '@', $hostingIp, null, null, null, null, null, 3600);
            $created = $domainRecord->create($newDomainName, 'A', 'www', $hostingIp, null, null, null, null, null, 3600);
            $created = $domainRecord->create($newDomainName, 'NS', '@', $new_nameservers[1] . ".", null, null, null, null, null, 86400);
            $created = $domainRecord->create($newDomainName, 'NS', '@', $new_nameservers[1] . ".", null, null, null, null, null, 86400);
            $created = $domainRecord->create($newDomainName, 'NS', '@', $new_nameservers[2] . ".", null, null, null, null, null, 86400);
            $created = $domainRecord->create($newDomainName, 'A', "ns1", $digitalocean_nameservers_ipies[0], null, null, null, null, null, 3600);
            $created = $domainRecord->create($newDomainName, 'A', "ns2", $digitalocean_nameservers_ipies[1], null, null, null, null, null, 3600);
            $created = $domainRecord->create($newDomainName, 'A', "ns3", $digitalocean_nameservers_ipies[2], null, null, null, null, null, 3600);
            //////////////////            

            sleep(60);
            //STEP 3 CREATE NEW APACHE CONFİGS



            $execute_code = 'echo "<VirtualHost *:80>
            ServerAdmin webmaster@localhost
            ServerName ' . $newDomainName . '
            ServerAlias www.' . $newDomainName . '
            DocumentRoot /var/www/' . $code_document_root . '
            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
        </VirtualHost>" >> /etc/apache2/sites-available/' . $newDomainName . '.conf';




            $connection = ssh2_connect($hostingIp, 22, array('hostkey' => 'ssh-rsa'));
            if (!ssh2_auth_pubkey_file(
                $connection,
                'root',
                $public_key_root,
                $private_key_root,
                'secret'

            )) {
                Mail::raw(" this server don't connect to " . $hostingIp, function ($mail) use ($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM, $hostingIp) {
                    $mail->from('ex@exaclicks.com');
                    $mail->to($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM)
                        ->subject(" this server don't connect to " . $hostingIp);
                });
                exit();
            }
            sleep(2);


            ssh2_exec($connection, "rm -r $code_document_root");
            ssh2_exec($connection, "git clone $code_link");
            sleep(5);
            ssh2_exec($connection, "mv $code_document_root /var/www/".$code_document_root);
        

            ssh2_exec($connection, $execute_code);
            ssh2_exec($connection, 'a2ensite ' . $newDomainName . '.conf');
            ssh2_exec($connection, 'systemctl restart apache2');
            sleep(60);

            //SSL CONFİG
            ssh2_exec($connection, 'certbot --apache -d ' . $newDomainName . ' -d www.' . $newDomainName);
            sleep(120);
            ssh2_exec($connection, '2');
            sleep(10);


            $execute_code_last = 'echo "<VirtualHost *:80>
            ServerAdmin webmaster@localhost
            ServerName ' . $newDomainName . '
            ServerAlias www.' . $newDomainName . '
            DocumentRoot /var/www/' . $code_document_root . '
            ErrorLog ${APACHE_LOG_DIR}/error.log
            CustomLog ${APACHE_LOG_DIR}/access.log combined
            RewriteEngine On
RewriteCond %{HTTPS} off
RewriteCond %{HTTP_HOST} ^(?:www\.)?(.*)$ [NC]
RewriteRule (.*) https://%1%{REQUEST_URI} [L,R=301]

        </VirtualHost>" >> /etc/apache2/sites-available/' . $newDomainName . '.conf';

        ssh2_exec($connection, $execute_code_last);



            $return = true;
        } catch (\Throwable $th) {
           
            $return = false;
        }

        if(!$return){
            $newGitDomain->setup = 0;
            $newGitDomain->save();
        }
        return $return ;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function error_new_domain_server_records_delete(Request $request)
    {
        $request->validate([
            'new_domain_name' => 'required',
        ]);
        $newDomainName = $request->get('new_domain_name');
        $token = Config::get('values.DIGITALOCEAN_ACCESS_TOKEN');
        $client = new Client();
        $client->authenticate($token);
        $domainRecord = $client->domainRecord();

        try {

            ////////////////STEP 1 DELETE NEW DROPLET
            $addNewDropletRequest = Request::create('/delete_droplet?old_domain_name=' . $newDomainName, 'GET');
            $res = app()->handle($addNewDropletRequest);
            ////////////////

            $domainRecordInfos = $domainRecord->getAll($newDomainName);

            //Delete old dns
            foreach ($domainRecordInfos as $value) {
                if ($value->type != "SOA") {
                    $domainRecord->remove($newDomainName, $value->id);
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function old_domain_move_redirect_server(Request $request)
    {
        $oldDomainName = $request->get('old_domain_name');
        $newDomainName = $request->get('new_domain_name');
        $response = false;
        $token = Config::get('values.DIGITALOCEAN_ACCESS_TOKEN');
        $redirectServerIp = Config::get('values.REDİRECT_SERVER_IP');
        $public_key_root = Config::get('values.PUBLIC_KEY_ROOT');
        $private_key_root = Config::get('values.PRIVATE_KEY_ROOT');
        $redirectServerDefaultPassword = Config::get('values.REDİRECT_SERVER_DEFAULT_PASSWORD');
        $WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM = Config::get('values.WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM');
        $digitalocean_nameservers_ipies = ["173.245.58.51", "173.245.59.41", "198.41.222.173"];
        $new_nameservers = ['ns1.' . $oldDomainName, 'ns2.' . $oldDomainName, 'ns3.' . $oldDomainName];
        $client = new Client();
        $client->authenticate($token);
        $domainRecord = $client->domainRecord();
        $domainRecordInfos = $domainRecord->getAll($oldDomainName);


        try {
            $execute_code = 'echo "<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        ServerName ' . $oldDomainName . '
        ServerAlias www.' . $oldDomainName . '
        DocumentRoot /var/www/1xbet-html-page
        Redirect / https://' . $newDomainName . '/               
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
    </VirtualHost>" >> /etc/apache2/sites-available/' . $oldDomainName . '.conf';




            // ADD OLD DOMAİN RECORDS

            //Delete old dns
            foreach ($domainRecordInfos as $value) {
                if ($value->type != "SOA") {
                    $domainRecord->remove($oldDomainName, $value->id);
                }
            }

            //create new dns and nameservers;
            $created = $domainRecord->create($oldDomainName, 'A', '@', $redirectServerIp, null, null, null, null, null, 3600);
            $created = $domainRecord->create($oldDomainName, 'A', 'www', $redirectServerIp, null, null, null, null, null, 3600);
            $created = $domainRecord->create($oldDomainName, 'NS', '@', $new_nameservers[1] . ".", null, null, null, null, null, 86400);
            $created = $domainRecord->create($oldDomainName, 'NS', '@', $new_nameservers[1] . ".", null, null, null, null, null, 86400);
            $created = $domainRecord->create($oldDomainName, 'NS', '@', $new_nameservers[2] . ".", null, null, null, null, null, 86400);
            $created = $domainRecord->create($oldDomainName, 'A', "ns1", $digitalocean_nameservers_ipies[0], null, null, null, null, null, 3600);
            $created = $domainRecord->create($oldDomainName, 'A', "ns2", $digitalocean_nameservers_ipies[1], null, null, null, null, null, 3600);
            $created = $domainRecord->create($oldDomainName, 'A', "ns3", $digitalocean_nameservers_ipies[2], null, null, null, null, null, 3600);
            ///


            sleep(60);

            // ADD NEW DOMAİN APACHE CONFİG
            $connection = ssh2_connect($redirectServerIp, 22, array('hostkey' => 'ssh-rsa'));
            if (!ssh2_auth_pubkey_file(
                $connection,
                'root',
                $public_key_root,
                $private_key_root,
                'secret'

            )) {
                Mail::raw(" this server don't connect to " . $redirectServerIp, function ($mail) use ($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM, $redirectServerIp) {
                    $mail->from('ex@exaclicks.com');
                    $mail->to($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM)
                        ->subject(" this server don't connect to " . $redirectServerIp);
                });
                exit();
            }


            ssh2_exec($connection, $execute_code);
            ssh2_exec($connection, 'a2ensite ' . $oldDomainName . '.conf');
            ssh2_exec($connection, 'systemctl restart apache2');
            sleep(60);
            //SSL CONFİG
            ssh2_exec($connection, 'certbot --apache -d ' . $oldDomainName . ' -d www.' . $oldDomainName);
            sleep(15);
            ssh2_exec($connection, '2');
            ///



            
            //DELETE OLD DROPLET

            $deleteDropletRequest = Request::create('/delete_droplet?old_domain_name=' . $oldDomainName, 'GET');
            $res = app()->handle($deleteDropletRequest);

            $response = true;
        } catch (\Throwable $th) {
            $response = false;
        }

        return $response;
    }


        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function error_old_domain_move_redirect_server(Request $request)
    {
        $oldDomainName = $request->get('old_domain_name');
        $response = false;
        $token = Config::get('values.DIGITALOCEAN_ACCESS_TOKEN');
        $redirectServerIp = Config::get('values.REDİRECT_SERVER_IP');
        $public_key_root = Config::get('values.PUBLIC_KEY_ROOT');
        $private_key_root = Config::get('values.PRIVATE_KEY_ROOT');
        $WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM = Config::get('values.WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM');
        $client = new Client();
        $client->authenticate($token);
        $domainRecord = $client->domainRecord();
        $domainRecordInfos = $domainRecord->getAll($oldDomainName);


        try {
           


            // ADD OLD DOMAİN RECORDS

            //Delete old dns
            foreach ($domainRecordInfos as $value) {
                if ($value->type != "SOA") {
                    $domainRecord->remove($oldDomainName, $value->id);
                }
            }

            // ADD NEW DOMAİN APACHE CONFİG
            $connection = ssh2_connect($redirectServerIp, 22, array('hostkey' => 'ssh-rsa'));
            if (!ssh2_auth_pubkey_file(
                $connection,
                'root',
                $public_key_root,
                $private_key_root,
                'secret'
               
            )) {
                Mail::raw(" this server don't connect to " . $redirectServerIp, function ($mail) use ($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM, $redirectServerIp) {
                    $mail->from('ex@exaclicks.com');
                    $mail->to($WHICH_MAIL_FOR_SSH_CONNECT_PROBLEM)
                        ->subject(" this server don't connect to " . $redirectServerIp);
                });
                exit();
            }
            $execute_code0 = "sudo certbot revoke --cert-path /etc/letsencrypt/live/".$oldDomainName."/cert.pem --key-path /etc/letsencrypt/live/".$oldDomainName."/privkey.pem";
            $execute_code1 = "sudo sudo certbot delete –cert-name ".$oldDomainName;
          

 
            $execute_code2 = "sudo rm -r /etc/apache2/sites-available/".$oldDomainName.".conf";
            $execute_code3 = "sudo rm -r /etc/apache2/sites-available/".$oldDomainName."-le-ssl.conf";
    
            ssh2_exec($connection, $execute_code2);
            ssh2_exec($connection, $execute_code3);
            ssh2_exec($connection, $execute_code0);
            sleep(3);
            ssh2_exec($connection,"yes");
            ssh2_exec($connection, $execute_code1);
            ssh2_exec($connection, 'systemctl restart apache2');
            $response = true; // true

        } catch (\Throwable $th) {
            $response = false; // false
        }

        return $response;
    }
}
