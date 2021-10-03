<?php
namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $codes = Code::all();
        return view('codes.index', compact('codes'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('codes.create');
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
            'git_address' => 'required',
            'type' => 'required',
            'limit' => 'required',

        ]);

        $code = Code::create([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'git_address' => $request->get('git_address'),
            'limit' => $request->get('limit'),

            'description' => $request->get('description')
        ]);

        $code->save();

        return redirect()->route('codes.index')
            ->with('success', 'Code oluşturma başarıyla tamamlandı');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        return view('codes.show', compact('code'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function edit(Code $code)
    {
        return view('codes.edit', compact('code'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Code $code)
    {
        $request->validate([
            'name' => 'required',
            'git_address' => 'required',
            'type' => 'required',
            'limit' => 'required',
            'description' => 'required',
        ]);


        $code->update($request->all());

        return redirect()->route('codes.index')
            ->with('success', 'Code güncellemesi başarıyla tamamlandı');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        $code->delete();

        return redirect()->route('codes.index')
            ->with('success', 'Code kaldırma başarıyla tamamlandı');
    }
}
