<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\contacts_company;
use App\HRPerson;
use App\projects;
use App\activity;
use App\programme;
use App\Http\Controllers\AuditReportsController;
class educationSearchController extends Controller
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
        $data['page_title'] = "Education Search";
        $data['page_description'] = "Education Search";
        $data['breadcrumb'] = [
            ['title' => 'Education', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Education Search', 'active' => 1, 'is_module' => 0]
        ];
		$programmeManagers = HRPerson::where('status', 1)->where('position', 3)->get();
		$activityFacilitators = HRPerson::where('status', 1)->where('position', 6)->get();
        //$serviceProviders = contacts_company::where('status', 1)->where('company_type', 1)->orderBy('name')->get();
        $serviceProviders = (object) array();
		$programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
		$projects = DB::table('projects')/*->where('status', 1)*/->orderBy('name', 'asc')->get();
		$facilitators = DB::table('hr_people')->where('position', 6)->orderBy('first_name', 'asc')->get();
		$managers = DB::table('hr_people')->where('position', 5)->orderBy('first_name', 'asc')->get();
		//$managers = DB::table('contacts_companies')->where('type', 2)->orderBy('name', 'asc')->get();
		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['programmes'] = $programmes;
		$data['projects'] = $projects;
		$data['facilitators'] = $facilitators;
		$data['managers'] = $managers;
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		$data['programme_managers'] = $programmeManagers;
		$data['activityFacilitators'] = $activityFacilitators;
        $data['service_providers'] = $serviceProviders;
		AuditReportsController::store('Programmes', 'Education Search', "Actioned By User", 0);
        return view('education.education_search')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $data['page_title'] = "Education Search";
        $data['page_description'] = "Education Search";
        $data['breadcrumb'] = [
            ['title' => 'Education', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Education Search', 'active' => 1, 'is_module' => 0]
        ];
		$programmeManagers = HRPerson::where('status', 1)->where('position', 3)->get();
		$activityFacilitators = HRPerson::where('status', 1)->where('position', 6)->get();
        $serviceProviders = contacts_company::where('status', 1)->where('company_type', 1)->orderBy('name')->get();
        $programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
		$projects = DB::table('projects')/*->where('status', 1)*/->orderBy('name', 'asc')->get();
		$facilitators = DB::table('hr_people')->where('position', 6)->orderBy('first_name', 'asc')->get();
		$managers = DB::table('hr_people')->where('position', 5)->orderBy('first_name', 'asc')->get();
		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
		$data['programmes'] = $programmes;
		$data['projects'] = $projects;
		$data['facilitators'] = $facilitators;
		$data['managers'] = $managers;
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		$data['programme_managers'] = $programmeManagers;
		$data['activityFacilitators'] = $activityFacilitators;
        $data['service_providers'] = $serviceProviders;
		AuditReportsController::store('Programmes', 'Education Search', "Actioned By User", 0);
        return view('education.education_search')->with($data);
    }
	public function programmeSearch(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$name = $request->name;
		$code = $request->code;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$managerID = $request->manager_id;
		$status = $request->status;
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
		/*->leftJoin('contacts_companies', 'programmes.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'programmes.manager_id', '=', 'hr_people.id')*/
		//->where('programmes.', $)
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
		->where(function ($query) use ($managerID) {
		if (!empty($managerID)) {
			$query->where('programmes.manager_id', $managerID);
		}
		})
		->where(function ($query) use ($serviceProviderID) {
		if (!empty($serviceProviderID)) {
			$query->where('programmes.service_provider_id', $serviceProviderID);
		}
		})
		->where(function ($query) use ($code) {
			if (!empty($code)) {
				$query->where('code', 'ILIKE', "%$code%");
			}
		})
		->where(function ($query) use ($name) {
			if (!empty($name)) {
				$query->where('name', 'ILIKE', "%$name%");
			}
		})
		->where(function ($query) use ($status) {
			if (!empty($status)) {
				$query->where('status', "$status");
			}
		})
		->orderBy('programmes.name')
		->get();
		$programmeStatus = ['' => '', -1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
		$data['page_title'] = "Programmes Search Results";
        $data['page_description'] = "Programmes Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Projects Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['programmes'] = $programmes;
        $data['status_strings'] = $programmeStatus;
		//return $data;
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Programmes', 'View Programmes Search Results', "View Search Results", 0);
        return view('education.programme_search')->with($data);
    }
    public function programmestatusSearch($status)
    {
    	//return 'gets here';
    	//$programmes = p
    	$programmes = programme::where('status', 1)->get();
    	$programmeStatus = ['' => '', -1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
		$data['page_title'] = "Programmes Search Results";
        $data['page_description'] = "Programmes Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Projects Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['programmes'] = $programmes;
        $data['status_strings'] = $programmeStatus;
		//return $data;
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Programmes', 'View Programmes Search Results', "View Search Results", 0);
		//return $programmes;
        return view('education.programme_search')->with($data);

    }

    //search the status for new projects
public function projectstatusSearch($status)
    {
    	//return 'gets here';
    	//$programmes = p
    	$project= projects::where('status', 1)->get();
    	$programmeStatus = ['' => '', -1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
		$data['status_strings'] = $programmeStatus;
		$data['projects'] = $project;
        $data['page_title'] = "Projects Search Results";
        $data['page_description'] = "Projects Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Education Search', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Programmes', 'View Projects Search Results', "View Search Results", 0);
        return view('education.projects_search')->with($data);

    }

    	//search the status for new Activity

    public function ActivitystatusSearch($status)
    {
    	//return 'gets here';
    	//$programmes = p
    	$activities= activity::where('status', 1)->get();
    	$programmeStatus = ['' => '', -1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
		$data['status_strings'] = $programmeStatus;
		$data['activities'] = $activities;
        $data['page_title'] = "Activities Search Results";
        $data['page_description'] = "Activities Search";
        $data['breadcrumb'] = [
            ['title' => 'Education', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Education Search', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Programmes', 'View Activities Search Results', "View Search Results", 0);
        return view('education.activity_search')->with($data);
        
    }


	public function projectsSearch(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$name = $request->project_name;
		$code = $request->project_code;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$programmeID = $request->programme_id;
		$facilitatorID = $request->facilitator_id;
		$managerID = $request->manager_id;
		$status = $request->status;
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
		$projects = DB::table('projects')
		/*->leftJoin('contacts_companies', 'projects.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'projects.manager_id', '=', 'hr_people.id')*/
		//->where('projects.', $)
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('projects.start_date', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($endFrom, $endTo) {
		if ($endFrom  > 0 && $endTo  > 0) {
			$query->whereBetween('projects.end_date', [$endFrom, $endTo]);
		}
		})
		->where(function ($query) use ($managerID) {
		if (!empty($managerID)) {
			$query->where('projects.manager_id', $managerID);
		}
		})
		->where(function ($query) use ($serviceProviderID) {
		if (!empty($serviceProviderID)) {
			$query->where('projects.service_provider_id', $serviceProviderID);
		}
		})
		->where(function ($query) use ($facilitatorID) {
		if (!empty($facilitatorID)) {
			$query->where('projects.facilitator_id', $facilitatorID);
		}
		})
		->where(function ($query) use ($programmeID) {
		if (!empty($programmeID)) {
			$query->where('projects.programme_id', $programmeID);
		}
		})
		->where(function ($query) use ($code) {
			if (!empty($code)) {
				$query->where('code', 'ILIKE', "%$code%");
			}
		})
		->where(function ($query) use ($name) {
			if (!empty($name)) {
				$query->where('name', 'ILIKE', "%$name%");
			}
		})
		->where(function ($query) use ($status) {
			if (!empty($status)) {
				$query->where('status', "$status");
			}
		})
		->orderBy('projects.name')
		->get();
		$programmeStatus = ['' => '', -1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
		$data['status_strings'] = $programmeStatus;
		$data['projects'] = $projects;
        $data['page_title'] = "Projects Search Results";
        $data['page_description'] = "Projects Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Education Search', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Programmes', 'View Projects Search Results', "View Search Results", 0);
        return view('education.projects_search')->with($data);
    }
	public function activitySearch(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$name = $request->activity_name;
		$code = $request->activity_code;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$projectID = $request->project_id;
		$facilitatorID = $request->activity_id;
		$managerID = $request->manager_id;
		$status = $request->status;
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
		$activities = DB::table('activities')
		/*->leftJoin('contacts_companies', 'activities.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'activities.manager_id', '=', 'hr_people.id')*/
		//->where('activities.', $)
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('activities.start_date', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($endFrom, $endTo) {
		if ($endFrom  > 0 && $endTo  > 0) {
			$query->whereBetween('activities.end_date', [$endFrom, $endTo]);
		}
		})
		->where(function ($query) use ($managerID) {
		if (!empty($managerID)) {
			$query->where('activities.manager_id', $managerID);
		}
		})
		->where(function ($query) use ($serviceProviderID) {
		if (!empty($serviceProviderID)) {
			$query->where('activities.service_provider_id', $serviceProviderID);
		}
		})
		->where(function ($query) use ($facilitatorID) {
		if (!empty($facilitatorID)) {
			$query->where('activities.facilitator_id', $facilitatorID);
		}
		})
		->where(function ($query) use ($projectID) {
		if (!empty($projectID)) {
			$query->where('activities.project_id', $projectID);
		}
		})
		->where(function ($query) use ($code) {
			if (!empty($code)) {
				$query->where('code', 'ILIKE', "%$code%");
			}
		})
		->where(function ($query) use ($name) {
			if (!empty($name)) {
				$query->where('name', 'ILIKE', "%$name%");
			}
		})
		->where(function ($query) use ($status) {
			if (!empty($status)) {
				$query->where('status', "$status");
			}
		})
		->orderBy('activities.name')
		->get();
		$programmeStatus = ['' => '', -1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
		$data['status_strings'] = $programmeStatus;
		$data['activities'] = $activities;
        $data['page_title'] = "Activities Search Results";
        $data['page_description'] = "Activities Search";
        $data['breadcrumb'] = [
            ['title' => 'Education', 'path' => '/education', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/education/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Education Search', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Programmes';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Programmes', 'View Activities Search Results', "View Search Results", 0);
        return view('education.activity_search')->with($data);
    }
}
