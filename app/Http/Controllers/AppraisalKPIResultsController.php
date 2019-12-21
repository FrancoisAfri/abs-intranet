<?php

namespace App\Http\Controllers;

use App\AppraisalKPIResult;
use App\appraisalsKpis;
use App\DivisionLevel;
use App\HRPerson;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use App\AppraisalQuery_report;
use App\AppraisalClockinResults;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
			->select('appraisals_kpis.indicator','appraisals_kpis.id')
			->where('appraisals_kpis.is_upload', 1)
			->whereNull('existing_kpi_id')
			->orWhere('existing_kpi_id', '<', 1)
			->orderBy('appraisals_kpis.indicator')
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

    public function loadEmpAppraisals($empID, $appraisalMonth, $isEmpAppraisal = false){
		//test
		//return AppraisalKPIResult::empAppraisal(2);
		//return AppraisalKPIResult::empAppraisalByKPA(2, 'March 2017');
		//return AppraisalKPIResult::empAppraisalForKPA(2, 'March 2017', 1);
		//end test
        $appraisalMonth = trim($appraisalMonth);
        $monthStart = strtotime(new Carbon("first day of $appraisalMonth"));
        $monthEnd = new Carbon("last day of $appraisalMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
        $appraiserID = Auth::user()->person->id;
        /*$emp = HRPerson::where('id', $empID)
            ->with(['jobTitle.kpiTemplate.kpi.results' => function ($query) use ($empID, $monthStart, $monthEnd) {
                $query->where('hr_id', $empID);
                $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
            }])
            ->with('jobTitle.kpiTemplate.kpi.kpiskpas')
            ->with('jobTitle.kpiTemplate.kpi.kpiranges')
            ->with('jobTitle.kpiTemplate.kpi.kpiNumber')
            ->with('jobTitle.kpiTemplate.kpi.kpiIntScore')
            ->get()
            ->first();*/

		$emp = HRPerson::find($empID)->load('jobTitle.kpiTemplate', 'threeSixtyPeople');
		if ($emp->jobTitle && $emp->jobTitle->kpiTemplate) {
			$kpis = appraisalsKpis::where(function ($query) {
				$query->where('is_upload', 2);
				$query->where(function ($query) {
                    $query->WhereNotIn('is_task_kpi', [1]);
                    $query->orWhereNull('is_task_kpi');
                });
				//$query->orWhereNotIn('upload_type', [1, 2, 3]);
			})
				->where('template_id', $emp->jobTitle->kpiTemplate->id)
				->where(function ($query) {
					$query->where('is_task_kpi', null);
					$query->orWhere('is_task_kpi', 0);
				})
				->where('appraisals_kpis.status', 1)
				->where('appraisal_kpas.status', 1)
				->join('appraisal_kpas', 'appraisals_kpis.kpa_id', '=', 'appraisal_kpas.id')
				->with(['results' => function ($query) use ($empID, $monthStart, $monthEnd) {
				    //if (! $isEmpAppraisal) {
                        $query->where('hr_id', $empID);
                        $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
                    //}
				}])
                ->with(['empResults' => function ($query) use ($empID, $monthStart, $monthEnd, $appraiserID) {
                    //if ($isEmpAppraisal) {
                        $query->where('hr_id', $empID);
                        $query->where('appraiser_id', $appraiserID);
                        $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
                    //}
                }])
				->with('kpiranges')
				->with('kpiNumber')
				->with('kpiIntScore')
				->select('appraisals_kpis.*', 'appraisal_kpas.id as kpa_id', 'appraisal_kpas.name as kpa_name', 'appraisal_kpas.weight as kpa_weight')
				->orderBy('appraisal_kpas.name')
				->get();
		}
		else $kpis = [];
			
        /*$kpis = appraisalsKpis::with(['results' => function ($query) use ($empID, $monthStart, $monthEnd) {
                $query->where('hr_id', $empID);
                $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
            }])
            ->with('kpiranges')
            ->with('kpiNumber')
            ->with('kpiIntScore')
            ->join('appraisal_kpas', 'appraisals_kpis.kpa_id', '=', 'appraisal_kpas.id')
            ->join('appraisal_templates', 'appraisals_kpis.template_id', '=', 'appraisal_templates.id')
            ->join('hr_positions', 'appraisal_templates.job_title_id', '=', 'hr_positions.id')
            ->join('hr_people', 'hr_positions.id', '=', 'hr_people.position')
            ->select('appraisals_kpis.*',
                'appraisal_kpas.id as kpa_id', 'appraisal_kpas.name as kpa_name', 'appraisal_kpas.weight as kpa_weight')
            ->orderBy('appraisal_kpas.name')
            ->get();*/
        //return $kpis;

        $aPositions = [];
        $cPositions = DB::table('hr_positions')->get();
        foreach ($cPositions as $position) {
            $aPositions[$position->id] = $position->name;
        }

        $formAction = '/appraisal/emp/appraisal/save';
        if ($isEmpAppraisal) $formAction = '/appraisal/appraise-yourself';

        $showThreeSixtySection = ($isEmpAppraisal && $empID === $appraiserID) ? true : false;
        $threeSixtyPeopleCollection = $emp->threeSixtyPeople;
        $threeSixtyPeopleIDs = [];
        foreach ($threeSixtyPeopleCollection as $threeSixtyPerson) {
            $threeSixtyPeopleIDs[] = $threeSixtyPerson->appraiser_id;
        }
        $threeSixtyPeople = HRPerson::whereIn('id', $threeSixtyPeopleIDs)->get();
        $threeSixtyPeopleIDs[] = $empID;
        $threeSixtyDDEmps = HRPerson::where('status', 1)->whereNotIn('id', $threeSixtyPeopleIDs)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();

        $data['emp'] = $emp;
        $data['kpis'] = $kpis;
        $data['isEmpAppraisal'] = $isEmpAppraisal;
        $data['appraisalMonth'] = $appraisalMonth;
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['positions'] = $aPositions;
        $data['formAction'] = $formAction;
        $data['showThreeSixtySection'] = $showThreeSixtySection;
        $data['threeSixtyPeople'] = $threeSixtyPeople;
        $data['threeSixtyDDEmps'] = $threeSixtyDDEmps;
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Capture an Employee's Appraisal Score";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-line-chart', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisal', 'path' => '/appraisal/load_appraisals', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 0],
            ['title' => 'List', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = ($isEmpAppraisal) ? 'My Appraisal' : 'Appraisals';
        AuditReportsController::store('Performance Appraisal', "Employee Appraisal $appraisalMonth Result Page Accessed", "Accessed by User", 0);

        return view('appraisals.view_emp_appraisals')->with($data);
    }

    public function storeEmpAppraisals(Request $request) {
        //return "gets here";
        $this->validate($request, [
            'score.*' => 'numeric|min:0',
        ]);

        $appraisalMonth = $request->input('appraisal_month');
        $monthStart = strtotime(new Carbon("first day of $appraisalMonth"));
        $monthEnd = new Carbon("last day of $appraisalMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
        $hrID = $request->input('hr_person_id');
        $kpiIDs = $request->input('kpi_id');
        $scores = $request->input('score');
		//return "Appraisal month: $appraisalMonth, HR id: $hrID, month start: $monthStart, month enf: $monthEnd";
		//return Carbon::today()->day . ' ' . $appraisalMonth;
        foreach ($kpiIDs as $kpiID) {
            $kpiResult = AppraisalKPIResult::where('kpi_id', $kpiID)->where('hr_id', $hrID)->whereBetween('date_uploaded', [$monthStart, $monthEnd])->get();
            if (count($kpiResult) > 0) { //update result
                $kpiResult = $kpiResult->first();
                $kpiResult->score = trim($scores[$kpiID]) != '' ? trim($scores[$kpiID]) : null;
                $kpiResult->update();
            }
            else { //insert new result
                $kpi = appraisalsKpis::find($kpiID);
                $result = new AppraisalKPIResult();
                $result->kpi_id = $kpiID;
                $result->hr_id = $hrID;
                $result->date_uploaded = strtotime('15 ' . $appraisalMonth);
                $result->score = trim($scores[$kpiID]) != '' ? trim($scores[$kpiID]) : null;
                $result->appraiser_id = Auth::user()->person->id;
                $result->save();
            }
        }
		AuditReportsController::store('Performance Appraisal', 'Appraisal result entered for ' . $appraisalMonth, "Actioned by User", 0);
        return redirect("appraisal/load/result/$hrID/$appraisalMonth")->with('success_edit', "The employee's appraisals have been saved successfully.");
    }

	# Redicte to upload type
	public function uploadAppraisal(Request $request)
    {
		$this->validate($request, [     
           'upload_type' => 'bail|required|integer|min:0',         
           'kpi_id' => 'bail|required|integer|min:0',  
		    'date_uploaded' => 'required',
        ]);
		$uploadTypes = [1 => "General", 2 => 'Clock In', 3 => 'Query Report '];
		$templateData = $request->all();
		$queryCodeNew = $employeeNO = $date = $count = 0;
		unset($templateData['_token']);
		$appraisalMonth = trim($templateData['date_uploaded']);
		$uploadType = $request->input('upload_type');
		//convert dates to unix time stamp
        if (isset($templateData['date_uploaded']))
            $templateData['date_uploaded'] = strtotime(Carbon::today()->day . ' ' . $appraisalMonth);
        $kipID = $templateData['kpi_id'];
		$kpis = appraisalsKpis::where('id', $kipID)->orWhere('existing_kpi_id', $kipID)->get(); //template_id
		//return $kpis;
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
						foreach ($value as $val) 
						{
							if ($uploadType == 1) $employeeCode = $value['employee_number'];
							elseif ($uploadType == 3) $employeeCode = $value['employee_number'];
							else $employeeCode = $val['employee_number'];
							$employees = HRPerson::where('employee_number', $employeeCode)->first();
							if ($employees) {
								$employees->load('jobTitle.kpiTemplate');
							} else continue;
							foreach ($kpis as $kip)
							{
								if (!empty($employees->jobTitle) && !empty($employees->jobTitle->kpiTemplate) && !empty($employees->jobTitle->kpiTemplate->id) && ($employees->jobTitle->kpiTemplate->id == $kip->template_id))
								{
									if ($uploadType == 1)
									{
										if ($employeeNO == $employees->id) continue;
										$insert[] = ['kpi_id' => $kip->id,'template_id' => $kip->template_id,
										'score' => $value['result'], 
										'date_uploaded' => $templateData['date_uploaded'],
										'hr_id' => $employees->id];
										$employeeNO = $employees->id;
										$count =  $count + 1;
									}
									elseif ($uploadType == 2) // Make calculations if clockin time is greater than normal time late else not late
									{// 1 for late, 2 for not late
										$attendance = 2;
										if (!empty($val['entry']) && !empty($val['normal_time']))
										{
											if ($employeeNO == $employees->id && $date == $val['date']) continue;
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
											$insert[] = ['kip_id' => $kip->id, 'attendance' => $attendance, 
											'date_uploaded' => $templateData['date_uploaded'], 
											'hr_id' => $employees->id];
											$employeeNO = $employees->id;
											$date = $val['date'];
											$count =  $count + 1;
										}
									}
									elseif ($uploadType == 3)
									{
										if ($queryCodeNew == $value['query_code']) continue;
										$value['query_date'] = !empty($value['query_date']) ? strtotime($value['query_date']) : 0;
										$value['departure_date'] = !empty($value['departure_date']) ? strtotime($value['departure_date']) : 0;
										$value['invoice_date'] = !empty($value['invoice_date']) ? strtotime($value['invoice_date']) : 0;
										$query = new AppraisalQuery_report();
										$query->kip_id = $kip->id;
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
										$insert = 1;
										$count =  $count + 1;										
										$queryCodeNew = $value['query_code'];										
									}
								}
							}
						}
					}
				}
				if(!empty($insert))
				{
					if ($uploadType == 1) AppraisalKPIResult::insert($insert);
					elseif ($uploadType == 2) AppraisalClockinResults::insert($insert);
					return redirect('/appraisal/load_appraisals')->with('success_insert', "$uploadTypes[$uploadType] Records were successfully inserted $count.");
				}
				else return redirect('/appraisal/load_appraisals')->with('success_insert', "$uploadTypes[$uploadType] Records were not uploaded.");

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
}