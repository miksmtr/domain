<?php

namespace App\Http\Controllers;

use App\Models\BetCompany;
use App\Models\Category;
use App\Models\Code;
use App\Models\Word;
use App\Models\Log;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\Input;
use Tests\Browser\LoginTest;
use Yajra\DataTables\Facades\DataTables;

class WordController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $data = Word::select('*');

            return DataTables::of($data)
                ->addIndexColumn()

               

                ->addColumn('action', function ($row) {
                    return '
                      <a href="/words/' . $row->id . '/edit">
                      <i class="fa fa-pencil fa-fw "></i>
                  </a>

                 ';
                })
                ->filter(function ($instance) use ($request) {


                    if (!empty($request->get('search'))) {

                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('word', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])

                ->make(true);
        }

        return view('words.index');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('words.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $word = Word::create([
            'word' => $request->get('word'),
            'count' => $request->get('count'),
        ]);

        $word->save();

        return redirect()->route('words.index')
            ->with('success', 'word oluşturma başarıyla tamamlandı');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function show(Word $word)
    {
        return view('words.show', compact('word'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function edit(Word $word)
    {
        return view('words.edit', compact('word'));
    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Word $word)
    {
        $word->update($request->all());
        return view('words.edit', compact('word'));
    }



 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function destroy(Word $word)
    {
        $word->delete();
        return redirect()->route('words.index')
            ->with('success', 'Word kaldırma başarıyla tamamlandı');
    }
}
