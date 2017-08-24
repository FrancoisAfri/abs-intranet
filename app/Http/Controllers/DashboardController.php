<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelOne;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\EmployeeTasks;
use App\HRPerson;
use App\HelpDesk;
use App\leave_application;
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

        $loggedInEmplID = Auth::user()->person->id;

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
            $canViewTaskWidget = ($isSuperuser || $isDivHead || $isSupervisor) ? true : false;
            $canViewEmpRankWidget = ($isSuperuser || $isDivHead) ? true : false;

            //$numManagedDivs = 0;
            //if ($isSuperuser) $numManagedDivs =

            if ($isSuperuser) $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();//->load('divisionLevelGroup');
            elseif ($isDivHead) {
                $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')
                    ->where('level', $managedDivsLevel->level)
                    ->get();
            }
            else $divisionLevels = (object) [];

            $statusLabels = [10 => "label-danger", 50 => "label-warning", 80 => 'label-success', 100 => 'label-info'];
			
			// Get tasks for logged user
			$today = strtotime(date('Y-m-d'));
			$taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
			$tasks = EmployeeTasks::
			select('employee_tasks.description','employee_tasks.start_date','employee_tasks.manager_duration'
			,'employee_tasks.employee_id','employee_tasks.upload_required'
			,'employee_tasks.order_no','employee_tasks.status','employee_tasks.due_date'
			,'employee_tasks.id as task_id','contact_companies.name as client_name',
                'employee_tasks.duration', 'employee_tasks.date_paused', 'employee_tasks.date_started')
			->leftJoin('client_inductions', 'employee_tasks.induction_id', '=', 'client_inductions.id')
			->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
			->where('employee_tasks.employee_id', $user->person->id)
			->where('employee_tasks.start_date', '<=', $today)
			->where('employee_tasks.status', '<', 4)
			->orderBy('client_name')
			->orderBy('employee_tasks.order_no')
			->get();


              #leave Balance
            $balance = DB::table('leave_credit')
            ->select('leave_credit.*','leave_types.name as leavetype')
            ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id') 
            ->where('leave_credit.hr_id', $user->person->id)
            ->orderBy('leave_credit.id')
            ->get();

            //return $application;

                #leave Application 
            $application = DB::table('leave_application')
            ->select('leave_application.*','leave_types.name as leavetype','leave_status.name as leaveStatus') 
            ->leftJoin('leave_types', 'leave_application.hr_id', '=', 'leave_types.id') 
            ->leftJoin('leave_status', 'leave_application.hr_id', '=', 'leave_status.id') 
            ->where('leave_application.hr_id', $user->person->id)
            ->orderBy('leave_application.id')
            ->get();
       // return $application;

			// check task
			$checkTasks = DB::table('employee_tasks')
			->select('employee_tasks.description','employee_tasks.employee_id'
			,'employee_tasks.status','employee_tasks.id as task_id'
			,'hr_people.first_name as firstname','hr_people.surname as surname')
			->leftJoin('hr_people', 'employee_tasks.employee_id', '=', 'hr_people.id')
			->where('employee_tasks.check_by_id', $user->person->id)
			->where('employee_tasks.status', '=', 4)
			->whereNull('checked')
			->orderBy('employee_tasks.employee_id')
			->get();
			//return $checkTasks;

            #View Tickets 
              
            $ticketStatus = array('' => '', 1 => 'Pending Assignment', 2 => 'Assigned to operator', 3 => 'Completed by operator', 4 => 'Submited to Admin for review');
            $tickets = DB::table('ticket')
                ->where('user_id', $loggedInEmplID)
                ->orderBy('id', 'asc')
                ->get();

                $email = $user->email;
                 
             $Helpdesk  = HelpDesk::orderBy('name', 'asc')->get();
             //return $Helpdesk;
            $name = HRPerson::where('id', $loggedInEmplID )
                        ->select('first_name', 'surname')
                        ->get()
                        ->first();
                        $names = $name ->first_name;
                        $surname =$name ->surname;
            
                //return $tickets;
            $data['Helpdesk'] = $Helpdesk;           
            $data['email'] = $email; 
            $data['names'] = $names;  
            $data['surname'] = $surname;         
            //$data['systems '] = $systems ;
            $data['ticketStatus'] = $ticketStatus;    
            $data['tickets'] = $tickets;
            $data['statusLabels'] = $statusLabels;
            $data['balance'] = $balance;
            $data['application'] = $application;
			$data['taskStatus'] = $taskStatus;
            $data['user'] = $user;
            $data['totNumEmp'] = $totNumEmp;
            $data['topGroupLvl'] = $topGroupLvl;
            $data['isSuperuser'] = $isSuperuser;
            $data['isDivHead'] = $isDivHead;
            $data['tasks'] = $tasks;
            $data['checkTasks'] = $checkTasks;
            //$data['managedDivsIDs'] = json_encode($managedDivsIDs);
            $data['managedDivsLevel'] = $managedDivsLevel;
            $data['isSupervisor'] = $isSupervisor;
            $data['canViewCPWidget'] = $canViewCPWidget;
            $data['canViewTaskWidget'] = $canViewTaskWidget;
            $data['canViewEmpRankWidget'] = $canViewEmpRankWidget;
            $data['divisionLevels'] = $divisionLevels;
            $data['page_title'] = "Dashboard";
			$data['page_description'] = "This is your main Dashboard";

            return view('dashboard.admin_dashboard')->with($data); //Admin Dashboard
        }
        else {
			$data['page_title'] = "Dashboard";
			$data['page_description'] = "Main Dashboard";
            return view('dashboard.client_dashboard')->with($data); //Clients Dashboard
        }
    }
}