<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;

use App\HRPerson;

use App\hr_people;

use App\DivisionLevel;

use App\doc_type;

use App\doc_type_category;
use App\DivisionLevelTwo;

//use App\User;

class EmployeeDocumentsController extends Controller
{
    //
      public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewDoc() {
        //$user->load('person');
        //$avatar = $user->person->profile_pic;
    	$hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $document = doc_type_category::where('active', 1)->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $division=DivisionLevelTwo::where('active', 1)->get();
        // return $divisionLevels;
    	//$HRPerson = DB::table('HRPerson')->orderBy('first_name', 'surname')->get();
     
     
        $data['page_title'] = "Employee Documents";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'employees documents';
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : '';
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division'] = $division;
        //$data['user'] = $user;
        $data['division_levels'] = $divisionLevels;
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.employee_documents')->with($data);
    }

     public function allocate(Request $request , LeaveType $lev)
    {
        $this->validate($request, [      
           'leave_type' => 'required',       
           'division_level_2' => 'required',       
            'division_level_1' => 'required',       
            'hr_person_id' => 'required',
                 
        ]);
        $allData= $request->all();
        unset($allData['_token']);
       // return $allData;
        
        $empl = $allData['hr_person_id'];
        $LevID = $allData['leave_type'];
       $days = $allData['adjust_days'];

        foreach($empl as $empID) {
            //return person records based on employee id
                $emp = HRPerson::find($empID);
             
//            return $emp;
                // return leave types records based on leave type id
                $leaveTyps = LeaveType::find($LevID);
            
                $annul = LeaveType::find(1)->id;
                    // return leave profile from the hr person table based on employee id 
                $levPro = HRPerson::find($empID)->leave_profile;
//                   return $levPro;
            // getting the hr_id from custom leave
                $levcust = leave_custom::find($empID)->hr_id;
                
            //return $custom days and custom status 
            $custDays = leave_custom::find($empID)->number_of_days;
             $custstaus = leave_custom::find($empID)->status;
            
            $levcustom = $custDays/12;
             
                // return leave profile id based on an user id;
               // $levProfile = leave_profile::find($levPro)->id;
                //return $levProfile;
         
                  //$credit = $minimum->hr_person->where('id', 1)->first();
//            return $levCreditv;    
            // get min value from pivot
             $minimum =$leaveTyps->leave_profle->where('id', 3)->first(); 
//                return $minimum;
             $min = $minimum->pivot->min;
            
            $mini=$min / 12;
        //      return $mini;
            
//            return $min;
            //$typID = $levcust && $custstaus = 0
            
                if ($LevID = $annul && $custstaus = 0 )
            {
                $emp->leave_types()->sync([$empID => ['leave_balance' => $levcustom ]]);   
            }
            else if($LevID != $annul )
            {
                $emp->leave_types()->sync([$empID => ['leave_balance' => $mini ]]);
            }
        
        // $emp = HRPerson::find($empID)->load('leave_types.leave_profle');
                
        } 
       
            
       return back(); 
    }
}
//