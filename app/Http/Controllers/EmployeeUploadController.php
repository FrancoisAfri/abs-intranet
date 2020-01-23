<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmRegistration;
use App\AppraisalKPIResult;
use App\appraisalsKpis;
use App\DivisionLevel;
use App\HRPerson;
use App\JobTitle;
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
class EmployeeUploadController extends Controller
{
	
	public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_title'] = "Employee Records";
        $data['page_description'] = "Upload Employees From Excel Sheet";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/employee_upload', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Employees Upload', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'Employees Upload';
        AuditReportsController::store('Employee Records', 'Upload page accessed', "Accessed by User", 0);
        return view('hr.employee_upload')->with($data);
    }

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
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
							$employees = HRPerson::where('email', $value['email'])->first();
							//$email = !empty($employees->email) ? $employees->email : '';
							// get employee jobtille
							if (!empty($value['job_title']))
							{
								$jobTitle = JobTitle::where('name', $value['job_title'])->first();
								$position = !empty($jobTitle->id) ? $jobTitle->id : 0;
							}
							else $position  = 0;
							if (empty($employees))
							{
								$birthday = !empty($value['date_of_birth']) ? str_replace('/', '-', $value['date_of_birth']) : '';
								$birthday = !empty($birthday) ? strtotime($birthday) : 0;
								$joined = !empty($value['date_started']) ? str_replace('/', '-', $value['date_started']) : '';
								$joined = !empty($joined) ? strtotime($joined) : 0;
								//if ($email == $value['email']) continue;
								$password = EmployeeUploadController::randomPass();
								$user = new User;
								$user->email = $value['email'];
								$user->password = Hash::make($password);
								$user->type = 1;
								$user->status = 1;
								$user->save();

								//Save HR record
								$person = new HRPerson();
								$person->email = $value['email'];
								$person->first_name = $value['firstname'];
								$person->surname = $value['surname'];
								$person->employee_number = $value['employee_number'];
								$person->date_of_birth = $birthday;
								$person->date_joined = $joined;
								$person->leave_profile = 2;
								$person->position = $position;
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
		
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load Appraisals KPI's";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/appraisal/load_appraisals', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Employee Records', "$uploadTypes[$uploadType] uploaded", "Accessed by User", 0);
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
