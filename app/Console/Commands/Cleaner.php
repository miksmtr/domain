<?php

namespace App\Console\Commands;

use App\Models\BetCompany;
use Illuminate\Console\Command;
use App\Models\Content;
use App\Models\ContentHelper;
use App\Models\ServerSetting;
use App\Models\Word;



class Cleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:Cleaner';

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
        //Word::truncate();
   
       

        for ($i = 0; $i < 4000; $i++) {
            $contents = Content::where('id',$i)->where('status', 0)->get();
            if(count($contents) > 0){
                $this->writeAgain($contents);

            }
        }
    }

    public function writeAgain($contents)
    {
        $companies = BetCompany::all();

        $this->browse(function ($browser) use ($contents, $companies) {
        foreach ($contents as $key => $value) {

                $browser->visit('https://aiarticlespinner.co');
                $validatedData = [];
                $last_content = '';
                $content_array =  $this->seperator($value);
                $last_description = $this->browserCu($browser, $value->first_description);
                foreach ($content_array as $key2 => $value2) {
                    $response_content =  $this->cleanSomething($value2[1]);
            

                    $response_content =  $this->browserCu($browser, $response_content); 
                    if ($response_content) {
                        $content_helper = new ContentHelper();
                        $content_helper->h = $value2[0];
                        $content_helper->p = $response_content;
                        $content_helper->content_id = $value->id;
                        $content_helper->save();
                        $responseTitle = "<h1>" . $value2[0] . "</h1>";
                        $response_content = "<p>" . $response_content . "</p>";
                        $last_content = $last_content . $responseTitle . $response_content;
                    }
                }
                // KAYDET
                if ($last_content !== '') {
                    $validatedData =
                        [
                            'last_description' => $last_description,
                            'last_content' => $last_content,
                            'last_title' => $value->first_title,
                            'last_link' => $value->first_link,
                            'status' => 2,
                            'bet_company_id' => $this->addCompany($companies, $value)
                        ];
                    $value->update($validatedData);
                    // KELİMELERİ EKLE
                    $this->addWord($value);
                }
    }

        });

    }

    public function browserCu($browser, $content)
    {

        $pleaseWaitText = "Paraphrasing Text. Please Wait...";
        $pleaseWaitText2 = "Metni dönüştürün, lütfen bekleyin...";
        $responseText = false;
        if(strlen($content) > 5000){
            return $responseText;
        }


        try {
            //code...

            //Step1
            $browser->script('document.getElementById("1").checked = true;');
            $browser->pause(1500);
            $browser->script("document.getElementById('select-state').innerHTML = " . '"' . "<option value='tr' selected='selected'>Turkish</option>" . '";');
            $browser->pause(1500);
            $contentx =  trim($content, "\n");
            $browser->value('#inp', '"' . $contentx . '"');
            $browser->pause(1500);
            //Step1 Click
            $browser->script("document.getElementById('refase').click();");
            $browser->pause(1500);

            //Step2
            $try_count = 1;
            b:
            $new_content  = $browser->script("return document.getElementById('out').innerHTML;")[0];
            // $new_content  =  $browser->value('#out');
            $browser->pause(1500);
            if ($new_content == $pleaseWaitText ||  $new_content == $pleaseWaitText2) {

                if ($try_count == 100) {
                    return $responseText;
                }
                $try_count = $try_count + 1;
                goto b;
            }
            $browser->value('#inp', $new_content);
            $browser->pause(1500);
            $browser->script('document.getElementById("3").checked = true;');
            $browser->pause(1500);
            //Step2 Click
            $browser->script('document.getElementById("refase").click();');
            $browser->pause(1500);

            //Result 
            $try_count = 1;
            c:
            $new_content  = $browser->script('return document.getElementById("out").innerHTML;')[0];
            // $new_content  =  $browser->value('#out');

            $browser->pause(1500);
            if ($new_content  == $pleaseWaitText ||    $new_content == $pleaseWaitText2) {
                if ($try_count == 100) {
                    return $responseText;
                }
                $try_count = $try_count + 1;
                goto c;
            }

            $responseText = $new_content;
        } catch (\Throwable $th) {
            return $responseText;
        }


        return $responseText;
    }




    public function seperator($content)
    {
        $content_array = array();
        $new_content = $this->cleanContent($content);
        $new_content =  html_entity_decode($new_content);

        $content->first_content = $new_content;


        $response_array = $this->control_tag_h1($new_content, $content->first_title);
        if ($response_array) {
            array_push($content_array, array($response_array[1], $response_array[2]));
            $new_content = $response_array[0];
        }

        for ($i = 0; $i < 10; $i++) {
            $response_array = $this->control_tag_h_all($new_content, "h2");
            if ($response_array) {
                $new_content = $response_array[0];
                array_push($content_array, array($response_array[1], $response_array[2]));
            }
            $response_array = $this->control_tag_h_all($new_content, "h3");
            if ($response_array) {
                $new_content = $response_array[0];
                array_push($content_array, array($response_array[1], $response_array[2]));
            }

            $response_array = $this->control_tag_h_all($new_content, "h4");

            if ($response_array) {
                $new_content = $response_array[0];
                array_push($content_array, array($response_array[1], $response_array[2]));
            }

            $response_array = $this->control_tag_h_all($new_content, "h5");

            if ($response_array) {
                $new_content = $response_array[0];
                array_push($content_array, array($response_array[1], $response_array[2]));
            }
            $response_array = $this->control_tag_h_all($new_content, "h6");
            if ($response_array) {
                $new_content = $response_array[0];
                array_push($content_array, array($response_array[1], $response_array[2]));
            }
        }
        $last_content = '';

        return $content_array;
    }





    public function cleanContent($content)
    {
        $response_content = $this->cleanContentTag($content->first_content, "img", true);
        $response_content = $this->cleanContentTag($response_content, "table", true);
        $response_content = $this->cleanContentTag($response_content, "tbody", true);
        return $response_content;
    }


    public function control_tag_h1($wp_content, $title = '')
    {
        $hLimit = strpos($wp_content, '<h');
        $control_h1 = strpos($wp_content, '<h1>');
        $h1Limit = strpos($wp_content, '</h1>');
        $response_content = '';
        $response_title = '';
        if ($control_h1) {
            $hLimit  =  strpos($wp_content, '<h', strpos($wp_content, '<h') + 1);
            if ($control_h1 > 100) {
                $response_title = $title;
                if ($hLimit) {
                    $response_content =  substr($wp_content, 0, ($hLimit - 2));
                } else {
                    $response_title = substr($wp_content, $control_h1, $h1Limit);
                    $response_content =  substr($wp_content, $h1Limit, strlen($wp_content));
                }
            } else {
                $response_title = substr($wp_content, $control_h1, $h1Limit);
                $response_content =  substr($wp_content, $control_h1, ($hLimit - 2));
            }
        } else {


            $response_title = $title;
            if ($hLimit) {
                $response_content =  substr($wp_content, 0, ($hLimit - 2));
            } else {
                $response_content =  substr($wp_content, 0, strlen($wp_content));
            }
        }


        $wp_content =    substr($wp_content, strlen($response_content), strlen($wp_content));
        $responseArray = array(
            $wp_content,
            $response_title,
            $response_content
        );

        return $responseArray;
    }

    public function control_tag_h_all($wp_content, $h)
    {


        $control_h1 = strpos($wp_content, '<' . $h . '>');
        $h1Limit = strpos($wp_content, '</' . $h . '>');
        $response_content = '';
        $response_title = '';



        if (!$control_h1 == false) {
            $hLimit  =  strpos($wp_content, '<h', strpos($wp_content, '<h') + 1);
            $response_title = substr($wp_content, $control_h1 + 4, $h1Limit - 4);
            $response_content =  substr($wp_content, $h1Limit + 4, ($hLimit - 2));
        } else {
            return false;
        }
        $wp_content =    substr($wp_content, strlen($response_content), strlen($wp_content));
        $responseArray = array(
            $wp_content,
            $response_title,
            $response_content
        );



        return $responseArray;
    }

    public function cleanContentTag($text, $tag, $type)
    {

 
        $response_content = $text;
        $tagStart = "<$tag";
        if (!$type)
            $tagEnd = "</$tag>";
        else
            $tagEnd = "/>";
        for ($i = 0; $i <= 10; $i++) {
            $tagStartPos = strpos($response_content, $tagStart);
            $tagEndtPos = strpos($response_content, $tagEnd);
            $endPosition = strlen($response_content);
            if ($tagStartPos) {

                $start_text = substr($response_content, 0, $tagStartPos);
                $tagLenght = 5;
                if ($type)
                    $tagLenght = 2;
                $end_text = substr($response_content, $tagEndtPos + $tagLenght, $endPosition);
                $response_content = $start_text . $end_text;
            }
        }
  






        return $response_content;
    }

    public function cleanSomething($text)
    {

        $response_content = $this->cleanContentTag($text, "a", false);
        $response_content = strip_tags($response_content);

        $asd = array(
            'span',
            'h1',
            'h2',
            'h3',
            'h4',
            'h5',
            'h6',
            'h7',
            'h8',
            'strong',
            'p',
            '\n',
            '"'
        );
        foreach ($asd as $key => $value) {
            $response_content = trim($response_content, "/" . $value . ">");
            $response_content = trim($response_content, $value . ">");
            $response_content = trim($response_content, "/" . $value);
            $response_content = trim($response_content, $value);
            $response_content = trim($response_content, "<" . $value);
            $response_content = trim($response_content, "< " . $value);
            $response_content = trim($response_content, $value);
        }



        return $response_content;
    }




    public function addWord($value)
    {
        $this->addWordFunction($value->last_content);
        $this->addWordFunction($value->last_description);
        $this->addWordFunction($value->last_title);
    }

    public function addWordFunction($text)
    {
        $arr = explode(" ", $text);
        foreach ($arr as $value2) {
            $word = new Word();
            $word_control = Word::where('word', $value2)->get();
            if (count($word_control) >  0) {
                $word = $word_control->first();
                $word->count = $word->count + 1;
            } else {
                $word->word = trim($value2,"  ");
                $word->count = 1;
            }
            $word->save();
        }
    }


    public function addCompany($companies, $value)
    {

        $bet_company_id = 1;
        foreach ($companies as $key3 => $company) {
            $contentTitle = strtolower($value->first_title);
            $contentTitle = str_replace("ü", "u", $contentTitle);
            $contentTitle = str_replace("ö", "o", $contentTitle);

            if (strpos($contentTitle, $company->name) !== false) {
                $bet_company_id = $company->id;
            }
        }

        return $bet_company_id;
    }
}
