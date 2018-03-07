<?php

namespace App\Http\Controllers;

use App\activity;
use App\contacts_company;
use App\HRPerson;
use App\programme;
use App\projects;
use App\User;
use App\AuditTrail;
use Illuminate\Http\Request;
use App\CompanyIdentity;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuditReportsController extends Controller
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
        $data['page_title'] = "Audit Report";
        $data['page_description'] = "Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Audit Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Audit';
        $data['active_rib'] = 'Audit Report';
		
		$modules = DB::table('security_modules')->where('active', 1)->orderBy('name', 'asc')->get();
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		
		$data['users'] = $users;
		$data['modules'] = $modules;
		AuditReportsController::store('Audit', 'View Audit Search', "view Audit", 0);
        return view('audit.audit_search')->with($data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public static function store($moduleName='',$action='',$notes='',$referenceID=0)
    {
		$user = Auth::user();
		$AuditTrail = new AuditTrail();
		$AuditTrail->module_name = $moduleName;
		$AuditTrail->user_id = !empty($user->id) ? $user->id : 0;
		$AuditTrail->action = $action;
		$AuditTrail->action_date = time();//strtotime(date('Y-m-d'));
		$AuditTrail->notes = $notes;
		$AuditTrail->reference_id = $referenceID;
		// Save Audit
        $AuditTrail->save();
    }
	// draw audit report acccording to search criteria
	public function getReport(Request $request)
    {
		$actionFrom = $actionTo = 0;
		$actionDate = $request->action_date;
		$userID = $request->user_id;
		$action = $request->action;
		$moduleName = $request->module_name;
		if (!empty($actionDate))
		{
			$startExplode = explode('-', $actionDate);
			$actionFrom = strtotime($startExplode[0]);
			$actionTo = strtotime($startExplode[1]);
		}
		$audits = DB::table('audit_trail')
		->select('audit_trail.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'audit_trail.user_id', '=', 'hr_people.user_id')
		->where(function ($query) use ($actionFrom, $actionTo) {
		if ($actionFrom > 0 && $actionTo  > 0) {
			$query->whereBetween('audit_trail.action_date', [$actionFrom, $actionTo]);
		}
		})
		->where(function ($query) use ($userID) {
		if (!empty($userID)) {
			$query->where('audit_trail.user_id', $userID);
		}
		})
		->where(function ($query) use ($moduleName) {
			if (!empty($moduleName)) {
				$query->where('audit_trail.module_name', 'ILIKE', "%$moduleName%");
			}
		})
		->where(function ($query) use ($action) {
			if (!empty($action)) {
				$query->where('audit_trail.action', 'ILIKE', "%$action%");
			}
		})
		->orderBy('audit_trail.module_name')
		->get();
		//die('do you get here');
        $data['action'] = $request->action;
        $data['module_name'] = $request->module_name;
        $data['user_id'] = $request->user_id;
        $data['action_date'] = $request->action_date;
        $data['audits'] = $audits;
		$data['page_title'] = "Audit Report";
        $data['page_description'] = "Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Audit Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Audit';
        $data['active_rib'] = 'Audit Report';
	
		AuditReportsController::store('Audit', 'View Audit Search Results', "view Audit Results", 0);
        return view('audit.audit_results')->with($data);
    }
	// Print audit report acccording to sent criteria
	public function printreport(Request $request)
    {
		$actionFrom = $actionTo = 0;
		$actionDate = $request->action_date;
		$userID = $request->user_id;
		$action = $request->action;
		$moduleName = $request->module_name;
		if (!empty($actionDate))
		{
			$startExplode = explode('-', $actionDate);
			$actionFrom = strtotime($startExplode[0]);
			$actionTo = strtotime($startExplode[1]);
		}
		$audits = DB::table('audit_trail')
		->select('audit_trail.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
		->leftJoin('hr_people', 'audit_trail.user_id', '=', 'hr_people.user_id')
		->where(function ($query) use ($actionFrom, $actionTo) {
		if ($actionFrom > 0 && $actionTo  > 0) {
			$query->whereBetween('audit_trail.action_date', [$actionFrom, $actionTo]);
		}
		})
		->where(function ($query) use ($userID) {
		if (!empty($userID)) {
			$query->where('audit_trail.user_id', $userID);
		}
		})
		->where(function ($query) use ($moduleName) {
			if (!empty($moduleName)) {
				$query->where('audit_trail.module_name', 'ILIKE', "%$moduleName%");
			}
		})
		->where(function ($query) use ($action) {
			if (!empty($action)) {
				$query->where('audit_trail.action', 'ILIKE', "%$action%");
			}
		})
		->orderBy('audit_trail.module_name')
		->get();
		
		$companyDetails = CompanyIdentity::systemSettings();

        $data['audits'] = $audits;   
        $data['page_title'] = "Audit Report";
        $data['page_description'] = "Audit Report";
        $data['breadcrumb'] = [
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Audit Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Audit';
        $data['active_rib'] = 'Audit Report';
		$user = Auth::user()->load('person');
		
		$data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		//return $data;
		AuditReportsController::store('Audit', 'Print Audit Search Results', "Print Audit Results", 0);
        return view('audit.audit_print')->with($data);
    }
}
