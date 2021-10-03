<?php
namespace App\Http\Controllers;

use App\Models\BetCompany;
use Illuminate\Http\Request;

class BetCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bet_companies = BetCompany::orderBy('type', 'DESC')->get();

        return view('bet_companies.index', compact('bet_companies'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

   


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bet_companies.create');
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
        ]);

        $bet_company = BetCompany::create([
            'name' => $request->get('name'),
            'status' => 0,  
        ]);

        $bet_company->save();

        return redirect()->route('bet_companies.index')
            ->with('success', 'BetCompany oluşturma başarıyla tamamlandı');
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BetCompany  $bet_company
     * @return \Illuminate\Http\Response
     */
    public function show(BetCompany $bet_company)
    {
        return view('bet_companies.show', compact('bet_company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BetCompany  $bet_company
     * @return \Illuminate\Http\Response
     */
    public function edit(BetCompany $bet_company)
    {
        return view('bet_companies.edit', compact('bet_company'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BetCompany  $bet_company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BetCompany $bet_company)
    {
        $request->validate([
            'name' => 'required',
         
        ]);

        $bet_company->update($request->all());

        return redirect()->route('bet_companies.index')
            ->with('success', 'BetCompany güncellemesi başarıyla tamamlandı');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BetCompany  $bet_company
     * @return \Illuminate\Http\Response
     */
    public function destroy(BetCompany $bet_company)
    {
        $bet_company->delete();

        return redirect()->route('bet_companies.index')
            ->with('success', 'BetCompany kaldırma başarıyla tamamlandı');
    }

}
