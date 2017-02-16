<?php

namespace App\Http\Controllers;

use App\agm;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;

class AGMContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 \ 
	public function __construct()
    {
        $this->middleware('auth');
    }
	
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = "AGM";
        $data['page_description'] = "Add a New AGM";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add AGM', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'AGM';
        $data['contact_type'] = 2; //Agm
		AuditReportsController::store('Programmes', 'View Create Activities Page', "Tried To Create Activity", 0);
        return view('contacts.general_meeting')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation
        $this->validate($request, [
            'names' => 'bail|required|min:2',
            'email' => 'email',
            'contact_type' => 'required',
        ]);
        $contactData = $request->all();

        //Exclude empty fields from query
        foreach ($contactData as $key => $value)
        {
            if (empty($contactData[$key])) {
                unset($contactData[$key]);
            }
        }

        //Inset contact data
        $contact = new agm($contactData);
        $contact->save();

        return $contact;
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
	public function educatorStore(Request $request)
    {
       
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
