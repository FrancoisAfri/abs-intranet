<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\HRPerson;
use Illuminate\Contracts\View\View;
use App\AppraisalQuery_report;
use App\AppraisalKPIResult;
use App\AppraisalClockinResults;
use App\appraisalsKpis;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Excel;
class AppraisalKPIResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $data['division_levels'] = $divisionLevels;
		# Get kpi from
		$kpis = DB::table('appraisals_kpis')
			->select('appraisals_kpis.measurement','appraisals_kpis.id')
			->where('appraisals_kpis.is_upload', 1)
			->orderBy('appraisals_kpis.measurement')
			->get();

        $data['kpis'] = $kpis;
        $data['employees'] = $employees;
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load an Employee's Appraisals";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-line-chart', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Performance Appraisal', 'Upload page accessed', "Accessed by User", 0);
        return view('appraisals.load_appraisal')->with($data);
    }

    public function loadEmpAppraisals(Request $request){
        $this->validate($request, [
            'appraisal_type' => 'required',
            'hr_person_id' => 'required',
        ]);
        $hrID = $request->input('hr_person_id');
        $emp = HRPerson::where('id', $hrID)
            ->with(['jobTitle.kpiTemplate.kpi.results' => function ($query) use ($hrID) {
                $query->where('hr_id', $hrID);
            }])
            ->with('jobTitle.kpiTemplate.kpi.kpiskpas')
            ->with('jobTitle.kpiTemplate.kpi.kpiranges')
            ->with('jobTitle.kpiTemplate.kpi.kpiNumber')
            ->with('jobTitle.kpiTemplate.kpi.kpiIntScore')
            ->get()
            ->first();
        //return $emp;

        $data['emp'] = $emp;
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load an Employee's Appraisals";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-line-chart', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisal', 'path' => '/appraisal/load_appraisals', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 0],
            ['title' => 'List', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Performance Appraisal', 'Employee Appraisal List Page Accessed', "Accessed by User", 0);
        return view('appraisals.view_emp_appraisals')->with($data);
    }

	# Redicte to upload type
	public function uploadAppraisal(Request $request)
    {
		$this->validate($request, [     
           'upload_type' => 'bail|required|integer|min:0',         
           'kpi_id' => 'bail|required|integer|min:0',         
           'date_uploaded' => 'bail|required|integer|min:0',         
        ]);
		$uploadTypes = [1 => "General", 2 => 'Clock In', 3 => 'Query Report '];
		$templateData = $request->all();
		unset($templateData['_token']);
		$uploadType = $request->input('upload_type');
		//convert dates to unix time stamp
        if (isset($templateData['date_uploaded'])) {
            $templateData['date_uploaded'] = str_replace('/', '-', $templateData['date_uploaded']);
            $templateData['date_uploaded'] = strtotime($templateData['date_uploaded']);
        }
        $kipID = $templateData['kpi_id'];
		if($request->hasFile('input_file'))
		{
			//return $templateData;
			$path = $request->file('input_file')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			if(!empty($data) && $data->count())
			{
				foreach ($data->toArray() as $key => $value) 
				{
					if(!empty($value))
					{
						foreach ($value as $val) 
						{
							//$employeeCode = $val['pin_code'];
							$employees = HRPerson::where('employee_number', 12)->first();
							$template = appraisalsKpis::where('id', $kipID)->first(); //template_id
							if ($uploadType == 1)
								$insert[] = ['kip_id' => $kipID,'template_id' => $template->template_id,
								'result' => $val['result'], 
								'date_uploaded' => $templateData['date_uploaded'],
								'hr_id' => $employees->id];
							elseif ($uploadType == 2) // Make calculations if clockin time is greater than normal time late else not late
							{// 1 for late, 2 for not late
								$attendance = 2;
								if (!empty($val['entry']))
								{
									$entryDate =  explode(" ", $val['entry']);
									$normalTimeDate = explode(" ", $val['normal_time']);
									$entry = explode(":", $entryDate[1]);
									$normalTime = explode(":", $normalTimeDate[1]);
									if ($entry[0] > $normalTime[0]) $attendance = 1;
									else 
									{
										if ($entry[1] > ($normalTime[1] + 15)) $attendance = 1;
										else $attendance = 2;
									}
									$insert[] = ['kip_id' => $kipID, 'attendance' => $attendance, 
									'date_uploaded' => $templateData['date_uploaded'], 
									'hr_id' => $employees->id];
								}
							}
							elseif ($uploadType == 3)
							{
								$value['query_date'] = !empty($value['query_date']) ? strtotime($value['query_date']) : 0;
								$value['departure_date'] = !empty($value['departure_date']) ? strtotime($value['departure_date']) : 0;
								$value['invoice_date'] = !empty($value['invoice_date']) ? strtotime($value['invoice_date']) : 0;
								
								$query = new AppraisalQuery_report();
								$query->kip_id = $kipID;
								$query->query_code = $value['query_code'];
								$query->voucher_verification_code = $value['voucher_verification_code'];
								$query->query_type = $value['query_type'];
								$query->query_date = $value['query_date'];
								$query->hr_id = $employees->id;
								$query->account_no = $value['account_no'];
								$query->account_name = $value['account_name'];
								$query->traveller_name = $value['traveller_name'];
								$query->departure_date = $value['departure_date'];
								$query->supplier_name = $value['supplier_name'];
								$query->supplier_invoice_number = $value['supplier_invoice_number'];
								$query->created_by = $value['created_by'];
								$query->voucher_number = $value['voucher_number'];
								$query->invoice_date = $value['invoice_date'];
								$query->order_umber = $value['order_number'];
								$query->invoice_amount = $value['invoice_amount'];
								$query->comment = $value['query_comments'];
								$query->date_uploaded = $templateData['date_uploaded'];
								$query->save();	
								return back()->with('success',"$uploadTypes[$uploadType] Records were successfully inserted.");								
							}
						}
					}
				}
				if(!empty($insert))
				{
					if ($uploadType == 1)
						AppraisalKPIResult::insert($insert);
					elseif ($uploadType == 2)
						AppraisalClockinResults::insert($insert);
					return back()->with('success',"$uploadTypes[$uploadType] Records were successfully inserted.");
				}
			}
			else return back()->with('error','Please Check your file, Something is wrong there.');
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
