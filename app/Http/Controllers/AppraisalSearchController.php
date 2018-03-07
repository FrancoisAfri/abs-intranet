<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\HRPerson;
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
use Carbon\Carbon;

class AppraisalSearchController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    /*
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Performance Appraisal', 'Search page accessed', "Accessed by User", 0);
        return view('appraisals.appraisal_search')->with($data);
    }

    // Search Results
    public function searchResults(Request $request)
    {
		/*$this->validate($request, [   
		    'date_uploaded' => 'required',
        ]);*/
		$results = $request->all();

		unset($results['_token']);
		$division5 = !empty($results['division_level_5']) ? $results['division_level_5'] : 0;
		$division4 = !empty($results['division_level_4']) ? $results['division_level_4'] : 0;
		$division3 = !empty($results['division_level_3']) ? $results['division_level_3'] : 0;
		$division2 = !empty($results['division_level_2']) ? $results['division_level_2'] : 0;
		$division1 = !empty($results['division_level_1']) ? $results['division_level_1'] : 0;
		$hrPersonID = !empty($results['hr_person_id']) ? $results['hr_person_id'] : 0;
		$dateUploaded = !empty($results['date_uploaded']) ? $results['date_uploaded'] : 0;
		
		$employees = HRPerson::where('status', 1)
		->where(function ($query) use ($division5) {
			if (!empty($division5)) {
				$query->where('division_level_5', $division5);
			}
		})
		->where(function ($query) use ($division4) {
			if (!empty($division4)) {
				$query->where('division_level_4', $division4);
			}
		})
		->where(function ($query) use ($division3) {
			if (!empty($division3)) {
				$query->where('division_level_3', $division3);
			}
		})
		->where(function ($query) use ($division2) {
			if (!empty($division2)) {
				$query->where('division_level_2', $division2);
			}
		})
		->where(function ($query) use ($division1) {
			if (!empty($division1)) {
				$query->where('division_level_1', $division1);
			}
		})
		->where(function ($query) use ($hrPersonID) {
			if (!empty($hrPersonID)) {
				$query->where('id', $hrPersonID);
			}
		})
		->orderBy('first_name')
		->orderBy('surname')
		->get();
		
		$scoresArray = array();
		foreach ($employees as $employee)
		{
			if (empty($employee->position)) continue;
			if (!empty($dateUploaded))
				$scoresArray[] = AppraisalKPIResult::empAppraisal($employee->id, $dateUploaded);
			else 
				$scoresArray[] = AppraisalKPIResult::empAppraisal($employee->id);
		}
		
		$data['page_title'] = "Appraisals Search Results";
        $data['page_description'] = "Appraisals Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['scoresArray'] = $scoresArray;
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Search';
        $data['year'] = date('Y');
		//return $data;
        AuditReportsController::store('Performance Appraisal', 'Search page accessed', "Accessed by User", 0);
        return view('appraisals.appraisal_search_results')->with($data);
    }

    //search result with predefined parameters
    public function searchResultsWithParameter($hrID, $monthName) {
        $hrPersonID = !empty($hrID) ? $hrID : 0;
        $dateUploaded = !empty($monthName) ? $monthName . ' ' . date('Y') : 0;

        $employees = HRPerson::where('status', 1)
            ->where(function ($query) use ($hrPersonID) {
                if (!empty($hrPersonID)) {
                    $query->where('id', $hrPersonID);
                }
            })
            ->orderBy('first_name')
            ->orderBy('surname')
            ->get();

        $scoresArray = array();
        foreach ($employees as $employee)
        {
            if (empty($employee->position)) continue;
            if (!empty($dateUploaded))
                $scoresArray[] = AppraisalKPIResult::empAppraisal($employee->id, $dateUploaded);
            else
                $scoresArray[] = AppraisalKPIResult::empAppraisal($employee->id);
        }

        $data['page_title'] = "Appraisals Search Results";
        $data['page_description'] = "Appraisals Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];

        $data['scoresArray'] = $scoresArray;
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Search';
        $data['year'] = date('Y');
        //return $data;
        AuditReportsController::store('Performance Appraisal', 'Search page accessed', "Accessed by User", 0);
        return view('appraisals.appraisal_search_results')->with($data);
    }

	public function kpasView($empID, $monthYear)
    {
		$kpasArray = array(); 
		
		$kpasArray = AppraisalKPIResult::empAppraisalByKPA($empID, $monthYear);
		//return $kpasArray;
		$data['page_title'] = "Appraisals KPAs Results";
        $data['page_description'] = "Appraisals KPAs Results";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['kpasArray'] = $kpasArray;
        $data['monthyear'] = $monthYear;
        $data['emp_id'] = $empID;
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Performance Appraisal', 'KPAs Scored Viewed', "Accessed by User", 0);
        return view('appraisals.kpas_scores')->with($data);
    }
	
	public function kpisView($empID, $kpaID, $dateUploaded)
    {
		$kpisArray = array(); 
		
		$kpisArray = AppraisalKPIResult::empAppraisalForKPA($empID, $dateUploaded, $kpaID);
		//return $kpisArray;
		$data['page_title'] = "Appraisals KPIs Results";
        $data['page_description'] = "Appraisals KPIs Results";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['kpisArray'] = $kpisArray;
        $data['monthyear'] = $dateUploaded;
		$data['emp_id'] = $empID;
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Performance Appraisal', 'KPIs Scored Viewed', "Accessed by User", 0);
        return view('appraisals.kpis_scores')->with($data);
    }
	public function queryReport($empID, $monthYear, $kpi)
    {
		$kpis = appraisalsKpis::where('id', $kpi)->first(); 
		$monthStart = strtotime(new Carbon("first day of $monthYear"));
        $monthEnd = new Carbon("last day of $monthYear");
        $monthEnd = strtotime($monthEnd->endOfDay());
		$querReports = AppraisalQuery_report::where('kip_id', $kpi)
											->where('hr_id', $empID)
											->whereBetween('date_uploaded', [$monthStart, $monthEnd])
											->orderBy('query_code')
											->get();
		//return $querReports;
		$data['page_title'] = "Appraisals Query Report Results";
        $data['page_description'] = "Appraisals KPIs Results";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['querReports'] = $querReports;
        $data['monthyear'] = $monthYear;
		$data['emp_id'] = $empID;
		$data['kpis'] = $kpis;
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Performance Appraisal', 'Query report Viewed', "Accessed by User", 0);
        return view('appraisals.query_report')->with($data);
    }
	// individual appraisal
	 public function viewAppraisals($empID)
    {
		
		$scoresArray = array();
		$scoresArray[] = AppraisalKPIResult::empAppraisal($empID);
		$data['page_title'] = "Appraisals Search Results";
        $data['page_description'] = "Appraisals Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['scoresArray'] = $scoresArray;
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Search';
        $data['year'] = date('Y');
		//return $data;
        AuditReportsController::store('Performance Appraisal', 'Search page accessed', "Accessed by User", 0);
        return view('appraisals.appraisal_search_results')->with($data);
    }
}
