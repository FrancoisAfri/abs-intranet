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
class EducatorsReportsController extends Controller
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
	// show report criteria page
    public function index()
    {
        $data['page_title'] = "Educators Report";
        $data['page_description'] = "Educators Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/educator', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Educators', 'path' => '/reports/educator', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Educators Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Educators';
		$projects = DB::table('projects')->where('status', 2)->orderBy('name', 'asc')->get();
		$Activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
		$schools = contacts_company::where('company_type', 2)->where('status', 1)->orderBy('name')->get();
        $data['schools'] = $schools;
		$data['projects'] = $projects;
		AuditReportsController::store('Reports', 'Educator Report Accessed', "Actioned By User", 0);
        return view('reports.educator_search')->with($data);
    }
	
	// draw learner report acccording to search criteria
	public function getReport(Request $request)
    {
		$startFrom = $startTo = 0;
		$startDate = $request->start_date;
		$schoolID = $request->school_id;
		$projectID = $request->project_id;
		$highest_qualification = $request->highest_qualification;
		if (!empty($startDate))
		{
			$startExplode = explode('-', $startDate);
			$startFrom = strtotime($startExplode[0]);
			$startTo = strtotime($startExplode[1]);
		}
		$educators = DB::table('educators')
		->select('educators.*','contacts_companies.name as com_name', 'projects.name as pro_name')
		->leftJoin('projects', 'educators.activity_id', '=', 'projects.id')
		->leftJoin('contacts_companies', 'educators.school_id', '=', 'contacts_companies.id')
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('educators.engagement_date', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($projectID) {
		if (!empty($projectID)) {
			$query->where('educators.activity_id', $projectID);
		}
		})
		->where(function ($query) use ($schoolID) {
		if (!empty($schoolID)) {
			$query->where('educators.school_id', $schoolID);
		}
		})
		->where(function ($query) use ($highest_qualification) {
			if (!empty($highest_qualification)) {
				$query->where('educators.highest_qualification', 'ILIKE', "%$highest_qualification%");
			}
		})
		->orderBy('educators.first_name')
		->get();
        $data['start_date'] = $request->start_date;
        $data['school_id'] = $request->school_id;
        $data['project_id'] = $request->project_id;
        $data['highest_qualification'] = $request->highest_qualification;
        $data['educators'] = $educators;
		$data['page_title'] = "Educators Report";
        $data['page_description'] = "Educators Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/educator', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Educators', 'path' => '/reports/educator', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Educators Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Educators';
		AuditReportsController::store('Reports', 'Educator Report Information Viewed', "Actioned By User", 0);
        return view('reports.educator_results')->with($data);
    }
	// Print learnerreport acccording to sent criteria
	public function printreport(Request $request)
    {
		$startFrom = $startTo = 0;
		$startDate = $request->start_date;
		$schoolID = $request->school_id;
		$projectID = $request->project_id;
		$highest_qualification = $request->highest_qualification;
		if (!empty($startDate))
		{
			$startExplode = explode('-', $startDate);
			$startFrom = strtotime($startExplode[0]);
			$startTo = strtotime($startExplode[1]);
		}
		$educators = DB::table('educators')
		->select('educators.*','contacts_companies.name as com_name', 'projects.name as pro_name')
		->leftJoin('projects', 'educators.activity_id', '=', 'projects.id')
		->leftJoin('contacts_companies', 'educators.school_id', '=', 'contacts_companies.id')
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('educators.engagement_date', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($projectID) {
		if (!empty($projectID)) {
			$query->where('educators.activity_id', $projectID);
		}
		})
		->where(function ($query) use ($schoolID) {
		if (!empty($schoolID)) {
			$query->where('educators.school_id', $schoolID);
		}
		})
		->where(function ($query) use ($highest_qualification) {
			if (!empty($highest_qualification)) {
				$query->where('educators.highest_qualification', 'ILIKE', "%$highest_qualification%");
			}
		})
		->orderBy('educators.first_name')
		->get();
		
        $data['start_date'] = $request->start_date;
        $data['school_id'] = $request->school_id;
        $data['project_id'] = $request->project_id;
        $data['highest_qualification'] = $request->highest_qualification;
        $data['educators'] = $educators;
		$data['page_title'] = "Educators Report";
        $data['page_description'] = "Educators Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/educator', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Educators', 'path' => '/reports/educator', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Educators Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Educators';
		$user = Auth::user()->load('person');
		$data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.png');
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		//return $data;
		AuditReportsController::store('Reports', 'Educator Report Information Printed', "Actioned By User", 0);
        return view('reports.educator_print')->with($data);
    }

}
