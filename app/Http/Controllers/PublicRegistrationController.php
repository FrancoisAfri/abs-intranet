<?php

namespace App\Http\Controllers;

use App\ContactPerson;
use App\Country;
use App\public_reg;
use App\Mail\ConfirmRegistration;
use Illuminate\Http\Request;
use App\Mail\adminEmail;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\Province;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PublicRegistrationController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = "Public Registration";
        $data['page_description'] = "Add a New Public Registration";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Public Registration', 'active' => 1, 'is_module' => 0]
        ];
		$activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$data['activities'] = $activities;
		$data['ethnicities'] = $ethnicities;
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'Public Registration';
		AuditReportsController::store('Clients', 'View Public Creation Page', "Actioned By User", 0);
        return view('contacts.public_registration')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //Validate form input
        $this->validate($request, [
            'names' => 'bail|required|min:2',
            'id_number' => 'required',
            'cell_number' => 'required_if:type,2',
            'gender' => 'required_if:type,2',
            'email' => 'required_if:type,2',
            
        ]);
        $publicData = $request->all();
		
        //Exclude empty fields from query
        foreach ($publicData as $key => $value)
        {
            if (empty($publicData[$key])) {
                unset($publicData[$key]);
            }
        }
		//Cell number formatting
        $publicData['cell_number'] = str_replace(' ', '', $publicData['cell_number']);
        $publicData['cell_number'] = str_replace('-', '', $publicData['cell_number']);
        $publicData['cell_number'] = str_replace('(', '', $publicData['cell_number']);
        $publicData['cell_number'] = str_replace(')', '', $publicData['cell_number']);

        //convert dates to unix time stamp
        if (isset($publicData['date'])) {
            $publicData['date'] = str_replace('/', '-', $publicData['date']);
            $publicData['date'] = strtotime($publicData['date']);
        }
        //Inset publicData data
        $publicData = new public_reg($publicData);
        $publicData->save();
        //Upload supporting document
        if ($request->hasFile('attendance_doc')) {
            $fileExt = $request->file('attendance_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('attendance_doc')->isValid()) {
                $fileName = $publicData->id . "attendance_doc." . $fileExt;
                $request->file('attendance_doc')->storeAs('public', $fileName);
                //Update file name in the table
                $publicData->attendance_doc = $fileName;
                $publicData->update();
            }
        }
		//return redirect("/contacts/$contact->id/edit")->with('success_add', "The contact has been added successfully.");
		AuditReportsController::store('Clients', 'Public Informations Added', "Actioned By User", 0);
        return redirect('/contacts/public/' . $publicData->id . '/edit')->with('success_add', "Record has been added successfully.");
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
    public function edit(public_reg $public)
    {
        $data['page_title'] = "View Public Registration";
        $data['page_description'] = "View/Update Public details";
        $data['back'] = "/contacts";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts/public', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Educators details', 'active' => 1, 'is_module' => 0]
        ];
		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$schools = DB::table('contacts_companies')->where('company_type', 2)->orderBy('name', 'asc')->get();
		$activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['activities'] = $activities;
        $data['public'] = $public;
		$data['active_mod'] = 'Clients';
        $data['active_rib'] = 'search';
		AuditReportsController::store('Clients', 'Public Informations Edited', "Actioned By User", 0);
        return view('contacts.public_view')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, public_reg $public)
    {
        //Validate form input
        $this->validate($request, [
            'names' => 'bail|required|min:2',
            'id_number' => 'required',
            'cell_number' => 'required',
        ]);
        $publicData = $request->all();
		
        //Exclude empty fields from query
        foreach ($publicData as $key => $value)
        {
            if (empty($publicData[$key])) {
                unset($publicData[$key]);
            }
        }
		//Cell number formatting
		$publicData['cell_number'] = str_replace(' ', '', $publicData['cell_number']);
        $publicData['cell_number'] = str_replace('-', '', $publicData['cell_number']);
        $publicData['cell_number'] = str_replace('(', '', $publicData['cell_number']);
        $publicData['cell_number'] = str_replace(')', '', $publicData['cell_number']);
		 //convert dates to unix time stamp
        if (isset($publicData['date'])) {
            $publicData['date'] = str_replace('/', '-', $publicData['date']);
            $publicData['date'] = strtotime($publicData['date']);
        }
        //Update Date
		$public->update($publicData);
        //Redirect to all usr view
		AuditReportsController::store('Clients', 'Public Informations Updated', "Actioned By User", 0);
        return redirect('/contacts/public/' . $public->id . '/edit')->with('success_edit', "Record has been updated successfully.");

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
