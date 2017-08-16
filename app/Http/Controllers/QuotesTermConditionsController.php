<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\ContactPerson;
use App\QuotesTermAndConditions;
use Illuminate\Http\Request;
use App\Http\Requests;

class QuotesTermConditionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $termConditions = QuotesTermAndConditions::where('status', 1)->get();
        
        $data['page_title'] = "Quotes";
        $data['page_description'] = "Term & Condition";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Term & Condition';
        $data['termConditions'] = $termConditions;
        AuditReportsController::store('Quote', 'Quote Setup Page Accessed', "Accessed By User", 0);

        return view('quote.quote_term')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
	public function stores(Request $request)
    {
		 $this->validate($request, [
            'term_name' => 'required',
        ]);
		unset($templateData['_token']);
        $quoteTerm = new QuotesTermAndConditions($request->all());
        $quoteTerm->status = 1;
        $quoteTerm->save();

        AuditReportsController::store('Quote', 'New Quote Term & Condition Added', "Added By User", 0);
        return response()->json(['term_id' => $quoteTerm->id], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
