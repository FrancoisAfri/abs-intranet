<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelOne;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\HRPerson;
use App\module_access;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\projects;
use App\activity;
use App\programme;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data['breadcrumb'] = [
            ['title' => 'Dashboard', 'path' => '/', 'icon' => 'fa fa-dashboard', 'active' => 1, 'is_module' => 1]
        ];
		$data['active_mod'] = 'dashboard';
        $user = Auth::user()->load('person');
		
        if ($user->type === 1 || $user->type === 3) {
            $topGroupLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first();
            $totNumEmp = HRPerson::count();
            
            //check if user can view the company performance widget (must be superuser or div head or have people reporting to him/her)
            $objAppraisalModAccess = module_access::where('module_id', 6)->where('user_id', $user->id)->get();
            if ($objAppraisalModAccess && count($objAppraisalModAccess) > 0) $appraisalModAccess = $objAppraisalModAccess->first()->access_level;
            else $appraisalModAccess = 0;
            //$appraisalModAccess = module_access::where('module_id', 6)->where('user_id', $user->id)->get()->first()->access_level;
            $numManagedDivs5 = DivisionLevelFive::where('manager_id', $user->person->id)->count();
            $numManagedDivs4 = DivisionLevelFour::where('manager_id', $user->person->id)->count();
            $numManagedDivs3 = DivisionLevelThree::where('manager_id', $user->person->id)->count();
            $numManagedDivs2 = DivisionLevelTwo::where('manager_id', $user->person->id)->count();
            $numManagedDivs1 = DivisionLevelOne::where('manager_id', $user->person->id)->count();
            $numSupervisedEmp = HRPerson::where('manager_id', $user->person->id)->count();
            $isSuperuser = ($appraisalModAccess == 5) ? true : false;
            //$managedDivsIDs = [];
            if ($numManagedDivs5 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs5 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 5)->orderBy('level', 'desc')->limit(1)->first();
            }
            elseif ($numManagedDivs4 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs4 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 4)->orderBy('level', 'desc')->limit(1)->first();
            }
            elseif ($numManagedDivs3 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs3 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 3)->orderBy('level', 'desc')->limit(1)->first();
            }
            elseif ($numManagedDivs2 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs2 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 2)->orderBy('level', 'desc')->limit(1)->first();
            }
            elseif ($numManagedDivs1 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs1 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 1)->orderBy('level', 'desc')->limit(1)->first();
            }
            else {
                $isDivHead = false;
                $managedDivsLevel = (object) [];
                $managedDivsLevel->level = 0;
            }
            $isSupervisor = ($numSupervisedEmp > 0) ? true : false;
            $canViewCPWidget = ($isSuperuser || $isDivHead || $isSupervisor) ? true : false;
			
			// Get tasks for logged user
			$today = strtotime(date('Y-m-d'));
			$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
			$tasks = DB::table('employee_tasks')
			->select('employee_tasks.description','employee_tasks.start_date'
			,'employee_tasks.employee_id','employee_tasks.upload_required'
			,'employee_tasks.order_no','employee_tasks.status','employee_tasks.due_date','employee_tasks.id as task_id')
			->where('employee_tasks.employee_id', $user->person->id)
			->where('employee_tasks.start_date', '<=', $today)
			->where('employee_tasks.status', '<', 4)
			->orderBy('employee_tasks.id')
			->orderBy('employee_tasks.order_no')
			->get();
			//return $tasks;
			$data['taskStatus'] = $taskStatus;
            $data['user'] = $user;
            $data['totNumEmp'] = $totNumEmp;
            $data['topGroupLvl'] = $topGroupLvl;
            $data['isSuperuser'] = $isSuperuser;
            $data['isDivHead'] = $isDivHead;
            $data['tasks'] = $tasks;
            //$data['managedDivsIDs'] = json_encode($managedDivsIDs);
            $data['managedDivsLevel'] = $managedDivsLevel;
            $data['isSupervisor'] = $isSupervisor;
            $data['canViewCPWidget'] = $canViewCPWidget;
            $data['page_title'] = "Dashboard";
			$data['page_description'] = "This is your main Dashboard";
            //return $data;
            return view('dashboard.admin_dashboard')->with($data); //Admin Dashboard
        }
        else {
			# Get loan status
            //$data['page_title'] = "Dashboard";
			//$data['page_description'] = "Main Dashboard";
            //return view('dashboard.client_dashboard')->with($data); //Clients Dashboard
        }
    }
}
