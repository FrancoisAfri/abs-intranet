<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\HRPerson;
use Illuminate\Contracts\View\View;
use App\AppraisalQuery_report;
use App\AppraisalKPIResult;
use App\AppraisalClockinResults;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
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
        AuditReportsController::store('Performance Appraisal', 'KPI Integer Score Range Details Page Accessed', "Accessed by User", 0);
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
    public function store(Request $request)
    {
        //
    }
	# Redicte to upload type
	public function uploadAppraisal(Request $request)
    {
		$this->validate($request, [     
           'upload_type' => 'bail|required|integer|min:0',         
        ]);
		$templateData = $request->all();
		unset($templateData['_token']);
		$uploadType = $request->input('upload_type');
		if($request->hasFile('input_file'))
		{
			return $templateData;
			$path = $request->file('input_file')->getRealPath();
			$data = Excel::load($path, function($reader) {})->get();
			if(!empty($data) && $data->count())
			{
				foreach ($data->toArray() as $key => $value) 
				{
					if(!empty($value))
					{
						foreach ($value as $v) 
						{
							if ($uploadType == 1)
							{
								$insert[] = ['employee_no' => $v['employee_no'], 'result' => $v['result'], 'date_uploaded' => $v['result'], 'result' => $v['result']];
							}
							elseif ($uploadType == 2) // Make calculations if clockin time is greater than normal time late else not late
							{
								$insert[] = ['title' => $v['title'], 'description' => $v['description']];
							}
							elseif ($uploadType == 3)
								$insert[] = ['title' => $v['title'], 'description' => $v['description']];
						}
					}
				}

				return $templateData;
				if(!empty($insert))
				{
					if ($file == 'general_upload')
						Item::insert($insert);
					elseif ($file == 'clockin_upload')
						Item::insert($insert);
					elseif ($file == 'query_upload')
						Item::insert($insert);
						
					return back()->with('success','Insert Record successfully.');
				}

			}

		}
		
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load Appraisals KPI's";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/load_appraisals', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Appraisals';
        //AuditReportsController::store('Performance Appraisal', 'Upload type accessed', "Accessed by User", 0);
		//return view('appraisals.upload_appraisal')->with($data);
    }
	
	/*public function uploadAppraisal(Request $request)
    {
		$this->validate($request, [     
           'upload_type' => 'bail|required|integer|min:0',         
        ]);
		# Get kpi from
		$kpis = DB::table('appraisals_kpis')
			->select('appraisals_kpis.measurement','appraisals_kpis.id')
			->where('appraisals_kpis.is_upload', 1)
			->orderBy('appraisals_kpis.measurement')
			->get();
		if ($request->input('upload_type') == 1) $uploadName = "general_upload";
		elseif ($request->input('upload_type') == 2) $uploadName = "clockin_upload";
		elseif ($request->input('upload_type') == 3) $uploadName = "query_upload";
		
        $data['kpis'] = $kpis;
        $data['uploadName'] = $uploadName;
        $data['upload_type'] = $request->input('upload_type');
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load Appraisals KPI's";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/load_appraisals', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Performance Appraisal', 'Upload type accessed', "Accessed by User", 0);
		return view('appraisals.upload_appraisal')->with($data);
    }*/
	
	public function uploadkpi(Request $request)
    {
		$this->validate($request, [     
           'upload_type' => 'bail|required|integer|min:0',         
           'date_uploaded' => '',         
           'kpi_id' => 'bail|required|integer|min:0',         
        ]);
		$file = $request->input('type');
		if($request->hasFile($file))
		{
			$path = $request->file($file)->getRealPath();

			$data = Excel::load($path, function($reader) {})->get();

			if(!empty($data) && $data->count()){

				foreach ($data->toArray() as $key => $value) {
					if(!empty($value)){
						foreach ($value as $v) {
							if ($file == 'general_upload')
								Item::insert($insert);
							elseif ($file == 'clockin_upload')
								Item::insert($insert);
							elseif ($file == 'query_upload')
								Item::insert($insert);
							$insert[] = ['title' => $v['title'], 'description' => $v['description']];
						}
					}
				}

				
				if(!empty($insert))
				{
					if ($file == 'general_upload')
						Item::insert($insert);
					elseif ($file == 'clockin_upload')
						Item::insert($insert);
					elseif ($file == 'query_upload')
						Item::insert($insert);
						
					return back()->with('success','Insert Record successfully.');
				}

			}

		}
		return $request;
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load Appraisals KPI's";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/load_appraisals', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Performance Appraisal', 'Upload type accessed', "Accessed by User", 0);
		return back()->with('error','Please Check your file, Something is wrong there.');
		return view('appraisals.upload_appraisal')->with($data);
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
