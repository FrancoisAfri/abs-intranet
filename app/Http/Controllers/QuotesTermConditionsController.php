<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\ContactPerson;
use App\QuotesTermAndConditions;
use App\termsConditionsCategories;
use Illuminate\Http\Request;
use App\Http\Requests;

class QuotesTermConditionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //$termConditions = QuotesTermAndConditions::get();
        $termCategories = termsConditionsCategories::get();
        
        $data['page_title'] = "Quotes";
        $data['page_description'] = "Term & Condition Categories";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote/categories-terms', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Term And Conditions', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Term & Conditions';
        $data['Categories'] = $termCategories;
        AuditReportsController::store('Quote', 'Quote Tern and Conditions Categories Page Accessed', "Accessed By User", 0);

        return view('quote.terms_categories')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewTerm(termsConditionsCategories $cat)
    {
		if ($cat->status == 1) 
		{
			$cat = $cat->load('terms');
			
			$data['page_title'] = "Terms & Conditions";
			$data['page_description'] = "Terms & Conditions";
			$data['breadcrumb'] = [
				['title' => 'Quote', 'path' => '/quote/categories-terms', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Term And Conditions', 'active' => 1, 'is_module' => 0]];
			$data['category'] = $cat;
			$data['active_mod'] = 'Quote';
			$data['active_rib'] = 'Term & Conditions';
			//return $data;
			AuditReportsController::store('Quote', 'Quote Tern and Conditions Page Accessed', "Accessed by User", 0);
	
			return view('quote.quote_term')->with($data);
		}
		else 
		{
			AuditReportsController::store('Quote', 'Quote Tern and Conditions Page Accessed', "Accessed by User", 0);
			return back();
		}
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
	public function store(Request $request, termsConditionsCategories $cat)
    {
		 $this->validate($request, [
            'term_name' => 'required',
        ]);
		unset($request['_token']);
		$temsData = $request->all();
        $quoteTerm = new QuotesTermAndConditions($temsData);
        $quoteTerm->term_name = html_entity_decode($temsData['term_name']);
        $quoteTerm->status = 1;
        $quoteTerm->category_id = $cat->id;
        $quoteTerm->save();

        AuditReportsController::store('Quote', 'New Quote Term & Condition Added', "Added By User", 0);
        return response()->json(['term_id' => $quoteTerm->id], 200);
    }
	
	public function saveCat(Request $request)
    {
		 $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
		unset($request['_token']);
		$catData = $request->all();
        $catTerm = new termsConditionsCategories();
        $catTerm->description = $catData['description'];
        $catTerm->name = $catData['name'];
        $catTerm->status = 1;
        $catTerm->save();

        AuditReportsController::store('Quote', 'New Quote Term & Condition Categories Added', "Added By User", 0);
        return response()->json(['term_id' => $catTerm->id], 200);
    }
	public function editterm(QuotesTermAndConditions $term)
	{ 
	   $data['page_title'] = "Quotes";
        $data['page_description'] = "Term & Condition Edit";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote/term-conditions', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Term And Conditions', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Term & Conditions';
        $data['term'] = $term;
        AuditReportsController::store('Quote', 'term and Conditions edit page accessed', "Edited by User", 0);
        return view('quote.term_edit')->with($data);
    }
	public function updateTerm(Request $request, QuotesTermAndConditions $term)
	{
        $this->validate($request, [
            'term_name' => 'required',              
        ]);

        $term->term_name = $request->input('term_name');
        $term->update();
        AuditReportsController::store('Quote', 'Update TermAnd Conditions', "Updated by User", 0);
		$termConditions = QuotesTermAndConditions::where('status', 1)->get();
        
        $data['page_title'] = "Quotes";
        $data['page_description'] = "Term & Condition";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Term And Conditions', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'Term & Conditions';
        $data['termConditions'] = $termConditions;
		return redirect('/quote/term-conditions/'.$term->category_id)->with('');
    }
	
	public function updateTermCat(Request $request, termsConditionsCategories $cat)
	{
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',              
        ]);

        $cat->name = $request->input('name');
        $cat->description = $request->input('description');
        $cat->update();

		$newname = $request->input('name');
        AuditReportsController::store('Quote', 'Update TermAnd Conditions Categories', "Updated by User", 0);
        return response()->json(['name' => $newname], 200);
    }
	
	public function termAct(QuotesTermAndConditions $term) 
	{
		if ($term->status == 1) $stastus = 0;
		else $stastus = 1;
		$term->status = $stastus;	
		$term->update();
		AuditReportsController::store('Quote', "Term And Condition Status Changed: $stastus", "Changed by User", 0);
		return back();
    }
	
	public function termCatAct(termsConditionsCategories $cat) 
	{
		if ($cat->status == 1) $stastus = 0;
		else $stastus = 1;
		$cat->status = $stastus;	
		$cat->update();
		AuditReportsController::store('Quote', "Term And Condition Categories Status Changed: $stastus", "Changed by User", 0);
		return back();
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
