<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\contacts_company;
use App\HRPerson;
use App\projects;
use App\programme;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class FinanceReportsController extends Controller
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
        $data['page_title'] = "Finance Report";
        $data['page_description'] = "Finance Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/finance', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Finance', 'path' => '/reports/finance', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Finance Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Finance';
		$programmeManagers = HRPerson::where('status', 1)->where('position', 3)->get();
		$activityFacilitators = HRPerson::where('status', 1)->where('position', 6)->get();
        $serviceProviders = contacts_company::where('status', 1)->where('company_type', 1)->orderBy('name')->get();     
		$programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
		$programme = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
		$activities = DB::table('activities')->where('status', 1)->orderBy('name', 'asc')->get();
		$projects = DB::table('projects')->where('status', 1)->orderBy('name', 'asc')->get();
		$project = DB::table('projects')->where('status', 1)->orderBy('name', 'asc')->get();
		$facilitators = DB::table('hr_people')->where('position', 6)->orderBy('first_name', 'asc')->get();
		$managers = DB::table('hr_people')->where('position', 5)->orderBy('first_name', 'asc')->get();
		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['programmes'] = $programmes;
		$data['programme'] = $programme;
		$data['projects'] = $projects;
		$data['activities'] = $activities;
		$data['project'] = $project;
		AuditReportsController::store('Reports', 'Finance Report Viewed ', "Actioned By User", 0);
        return view('reports.finance_search')->with($data);
    }
	public function programmeReports(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$programmeID = $request->programme_id;
		if (!empty($startDate))
		{
			$startExplode = explode('-', $startDate);
			$startFrom = strtotime($startExplode[0]);
			$startTo = strtotime($startExplode[1]);
		}
		if (!empty($endDate))
		{
			$endExplode = explode('-', $endDate);
			$endFrom = strtotime($endExplode[0]);
			$endTo = strtotime($endExplode[1]);
		}
		$programmes = DB::table('programmes')
		->leftJoin('programme_incomes', 'programmes.id', '=', 'programme_incomes.programme_id')
		->leftJoin('programme_expenditures', 'programmes.id', '=', 'programme_expenditures.programme_id')
		->select('programmes.budget_expenditure','programmes.budget_income','programmes.name')
		->where('programmes.status', 2)
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('programmes.start_date', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($endFrom, $endTo) {
		if ($endFrom  > 0 && $endTo  > 0) {
			$query->whereBetween('programmes.end_date', [$endFrom, $endTo]);
		}
		})
		->where(function ($query) use ($programmeID) {
		if (!empty($programmeID)) {
			$query->where('programmes.id', $programmeID);
		}
		})
		->sum('programme_incomes.amount', 'programme_expenditures.amount');
        //->get();
		$data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['service_provider_id'] = $request->service_provider_id;
        $data['Prog_manager_id'] = $request->Prog_manager_id ;
        $data['programmes'] = $programmes;
		$data['page_title'] = "Programme Report";
        $data['page_description'] = "Programme Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/finance', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Finance', 'path' => '/reports/finance', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Finance Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Finance';
		return $data;
		//die('ddddd');
        return view('reports.programme_results')->with($data);
    }
}
