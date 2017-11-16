<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmRegistration;
use App\AppraisalKPIResult;
use App\DivisionLevel;
use App\HRPerson;
use App\User;
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
        //phpinfo();
        $data['page_title'] = "Contacts Upload";
        $data['page_description'] = "Upload Contacts From Excel Sheet";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/import/company', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Import Company', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Import Company';
        AuditReportsController::store('Performance Appraisal', 'Upload page accessed', "Accessed by User", 0);
        return view('contacts.contacts_upload')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	function randomPass($number = 10)
	{
		$passGen = '';
		$sample = "84h57h67hc767c5765ndkydybg6b7b4b";  
		$i = 0; 
		while ($i < $number) {
			$char = substr($sample, mt_rand(0, strlen($sample)-1), 1);
			if (!strstr($passGen, $char)) { 
				$passGen .= $char;
				$i++;
			}
		}
		return $passGen;
	}
	
	public function store(Request $request)
    {
		$uploadData = $request->all();
		$uploadType = $uploadData['upload_type'];
		if ($uploadType == 1)
		{
			if($request->hasFile('input_file'))
			{
				$path = $request->file('input_file')->getRealPath();
				$data = Excel::load($path, function($reader) {})->get();
				if(!empty($data) && $data->count())
				{
					//die('do you come here');
					foreach ($data->toArray() as $key => $value) 
					{
					
						if(!empty($value))
						{
							echo "dkdkdkdnkdn";
								print_r($value);
				die;
							if (!empty($value['email']))
							{
								$employees = HRPerson::where('employee_number', $value['job_number'])->first();
								$email = !empty($employees->email) ? $employees->email : '';
								
								if (empty($employees))
								{
									if ($email == $value['email']) continue;
									$password = EmployeeUploadController::randomPass();
									$user = new User;
									$user->email = $value['email'];
									$user->password = Hash::make($password);
									$user->type = 1;
									$user->status = 1;
									$user->save();
									//Save record
									$person = new HRPerson();
									$person->email = $value['email'];
									$person->first_name = $value['firstname'];
									$person->surname = $value['surname'];
									$person->employee_number = $value['job_number'];
									$person->status = 1;
									$user->addPerson($person);
									//Send email
									Mail::to("$user->email")->send(new ConfirmRegistration($user, $password));
									AuditReportsController::store('Security', 'New User Created', "Login Details Sent To User $user->email", 0);
								}
							}
						}
					}
					return back()->with('success_add',"Records were successfully inserted.");
				}
				else return back()->with('error_add','Please Check your file, Something is wrong there.');
			}
			else return back()->with('error_add','Please Upload A File.');
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
							if (!empty($value['email']))
							{
								$employees = ContactCompany::where('employee_number', $value['job_number'])->first();
								$email = !empty($employees->email) ? $employees->email : '';
								
								if ($email == $value['email']) continue;
								$password = EmployeeUploadController::randomPass();
								$user = new User;
								$user->email = $value['email'];
								$user->password = Hash::make($password);
								$user->type = 1;
								$user->status = 1;
								$user->save();

								//Save record
								$person = new HRPerson();
								$person->email = $value['email'];
								$person->first_name = $value['firstname'];
								$person->surname = $value['surname'];
								$person->employee_number = $value['job_number'];
								$person->status = 1;
								$user->addPerson($person);

								//Send email
								Mail::to("$user->email")->send(new ConfirmRegistration($user, $password));
								AuditReportsController::store('Security', 'New User Created', "Login Details Sent To User $user->email", 0);
							}
						}
					}
					return back()->with('success_add',"Records were successfully inserted.");
				}
				else return back()->with('error_add','Please Check your file, Something is wrong there.');
			}
			else return back()->with('error_add','Please Upload A File.');

		}
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load Appraisals KPI's";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/load_appraisals', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Performance Appraisal', "$uploadTypes[$uploadType] uploaded", "Accessed by User", 0);
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
