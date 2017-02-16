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
class LeanersReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 // Authenticate user if not login send him back to login page
	public function __construct()
    {
        $this->middleware('auth');
    }
	// show report criteria page
    public function index()
    {
        $data['page_title'] = "Learners Report";
        $data['page_description'] = "Learners Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/learner', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Learners', 'path' => '/reports/learner', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Learners Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Learners';
		
		$programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
		$projects = DB::table('projects')->where('status', 2)->orderBy('name', 'asc')->get();
		$Activities = DB::table('activities')->where('status', 2)->orderBy('name', 'asc')->get();
		$schools = contacts_company::where('company_type', 2)->where('status', 1)->orderBy('name')->get();
		
        $data['schools'] = $schools;
		$data['programmes'] = $programmes;
		$data['projects'] = $projects;
		AuditReportsController::store('Reports', 'Learner Report Page Viewed ', "Actioned By User", 0);
        return view('reports.learner_search')->with($data);
    }
	
	// draw learner report acccording to search criteria
	public function getReport(Request $request)
    {
		$startFrom = $startTo = 0;
		$startDate = $request->start_date;
		$schoolID = $request->school_id;
		$projectID = $request->project_id;
		$grade = $request->grade;
		if (!empty($startDate))
		{
			$startExplode = explode('-', $startDate);
			$startFrom = strtotime($startExplode[0]);
			$startTo = strtotime($startExplode[1]);
		}
		$learners = DB::table('learners')
		->select('learners.*','contacts_companies.name as com_name', 'projects.name as pro_name')
		->leftJoin('projects', 'learners.activity_id', '=', 'projects.id')
		->leftJoin('contacts_companies', 'learners.school_id', '=', 'contacts_companies.id')
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('learners.date_started_project', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($projectID) {
		if (!empty($projectID)) {
			$query->where('learners.activity_id', $projectID);
		}
		})
		->where(function ($query) use ($schoolID) {
		if (!empty($schoolID)) {
			$query->where('learners.school_id', $schoolID);
		}
		})
		->where(function ($query) use ($grade) {
		if (!empty($grade)) {
			$query->where('learners.grade', $grade);
		}
		})
		->orderBy('learners.first_name')
		->get();
        $data['start_date'] = $request->start_date;
        $data['school_id'] = $request->school_id;
        $data['project_id'] = $request->project_id;
        $data['grade'] = $request->grade;
        $data['learners'] = $learners;
		$data['page_title'] = "Learners Report";
        $data['page_description'] = "Learners Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/learners', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Learners', 'path' => '/reports/learners', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Learners Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Learners';
		AuditReportsController::store('Reports', 'Learner Report Informations Viewed ', "Actioned By User", 0);
        return view('reports.learner_results')->with($data);
    }
	// Print learnerreport acccording to sent criteria
	public function printreport(Request $request)
    {
		$startFrom = $startTo = 0;
		$startDate = $request->start_date;
		$schoolID = $request->school_id;
		$projectID = $request->project_id;
		$grade = $request->grade;
		if (!empty($startDate))
		{
			$startExplode = explode('-', $startDate);
			$startFrom = strtotime($startExplode[0]);
			$startTo = strtotime($startExplode[1]);
		}
		$learners = DB::table('learners')
		->select('learners.*','contacts_companies.name as com_name', 'projects.name as pro_name')
		->leftJoin('projects', 'learners.activity_id', '=', 'projects.id')
		->leftJoin('contacts_companies', 'learners.school_id', '=', 'contacts_companies.id')
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('learners.date_started_project', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($projectID) {
		if (!empty($projectID)) {
			$query->where('learners.activity_id', $projectID);
		}
		})
		->where(function ($query) use ($schoolID) {
		if (!empty($schoolID)) {
			$query->where('learners.school_id', $schoolID);
		}
		})
		->where(function ($query) use ($grade) {
		if (!empty($grade)) {
			$query->where('learners.grade', $grade);
		}
		})
		->orderBy('learners.first_name')
		->get();
        $data['learners'] = $learners;
		$data['page_title'] = "Learners Report";
        $data['page_description'] = "Learners Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports/learners', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Learners', 'path' => '/reports/learners', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Learners Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'Learners';
		$user = Auth::user()->load('person');
		$data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT \TRUST';
        $data['company_logo'] = 'http://osizweni.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.png');
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		AuditReportsController::store('Reports', 'Learner Report Informations Printed ', "Actioned By User", 0);
        return view('reports.learner_print')->with($data);
    }
}
