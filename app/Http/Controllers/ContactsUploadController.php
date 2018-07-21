<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmRegistration;
use App\AppraisalKPIResult;
use App\DivisionLevel;
use App\HRPerson;
use App\User;
use App\ContactCompany;
use App\ContactPerson;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use App\AppraisalQuery_report;
use App\AppraisalClockinResults;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Excel;
class ContactsUploadController extends Controller
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
        $data['page_title'] = "Contacts Upload";
        $data['page_description'] = "Upload Contacts From Excel Sheet";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/import/company', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Import Contacts', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Import Contacts';
        AuditReportsController::store('Contacts', 'Import Contacts Page Accessed', "Accessed by User", 0);
        return view('contacts.contacts_upload')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	
	public function store(Request $request)
    {
		$uploadData = $request->all();
		$uploadType = $uploadData['upload_type'];
		$uploadTypes = [1 => "Company", 2 => 'Company Rep'];

		if ($uploadType == 1)
		{
			if($request->hasFile('input_file'))
			{
				$path = $request->file('input_file')->getRealPath();
				$data = Excel::load($path, function($reader) {})->get();
				//print_r($data);
				
				if(!empty($data) && $data->count())
				{
					
					foreach ($data->toArray() as $key => $value) 
					{
						
						
						if(!empty($value))
						{
							if (!empty($value['name']))
							{
								
								$company = new ContactCompany();
								$company->email = !empty($value['email']) ? $value['email'] : '';
								$company->name = !empty($value['name']) ? $value['name'] : '';
								$company->registration_number = !empty($value['company_registration_number']) ? $value['company_registration_number'] : '';;
								$company->vat_number = !empty($value['vat_number']) ? $value['vat_number'] : '';
								$company->phys_address = !empty($value['physical_address']) ? $value['physical_address'] : '';
								$company->postal_address = !empty($value['postal_address']) ? $value['postal_address'] : '';
								$company->cp_home_number = !empty($value['office_number']) ? $value['office_number'] : '';
								if (!starts_with($company->cp_home_number, '0') && !empty($company->cp_home_number)) $company->cp_home_number = '0'.$company->cp_home_number;
								$company->fax_number = !empty($value['fax_number']) ? $value['fax_number'] : '';
								if (!starts_with($company->fax_number, '0') && !empty($company->fax_number)) $company->fax_number = '0'.$company->fax_number;
								
								$company->save();
								
								AuditReportsController::store('Contacts', 'New Company Created', "Company Name: $company->name", 0);
							}
						}
					}
				}
			}
		}
		else
		{
			if($request->hasFile('input_file'))
			{
				$path = $request->file('input_file')->getRealPath();
				$data = Excel::load($path, function($reader) {})->get();
				if(!empty($data) && $data->count())
				{
					foreach ($data->toArray() as $key => $value) 
					{
						if(!empty($value))
						{
							if (!empty($value['firstname']))
							{
								$companyName = ContactCompany::where('name', $value['company_name'])->first();
								//return $companyName;
								$email = !empty($employees->email) ? $employees->email : '';
								$contact = new ContactPerson();
								$contact->email = !empty($value['email']) ? $value['email'] : '';
								$contact->first_name = !empty($value['firstname']) ? $value['firstname'] : '';
								$contact->surname = !empty($value['surname']) ? $value['surname'] : '';;
								$contact->cell_number = !empty($value['mobile_number']) ? $value['mobile_number'] : '';
								$contact->phone_number = !empty($value['office_number']) ? $value['office_number'] : '';
								$contact->res_address = !empty($value['postal_address']) ? $value['postal_address'] : '';
								$contact->company_id = !empty($companyName->id) ? $companyName->id : 0;
								if (!starts_with($contact->cell_number, '0') && !empty($contact->cell_number)) $contact->cell_number = '0'.$contact->cell_number;
								if (!starts_with($contact->phone_number, '0') && !empty($contact->phone_number)) $contact->phone_number = '0'.$contact->phone_number;
								
								$contact->save();
								
								AuditReportsController::store('Contacts', 'New Company Rep Created', "Contact Name: $contact->first_name $contact->surname ", 0);
							}
						}
					}
				}
			}
		}
        $data['page_title'] = "Contacts Import";
        $data['page_description'] = "Import Contacts Details";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/import/company', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Contacts', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Import Contacts';
		return back();
    }
	
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
