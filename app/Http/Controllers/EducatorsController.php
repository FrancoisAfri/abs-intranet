<?php

namespace App\Http\Controllers;

use App\ContactPerson;
use App\Country;
use App\educator;
use App\projects;
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

class EducatorsController extends Controller
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
	 
    public function create($projectID = -1, $activityID = -1)
    {
        $data['page_title'] = "Educator Registration";
        $data['page_description'] = "Add a New Educator Registration";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Educator Registration', 'active' => 1, 'is_module' => 0]
        ];

		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$educators = DB::table('educators')->orderBy('first_name', 'asc')->get();
		$schools = DB::table('contacts_companies')->where('company_type', 2)->orderBy('name', 'asc')->get();
		$activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
        $projects = projects::where('status', 2)->orderBy('name', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['schools'] = $schools;
		$data['activities'] = $activities;
        $data['projects'] = $projects;
        $data['project_id'] = $projectID;
        //$data['educators'] = $educators;
        $data['activity_id'] = $activityID;
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'Educator Registration';
		AuditReportsController::store('Clients', 'Educator Registration Page Accessed', "Actioned By User", 0);
        return view('contacts.educator_registration')->with($data);
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
            'first_name' => 'bail|required|min:2',
            'surname' => 'bail|required|min:2',
            'project_id' => 'required',
            'id_number' => 'required_if:type,2',
            'cell_number' => 'required_if:type,2',
            'physical_address' => 'required_if:type,2',
            'course_sponsored' => 'required_if:type,1',
            'email' => 'required_if:type,1',
            'institution' => 'required_if:type,1',
            
        ]);
        $educatorData = $request->all();
		
        //Exclude empty fields from query
        foreach ($educatorData as $key => $value)
        {
            if (empty($educatorData[$key])) {
                unset($educatorData[$key]);
            }
        }
		///$moduleRegistered = isset($educatorData['module_registered']) ? $educatorData['module_registered'] : array();
		//Cell number formatting
        $educatorData['cell_number'] = str_replace(' ', '', $educatorData['cell_number']);
        $educatorData['cell_number'] = str_replace('-', '', $educatorData['cell_number']);
        $educatorData['cell_number'] = str_replace('(', '', $educatorData['cell_number']);
        $educatorData['cell_number'] = str_replace(')', '', $educatorData['cell_number']);

        //convert dates to unix time stamp
        if (isset($educatorData['engagement_date'])) {
            $educatorData['engagement_date'] = str_replace('/', '-', $educatorData['engagement_date']);
            $educatorData['engagement_date'] = strtotime($educatorData['engagement_date']);
        }
        //Inset educatorData data
        $educatorData = new educator($educatorData);
        $educatorData->active = 1;
        $educatorData->save();

        //Upload contract document
        if ($request->hasFile('contract_doc')) {
            $fileExt = $request->file('contract_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('contract_doc')->isValid()) {
                $fileName = $educatorData->id . "_contract." . $fileExt;
                $request->file('contract_doc')->storeAs('educators', $fileName);
                //Update file name in the table
                $educatorData->contract_doc = $fileName;
                $educatorData->update();
            }
        }
        //Upload supporting document
        if ($request->hasFile('cv_doc')) {
            $fileExt = $request->file('cv_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('cv_doc')->isValid()) {
                $fileName = $educatorData->id . "cv_doc." . $fileExt;
                $request->file('cv_doc')->storeAs('educators', $fileName);
                //Update file name in the table
                $educatorData->cv_doc = $fileName;
                $educatorData->update();
            }
        }
		// Save educato module registered
		/*if (!empty($moduleRegistered))
		{
			foreach ($moduleRegistered as $value) {
				$value = $value * 2;
			}
		}*/
		//return redirect("/contacts/$contact->id/edit")->with('success_add', "The contact has been added successfully.");
        AuditReportsController::store('Clients', 'Educator Registered', "Actioned By User", 0);
		return redirect('/contacts/educator/' . $educatorData->id . '/edit')->with('success_add', "Educator has been added successfully.");
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
    public function edit(educator $educator)
    {
		$cv_doc = $educator->cv_doc;
		$contract_doc = $educator->contract_doc;
        $data['page_title'] = "View Educator";
        $data['page_description'] = "View/Update Educator details";
        $data['back'] = "/contacts";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts/educator', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Educators details', 'active' => 1, 'is_module' => 0]
        ];
		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$schools = DB::table('contacts_companies')->where('company_type', 2)->orderBy('name', 'asc')->get();
		$activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['schools'] = $schools;
		$data['activities'] = $activities;
        $data['educator'] = $educator;
		$data['cv_doc'] = (!empty($cv_doc)) ? Storage::disk('local')->url("educators/$cv_doc") : '';
		$data['contract_doc'] = (!empty($contract_doc)) ? Storage::disk('local')->url("educators/$contract_doc") : '';
        $data['active_mod'] = 'Clients';
        $data['active_rib'] = 'search';
		AuditReportsController::store('Clients', 'Educator Informations Edited', "Actioned By User", 0);
        return view('contacts.educator_view')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, educator $educator)
    {
        //Validate form input
        $this->validate($request, [
            'first_name' => 'bail|required|min:2',
            'surname' => 'bail|required|min:2',
            'id_number' => 'required',
            'cell_number' => 'required',
        ]);
        $educatorData = $request->all();
		
        //Exclude empty fields from query
        foreach ($educatorData as $key => $value)
        {
            if (empty($educatorData[$key])) {
                unset($educatorData[$key]);
            }
        }
		//Cell number formatting
        $educatorData['cell_number'] = str_replace(' ', '', $educatorData['cell_number']);
        $educatorData['cell_number'] = str_replace('-', '', $educatorData['cell_number']);
        $educatorData['cell_number'] = str_replace('(', '', $educatorData['cell_number']);
        $educatorData['cell_number'] = str_replace(')', '', $educatorData['cell_number']);

		 //convert dates to unix time stamp
        if (isset($educatorData['engagement_date'])) {
            $educatorData['engagement_date'] = str_replace('/', '-', $educatorData['engagement_date']);
            $educatorData['engagement_date'] = strtotime($educatorData['engagement_date']);
        }
        //Update Date
		$educator->update($educatorData);
        //Upload contract document
        if ($request->hasFile('contract_doc')) {
            $fileExt = $request->file('contract_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('contract_doc')->isValid()) {
                $fileName = $educator->id . "_contract." . $fileExt;
                $request->file('contract_doc')->storeAs('educators', $fileName);
                //Update file name in the table
                $educator->contract_doc = $fileName;
                $educator->update();
            }
        }
        //Upload supporting document
        if ($request->hasFile('cv_doc')) {
            $fileExt = $request->file('cv_doc')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('cv_doc')->isValid()) {
                $fileName = $educator->id . "cv_doc." . $fileExt;
                $request->file('cv_doc')->storeAs('educators', $fileName);
                //Update file name in the table
                $educator->cv_doc = $fileName;
                $educator->update();
            }
        }
        //Redirect to all usr view
		AuditReportsController::store('Clients', 'Educator Informations Updated', "Actioned By User", 0);
        return redirect('/contacts/educator/' . $educator->id . '/edit')->with('success_edit', "Educator has been updated successfully.");

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
