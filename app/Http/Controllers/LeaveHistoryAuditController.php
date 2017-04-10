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

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LeaveHistoryAuditController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function index()
    {
        $data['page_title'] = "Leave History Audit";
        $data['page_description'] = "Leave History Audit";
        $data['breadcrumb'] = [
            ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave History Audit', 'path' => '/audit/reports', 'icon' => 'fa fa-circle-o-notch', 'active' => 0, 'is_module' => 0],
            ['title' => 'Leave History Audit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'leave';
        $data['active_rib'] = 'Leave History Audit';
		
		$users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();
		
		$data['users'] = $users;
		AuditReportsController::store('Audit', 'View Audit Search', "view Audit", 0);
        return view('audit.audit_search')->with($data);  
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
    */
    public static function store($performedBy='',$descriptionAction ='',$previousBalance='',$transcation='')
    {
        $user = Auth::user();
        $leave_history = new leave_history();
//        $leave_history
        $leave_history->performed_by = $performedBy;
        $leave_history->description_action = $$descriptionAction;   
        $leave_history->previous_balance = $previousBalance;  
        $leave_history->transcation = $transcation;
        $leave_history->save();    
    }
    public function getReport(Request $request)
    {
        
    }
    public function printreport(Request $request)
    {
        
    }
}
