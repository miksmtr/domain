<?php

namespace App\Http\Controllers;

use App\Models\BetCompany;
use App\Models\Content;
use App\Models\ContentHelper;
use App\Models\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use DigitalOceanV2\Client;


class PageController extends Controller
{





  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function create_sitemap(Request $request)
  {

    $website_link = "https://renterall.com";
    $now = date('Y.m.d', strtotime("-1 days"));
    $sitemapFile = fopen("/home/nox/Sites/website/sitemap.xml", "w") or die("Unable to open file!");
    fwrite($sitemapFile, '<?xml version="1.0" encoding="UTF-8"?>
        <urlset
              xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
              xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
              xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
        
        \n');

    $contents = Content::where('status', 2)->get();

    foreach ($contents as $key => $content) {
      fwrite($sitemapFile, '<url>
        <loc>' . $website_link . '/' . $content->last_link . '.php</loc>
        <lastmod>' . $now . 'T11:58:47+00:00</lastmod>
      </url>');
    }




    $txt = "Jane Doe\n";
    fwrite($sitemapFile, "</urlset>");
    fclose($sitemapFile);
  }



  public function content_helper_changer($model, $content)
  {
    $content_helpers = array();
    $bet_company_id = $model->bet_company_id;

    // eğer kendi içinde tekrarlamış varsa onun amınakoy 
    $content = $this->deleteRepeatElements($content);
    // 2ciden sonra diğerlerini değiştir.
    foreach ($content as $key => $value) {
      if ($key > 0 && $value->status == 0) {
        $contents = Content::where('bet_company_id', $bet_company_id)->get();
        //        dd($contents);
        $contents = $this->myshuffle($contents);

        if (count($contents) > 1) {
          $id = 0;
          if ($contents[0]->id == $value->content_id) {
            echo $value->content_id;
            dd($contents);
            $id = $contents[1]->id;
          } else {
            $id = $contents[0]->id;
          }
          if ($id != 0) {
            $cha = ContentHelper::where('content_id', $id)->where('status', 0)->get();

            if (count($cha) > 1) {
              $oldH = $value->h;
              $oldP = $value->p;
              $oldStatus = $value->status;
              $oldContantId = $value->content_id;

              $cha = $cha[1];

              $value->h = $cha->h;
              $value->p = $cha->p;
              $value->status = 1;
              // $value->content_id = $cha->content_id;

              $value->save();

              $cha->h = $oldH;
              $cha->p = $oldP;
              $cha->status = $oldStatus;
              $cha->save();
            }
          }
        }
      }
    }

    $content = ContentHelper::where('content_id', $model->id)->get();

    return $content;
  }




  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function create_page(Request $request)
  {

    ini_set('max_execution_time', '0'); //300 seconds = 5 minutes

    $contets = Content::where('status',2)->get();
   foreach ($contets as $key => $value) {
     if($key==10)
     dd('done');
     $this->create_page_from_model($value->id);
   }
   
  
  
  
  }
  
  public function create_page_from_model($id)
  {
    $model = Content::where('id', $id)->get()->first();
    $bet_campanies = BetCompany::where('type', 1)->get();
    $bet_campanies_all = BetCompany::all();

    $website_link = "https://renterall.com";
    $title = $model->last_title;
    $description = $model->last_description;
    $link = $model->last_link;
    $content = ContentHelper::where('content_id', $model->id)->get();

    $content = $this->content_helper_changer($model, $content);

    $this->create_page_function($model, $website_link, $link, $title, $description, $content, $bet_campanies, $bet_campanies_all);
  
  }

  public function create_page_function($model, $website_link, $link, $title, $description, $content, $bet_campanies, $bet_campanies_all)
  {
    $this->create_image_for_page($link);
    $this->create_image_for_page($link."-2");
    $this->create_image_for_page($link."-3");


    $phpFile = fopen("/home/nox/Sites/website/pages/" . $link . ".php", "w") or die("Unable to open file!");
    $htmlFile = fopen("/home/nox/Sites/website/pages/" . $link . ".html", "w") or die("Unable to open file!");
    $phpFileAmp = fopen("/home/nox/Sites/website/pages/" . $link . "-amp.php", "w") or die("Unable to open file!");
    $htmlFileAmp = fopen("/home/nox/Sites/website/pages/" . $link . "-amp.html", "w") or die("Unable to open file!");
    
    fwrite($phpFile, "<?php include '../router.php';?>");
    fwrite($phpFileAmp, "<?php include '../router.php';?>");
    fclose($phpFileAmp);
    fclose($phpFile);


    $data = array(
      'website_link' => $website_link,
      'link' => $website_link,
      'model' => $model,
      'link' => $link,
      'title' => $title,
      'content' => $content,
      'bet_campanies' => $bet_campanies,
      'bet_campanies_all' => $bet_campanies_all,
      'description' => $description,
    );


    fwrite($htmlFile, $this->get_page($data));
    fwrite($htmlFileAmp, $this->get_page_amp($data));

    fclose($htmlFile);
  }



  public function cleaner($text)
  {
    $response = strip_tags($text, "</");
    $response = trim($response, "</");
    $response = trim($response, "<");
    $response = trim($response, ">");

    return $response;
  }

  public function get_body($data,$amp=false)
  {
    $website_link =  $data['website_link'];
    $link = $data['link'];
    $bet_campanies = $data['bet_campanies'];
    $bet_campanies_all = $data['bet_campanies_all'];
    $model = $data['model'];
    $content = $data['content'];
    if($amp){
      $image = '<amp-img data-amp-auto-lightbox-disable class="" layout="responsive" src="' . $website_link . '/images/' . $link . '.webp" alt="' . $model->last_title . '" width="1280" height="720"></amp-img>';
      $image2 = '<amp-img data-amp-auto-lightbox-disable class="" layout="responsive" src="' . $website_link . '/images/' . $link . '-2.webp" alt="' . $model->last_title . '-2" width="1280" height="720"></amp-img>';
      $image3 = '<amp-img data-amp-auto-lightbox-disable class="" layout="responsive" src="' . $website_link . '/images/' . $link . '-3.webp" alt="' . $model->last_title . '-3" width="1280" height="720"></amp-img>';
  
    }else{
      $image = '<img class="responsive w3-container" src="' . $website_link . '/images/' . $link . '.webp" alt="' . $model->last_title . '" />';
      $image2 = '<img class="responsive w3-container"  src="' . $website_link . '/images/' . $link . '-2.webp" alt="' . $model->last_title . '-2" />';
      $image3 = '<img class="responsive w3-container" src="' . $website_link . '/images/' . $link . '-3.webp" alt="' . $model->last_title . '-3"/>';
    }
 
    $table = $this->create_table($bet_campanies, $website_link);
    $smallTable = $this->create_small_table($bet_campanies, $website_link);
    $afiliateButton = $this->create_afiliate_button($model, $website_link);
    $suggestions = $this->create_suggestions($model, $website_link);
    $suggestionsGeneral = $this->create_suggestions_general($model, $website_link);



    $text =  $image;
    $extra = '';
    if ($model->bet_company_id == 1) {
      $extra = $table;
    } else {
      if ($model) {
        $extra = $afiliateButton;
      } else {
        $extra = $table;
      }
    }

    foreach ($content as $key => $value) {

      if ($key == 0) {
        $text = $text . "<div class='w3-container'><h1>" . $this->cleaner($value->h)  . "</h1>" . "<p>" . $this->cleaner($value->p) . "</p></div>";
      } else if ($key == 1) {
        $text = $text . "<div class='extra'>$extra</div>" . "<div class='w3-container'><h2>" . $this->cleaner($value->h)  . "</h2>" . "<p>" . $this->cleaner($value->p) . "</p></div>";
      } else {
        if($key==3){
          $text = $text .$image2. "<div class='w3-container'><h2>" . $this->cleaner($value->h)  . "</h2>" . "<p>" . $this->cleaner($value->p) . "</p></div>";
        }else if($key==5){
          $text = $text .$image3. "<div class='w3-container'><h2>" . $this->cleaner($value->h)  . "</h2>" . "<p>" . $this->cleaner($value->p) . "</p></div>";
        }else{
          $text = $text . "<div class='w3-container'><h2>" . $this->cleaner($value->h)  . "</h2>" . "<p>" . $this->cleaner($value->p) . "</p></div>";

        }
      }
    }





    $bodyContent = '
    <div class="w3-col l8 s12">
    <div class="w3-card-4 w3-margin w3-white">
        
        ' . $text . '
        
        <div class="suggestions w3-container">' . $suggestions . '</div>
        
        <div class="suggestions w3-container">' . $suggestionsGeneral . '</div>
      
        
        ' .


      ' 
       
        </div>
        <hr>
      </div>
     
      ' . $smallTable  . '
        ';
    $body =
      $bodyContent;


    return $body;
  }




  function create_table($bet_campanies, $website_link)
  {

    // Image
    $table = '';
    $tableMid = '';
    $tableHeader = '
        <table class="darkTable"><thead><tr>
        
           <td class="desktop" style="width: 5%;"></td>
           <td style="width: 20%;">Bahis Sitesi</td>
           <td class="desktop" style="width: 35%;">Bonuslar</td>
           <td id="desktop" style="width: 15%;">Puan</td>
           <td style="width: 25%;">İnceleme</td>

        </tr></thead><tbody> ';
    $tableFooter = '</tbody></table>';

    foreach ($bet_campanies as $key => $value) {
      $logo = '<amp-img data-amp-auto-lightbox-disable src="' . $website_link . '/images/' . $value->name . '_logo.webp" alt="' . $value->name  . '" width="90" height="40"></amp-img>';



      $tableMid = $tableMid . '     <tr>
            <td class="desktop" style="width: 5%;"> ' . ($key + 1) . '</td>
            <td style="width: 20%;"><a href="' . $website_link . '/' . $value->name . '.php"' . $website_link . '/">' . $logo . '</a></td>
            <td class="desktop" style="width: 35%;">' . $value->free_bonus . '</td>
            <td id="desktop" style="width: 15%;">&#11088;&#11088;&#11088;&#11088;&#11088;</td>
            <td style="width: 25%;"><a class="reviewButton" href="' . $website_link . '/' . $value->name . '.php">İncele</a><a href="' . $value->link . '" class="goButton">Bonus</a></td></tr>';
    }

    $table = $tableHeader . $tableMid . $tableFooter;



    return $table;
  }

  function create_small_table($bet_campanies, $website_link)
  {

    // Image
    $table = '';
    $tableMid = '';
    $tableHeader = '
    <div class="w3-col l4">

    <!-- Güvenilir Bahis Siteleri -->
    <div class="w3-card w3-margin">
      <div class="w3-container w3-padding">
        <h4>Güvenilir Bahis Siteleri</h4>
      </div> <ul>';
    $tableFooter = ' 
    </ul>
  </div></div>';
    foreach ($bet_campanies as $key => $value) {
      $logo = '<amp-img data-amp-auto-lightbox-disable src="' . $website_link . '/images/' . $value->name . '_logo.webp" alt="' . $value->name  . '" width="90" height="40"></amp-img>';

      $tableMid = $tableMid . '
      <li class="w3-padding-16">
      ' . $logo . '
      <span class="w3-large">' . $value->name . '</span><div class="buttonArea"><a href="' . $website_link . '/' . $value->name . '.php" class="reviewButton">İncele</a><a href="' . $value->link . '" class="goButton">Bonus</a></div>
    </li>
      ';
    }

    $table = $tableHeader . $tableMid . $tableFooter;



    return $table;
  }

  

  function create_suggestions($model, $website_link)
  {

    $contents = Content::where('bet_company_id', $model->bet_company_id)->where('status', 2)->get();
    if(count($contents) > 9){
      $contents = $contents->random(9);
    }
    // Image
    //    dd($contents);
    $table = '';
    $tableMid = '';

    $tableHeader = '<hr><h3 style="font-size: 34px;text-align:left;">Alakalı Yazılar</h3>';
    $tableFooter = '';
    foreach ($contents as $key => $value) {
      $image = '<amp-img data-amp-auto-lightbox-disable layout="responsive" src="' . $website_link . '/images/' . $value->last_link . '.webp" alt="' . $value->last_title . '" width="200" height="150"></amp-img>';
      $tableMid = $tableMid . '

      <div class="suggestionsItem">
      ' . $image . '
   
      <p><a href="' . $website_link . '/' . $value->last_link . '.php" class="">' . $value->last_title . '</a></p>
      </div>
      ';
    }

    $table = $tableHeader . "<div>" . $tableMid . "</div>" . $tableFooter;



    return $table;
  }

  function create_suggestions_general($model, $website_link)
  {

    $contents = Content::where('bet_company_id', 1)->where('status', 2)->get();
    if(count($contents) > 9){
      $contents = $contents->random(9);
    }
    // Image
    //    dd($contents);
    $table = '';
    $tableMid = '';
    $tableHeader = '<hr><h3 style="font-size: 34px;text-align:left;" >Son Yazılar</h3>';
    $tableFooter = '';
    foreach ($contents as $key => $value) {
      $image = '<amp-img data-amp-auto-lightbox-disable layout="responsive" src="' . $website_link . '/images/' . $value->last_link . '.webp" alt="' . $value->last_title . '" width="200" height="150"></amp-img>';
      $tableMid = $tableMid . '

      <div class="suggestionsItem">
      ' . $image . '
   
      <p><a href="' . $website_link . '/' . $value->last_link . '.php" class="">' . $value->last_title . '</a></p>
      </div>
      ';
    }

    $table = $tableHeader . "<div>" . $tableMid . "</div>" . $tableFooter;



    return $table;
  }

  function  create_afiliate_button($model, $website_link)
  {
    $bet_company = BetCompany::where('id', $model->bet_company_id)->get();
    if (count($bet_company) > 0) {
      return '<p style="text-align:center;"><a class="goButtonInPage" href="' . $website_link . '/' . $bet_company->first()->link . '" class="">Giriş Yapmak İçin Tıkla</a></p>';
    } else {
      return '';
    }
  }






  public function get_header($data)
  {
    return $header = '<div class="header">
    <a href="' . $data['website_link'] . '" class="logo"><img id="desktop" src="' . $data['website_link'] . '/images/logo.webp" alt="logo" width="70" height="70"/></a>
    <div class="header-right">
      <a href=""' . $data['website_link'] . '">Ana Sayfa</a>
      <a href="#contact">Bahis Siteleri</a>
      <a href="' . $data['website_link'] . '/hakkimizda.php">Hakkımızda</a>
      <a href="' . $data['website_link'] . '/iletisim.php">İletişim</a>
    </div>
    </div>';
  }


  public function get_header_amp($data)
  {
    return $header = '<div class="header">
    <a href="' . $data['website_link'] . '" class="logo"><amp-img data-amp-auto-lightbox-disable id="desktop" src="' . $data['website_link'] . '/images/logo.webp" alt="logo" width="70" height="70"></amp-img></a>
    <div class="header-right">
      <a href=""' . $data['website_link'] . '">Ana Sayfa</a>
      <a href="#contact">Bahis Siteleri</a>
      <a href="' . $data['website_link'] . '/hakkimizda.php">Hakkımızda</a>
      <a href="' . $data['website_link'] . '/iletisim.php">İletişim</a>
    </div>
    </div>';
  }

  public function get_footer($data)
  {
    // Image
    $bet_campanies = $data['bet_campanies'];
    $website_link = $data['website_link'];
    $footer = '';
    $footerTop = '<footer style="width:100%;"><div style="text-align: center;padding:3%;background-color:#f1f1f1;margin-left: 5%;margin-right: 5%;margin-top: 1%;padding:0.5%;">';
    $footerBottom = ' <div>Bahis siteleri hakkında bilgiler</div></div></footer>';
    $footerMid = '2021 Yılı Bahis Siteler Giriş Adresleri ve Promosyonlar...';
    /* foreach ($bet_campanies as $key => $value) {
      $footerMid = $footerMid . '   
             <a href="' . $website_link . '/' . $value->name . '" target="_blank">' . $value->name . '</a> |
             ';
    } */
    $footer = $footerTop . $footerMid . $footerBottom;
    return $footer;
  }

  public function get_page($data)
  {
    return $body = '
    <!DOCTYPE html>
        <html lang="TR-tr">
        ' . $this->get_head($data) . '
        <body class="w3-light-grey">

        <div class="w3-content" style="max-width:1400px">
        ' . $this->get_header($data) . '


          <!-- Grid -->
          <div class="w3-row">
        ' . $this->get_body($data) . ' 
     </div><br>
' . $this->get_footer($data) . '
        <!-- END w3-content -->
      </div>
  
        </html>
    ';
  }

  public function get_page_amp($data)
  {
    return $body = '
    <!DOCTYPE html>
        <html amp lang="TR-tr">
        ' . $this->get_head_amp($data) . '
        <body class="w3-light-grey">

        <div class="w3-content" style="max-width:1400px">
        ' . $this->get_header_amp($data) . '


          <!-- Grid -->
          <div class="w3-row">
        ' . $this->get_body($data,true) . ' 
     </div><br>
' . $this->get_footer($data) . '
        <!-- END w3-content -->
      </div>
  
        </html>
    ';
  }




  public function get_head($data)
  {
    $website_link =  $data['website_link'];
    $title = $data['title'];
    $link = $data['link'];
    $description = $data['description'];

    $titleChangeWords = array("2017", "2018", "2019", "2020");
    foreach ($titleChangeWords as $key => $value) {
      if (strpos($title, $value))
        $title = str_replace($value, "2021", $title);
    }


    return $head = '
    <head>
        <meta name="robots" content="yes" />
        <meta name="robots" content="all, index, follow" />
        <meta charset="utf-8"> 
        <meta http-equiv="Content-Language" content="tr" />
        <meta name="location" content="Türkiye, tr, turkey" />
        <meta name="language" content="tr-TR" />
        <link rel="amphtml" href="' . $website_link . '/' . $link . '-amp.php">
        <title> ' . $title . ' </title>
        <meta name="description" content="' . $description . '">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta property="og:url" content="' . $website_link . '/' . $link . '.php"/>
        <meta property="og:title" content="' . $title . '"/>
        <meta property="og:site_name" content="1xbet Tanıtım Sitesi"/>
        <meta property="og:image" content="' . $website_link . '/images/logo.webp"/>
        <meta name="twitter:url" content="' . $website_link . '/' . $link . '.php"/>
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:title" content="' . $title . '"/>
        <meta name="twitter:image:src" content="' . $website_link . '/images/logo.webp"/>
        <script type="application/ld+json">
          {
            "@context": "https://schema.org",
            "@graph": [
              {
                "@type": "Organization",
                "@id": "' . $website_link . '/#organization",
                "name": "Bahis Siteleri",
                "url": "' . $website_link . '/",
                "logo": {
                  "@type": "ImageObject",
                  "@id": "' . $website_link . '/#logo",
                  "inLanguage": "tr",
                  "url": "' . $website_link . '/images/logo.webp",
                  "contentUrl": "' . $website_link . '/images/logo.webp",
                  "width": 200,
                  "height": 200,
                  "caption": "Bahis Siteleri"
                },
                "image": {
                  "@id": "' . $website_link . '/#logo"
                }
              },
              {
                "@type": "WebSite",
                "@id": "' . $website_link . '/#website",
                "url": "' . $website_link . '/",
                "name": "Bahis Siteleri",
                "description": "' . $description . '",
                "publisher": {
                  "@id": "' . $website_link . '/#organization"
                },
                "inLanguage": "tr"
              },
              {
                "@type": "ImageObject",
                "@id": "' . $website_link . '/' . $link . '.php#primaryimage",
                "inLanguage": "tr",
                "url": "' . $website_link . '/images/' . $link . '.webp",
                "contentUrl": "' . $website_link . '/images/' . $link . '.webp",
                "width": 500,
                "height": 300
              },
              {
                "@type": "WebPage",
                "@id": "' . $website_link . '/' . $link . '.php#webpage",
                "url": "' . $website_link . '/' . $link . '.php",
                "name": "' . $title . ' | Bahis Siteleri",
                "isPartOf": {
                  "@id": "' . $website_link . '/#website"
                },
                "primaryImageOfPage": {
                  "@id": "' . $website_link . '/' . $link . '.php#primaryimage"
                },
                "datePublished": "2019-11-19T20:06:56+00:00",
                "dateModified": "2021-06-24T15:17:05+00:00",
                "breadcrumb": {
                  "@id": "' . $website_link . '/' . $link . '.php#breadcrumb"
                },
                "inLanguage": "tr",
                "potentialAction": [
                  {
                    "@type": "ReadAction",
                    "target": [
                      "' . $website_link . '/' . $link . '.php"
                    ]
                  }
                ]
              },
              {
                "@type": "BreadcrumbList",
                "@id": "' . $website_link . '/' . $link . '.php#breadcrumb",
                "itemListElement": [
                  {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Ana sayfa",
                    "item": "' . $website_link . '/"
                  },
                  {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "' . $title . '"
                  }
                ]
              }
            ]
          }
            </script>
        
            <link href="/images/logo.webp" rel="icon" type="image/x-icon" />
          
        

        <link href="/images/logo.webp" rel="icon" type="image/x-icon" />
        <link rel="preload" as="image" href="/images/' . $link . '.webp" />
        <link rel="preload" as="image" href="/images/' . $link . '-mobile.webp" />

        ' . $this->get_style() . '
        
        </head>
    ';
  }

  public function get_head_amp($data)
  {
    $website_link =  $data['website_link'];
    $title = $data['title'];
    $link = $data['link'];
    $description = $data['description'];

    $titleChangeWords = array("2017", "2018", "2019", "2020");
    foreach ($titleChangeWords as $key => $value) {
      if (strpos($title, $value))
        $title = str_replace($value, "2021", $title);
    }


    return $head = '
    <head>
        <meta name="robots" content="yes" />
        <meta name="robots" content="all, index, follow" />
        <meta charset="utf-8"> 
        <meta http-equiv="Content-Language" content="tr" />
        <meta name="location" content="Türkiye, tr, turkey" />
        <meta name="language" content="tr-TR" />
        <link rel="alternate" href="' . $website_link . '/' . $link . '-amp.php" hreflang="tr-TR"/>
        <link rel="canonical" href="' . $website_link . '/' . $link . '.php">
        <title> ' . $title . ' </title>
        <meta name="description" content="' . $description . '">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
        <meta property="og:url" content="' . $website_link . '/' . $link . '.php"/>
        <meta property="og:title" content="' . $title . '"/>
        <meta property="og:site_name" content="1xbet Tanıtım Sitesi"/>
        <meta property="og:image" content="' . $website_link . '/images/logo.webp"/>
        <meta name="twitter:url" content="' . $website_link . '/' . $link . '.php"/>
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:title" content="' . $title . '"/>
        <meta name="twitter:image:src" content="' . $website_link . '/images/logo.webp"/>
        <script async src="https://cdn.ampproject.org/v0.js"></script> 
        <script type="application/ld+json">
          {
            "@context": "https://schema.org",
            "@graph": [
              {
                "@type": "Organization",
                "@id": "' . $website_link . '/#organization",
                "name": "Bahis Siteleri",
                "url": "' . $website_link . '/",
                "logo": {
                  "@type": "ImageObject",
                  "@id": "' . $website_link . '/#logo",
                  "inLanguage": "tr",
                  "url": "' . $website_link . '/images/logo.webp",
                  "contentUrl": "' . $website_link . '/images/logo.webp",
                  "width": 200,
                  "height": 200,
                  "caption": "Bahis Siteleri"
                },
                "image": {
                  "@id": "' . $website_link . '/#logo"
                }
              },
              {
                "@type": "WebSite",
                "@id": "' . $website_link . '/#website",
                "url": "' . $website_link . '/",
                "name": "Bahis Siteleri",
                "description": "' . $description . '",
                "publisher": {
                  "@id": "' . $website_link . '/#organization"
                },
                "inLanguage": "tr"
              },
              {
                "@type": "ImageObject",
                "@id": "' . $website_link . '/' . $link . '.php#primaryimage",
                "inLanguage": "tr",
                "url": "' . $website_link . '/images/' . $link . '.webp",
                "contentUrl": "' . $website_link . '/images/' . $link . '.webp",
                "width": 500,
                "height": 300
              },
              {
                "@type": "WebPage",
                "@id": "' . $website_link . '/' . $link . '.php#webpage",
                "url": "' . $website_link . '/' . $link . '.php",
                "name": "' . $title . ' | Bahis Siteleri",
                "isPartOf": {
                  "@id": "' . $website_link . '/#website"
                },
                "primaryImageOfPage": {
                  "@id": "' . $website_link . '/' . $link . '.php#primaryimage"
                },
                "datePublished": "2019-11-19T20:06:56+00:00",
                "dateModified": "2021-06-24T15:17:05+00:00",
                "breadcrumb": {
                  "@id": "' . $website_link . '/' . $link . '.php#breadcrumb"
                },
                "inLanguage": "tr",
                "potentialAction": [
                  {
                    "@type": "ReadAction",
                    "target": [
                      "' . $website_link . '/' . $link . '.php"
                    ]
                  }
                ]
              },
              {
                "@type": "BreadcrumbList",
                "@id": "' . $website_link . '/' . $link . '.php#breadcrumb",
                "itemListElement": [
                  {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Ana sayfa",
                    "item": "' . $website_link . '/"
                  },
                  {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "' . $title . '"
                  }
                ]
              }
            ]
          }
            </script>
        
            <link href="/images/logo.webp" rel="icon" type="image/x-icon" />
          
        
        <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>

        <link href="/images/logo.webp" rel="icon" type="image/x-icon" />
        <link rel="preload" as="image" href="/images/' . $link . '.webp" />
        <link rel="preload" as="image" href="/images/' . $link . '-mobile.webp" />

        ' . $this->get_style(true) . '
        
        </head>
    ';
  }

  function create_image_for_page($imageName)
  {
    // Image
    $dir = '/home/nox/Sites/website/images/';
    $sourceDir = '/home/nox/Sites/website/source-images/';

    $new_w = 1280;
    $new_h = 720;
    $new_w2 = 200;
    $new_h2 = 150;
    $fileName = rand(1, 140) . ".webp";

    list($width, $height) = getimagesize($sourceDir . $fileName);
    $thumb = imagecreatetruecolor($new_w, $new_h);
    $img = imagecreatefromwebp($sourceDir . $fileName);
    imagepalettetotruecolor($img);
    imagealphablending($img, true);
    imagesavealpha($img, true);
    $newFileName  = $dir . $imageName . ".webp";
    imagecopyresized($thumb, $img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
    imagewebp($thumb, $newFileName, 100);
    imagedestroy($img);

    list($width2, $height2) = getimagesize($newFileName);
    $img2 = imagecreatefromwebp($newFileName);
    imagepalettetotruecolor($img2);
    imagealphablending($img2, true);
    imagesavealpha($img2, true);
    $thumb2 = imagecreatetruecolor($new_w2, $new_h2);
    $newFileName  = $dir . $imageName . "-mobile" . ".webp";
    imagecopyresized($thumb2, $img2, 0, 0, 0, 0, $new_w2, $new_h2, $width2, $height2);
    imagewebp($thumb2, $newFileName, 100);
    imagedestroy($img2);
  }

  public function get_style($amp=false)
  {
    $amptext = '';

    if($amp)
    $amptext = "amp-custom";
    return $style = ' <style '.$amptext.'>
/* W3.CSS 4.15 December 2020 by Jan Egil and Borge Refsnes */
    html {
      box-sizing: border-box
    }

    .responsive{
      width: 100%;
    height: auto;
    }
    ul {
      list-style-type: none;
      margin: 0;
      padding: 0;
    }
    *,
    *:before,
    *:after {
      box-sizing: inherit
    }

    /* Extract from normalize.css by Nicolas Gallagher and Jonathan Neal git.io/normalize */
    html {
      -ms-text-size-adjust: 100%;
      -webkit-text-size-adjust: 100%
    }

    body {
      margin: 0
    }

    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    main,
    menu,
    nav,
    section {
      display: block
    }

    summary {
      display: list-item
    }

    audio,
    canvas,
    progress,
    video {
      display: inline-block
    }

    progress {
      vertical-align: baseline
    }

    audio:not([controls]) {
      display: none;
      height: 0
    }

    [hidden],
    template {
      display: none
    }

    a {
      background-color: transparent
    }

    a:active,
    a:hover {
      outline-width: 0
    }

    abbr[title] {
      border-bottom: none;
      text-decoration: underline;
      text-decoration: underline dotted
    }

    b,
    strong {
      font-weight: bolder
    }

    dfn {
      font-style: italic
    }

    mark {
      background: #ff0;
      color: #000
    }

    small {
      font-size: 80%
    }

    sub,
    sup {
      font-size: 75%;
      line-height: 0;
      position: relative;
      vertical-align: baseline
    }

    sub {
      bottom: -0.25em
    }

    sup {
      top: -0.5em
    }

    figure {
      margin: 1em 40px
    }

    img {
      border-style: none
    }

    code,
    kbd,
    pre,
    samp {
      font-family: monospace, monospace;
      font-size: 1em
    }

    hr {
      box-sizing: content-box;
      height: 0;
      overflow: visible
    }

    button,
    input,
    select,
    textarea,
    optgroup {
      font: inherit;
      margin: 0
    }

    optgroup {
      font-weight: bold
    }

    button,
    input {
      overflow: visible
    }

    button,
    select {
      text-transform: none
    }

    button,
    [type=button],
    [type=reset],
    [type=submit] {
      -webkit-appearance: button
    }

    button::-moz-focus-inner,
    [type=button]::-moz-focus-inner,
    [type=reset]::-moz-focus-inner,
    [type=submit]::-moz-focus-inner {
      border-style: none;
      padding: 0
    }

    button:-moz-focusring,
    [type=button]:-moz-focusring,
    [type=reset]:-moz-focusring,
    [type=submit]:-moz-focusring {
      outline: 1px dotted ButtonText
    }

    fieldset {
      border: 1px solid #c0c0c0;
      margin: 0 2px;
      padding: .35em .625em .75em
    }

    legend {
      color: inherit;
      display: table;
      max-width: 100%;
      padding: 0;
      white-space: normal
    }

    textarea {
      overflow: auto
    }

    [type=checkbox],
    [type=radio] {
      padding: 0
    }

    [type=number]::-webkit-inner-spin-button,
    [type=number]::-webkit-outer-spin-button {
      height: auto
    }

    [type=search] {
      -webkit-appearance: textfield;
      outline-offset: -2px
    }

    [type=search]::-webkit-search-decoration {
      -webkit-appearance: none
    }

    ::-webkit-file-upload-button {
      -webkit-appearance: button;
      font: inherit
    }

    /* End extract */
    html,
    body {
      font-family: Verdana, sans-serif;
      font-size: 15px;
      line-height: 1.5
    }

    html {
      overflow-x: hidden
    }

    h1 {
      font-size: 36px
    }

    h2 {
      font-size: 30px
    }

    h3 {
      font-size: 24px
    }

    h4 {
      font-size: 20px
    }

    h5 {
      font-size: 18px
    }

    h6 {
      font-size: 16px
    }

    .w3-serif {
      font-family: serif
    }

    .w3-sans-serif {
      font-family: sans-serif
    }

    .w3-cursive {
      font-family: cursive
    }

    .w3-monospace {
      font-family: monospace
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
      font-family: "Segoe UI", Arial, sans-serif;
      font-weight: 400;
      margin: 10px 0
    }

    .w3-wide {
      letter-spacing: 4px
    }

    hr {
      border: 0;
      border-top: 1px solid #eee;
      margin: 20px 0
    }

    .w3-image {
      max-width: 100%;
      height: auto
    }

    img {
      vertical-align: middle
    }

    a {
      color: inherit
    }

    .w3-table,
    .w3-table-all {
      border-collapse: collapse;
      border-spacing: 0;
      width: 100%;
      display: table
    }

    .w3-table-all {
      border: 1px solid #ccc
    }

    .w3-bordered tr,
    .w3-table-all tr {
      border-bottom: 1px solid #ddd
    }

    .w3-striped tbody tr:nth-child(even) {
      background-color: #f1f1f1
    }

    .w3-table-all tr:nth-child(odd) {
      background-color: #fff
    }

    .w3-table-all tr:nth-child(even) {
      background-color: #f1f1f1
    }

    .w3-hoverable tbody tr:hover,
    .w3-ul.w3-hoverable li:hover {
      background-color: #ccc
    }

    .w3-centered tr th,
    .w3-centered tr td {
      text-align: center
    }

    .w3-table td,
    .w3-table th,
    .w3-table-all td,
    .w3-table-all th {
      padding: 8px 8px;
      display: table-cell;
      text-align: left;
      vertical-align: top
    }

    .w3-table th:first-child,
    .w3-table td:first-child,
    .w3-table-all th:first-child,
    .w3-table-all td:first-child {
      padding-left: 16px
    }

    .w3-btn,
    .w3-button {
      border: none;
      display: inline-block;
      padding: 8px 16px;
      vertical-align: middle;
      overflow: hidden;
      text-decoration: none;
      color: inherit;
      background-color: inherit;
      text-align: center;
      cursor: pointer;
      white-space: nowrap
    }

    .w3-btn:hover {
      box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
    }

    .w3-btn,
    .w3-button {
      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none
    }

    .w3-disabled,
    .w3-btn:disabled,
    .w3-button:disabled {
      cursor: not-allowed;
      opacity: 0.3
    }

    .w3-disabled *,
    :disabled * {
      pointer-events: none
    }

    .w3-btn.w3-disabled:hover,
    .w3-btn:disabled:hover {
      box-shadow: none
    }

    .w3-badge,
    .w3-tag {
      background-color: #000;
      color: #fff;
      display: inline-block;
      padding-left: 8px;
      padding-right: 8px;
      text-align: center
    }

    .w3-badge {
      border-radius: 50%
    }

    .w3-ul {
      list-style-type: none;
      padding: 0;
      margin: 0
    }

    .w3-ul li {
      padding: 8px 16px;
      border-bottom: 1px solid #ddd
    }

    .w3-ul li:last-child {
      border-bottom: none
    }

    .w3-tooltip,
    .w3-display-container {
      position: relative
    }

    .w3-tooltip .w3-text {
      display: none
    }

    .w3-tooltip:hover .w3-text {
      display: inline-block
    }

    .w3-ripple:active {
      opacity: 0.5
    }

    .w3-ripple {
      transition: opacity 0s
    }

    .w3-input {
      padding: 8px;
      display: block;
      border: none;
      border-bottom: 1px solid #ccc;
      width: 100%
    }

    .w3-select {
      padding: 9px 0;
      width: 100%;
      border: none;
      border-bottom: 1px solid #ccc
    }

    .w3-dropdown-click,
    .w3-dropdown-hover {
      position: relative;
      display: inline-block;
      cursor: pointer
    }

    .w3-dropdown-hover:hover .w3-dropdown-content {
      display: block
    }

    .w3-dropdown-hover:first-child,
    .w3-dropdown-click:hover {
      background-color: #ccc;
      color: #000
    }

    .w3-dropdown-hover:hover>.w3-button:first-child,
    .w3-dropdown-click:hover>.w3-button:first-child {
      background-color: #ccc;
      color: #000
    }

    .w3-dropdown-content {
      cursor: auto;
      color: #000;
      background-color: #fff;
      display: none;
      position: absolute;
      min-width: 160px;
      margin: 0;
      padding: 0;
      z-index: 1
    }

    .w3-check,
    .w3-radio {
      width: 24px;
      height: 24px;
      position: relative;
      top: 6px
    }

    .w3-sidebar {
      height: 100%;
      width: 200px;
      background-color: #fff;
      position: fixed  ;
      z-index: 1;
      overflow: auto
    }

    .w3-bar-block .w3-dropdown-hover,
    .w3-bar-block .w3-dropdown-click {
      width: 100%
    }

    .w3-bar-block .w3-dropdown-hover .w3-dropdown-content,
    .w3-bar-block .w3-dropdown-click .w3-dropdown-content {
      min-width: 100%
    }

    .w3-bar-block .w3-dropdown-hover .w3-button,
    .w3-bar-block .w3-dropdown-click .w3-button {
      width: 100%;
      text-align: left;
      padding: 8px 16px
    }

    .w3-main,
    #main {
      transition: margin-left .4s
    }

    .w3-modal {
      z-index: 3;
      display: none;
      padding-top: 100px;
      position: fixed;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgb(0, 0, 0);
      background-color: rgba(0, 0, 0, 0.4)
    }

    .w3-modal-content {
      margin: auto;
      background-color: #fff;
      position: relative;
      padding: 0;
      outline: 0;
      width: 600px
    }

    .w3-bar {
      width: 100%;
      overflow: hidden
    }

    .w3-center .w3-bar {
      display: inline-block;
      width: auto
    }

    .w3-bar .w3-bar-item {
      padding: 8px 16px;
      float: left;
      width: auto;
      border: none;
      display: block;
      outline: 0
    }

    .w3-bar .w3-dropdown-hover,
    .w3-bar .w3-dropdown-click {
      position: static;
      float: left
    }

    .w3-bar .w3-button {
      white-space: normal
    }

    .w3-bar-block .w3-bar-item {
      width: 100%;
      display: block;
      padding: 8px 16px;
      text-align: left;
      border: none;
      white-space: normal;
      float: none;
      outline: 0
    }

    .w3-bar-block.w3-center .w3-bar-item {
      text-align: center
    }

    .w3-block {
      display: block;
      width: 100%
    }

    .w3-responsive {
      display: block;
      overflow-x: auto
    }

    .w3-container:after,
    .w3-container:before,
    .w3-panel:after,
    .w3-panel:before,
    .w3-row:after,
    .w3-row:before,
    .w3-row-padding:after,
    .w3-row-padding:before,
    .w3-cell-row:before,
    .w3-cell-row:after,
    .w3-clear:after,
    .w3-clear:before,
    .w3-bar:before,
    .w3-bar:after {
      content: "";
      display: table;
      clear: both
    }

    .w3-col,
    .w3-half,
    .w3-third,
    .w3-twothird,
    .w3-threequarter,
    .w3-quarter {
      float: left;
      width: 100%
    }

    .w3-col.s1 {
      width: 8.33333%
    }

    .w3-col.s2 {
      width: 16.66666%
    }

    .w3-col.s3 {
      width: 24.99999%
    }

    .w3-col.s4 {
      width: 33.33333%
    }

    .w3-col.s5 {
      width: 41.66666%
    }

    .w3-col.s6 {
      width: 49.99999%
    }

    .w3-col.s7 {
      width: 58.33333%
    }

    .w3-col.s8 {
      width: 66.66666%
    }

    .w3-col.s9 {
      width: 74.99999%
    }

    .w3-col.s10 {
      width: 83.33333%
    }

    .w3-col.s11 {
      width: 91.66666%
    }

    .w3-col.s12 {
      width: 99.99999%
    }

    @media (min-width:601px) {
      .w3-col.m1 {
        width: 8.33333%
      }

      .w3-col.m2 {
        width: 16.66666%
      }

      .w3-col.m3,
      .w3-quarter {
        width: 24.99999%
      }

      .w3-col.m4,
      .w3-third {
        width: 33.33333%
      }

      .w3-col.m5 {
        width: 41.66666%
      }

      .w3-col.m6,
      .w3-half {
        width: 49.99999%
      }

      .w3-col.m7 {
        width: 58.33333%
      }

      .w3-col.m8,
      .w3-twothird {
        width: 66.66666%
      }

      .w3-col.m9,
      .w3-threequarter {
        width: 74.99999%
      }

      .w3-col.m10 {
        width: 83.33333%
      }

      .w3-col.m11 {
        width: 91.66666%
      }

      .w3-col.m12 {
        width: 99.99999%
      }
    }

    @media (min-width:993px) {
      .w3-col.l1 {
        width: 8.33333%
      }

      .w3-col.l2 {
        width: 16.66666%
      }

      .w3-col.l3 {
        width: 24.99999%
      }

      .w3-col.l4 {
        width: 33.33333%
      }

      .w3-col.l5 {
        width: 41.66666%
      }

      .w3-col.l6 {
        width: 49.99999%
      }

      .w3-col.l7 {
        width: 58.33333%
      }

      .w3-col.l8 {
        width: 66.66666%
      }

      .w3-col.l9 {
        width: 74.99999%
      }

      .w3-col.l10 {
        width: 83.33333%
      }

      .w3-col.l11 {
        width: 91.66666%
      }

      .w3-col.l12 {
        width: 99.99999%
      }
    }

    .w3-rest {
      overflow: hidden
    }

    .w3-stretch {
      margin-left: -16px;
      margin-right: -16px
    }

    .w3-content,
    .w3-auto {
      margin-left: auto;
      margin-right: auto
    }

    .w3-content {
      max-width: 980px
    }

    .w3-auto {
      max-width: 1140px
    }

    .w3-cell-row {
      display: table;
      width: 100%
    }

    .w3-cell {
      display: table-cell
    }

    .w3-cell-top {
      vertical-align: top
    }

    .w3-cell-middle {
      vertical-align: middle
    }

    .w3-cell-bottom {
      vertical-align: bottom
    }

    .w3-hide {
      display: none  
    }

    .w3-show-block,
    .w3-show {
      display: block  
    }

    .w3-show-inline-block {
      display: inline-block  
    }

    @media (max-width:1205px) {
      .w3-auto {
        max-width: 95%
      }
    }

    @media (max-width:600px) {
      .w3-modal-content {
        margin: 0 10px;
        width: auto  
      }

      .w3-modal {
        padding-top: 30px
      }

      .w3-dropdown-hover.w3-mobile .w3-dropdown-content,
      .w3-dropdown-click.w3-mobile .w3-dropdown-content {
        position: relative
      }

      .w3-hide-small {
        display: none  
      }

      .w3-mobile {
        display: block;
        width: 100%  
      }

      .w3-bar-item.w3-mobile,
      .w3-dropdown-hover.w3-mobile,
      .w3-dropdown-click.w3-mobile {
        text-align: center
      }

      .w3-dropdown-hover.w3-mobile,
      .w3-dropdown-hover.w3-mobile .w3-btn,
      .w3-dropdown-hover.w3-mobile .w3-button,
      .w3-dropdown-click.w3-mobile,
      .w3-dropdown-click.w3-mobile .w3-btn,
      .w3-dropdown-click.w3-mobile .w3-button {
        width: 100%
      }
    }

    @media (max-width:768px) {
      .w3-modal-content {
        width: 500px
      }

      .w3-modal {
        padding-top: 50px
      }
    }

    @media (min-width:993px) {
      .w3-modal-content {
        width: 900px
      }

      .w3-hide-large {
        display: none  
      }

      .w3-sidebar.w3-collapse {
        display: block  
      }
    }

    @media (max-width:992px) and (min-width:601px) {
      .w3-hide-medium {
        display: none  
      }
    }

    @media (max-width:992px) {
      .w3-sidebar.w3-collapse {
        display: none
      }

      .w3-main {
        margin-left: 0  ;
        margin-right: 0  
      }

      .w3-auto {
        max-width: 100%
      }
    }

    .w3-top,
    .w3-bottom {
      position: fixed;
      width: 100%;
      z-index: 1
    }

    .w3-top {
      top: 0
    }

    .w3-bottom {
      bottom: 0
    }

    .w3-overlay {
      position: fixed;
      display: none;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 2
    }

    .w3-display-topleft {
      position: absolute;
      left: 0;
      top: 0
    }

    .w3-display-topright {
      position: absolute;
      right: 0;
      top: 0
    }

    .w3-display-bottomleft {
      position: absolute;
      left: 0;
      bottom: 0
    }

    .w3-display-bottomright {
      position: absolute;
      right: 0;
      bottom: 0
    }

    .w3-display-middle {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%)
    }

    .w3-display-left {
      position: absolute;
      top: 50%;
      left: 0%;
      transform: translate(0%, -50%);
      -ms-transform: translate(-0%, -50%)
    }

    .w3-display-right {
      position: absolute;
      top: 50%;
      right: 0%;
      transform: translate(0%, -50%);
      -ms-transform: translate(0%, -50%)
    }

    .w3-display-topmiddle {
      position: absolute;
      left: 50%;
      top: 0;
      transform: translate(-50%, 0%);
      -ms-transform: translate(-50%, 0%)
    }

    .w3-display-bottommiddle {
      position: absolute;
      left: 50%;
      bottom: 0;
      transform: translate(-50%, 0%);
      -ms-transform: translate(-50%, 0%)
    }

    .w3-display-container:hover .w3-display-hover {
      display: block
    }

    .w3-display-container:hover span.w3-display-hover {
      display: inline-block
    }

    .w3-display-hover {
      display: none
    }

    .w3-display-position {
      position: absolute
    }

    .w3-circle {
      border-radius: 50%
    }

    .w3-round-small {
      border-radius: 2px
    }

    .w3-round,
    .w3-round-medium {
      border-radius: 4px
    }

    .w3-round-large {
      border-radius: 8px
    }

    .w3-round-xlarge {
      border-radius: 16px
    }

    .w3-round-xxlarge {
      border-radius: 32px
    }

    .w3-row-padding,
    .w3-row-padding>.w3-half,
    .w3-row-padding>.w3-third,
    .w3-row-padding>.w3-twothird,
    .w3-row-padding>.w3-threequarter,
    .w3-row-padding>.w3-quarter,
    .w3-row-padding>.w3-col {
      padding: 0 8px
    }

    .w3-container,
    .w3-panel {
      padding: 0.01em 16px
    }

    .w3-panel {
      margin-top: 16px;
      margin-bottom: 16px
    }

    .w3-code,
    .w3-codespan {
      font-family: Consolas, "courier new";
      font-size: 16px
    }

    .w3-code {
      width: auto;
      background-color: #fff;
      padding: 8px 12px;
      border-left: 4px solid #4CAF50;
      word-wrap: break-word
    }

    .w3-codespan {
      color: crimson;
      background-color: #f1f1f1;
      padding-left: 4px;
      padding-right: 4px;
      font-size: 110%
    }

    .w3-card,
    .w3-card-2 {
      box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12)
    }

    .w3-card-4,
    .w3-hover-shadow:hover {
      box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 0.2), 0 4px 20px 0 rgba(0, 0, 0, 0.19)
    }

    .w3-spin {
      animation: w3-spin 2s infinite linear
    }

    @keyframes w3-spin {
      0% {
        transform: rotate(0deg)
      }

      100% {
        transform: rotate(359deg)
      }
    }

    .w3-animate-fading {
      animation: fading 10s infinite
    }

    @keyframes fading {
      0% {
        opacity: 0
      }

      50% {
        opacity: 1
      }

      100% {
        opacity: 0
      }
    }

    .w3-animate-opacity {
      animation: opac 0.8s
    }

    @keyframes opac {
      from {
        opacity: 0
      }

      to {
        opacity: 1
      }
    }

    .w3-animate-top {
      position: relative;
      animation: animatetop 0.4s
    }

    @keyframes animatetop {
      from {
        top: -300px;
        opacity: 0
      }

      to {
        top: 0;
        opacity: 1
      }
    }

    .w3-animate-left {
      position: relative;
      animation: animateleft 0.4s
    }

    @keyframes animateleft {
      from {
        left: -300px;
        opacity: 0
      }

      to {
        left: 0;
        opacity: 1
      }
    }

    .w3-animate-right {
      position: relative;
      animation: animateright 0.4s
    }

    @keyframes animateright {
      from {
        right: -300px;
        opacity: 0
      }

      to {
        right: 0;
        opacity: 1
      }
    }

    .w3-animate-bottom {
      position: relative;
      animation: animatebottom 0.4s
    }

    @keyframes animatebottom {
      from {
        bottom: -300px;
        opacity: 0
      }

      to {
        bottom: 0;
        opacity: 1
      }
    }

    .w3-animate-zoom {
      animation: animatezoom 0.6s
    }

    @keyframes animatezoom {
      from {
        transform: scale(0)
      }

      to {
        transform: scale(1)
      }
    }

    .w3-animate-input {
      transition: width 0.4s ease-in-out
    }

    .w3-animate-input:focus {
      width: 100%  
    }

    .w3-opacity,
    .w3-hover-opacity:hover {
      opacity: 0.60
    }

    .w3-opacity-off,
    .w3-hover-opacity-off:hover {
      opacity: 1
    }

    .w3-opacity-max {
      opacity: 0.25
    }

    .w3-opacity-min {
      opacity: 0.75
    }

    .w3-greyscale-max,
    .w3-grayscale-max,
    .w3-hover-greyscale:hover,
    .w3-hover-grayscale:hover {
      filter: grayscale(100%)
    }

    .w3-greyscale,
    .w3-grayscale {
      filter: grayscale(75%)
    }

    .w3-greyscale-min,
    .w3-grayscale-min {
      filter: grayscale(50%)
    }

    .w3-sepia {
      filter: sepia(75%)
    }

    .w3-sepia-max,
    .w3-hover-sepia:hover {
      filter: sepia(100%)
    }

    .w3-sepia-min {
      filter: sepia(50%)
    }

    .w3-tiny {
      font-size: 10px  
    }

    .w3-small {
      font-size: 12px  
    }

    .w3-medium {
      font-size: 15px  
    }

    .w3-large {
      font-size: 18px  
    }

    .w3-xlarge {
      font-size: 24px  
    }

    .w3-xxlarge {
      font-size: 36px  
    }

    .w3-xxxlarge {
      font-size: 48px  
    }

    .w3-jumbo {
      font-size: 64px  
    }

    .w3-left-align {
      text-align: left  
    }

    .w3-right-align {
      text-align: right  
    }

    .w3-justify {
      text-align: justify  
    }

    .w3-center {
      text-align: center  
    }

    .w3-border-0 {
      border: 0  
    }

    .w3-border {
      border: 1px solid #ccc  
    }

    .w3-border-top {
      border-top: 1px solid #ccc  
    }

    .w3-border-bottom {
      border-bottom: 1px solid #ccc  
    }

    .w3-border-left {
      border-left: 1px solid #ccc  
    }

    .w3-border-right {
      border-right: 1px solid #ccc  
    }

    .w3-topbar {
      border-top: 6px solid #ccc  
    }

    .w3-bottombar {
      border-bottom: 6px solid #ccc  
    }

    .w3-leftbar {
      border-left: 6px solid #ccc  
    }

    .w3-rightbar {
      border-right: 6px solid #ccc  
    }

    .w3-section,
    .w3-code {
      margin-top: 16px  ;
      margin-bottom: 16px  
    }

    .w3-margin {
      margin: 16px  
    }

    .w3-margin-top {
      margin-top: 16px  
    }

    .w3-margin-bottom {
      margin-bottom: 16px  
    }

    .w3-margin-left {
      margin-left: 16px  
    }

    .w3-margin-right {
      margin-right: 16px  
    }

    .w3-padding-small {
      padding: 4px 8px  
    }

    .w3-padding {
      padding: 8px 16px  
    }

    .w3-padding-large {
      padding: 12px 24px  
    }

    .w3-padding-16 {
      padding-top: 16px  ;
      padding-bottom: 16px  
    }

    .w3-padding-24 {
      padding-top: 24px  ;
      padding-bottom: 24px  
    }

    .w3-padding-32 {
      padding-top: 32px  ;
      padding-bottom: 32px  
    }

    .w3-padding-48 {
      padding-top: 48px  ;
      padding-bottom: 48px  
    }

    .w3-padding-64 {
      padding-top: 64px  ;
      padding-bottom: 64px  
    }

    .w3-padding-top-64 {
      padding-top: 64px  
    }

    .w3-padding-top-48 {
      padding-top: 48px  
    }

    .w3-padding-top-32 {
      padding-top: 32px  
    }

    .w3-padding-top-24 {
      padding-top: 24px  
    }

    .w3-left {
      float: left  
    }

    .w3-right {
      float: right  
    }

    .w3-button:hover {
      color: #000  ;
      background-color: #ccc  
    }

    .w3-transparent,
    .w3-hover-none:hover {
      background-color: transparent  
    }

    .w3-hover-none:hover {
      box-shadow: none  
    }

    /* Colors */
    .w3-amber,
    .w3-hover-amber:hover {
      color: #000  ;
      background-color: #ffc107  
    }

    .w3-aqua,
    .w3-hover-aqua:hover {
      color: #000  ;
      background-color: #00ffff  
    }

    .w3-blue,
    .w3-hover-blue:hover {
      color: #fff  ;
      background-color: #2196F3  
    }

    .w3-light-blue,
    .w3-hover-light-blue:hover {
      color: #000  ;
      background-color: #87CEEB  
    }

    .w3-brown,
    .w3-hover-brown:hover {
      color: #fff  ;
      background-color: #795548  
    }

    .w3-cyan,
    .w3-hover-cyan:hover {
      color: #000  ;
      background-color: #00bcd4  
    }

    .w3-blue-grey,
    .w3-hover-blue-grey:hover,
    .w3-blue-gray,
    .w3-hover-blue-gray:hover {
      color: #fff  ;
      background-color: #607d8b  
    }

    .w3-green,
    .w3-hover-green:hover {
      color: #fff  ;
      background-color: #4CAF50  
    }

    .w3-light-green,
    .w3-hover-light-green:hover {
      color: #000  ;
      background-color: #8bc34a  
    }

    .w3-indigo,
    .w3-hover-indigo:hover {
      color: #fff  ;
      background-color: #3f51b5  
    }

    .w3-khaki,
    .w3-hover-khaki:hover {
      color: #000  ;
      background-color: #f0e68c  
    }

    .w3-lime,
    .w3-hover-lime:hover {
      color: #000  ;
      background-color: #cddc39  
    }

    .w3-orange,
    .w3-hover-orange:hover {
      color: #000  ;
      background-color: #ff9800  
    }

    .w3-deep-orange,
    .w3-hover-deep-orange:hover {
      color: #fff  ;
      background-color: #ff5722  
    }

    .w3-pink,
    .w3-hover-pink:hover {
      color: #fff  ;
      background-color: #e91e63  
    }

    .w3-purple,
    .w3-hover-purple:hover {
      color: #fff  ;
      background-color: #9c27b0  
    }

    .w3-deep-purple,
    .w3-hover-deep-purple:hover {
      color: #fff  ;
      background-color: #673ab7  
    }

    .w3-red,
    .w3-hover-red:hover {
      color: #fff  ;
      background-color: #f44336  
    }

    .w3-sand,
    .w3-hover-sand:hover {
      color: #000  ;
      background-color: #fdf5e6  
    }

    .w3-teal,
    .w3-hover-teal:hover {
      color: #fff  ;
      background-color: #009688  
    }

    .w3-yellow,
    .w3-hover-yellow:hover {
      color: #000  ;
      background-color: #ffeb3b  
    }

    .w3-white,
    .w3-hover-white:hover {
      color: #000  ;
      background-color: #fff  
    }

    .w3-black,
    .w3-hover-black:hover {
      color: #fff  ;
      background-color: #000  
    }

    .w3-grey,
    .w3-hover-grey:hover,
    .w3-gray,
    .w3-hover-gray:hover {
      color: #000  ;
      background-color: #9e9e9e  
    }

    .w3-light-grey,
    .w3-hover-light-grey:hover,
    .w3-light-gray,
    .w3-hover-light-gray:hover {
      color: #000  ;
      background-color: #f1f1f1  
    }

    .w3-dark-grey,
    .w3-hover-dark-grey:hover,
    .w3-dark-gray,
    .w3-hover-dark-gray:hover {
      color: #fff  ;
      background-color: #616161  
    }

    .w3-pale-red,
    .w3-hover-pale-red:hover {
      color: #000  ;
      background-color: #ffdddd  
    }

    .w3-pale-green,
    .w3-hover-pale-green:hover {
      color: #000  ;
      background-color: #ddffdd  
    }

    .w3-pale-yellow,
    .w3-hover-pale-yellow:hover {
      color: #000  ;
      background-color: #ffffcc  
    }

    .w3-pale-blue,
    .w3-hover-pale-blue:hover {
      color: #000  ;
      background-color: #ddffff  
    }

    .w3-text-amber,
    .w3-hover-text-amber:hover {
      color: #ffc107  
    }

    .w3-text-aqua,
    .w3-hover-text-aqua:hover {
      color: #00ffff  
    }

    .w3-text-blue,
    .w3-hover-text-blue:hover {
      color: #2196F3  
    }

    .w3-text-light-blue,
    .w3-hover-text-light-blue:hover {
      color: #87CEEB  
    }

    .w3-text-brown,
    .w3-hover-text-brown:hover {
      color: #795548  
    }

    .w3-text-cyan,
    .w3-hover-text-cyan:hover {
      color: #00bcd4  
    }

    .w3-text-blue-grey,
    .w3-hover-text-blue-grey:hover,
    .w3-text-blue-gray,
    .w3-hover-text-blue-gray:hover {
      color: #607d8b  
    }

    .w3-text-green,
    .w3-hover-text-green:hover {
      color: #4CAF50  
    }

    .w3-text-light-green,
    .w3-hover-text-light-green:hover {
      color: #8bc34a  
    }

    .w3-text-indigo,
    .w3-hover-text-indigo:hover {
      color: #3f51b5  
    }

    .w3-text-khaki,
    .w3-hover-text-khaki:hover {
      color: #b4aa50  
    }

    .w3-text-lime,
    .w3-hover-text-lime:hover {
      color: #cddc39  
    }

    .w3-text-orange,
    .w3-hover-text-orange:hover {
      color: #ff9800  
    }

    .w3-text-deep-orange,
    .w3-hover-text-deep-orange:hover {
      color: #ff5722  
    }

    .w3-text-pink,
    .w3-hover-text-pink:hover {
      color: #e91e63  
    }

    .w3-text-purple,
    .w3-hover-text-purple:hover {
      color: #9c27b0  
    }

    .w3-text-deep-purple,
    .w3-hover-text-deep-purple:hover {
      color: #673ab7  
    }

    .w3-text-red,
    .w3-hover-text-red:hover {
      color: #f44336  
    }

    .w3-text-sand,
    .w3-hover-text-sand:hover {
      color: #fdf5e6  
    }

    .w3-text-teal,
    .w3-hover-text-teal:hover {
      color: #009688  
    }

    .w3-text-yellow,
    .w3-hover-text-yellow:hover {
      color: #d2be0e  
    }

    .w3-text-white,
    .w3-hover-text-white:hover {
      color: #fff  
    }

    .w3-text-black,
    .w3-hover-text-black:hover {
      color: #000  
    }

    .w3-text-grey,
    .w3-hover-text-grey:hover,
    .w3-text-gray,
    .w3-hover-text-gray:hover {
      color: #757575  
    }

    .w3-text-light-grey,
    .w3-hover-text-light-grey:hover,
    .w3-text-light-gray,
    .w3-hover-text-light-gray:hover {
      color: #f1f1f1  
    }

    .w3-text-dark-grey,
    .w3-hover-text-dark-grey:hover,
    .w3-text-dark-gray,
    .w3-hover-text-dark-gray:hover {
      color: #3a3a3a  
    }

    .w3-border-amber,
    .w3-hover-border-amber:hover {
      border-color: #ffc107  
    }

    .w3-border-aqua,
    .w3-hover-border-aqua:hover {
      border-color: #00ffff  
    }

    .w3-border-blue,
    .w3-hover-border-blue:hover {
      border-color: #2196F3  
    }

    .w3-border-light-blue,
    .w3-hover-border-light-blue:hover {
      border-color: #87CEEB  
    }

    .w3-border-brown,
    .w3-hover-border-brown:hover {
      border-color: #795548  
    }

    .w3-border-cyan,
    .w3-hover-border-cyan:hover {
      border-color: #00bcd4  
    }

    .w3-border-blue-grey,
    .w3-hover-border-blue-grey:hover,
    .w3-border-blue-gray,
    .w3-hover-border-blue-gray:hover {
      border-color: #607d8b  
    }

    .w3-border-green,
    .w3-hover-border-green:hover {
      border-color: #4CAF50  
    }

    .w3-border-light-green,
    .w3-hover-border-light-green:hover {
      border-color: #8bc34a  
    }

    .w3-border-indigo,
    .w3-hover-border-indigo:hover {
      border-color: #3f51b5  
    }

    .w3-border-khaki,
    .w3-hover-border-khaki:hover {
      border-color: #f0e68c  
    }

    .w3-border-lime,
    .w3-hover-border-lime:hover {
      border-color: #cddc39  
    }

    .w3-border-orange,
    .w3-hover-border-orange:hover {
      border-color: #ff9800  
    }

    .w3-border-deep-orange,
    .w3-hover-border-deep-orange:hover {
      border-color: #ff5722  
    }

    .w3-border-pink,
    .w3-hover-border-pink:hover {
      border-color: #e91e63  
    }

    .w3-border-purple,
    .w3-hover-border-purple:hover {
      border-color: #9c27b0  
    }

    .w3-border-deep-purple,
    .w3-hover-border-deep-purple:hover {
      border-color: #673ab7  
    }

    .w3-border-red,
    .w3-hover-border-red:hover {
      border-color: #f44336  
    }

    .w3-border-sand,
    .w3-hover-border-sand:hover {
      border-color: #fdf5e6  
    }

    .w3-border-teal,
    .w3-hover-border-teal:hover {
      border-color: #009688  
    }

    .w3-border-yellow,
    .w3-hover-border-yellow:hover {
      border-color: #ffeb3b  
    }

    .w3-border-white,
    .w3-hover-border-white:hover {
      border-color: #fff  
    }

    .w3-border-black,
    .w3-hover-border-black:hover {
      border-color: #000  
    }

    .w3-border-grey,
    .w3-hover-border-grey:hover,
    .w3-border-gray,
    .w3-hover-border-gray:hover {
      border-color: #9e9e9e  
    }

    .w3-border-light-grey,
    .w3-hover-border-light-grey:hover,
    .w3-border-light-gray,
    .w3-hover-border-light-gray:hover {
      border-color: #f1f1f1  
    }

    .w3-border-dark-grey,
    .w3-hover-border-dark-grey:hover,
    .w3-border-dark-gray,
    .w3-hover-border-dark-gray:hover {
      border-color: #616161  
    }

    .w3-border-pale-red,
    .w3-hover-border-pale-red:hover {
      border-color: #ffe7e7  
    }

    .w3-border-pale-green,
    .w3-hover-border-pale-green:hover {
      border-color: #e7ffe7  
    }

    .w3-border-pale-yellow,
    .w3-hover-border-pale-yellow:hover {
      border-color: #ffffcc  
    }

    .w3-border-pale-blue,
    .w3-hover-border-pale-blue:hover {
      border-color: #e7ffff  
    }

    body,
    h1,
    h2,
    h3,
    h4,
    h5 {
      font-family: "Raleway", sans-serif
    }



    @media only screen and (min-device-width: 800px) {
      .desktop{
        display:block;
      }
      .mobile{
        display:none;
      }
      .leftContent{
        width:65%;
      }
      .suggestionsItem{float:left;text-align:center;background-color:#dcdada;margin:1%;width:30%;
      
        display: inline-block;
        line-height: 1.15;
        padding: 0 0 20px;
        background: #fff;
        -webkit-box-shadow: 0 4px 20px rgb(0 0 0 / 15%);
        box-shadow: 0 4px 20px rgb(0 0 0 / 15%);
      
      }

  }
    @media only screen and (max-device-width: 800px) {
      .desktop{
        display:none;
      }
      .mobile{
        display:block;
      }
    
      .rightContent{
      display:none;
      }
      .leftContent{
        width:100%;
      }
      .suggestionsItem{margin:auto;text-align:center;background-color:#dcdada;margin-bottom:1%;width:275px;
      
      
        line-height: 1.15;
        padding: 0 0 20px;
        background: #fff;
        -webkit-box-shadow: 0 4px 20px rgb(0 0 0 / 15%);
        box-shadow: 0 4px 20px rgb(0 0 0 / 15%);
      
      }

  }

      main{
        padding:0% 3%;
        
      }
      a{
        color:black;
      }
      .rightContent{width: 30%;float:right;background-color:#f1f1f1;padding:1%;margin-top:0.5%;}
      .leftContent{float:left;background-color:#f1f1f1;;padding:1%;margin-top:0.5%;}
       
     
      
    
      table.darkTable {color: white;font-family: "Arial Black", Gadget, sans-serif;border: 2px solid #000000;background-color: #4A4A4A;width: 100%;height: 200px;text-align: center;border-collapse: collapse;}table.darkTable td, table.darkTable th {padding: 6px 6px;}table.darkTable tbody td {font-size: 13px;color: #E6E6E6;}table.darkTable tr:nth-child(even) {background: #888888;}table.darkTable thead {background: #000000;border-bottom: 3px solid #000000;}table.darkTable thead th {font-size: 15px;font-weight: bold;color: #E6E6E6;text-align: center;border-left: 2px solid #4A4A4A;}table.darkTable thead th:first-child {border-left: none;}table.darkTable tfoot {font-size: 12px;font-weight: bold;color: #E6E6E6;background: #000000;background: -moz-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);background: -webkit-linear-gradient(top, #404040 0%, #191919 66%, #000000 100%);background: linear-gradient(to bottom, #404040 0%, #191919 66%, #000000 100%);border-top: 1px solid #4A4A4A;}table.darkTable tfoot td {font-size: 12px;} 
       .reviewButton{float:right;font-family: "Droid Sans", Arial, Verdana, sans-serif;word-wrap: break-word;border-spacing: 0;border-collapse: separate;text-align: center;outline: none;list-style: none;transition: all .2s ease-in-out;padding: 7px 9px;font-size: 14px;line-height: 19px;display: inline-block;text-decoration: none;margin: 3px 0;border: 0;box-shadow: none;box-sizing: border-box;font-weight: bold;border-radius: 9px;background-color: #cc3812;color: #ffffff;} 
       .goButton{float:right;font-family: "Droid Sans", Arial, Verdana, sans-serif;word-wrap: break-word;border-spacing: 0;border-collapse: separate;text-align: center;outline: none;list-style: none;transition: all .2s ease-in-out;padding: 7px 9px;font-size: 14px;line-height: 19px;display: inline-block;text-decoration: none;margin: 3px 0;border: 0;box-shadow: none;box-sizing: border-box;font-weight: bold;border-radius: 9px;background-color: #0e8f0e;color: #ffffff;} 
       .goButtonInPage{margin-left:5%;font-family: "Droid Sans", Arial, Verdana, sans-serif;word-wrap: break-word;border-spacing: 0;border-collapse: separate;text-align: center;outline: none;list-style: none;transition: all .2s ease-in-out;padding: 7px 9px;font-size: 14px;line-height: 19px;display: inline-block;text-decoration: none;margin: 3px 0;border: 0;box-shadow: none;box-sizing: border-box;font-weight: bold;border-radius: 9px;background-color: #0e8f0e;color: #ffffff;} 
      
       .star{font-size: 13px;word-wrap: break-word;border-spacing: 0;border-collapse: separate;color: #111;text-align: center;padding: 0;margin: 0;outline: none;list-style: none;border: 0 none;box-sizing: border-box;font-family: "wpsm-icons";speak: none;font-style: normal;font-weight: normal;font-variant: normal;text-transform: none;line-height: 1;-webkit-font-smoothing: antialiased;}
    .w3-card h4{color: #4A4A4A;  }
    .w3-card span{color:white; padding:0px 5%;}

    .w3-card .w3-padding-16{
      padding:3%;
      background-color: #4A4A4A;
    }
      .suggestions{text-align:center;}
      .suggestionsItem a{margin:auto;font-size:20px;text-decoration:none;font-weight:bold;}
  .buttonArea{
    float:right;
  padding-right:20px;
  }
      footer{float:right;}
  
  .header {
  overflow: hidden;
  background-color: #f1f1f1;
  
  }
  
  .header a {
  float: left;
  color: black;
  text-align: center;
  padding: 12px;
  text-decoration: none;
  font-size: 18px; 
  line-height: 25px;
  border-radius: 4px;
  }
  
  .header a.logo {
  font-size: 25px;
  font-weight: bold;
  margin-left:25px;
  }
  
  .header a:hover {
  background-color: #ddd;
  color: black;
  }
  
  .header a.active {
  background-color: gray;
  color: white;
  }
  
  .header-right {
  float: right;
  padding:20px;
  margin-right:20px;
  }
  
  @media screen and (max-width: 500px) {
  .header a {
  float: none;
  display: block;
  text-align: left;
  padding:2px;
  }
  
  .header-right {
  float: none;
  padding: 0px 20px;
  }
  }

  h1,h2,h3,h4,h5,h6{
    margin:0px;
  }

  .suggestionsItem p{
    margin:0.5rem;
    padding: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
  

.w3-card .w3-large{
  ext-transform:capitalize;
  font-size:24px;
}

  
       </style>';
  }

  function myshuffle($arr)
  {
    // extract the array keys
    $keys = [];
    foreach ($arr as $key => $value) {
      $keys[] = $key;
    }

    // shuffle the keys    
    for ($i = count($keys) - 1; $i >= 1; --$i) {
      $r = mt_rand(0, $i);
      if ($r != $i) {
        $tmp = $keys[$i];
        $keys[$i] = $keys[$r];
        $keys[$r] = $tmp;
      }
    }

    // reconstitute
    $result = [];
    foreach ($keys as $key) {
      array_push($result, $arr[$key]);
      //  $result[$key] = $arr[$key];
    }

    return $result;
  }

  public function deleteRepeatElements($content)
  {
      // eğer kendi içinde tekrarlamış varsa onun amınakoy 
      $repeatControlArr = array();
      
    foreach ($content as $key => $value) {
      if($value->status == 0){
        array_push($repeatControlArr,$value->h);
      }
    }

   $repeatControlArr= array_diff_key( $repeatControlArr , array_unique( $repeatControlArr ) );
   //dd($content);

   if(count($repeatControlArr) > 0 ){
     foreach ($repeatControlArr as $key => $value) {
      $content[$key]->delete();
      unset($content[$key]);
     }

   }

   return $content;
  }


  //bu amınakodugum fonksiyonu linkleri değiştiriyor en son bunu yap.
  public function changeRepeatLink()
  {
     for ($i=3; $i < 4; $i++) { 
       $content = Content::where('id',$i)->get();
       if(count($content) > 0){
         $content_control = Content::where('last_link',$content->first()->last_link)->get();
         if(count($content_control) > 1){
           foreach ($content_control as $key => $value) {
             if($key > 0){
              $value->last_link = $value->last_link."-".$key;
              $value->save();
             }
           }
         }
       }
     }
  }
}

