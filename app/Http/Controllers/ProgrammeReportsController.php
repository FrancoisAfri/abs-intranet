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
class ProgrammeReportsController extends Controller
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
        $data['page_title'] = "Programme Report";
        $data['page_description'] = "Programme Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Programmes', 'path' => '/reports/programme', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Programmes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Programmes';
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
		$data['programme_managers'] = $programmeManagers;
		$data['activityFacilitators'] = $activityFacilitators;
        $data['service_providers'] = $serviceProviders;
		AuditReportsController::store('Reports', 'Programme Search Form Viewed ', "Actioned By User", 0);
        return view('reports.programme_search')->with($data);
    }

    public function programmeReports(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$managerID = $request->Prog_manager_id;
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
		->select('programmes.*','contacts_companies.name as com_name', 'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname')
		->leftJoin('contacts_companies', 'programmes.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'programmes.manager_id', '=', 'hr_people.id')
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
		->orderBy('programmes.name')
		->get();
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['service_provider_id'] = $request->service_provider_id;
        $data['Prog_manager_id'] = $request->Prog_manager_id ;
        $data['programmes'] = $programmes;
		$data['page_title'] = "Programme Report";
        $data['page_description'] = "Programme Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Programmes', 'path' => '/reports/programme', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Programmes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Programmes';
		AuditReportsController::store('Reports', 'Programme Search Results Viewed ', "Actioned By User", 0);
        return view('reports.programme_results')->with($data);
    }
	public function printProgramme(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$managerID = $request->Prog_manager_id;
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
		->select('programmes.*','contacts_companies.name as com_name', 'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname')
		->leftJoin('contacts_companies', 'programmes.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'programmes.manager_id', '=', 'hr_people.id')
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
		->orderBy('programmes.name')
		->get();
		$user = Auth::user()->load('person');
        $data['programmes'] = $programmes;
		$data['page_title'] = "Programme Report";
        $data['page_description'] = "Programme Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Programmes', 'path' => '/reports/programme', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Programmes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Programmes';
		$data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.png');
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		AuditReportsController::store('Reports', 'Programme Search Results Printed ', "Actioned By User", 0);
        return view('reports.programme_print')->with($data);
    }
	public function projectsReports(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$programmeID = $request->programme_id;
		$facilitatorID = $request->facilitator_id;
		$managerID = $request->manager_id;
		
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
		->select('projects.*','contacts_companies.name as com_name', 'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname', 'programmes.name as prog_name')
		->leftJoin('contacts_companies', 'projects.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'projects.facilitator_id', '=', 'hr_people.id')
		->leftJoin('programmes', 'projects.programme_id', '=', 'programmes.id')
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
		->where(function ($query) use ($managerID) {
		if (!empty($managerID)) {
			$query->where('projects.manager_id', $managerID);
		}
		})
		->orderBy('projects.name')
		->get();
		$data['projects'] = $projects;
		$data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['service_provider_id'] = $request->service_provider_id;
        $data['manager_id'] = $request->manager_id ;
        $data['programme_id'] = $request->programme_id ;
        $data['facilitator_id'] = $request->facilitator_id ;
        $data['page_title'] = "Project Report";
        $data['page_description'] = "Project Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Projects', 'path' => '/reports/programme', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Projects Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Programmes';
		AuditReportsController::store('Reports', 'Project Search Form Viewed ', "Actioned By User", 0);
        return view('reports.projects_results')->with($data);
    }
	public function printProjects(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$programmeID = $request->programme_id;
		$facilitatorID = $request->facilitator_id;
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
		->select('projects.*','contacts_companies.name as com_name', 'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname', 'programmes.name as prog_name')
		->leftJoin('contacts_companies', 'projects.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'projects.facilitator_id', '=', 'hr_people.id')
		->leftJoin('programmes', 'projects.programme_id', '=', 'programmes.id')
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
		->orderBy('projects.name')
		->get();
		$user = Auth::user()->load('person');
		$data['projects'] = $projects;
        $data['page_title'] = "Project Report";
        $data['page_description'] = "Project Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Programmes', 'path' => '/reports/programme', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Programmes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Programmes';
		$data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.png');
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		AuditReportsController::store('Reports', 'Project Search Form Printed ', "Actioned By User", 0);
        return view('reports.projects_print')->with($data);
    }
	public function activityReports(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$projectID = $request->project_id;
		$facilitatorID = $request->act_facilitator_id;
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
		->select('activities.*','contacts_companies.name as com_name', 'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname', 'projects.name as proj_name')
		->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
		->leftJoin('contacts_companies', 'activities.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'activities.facilitator_id', '=', 'hr_people.id')
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
		->orderBy('activities.name')
		->get();
		$data['activities'] = $activities;
		$data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['service_provider_id'] = $request->service_provider_id;
        $data['project_id'] = $request->project_id ;
        $data['act_facilitator_id'] = $request->act_facilitator_id ;
        $data['page_title'] = "Activities Report";
        $data['page_description'] = "Activities Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Programmes', 'path' => '/reports/programme', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Programmes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Programmes';
		AuditReportsController::store('Reports', 'Activity Search Results Viewed ', "Actioned By User", 0);
        return view('reports.activities_results')->with($data);
    }
	public function printActivity(Request $request)
    {
		$startFrom = $startTo = $endFrom = $endTo = 0;
		$startDate = $request->start_date;
		$endDate = $request->end_date;
		$serviceProviderID = $request->service_provider_id;
		$projectID = $request->project_id;
		$facilitatorID = $request->act_facilitator_id;
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
		->select('activities.*','contacts_companies.name as com_name', 'hr_people.first_name as hr_firstname', 'hr_people.surname as hr_surname', 'projects.name as proj_name')
		->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
		->leftJoin('contacts_companies', 'activities.service_provider_id', '=', 'contacts_companies.id')
		->leftJoin('hr_people', 'activities.facilitator_id', '=', 'hr_people.id')
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
		->orderBy('activities.name')
		->get();
		$user = Auth::user()->load('person');
		$data['activities'] = $activities;
        $data['projects'] = $projects;
        $data['page_title'] = "Activities Report";
        $data['page_description'] = "Activities Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/programme', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Programmes', 'path' => '/reports/programme', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Programmes Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Programmes';
		$data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.png');
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		AuditReportsController::store('Reports', 'Activity Search Results Printed ', "Actioned By User", 0);
        return view('reports.activities_print')->with($data);
    }
}