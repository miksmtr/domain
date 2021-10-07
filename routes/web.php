<?php

use App\Http\Controllers\CodeController;
use App\Http\Controllers\BetCompanyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\WordController;
use App\Http\Controllers\PageController;

use App\Models\BannedList;
use App\Models\BetCompany;
use App\Models\Code;
use App\Models\Content;
use App\Models\ContentHelper;
use App\Models\Domain;
use App\Models\GitDomain;
use App\Models\Log;
use App\Models\ServerSetting;
use App\Models\Website;
use App\Models\Word;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use phpseclib3\Net\SSH2;

Route::get('/wordcreator', function () {


    BetCompany::truncate();

});






Route::get('/getallwebsites', function () {
    $website = Website::all()->first();
    $part = "/wp-json/wp/v2/posts/";
    $category_part = "/wp-json/wp/v2/categories";
    $post_id = 1;
    $rest_api_link = $website->link . $part . $post_id;
    $rest_api_link_category_part = $website->link . $category_part;
    $categories = null;

    // GET CATEGORIES

    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $rest_api_link_category_part);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    $jsonData = json_decode(curl_exec($curlSession));
    curl_close($curlSession);
    if (!isset($jsonData->data->status))
        $categories = $jsonData;

    //

    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $rest_api_link);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    $jsonData = json_decode(curl_exec($curlSession));

    curl_close($curlSession);
    if (!isset($jsonData->data->status)) {
        $link = $jsonData->slug;
        $title = $jsonData->title->rendered;
        $wp_content = $jsonData->content->rendered;
        $description = $jsonData->excerpt->rendered;
        $category  = '';
    }
    if (isset($jsonData->categories[0])) {
        $cat_id = $jsonData->categories[0];
        foreach ($categories as $key => $value) {
            if ($value->id == $cat_id)
                $category = $value->name;
        }
    }
    $content = new Content();
    $content->first_link = $link;
    $content->first_title = $title;
    $content->first_description = $description;
    $content->first_content = $wp_content;
    $content->first_category = $category;

    $content->rewriter_title = $title;
    $content->rewriter_description = $description;
    $content->rewriter_content = $wp_content;

    $content->status = 1;


    $content->save();
});


Route::get('/server_free', function () {
    $server_settings = ServerSetting::all()->first();
    $server_settings->is_server_busy = false;
    $server_settings->website_picker_busy = false;
    $server_settings->website_picker_second_busy = false;
    $server_settings->new_domain_get_controller = false;
    $server_settings->check_domain_controller = false;
    $server_settings->banned_domain_get_controller = false;

    $server_settings->save();
});



Route::get('/contentdasasdas', function () {
/*    
    Word::truncate();
    ContentHelper::truncate();
    $contents = Content::where('id', ">=", 0)->where('id', "<=", 1000)->where('status', 2)->get();
    foreach ($contents as $key => $value) {
        $value->update(['status' => 0]);
    }
 */

});

use Laravel\Dusk\Browser;



// Homepage Route
Route::get('/cleanerdd', function () {

    $text = '<p>T&uuml;rkiye bahis piyasasında bulunan ve en eski bahis sitelerinden biri olarak bilinen <strong>Tipobet</strong> bahis sitesi, bug&uuml;ne kadar s&uuml;rekliliğini devam ettirmiş ve b&uuml;nyesindeki bahis severlerin b&uuml;y&uuml;k bir &ccedil;oğunluğundan olumlu yorumlar almış ender <a href="https://www.domain.comt/"><em><strong>ka&ccedil;ak&nbsp;bahis siteleri</strong></em></a>nden biridir.</p>

<p>Her ne kadar k&uuml;&ccedil;&uuml;k bir kesimden bazı kısımları adına şikayet alsa da, genel anlamda diğer bir&ccedil;ok bahis sitesinden daha &uuml;st&uuml;n olanakları ve avantajları ile memnun etmektedir. <strong>Tipobet365</strong> Almanya k&ouml;kenli olarak kurulmuştur ve lisansını Curacao elde etmiştir.</p>

<p>G&uuml;&ccedil;l&uuml; bir mali kaynak ile <a href="https://www.domain.comt/" target="_blank"><strong>bedava bahis</strong></a> d&uuml;nyasında bulunan <strong>tipobet bahis sitesi</strong>, lisansı adına t&uuml;m kriterleri yerine getirmiş ve &uuml;stelik s&uuml;rekli olarak denetlenen birisi olarak &ouml;n plana &ccedil;ıkmaktadır.</p>

<p><strong>Tipobet&rsquo;e &uuml;ye</strong> olarak sizlerde iyi kazan&ccedil;lar sağlayabilirsiniz. En yeni ma&ccedil;lar ve bahislerle beraber kazanma şansınızı arttırın. Avrupanın en iyi bahis sitesinde oynama keyfini sizde &ccedil;ıkartın.</p>

<p>&nbsp;</p>

<p><a href="http://girarmut.xyz/go/tipobet" target="_blank">HEMEN &Uuml;YE OL</a></p>

<h2>Tipobet Bahis</h2>

<p>2008 yılından bu yana d&uuml;nya bahis piyasası ile birlikte T&uuml;rkiye bahis piyasasında bulunan <strong>tipobet şikayet</strong>, T&uuml;rk&ccedil;e dil desteği sayesinde kullanışlı bir ara y&uuml;z&uuml; sunarak zengin bir bahis se&ccedil;eneği ile &ccedil;ok uzun bir s&uuml;redir g&uuml;venilir bir hizmet vermektedir.</p>

<p>&nbsp;</p>

<h2><strong>Tipobet G&uuml;venilir mi?</strong></h2>

<p>Masa&uuml;st&uuml; versiyonu ile birlikte <a href="https://www.domain.comt/tipobet-mobil.html"><strong>Tipobet mobil</strong></a> versiyon sistemini de b&uuml;nyesinde bulunduran <strong>Tipobet365 bahis sitesi giriş</strong> i&ccedil;in her iki se&ccedil;eneği de rahatlıkla kullanabilirsiniz. İş yerinden, evden veya dışarıda herhangi bir noktadan kolayca ve herhangi bir bağlantı sorun yaşamadan mobil hizmeti sayesinde t&uuml;m bahis oyunlarına keyifle oynayabilirsiniz.</p>

<p><strong>Tipobet giriş</strong> ayrıca 2008 yılından beri canlı destek hizmetini 7/24 sağlayarak b&uuml;nyesindeki t&uuml;m bahis severlerin sorunlarına doğrudan iletişim kurup &ccedil;&ouml;z&uuml;mlemektedir. &Ouml;zellikle g&uuml;n ge&ccedil;tik&ccedil;e artan y&uuml;ksek oranları ile canlı bahis fırsatı, spor bahis olanakları ve t&uuml;m <strong>Tipobet canlı casino</strong> oyunlarını da sitenin ana sayfası &uuml;zerinden olabilir ve <strong>Tipobet bahis sitesi&nbsp;</strong>b&uuml;nyesinde iyi kazan&ccedil;lar elde edebilirsiniz.</p>

<h2><strong>Tipobet365 bahis se&ccedil;enekleri</strong></h2>

<p>Her anlamda bug&uuml;ne kadar b&uuml;nyesine kattığı bahis severlere kaliteli ve g&uuml;venilir hizmet sunmaya devam eden <strong>tipobet bahis sitesi</strong>, zengin bir se&ccedil;enek altında <a href="https://www.domain.comt/tipobet-para-yatirma.html"><strong>Tipobet365 para yatırma</strong></a> ve <a href="https://www.domain.comt/tipobet-para-cekme.html"><strong>Tipobet para &ccedil;ekme</strong></a> işlemlerini de hem normal sistemler hem de sanal işlemler kapsamında her bahis severin kişisel tercihlerine g&ouml;re sunmaktadır.</p>

<p>&Ouml;zellikle bookie altyapı b&uuml;nyesinde &ccedil;ok uzun bir s&uuml;redir sorunsuz bir bağlantı sunan <strong>tipobet365 giriş </strong>ile aynı zamanda bir&ccedil;ok değişik bonus se&ccedil;eneklerini de değerlendirebilirsiniz. <strong>Tipobet şikayet</strong> sitesi b&uuml;nyesinde en &ccedil;ok ilgi &ccedil;ekenlerden biri 365 TL değerindeki 0 gibi bir geri d&ouml;n&uuml;ş sayesinde bahis severlere sunulan hoş geldin bonusudur.</p>

<p><strong>Tipobet365</strong> d&uuml;nyanın en iyi sitelerinden birisi olduğunu verdiği bonuslar ve bedava bahislerle belli ediyor. Sizlerde bu harika siteden para kazanmak istiyorsanız &uuml;ye olup bedava bonus imkanlarından yararlanabilirsiniz.</p>

<p>Basit birka&ccedil; &ccedil;evrim şartını yerine getirdikten sonra &uuml;ye olurken bu bonus &ccedil;eşidini kolaylıkla elde edebilirsiniz. Ayrıca daha &ouml;nce iyi olmuş bahis severler i&ccedil;in de yeni site b&uuml;nyesinde farklı boncuk &ccedil;eşitleri s&uuml;rekli olarak g&uuml;ncellenmiş hali de sunulmaktadır.</p>

<p><a href="http://girarmut.xyz/go/tipobet365" target="_blank">HEMEN &Uuml;YE OL</a></p>
';
    echo strpos($text, '<h2>') . "<br>";
    echo strpos($text, '</h2>');

    /* ; ilk h yi bul 
bu h den </h< ye kadar al bu başlık
ilk h konumundan ikinci h ye kadar içerik

başlık ve içerik kaydet 
 */
    /*     GitDomain::truncate();
    Domain::truncate();
    BannedList::truncate();
    Content::truncate();
    Website::truncate();
 */
});

Route::get('/change_website_status/{id}', function ($id) {
    $website = Website::where('id', $id)->get()->first();
    $website->status = -1;
    $website->save();
});

Route::get('/delete_website_content/{id}', function ($id) {

    $contents = Content::where('website_id', $id)->get();
    foreach ($contents as $key => $value) {
        $value->delete();
    }
});


Route::get('/wrong_content_delete', function () {
    dd(5);
    for ($i = 1093; $i < 1433; $i++) {
        $content = Content::where('id', $i)->get()->first();
        $content->delete();
    }
});


Route::get('/testerrrr', function () {

    $TR_SERVER_IP = Config::get('values.TR_SERVER_IP');
    $TR_SERVER_SSH_USERNAME = Config::get('values.TR_SERVER_SSH_USERNAME');
    $TR_SERVER_PASSWORD = Config::get('values.TR_SERVER_PASSWORD');

    $ssh = new SSH2($TR_SERVER_IP);
    if (!$ssh->login($TR_SERVER_SSH_USERNAME, $TR_SERVER_PASSWORD)) {


        dd("hata");
    }

    $link = "http://rosaluxspba.org/wp-json/wp/v2/categories";
    $command = 'curl -s -H "Proxy-Connection: keep-alive"  -H "Cache-Control: max-age=0"   -H "Upgrade-Insecure-Requests: 1" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36" -H "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"  -H "Accept-Language: tr-TR,tr;q=0.9,tr;q=0.8" ' . $link;
    $html = $ssh->exec($command);
    $jsonData = json_decode($html);
    dd($jsonData);
});

Route::get('/custom_get_content/{id}', function ($id) {

    $TR_SERVER_IP = Config::get('values.TR_SERVER_IP');
    $TR_SERVER_SSH_USERNAME = Config::get('values.TR_SERVER_SSH_USERNAME');
    $TR_SERVER_PASSWORD = Config::get('values.TR_SERVER_PASSWORD');

    $part = "/wp-json/wp/v2/posts/";
    $category_part = "/wp-json/wp/v2/categories";


    $ssh = new SSH2($TR_SERVER_IP);
    if (!$ssh->login($TR_SERVER_SSH_USERNAME, $TR_SERVER_PASSWORD)) {


        return 0;
    }


    $website = Website::where('id', $id)->get()->first();



    $log = new Log();
    $log->type = 0;
    $log->title = "Başarılı";
    $log->which_worker = "websitePickerSecond";
    $log->description = $website->link . " içerikleri çekilmeye başlandı.";


    try {
        $timer = 0;
        for ($i = 1; $i < 50000; $i++) {



            $post_id = $i;
            $rest_api_link = $website->link . $part . $post_id;
            $command = 'curl -s -H "Proxy-Connection: keep-alive"  -H "Cache-Control: max-age=0"   -H "Upgrade-Insecure-Requests: 1" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36" -H "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9"  -H "Accept-Language: tr-TR,tr;q=0.9,tr;q=0.8" ' . $rest_api_link;
            $html = $ssh->exec($command);
            $jsonData = json_decode($html);

            if ($timer == 500) {
                //break;
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

    }
});



Route::get('/ban/{newDomainName}', function ($newDomainName) {
    $domain = Domain::where('name', $newDomainName)->get()->first();
    if ($domain) {
        $domain->status = 3;
        $domain->domain_status = 1;
        $domain->save();
    }
});

Route::get('/', 'App\Http\Controllers\WelcomeController@welcome')->name('welcome');
Route::get('/terms', 'App\Http\Controllers\TermsController@terms')->name('terms');
Route::get('/un_used_domain_create', 'App\Http\Controllers\DomainController@un_used_domain_create')->name('un_used_domain_create');
Route::post('/un_used_domain_delete', 'App\Http\Controllers\DomainController@un_used_destroy')->name('un_used_domain_delete');
Route::get('/un_used_domain_index', 'App\Http\Controllers\DomainController@un_used_domain_index')->name('un_used_domain_index');
Route::post('/un_used_domain_store', 'App\Http\Controllers\DomainController@un_used_domain_store')->name('un_used_domain_store');
Route::get('/movable_and_used_domain_index', 'App\Http\Controllers\DomainController@movable_and_used_domain_index')->name('movable_and_used_domain_index');


Route::resource('domains', DomainController::class);
Route::resource('codes', CodeController::class);
Route::resource('bet_companies', BetCompanyController::class);
Route::resource('websites', WebsiteController::class);
Route::resource('contents', ContentController::class);
Route::resource('words', WordController::class);


Route::post('/rewriter', 'App\Http\Controllers\ContentController@rewriter')->name('rewriter');


// Homepage Route
Route::group(['middleware' => ['web', 'checkblocked']], function () {


    // PAGE CONTROLLER

    Route::get('/create_sitemap', 'App\Http\Controllers\PageController@create_sitemap')->name('create_sitemap');
    Route::get('/create_page', 'App\Http\Controllers\PageController@create_page')->name('create_page');





    //ADD NEW DROPLET
    Route::get('/add_new_droplet', 'App\Http\Controllers\DropletController@add_new_droplet')->name('add_new_droplet');
    //DELETE NEW DROPLET
    Route::get('/delete_droplet', 'App\Http\Controllers\DropletController@delete_droplet')->name('delete_droplet');

    // GİT
    Route::get('/add_new_git_domain', 'App\Http\Controllers\GitController@add_new_git_domain')->name('add_new_git_domain');
    Route::get('/delete_git_domain', 'App\Http\Controllers\GitController@delete_git_domain')->name('delete_git_domain');




    // ACTIONS

    Route::get('/new_add_and_old_delete_request', 'App\Http\Controllers\ActionController@new_add_and_old_delete_request')->name('new_add_and_old_delete_request');
    Route::get('/new_add_request', 'App\Http\Controllers\ActionController@new_add_request')->name('new_add_request');



    // THİS APİ CAN ADD NEW DNS RECORDS AND APACHE CONFİG
    Route::get('/add_new_domain_server_records', 'App\Http\Controllers\DomainController@add_new_domain_server_records')->name('add_new_domain_server_records');
    // THİS APİ CAN ADD OLD DNS RECORDS AND REDİRECT SERVER APACHE CONFİG
    Route::get('/old_domain_move_redirect_server', 'App\Http\Controllers\DomainController@old_domain_move_redirect_server')->name('old_domain_move_redirect_server');

    Route::get('/error_new_domain_server_records_delete', 'App\Http\Controllers\DomainController@error_new_domain_server_records_delete')->name('error_new_domain_server_records_delete');

    Route::get('/error_old_domain_move_redirect_server', 'App\Http\Controllers\DomainController@error_old_domain_move_redirect_server')->name('error_old_domain_move_redirect_server');

    Route::get('/banlanmalogu', 'App\Http\Controllers\DomainController@banlanmalogu')->name('banlanmalogu');
    Route::get('/server_setting', 'App\Http\Controllers\ServerSettingController@index')->name('server_setting');

    // LOGGİNG
    Route::get('/logging', 'App\Http\Controllers\LogController@index')->name('logging');
});































































// Authentication Routes
Auth::routes();

// Public Routes
Route::group(['middleware' => ['web', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activate', ['as' => 'activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@initial']);

    Route::get('/activate/{token}', ['as' => 'authenticated.activate', 'uses' => 'App\Http\Controllers\Auth\ActivateController@activate']);
    Route::get('/activation', ['as' => 'authenticated.activation-resend', 'uses' => 'App\Http\Controllers\Auth\ActivateController@resend']);
    Route::get('/exceeded', ['as' => 'exceeded', 'uses' => 'App\Http\Controllers\Auth\ActivateController@exceeded']);

    // Socialite Register Routes
    Route::get('/social/redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialRedirect']);
    Route::get('/social/handle/{provider}', ['as' => 'social.handle', 'uses' => 'App\Http\Controllers\Auth\SocialController@getSocialHandle']);

    // Route to for user to reactivate their user deleted account.
    Route::get('/re-activate/{token}', ['as' => 'user.reactivate', 'uses' => 'App\Http\Controllers\RestoreUserController@userReActivate']);
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'checkblocked']], function () {

    // Activation Routes
    Route::get('/activation-required', ['uses' => 'App\Http\Controllers\Auth\ActivateController@activationRequired'])->name('activation-required');
    Route::get('/logout', ['uses' => 'App\Http\Controllers\Auth\LoginController@logout'])->name('logout');
});

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'activated', 'activity', 'twostep', 'checkblocked']], function () {

    //  Homepage Route - Redirect based on user role is in controller.
    Route::get('/home', ['as' => 'public.home',   'uses' => 'App\Http\Controllers\UserController@index']);





    // Show users profile - viewable by other users.
    Route::get('profile/{username}', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@show',
    ]);
});

// Registered, activated, and is current user routes.
Route::group(['middleware' => ['auth', 'activated', 'currentUser', 'activity', 'twostep', 'checkblocked']], function () {



    // User Profile and Account Routes
    Route::resource(
        'profile',
        \App\Http\Controllers\ProfilesController::class,
        [
            'only' => [
                'show',
                'edit',
                'update',
                'create',
            ],
        ]
    );
    Route::put('profile/{username}/updateUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserAccount',
    ]);
    Route::put('profile/{username}/updateUserPassword', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@updateUserPassword',
    ]);
    Route::delete('profile/{username}/deleteUserAccount', [
        'as'   => '{username}',
        'uses' => 'App\Http\Controllers\ProfilesController@deleteUserAccount',
    ]);

    // Route to show user avatar
    Route::get('images/profile/{id}/avatar/{image}', [
        'uses' => 'App\Http\Controllers\ProfilesController@userProfileAvatar',
    ]);

    // Route to upload user avatar.
    Route::post('avatar/upload', ['as' => 'avatar.upload', 'uses' => 'App\Http\Controllers\ProfilesController@upload']);
});

// Registered, activated, and is admin routes.
Route::group(['middleware' => ['auth', 'activated', 'role:admin', 'activity', 'twostep', 'checkblocked']], function () {
    Route::resource('/users/deleted', \App\Http\Controllers\SoftDeletesController::class, [
        'only' => [
            'index', 'show', 'update', 'destroy',
        ],
    ]);

    Route::resource('users', \App\Http\Controllers\UsersManagementController::class, [
        'names' => [
            'index'   => 'users',
            'destroy' => 'user.destroy',
        ],
        'except' => [
            'deleted',
        ],
    ]);
    Route::post('search-users', 'App\Http\Controllers\UsersManagementController@search')->name('search-users');

    Route::resource('themes', \App\Http\Controllers\ThemesManagementController::class, [
        'names' => [
            'index'   => 'themes',
            'destroy' => 'themes.destroy',
        ],
    ]);

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('routes', 'App\Http\Controllers\AdminDetailsController@listRoutes');
    Route::get('active-users', 'App\Http\Controllers\AdminDetailsController@activeUsers');
});

Route::redirect('/php', '/phpinfo', 301);























Route::get('/create_image_from_big_images', function () {

    // Image
    $dir = '/home/nox/Sites/website/source-images/';
    $sourceDir = '/home/nox/Downloads/images/';
    $files = scandir($sourceDir);

    for ($i = 3; $i < count($files); $i++) {


        $new_w= 1280;
        $new_h= 720;

        $fileName = $files[$i];
        list($width, $height) = getimagesize($sourceDir .$fileName);
        $fileNameType = explode(".", $fileName)[1];
        $thumb = imagecreatetruecolor($new_w, $new_h);
        if ($fileNameType == "jpeg")
            $img = imagecreatefromjpeg($sourceDir . $fileName);
        else
            $img = imagecreatefrompng($sourceDir . $fileName);
        imagepalettetotruecolor($img);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        $newFileName  = $dir . ($i - 2) . ".webp";
        imagecopyresized($thumb, $img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
        imagewebp($thumb, $newFileName, 70);
        imagedestroy($img);
       
    }




    exit();



    ini_set('max_execution_time', 18000); //3 minutes
    $contents = ContentHelper::all();
    dd($contents);
});
