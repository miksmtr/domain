<?php

namespace App\Http\Controllers;

use App\Models\BetCompany;
use App\Models\Category;
use App\Models\Code;
use App\Models\Content;
use App\Models\Log;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\Input;
use Tests\Browser\LoginTest;
use Yajra\DataTables\Facades\DataTables;

class ContentController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = Content::select('*');

            return DataTables::of($data)
                ->addIndexColumn()


                ->addColumn('statustext', function ($row) {
                    $duzenlenmemis = "Düzenlenmemiş";
                    $taslak = "Taslak";
                    $yayinlanmis = "Yayınlanmış";
                    if ($row->status == 0) {
                        return '<button class="btn btn-danger">' . $duzenlenmemis . '</button>';
                    } else if ($row->status == 1) {
                        return '<button class="btn btn-warning">' . $taslak . '</button>';
                    } else if ($row->status == 2) {
                        return '<button class="btn btn-success">' . $yayinlanmis . '</button>';
                    }
                })
                ->addColumn('website', function ($row) {
                    $website = Website::where('id', $row->website_id)->get();
                    if (count($website) > 0) {
                        return $website->first()->id;
                    } else {
                        return "unknown";
                    }
                })

                ->addColumn('action', function ($row) {



                    return '
                      <a href="/contents/' . $row->id . '/edit">
                      <i class="fa fa-pencil fa-fw "></i>
                  </a>
                 ';
                })
                ->filter(function ($instance) use ($request) {


                    if (!empty($request->get('search'))) {

                        $instance->where(function ($w) use ($request) {
                            $duzenlenmemis = "Düzenlenmemiş";
                            $taslak = "Taslak";
                            $yayinlanmis = "Yayınlanmış";

                            $search = $request->get('search');

                            $website_id = 0;
                            $website = Website::where('link', 'LIKE', $search)->get();
                            if (count($website) > 0) {
                                $website_id = $website->first()->id;
                            }

                            $status = -1;
                            if ($search == $duzenlenmemis) $status = 0;
                            if ($search == $taslak) $status = 1;
                            if ($search == $yayinlanmis) $status = 2;
                            if ($status == -1) {
                                $w->orWhere('first_title', 'LIKE', "%$search%")
                                    ->orWhere('rewriter_title', 'LIKE', "%$search%")
                                    ->orWhere('last_title', 'LIKE', "%$search%")
                                    ->orWhere('website_id', $website_id);
                            } else {
                                $w->orWhere('first_title', 'LIKE', "%$search%")
                                    ->orWhere('rewriter_title', 'LIKE', "%$search%")
                                    ->orWhere('status', $status)
                                    ->orWhere('website_id', $website_id)
                                    ->orWhere('last_title', 'LIKE', "%$search%");
                            }
                        });
                    }
                })
                ->rawColumns(['action', 'statustext'])

                ->make(true);
        }

        return view('contents.index');
    }




    /*      public function index()
    {
        $contents = Content::all();
        return view('contents.index', compact('contents'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    **/


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $content = Content::create([
            'first_link' => $request->get('first_link'),
            'first_title' => $request->get('first_title'),
            'first_description' => $request->get('first_description'),
            'first_content' => $request->get('first_content'),
            'status' => 0
        ]);

        $content->save();

        return redirect()->route('contents.index')
            ->with('success', 'Content oluşturma başarıyla tamamlandı');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        return view('contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {






        $bet_companies = BetCompany::all();
        $categories = Category::all();
        $category = Category::where('id', $content->category_id)->get()->first();
        $bet_company = BetCompany::where('id', $content->bet_company_id)->get()->first();

        return view('contents.edit', compact('content', 'categories', 'bet_companies', 'category', 'bet_company'));
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        $bet_companies = BetCompany::all();
        $categories = Category::all();
        $category = Category::where('id', $content->category_id)->get()->first();
        $bet_company = BetCompany::where('id', $content->bet_company_id)->get()->first();



        if ($request->get('last_link') && $request->get('last_content') && $request->get('last_title') && $request->get('last_description')) {
            $content->status = 2;
            $content->save();
        }

        $content->update($request->all());

        return view('contents.edit', compact('content', 'categories', 'bet_companies', 'category', 'bet_company'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function rewriter(Request $request)
    {
        $first_content = $request->get('first_content');
        $content_id = $request->get('content_id');



        $content = Content::where('id', $content_id)->get()->first();

        //$request_content = preg_replace("/(<([^>]+)>)/", '', $request->first_content);

        $response_content = $this->get_new_sentence($first_content);


        if (!$response_content['err']) {
            $content->rewriter_content = $response_content['response'];
            $content->last_content = $response_content['response'];
            $content->status =  1;
            $content->save();
            $response = array(
                'status' => 1, // 1,0
                'msg' => "",
                'text' => $response_content['response'],
            );
        } else {
            $log = new Log();
            $log->type = -1;
            $log->title = "Hata";
            $log->which_worker = "rewriter";
            $log->description = $content->name . " içerikleri yeniden yazarken hata meydana geldi.";
            $response = array(
                'status' => 0, // 1,0
                'msg' => $response_content['response'],
                'text' => '',
            );
        }






        return response()->json($response);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        $content->delete();

        return redirect()->route('contents.index')
            ->with('success', 'Content kaldırma başarıyla tamamlandı');
    }

    public function get_new_sentence($text)
    {
        $rewriterApiUrl = Config::get('values.REWRITER_API_URL');
        $rewriterApiToken = Config::get('values.REWRITER_API_TOKEN');

        $curl = curl_init();


        $text = preg_replace('/\s+/u', ' ', $text);

        curl_setopt_array($curl, [
            CURLOPT_URL => $rewriterApiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{
                \"language\": \"tr\",
                \"strength\": 3,
                \"text\": \"$text\"
            }",


            CURLOPT_HTTPHEADER => [
                "content-type: application/json",
                "x-rapidapi-host: rewriter-paraphraser-text-changer-multi-language.p.rapidapi.com",
                "x-rapidapi-key: $rewriterApiToken"
            ],
        ]);

        $response = curl_exec($curl);


        $json = json_decode($response);

        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $responseArray = array(
                'err' => true,
                'response' => $err
            );
        } else {
            $responseArray = array(
                'err' => false,
                'response' => $json->rewrite
            );
        }

        return $responseArray;
    }
}
