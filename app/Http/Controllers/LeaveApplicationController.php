<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Mail\leave_applications;
use App\Http\Requests;
use App\LeaveType;
use App\Users;
use App\DivisionLevel;
use App\leave_custom;
use App\leave_configuration;
use App\leave_application;
use App\HRPerson;
use App\hr_person;
use App\modules;
use App\leave_credit;
use App\leave_history;
use App\type_profile;
use App\DivisionLevelTwo;
use App\leave_profile;
use APP\leavDetails;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class LeaveApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
        if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');
        


        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
        $leaveTypes = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);


        $data['page_title'] = "leave Types";
        $data['page_description'] = "Leave Application";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => '', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Apply';
        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['leave_customs']=$leave_customs;

        // return $employees;


        AuditReportsController::store('Leave', 'Leave Type Page Accessed', "Accessed By User", 0);
        return view('leave.application')->with($data);  
    }

   public function show()
   {
   
        $data['page_title'] = "leave Management";
        $data['page_description'] = "Leave Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => 'leave/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => '', 'active' => 1, 'is_module' => 0]
        ];
        // $hrDetails = HRPerson::where('id',3 )->where('status', 1)->get();
        // // $Dept = DivisionLevelTwo::where('id' , 2 ) -> get();
        //     return $Dept;
       
       $people = DB::table('hr_people')->orderBy('id', 'asc')->get();
        
        $leaveTypes = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);
       
       // left join between leaveApplication & HRPerson & LeaveType
       $loggedInEmplID = Auth::user()->person->id;
      //return $loggedInEmplID;

       $leaveApplication = DB::table('leave_application')
        ->select('leave_application.*','hr_people.first_name as firstname','hr_people.surname as surname','leave_types.name as leavetype','hr_people.manager_id as manager','leave_credit.leave_balance as leave_Days') 
        ->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
        ->leftJoin('leave_types', 'leave_application.hr_id', '=', 'leave_types.id') 
        ->leftJoin('leave_credit', 'leave_application.hr_id', '=', 'leave_credit.hr_id' )
       ->where('hr_people.manager_id', $loggedInEmplID)
       //->where( 'leave_credit.leave_type_id',3)
        ->orderBy('leave_application.hr_id')
        ->get();

        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Approve';
        $data['leaveTypes'] = $leaveTypes;
        //$data['employees'] = $employees;
        $data['leaveApplication'] = $leaveApplication;

        AuditReportsController::store('Leave', 'Leave Approval Page Accessed', "Accessed By User", 0);
        return view('leave.leave_approval')->with($data);   
       
   }
             

            public function ApplicationDetails($status=0, $hrID=0){

                   #
     //      --status--
     // 1 => 'require_managers_approval',
     // 2 => 'require_department_head_approval',
     // 3 => 'require_hr_approval',
     // 4 => 'require_payroll_approval',
     // 5 => 'Approved', 5 => 'Rejected'
     // ];
                #

        // query the leave congif table and bring back the values
        $approvals  =  DB::table('leave_configuration')
                        ->select('require_managers_approval','require_department_head_approval','require_hr_approval','require_payroll_approval')
                        ->first(); 
         // query the hrperon  model and bring back the values of the managerg
        $hrDetails = HRPerson::where('id',$hrID )->where('status', 1)->first();

        if ($approvals->require_managers_approval == 1  ) {
            # code...
            $approvals  =  DB::table('leave_configuration')
                        ->select('require_managers_approval','require_department_head_approval','require_hr_approval','require_payroll_approval')
                        ->first();
            // query the hrperon  model and bring back the values of the manager
            $managerDetails = HRPerson::where('id', $hrDetails->manager_id)->where('status', 1)
                                -> select('first_name' , 'surname', 'email' )
                                ->first();

                // echo $managerDetails;

             $details = array('status' => 1,'first_name' => $managerDetails->first_name,'surname' => $managerDetails->surname,'email' => $managerDetails->email);
          
            }
                elseif ($approvals->require_department_head_approval == 1 ) {
                    # code...  division_level_twos
                    // $dept = DB::('civ2')::were('id', $hrDetails->div2)->first();
                       //$Dept = DivisionLevelTwo::where('id' , $hrDetails->manager_id ) -> first();
                    // query the hrperon  model and bring back the values of the manager
                    $msamgerDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                    -> select('first_name' , 'surname', 'email' )
                    ->first();

                            // array to store manager details
                    $details = array('status' => 2,'first_name' => $mamgerDetails->firstname,'surname' => $mamgerDetails->surname,'email' => $mamgerDetails->email);
                    return $details;
                }
                // elseif ($approvals->require_department_head_approval == 1 &&  $status <= 3) {
                //     # code...  division_level_twos
                //     $dept = DB::('civ2')::were('id', $hrDetails->div2)->first();
                //     // query the hrperon  model and bring back the values of the manager
                //     $msamgerDetails = HRPerson::where('id', $dept->manager_id)->where('status', 1)
                //     -> select('first_name' , 'surname', 'email' )
                //     ->first();

                //             // array to store manager details
                //      $details ('tatus' => 3, 'firstname' => $mamgerDetails->firstname,'surname' => $mamgerDetails->surname,'email' => $mamgerDetails->email);
                //     return $details;
                // }
                // elseif ($approvals->require_hr_approval == 1 &&  $status < 4) {
                //     # code...
                //      $div = DB::('civ1')::were('id', $hrDetails->div1)->first();
                //     // query the hrperon  model and bring back the values of the manager
                //     $msamgerDetails = HRPerson::where('id', $dept->hr_oficer_id)->where('status', 1)
                //     -> select('first_name' , 'surname', 'email' )
                //     ->first();
                //      $details ('tatus' => 3, 'firstname' => $mamgerDetails->firstname,'surname' => $mamgerDetails->surname,'email' => $mamgerDetails->email,);
                //     return $details;
                // }
                // elseif ($approvals->require_payroll_approval == 1 &&  $status < 5) {
                //     # code...
                // }
            else
            {

            $details = array('status' => 2,'first_name' => $hrDetails->first_name,'surname' => $hrDetails->surname,'email' => $hrDetails->email);
               
                return $details;
              
            }

    }

    public function day(Request $request, leave_application $levApp )
    {
        $this->validate($request, [           
           'hr_person_id'=>'bail|required' ,
           "leave_type"=>'required',
            'day' =>'required',
//           'description' =>'required',
           //"supporting_doc",     

        ]);
        $ApplicationDetails = array();

        $leaveApp = $request->all(); 

        //Exclude empty fields from query
        foreach ($leaveApp as $key => $value)
        {
            if (empty($leaveApp[$key])) {
                unset($leaveApp[$key]);
            }
        }
//return $leaveApp;
        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);

        
        //Query leave holiday Tables
        $public_holiday = DB::table('public_holidays')->pluck('day');

        
        $public_holiday = DB::table('public_holidays')->pluck('day');    
        
        //$levApp->array(hr_id = $request->input('hr_person_id'));
        $levApp->leave_type_id = $request->input('leave_type');
        
          // Get employeeId from dropbbox
        $employees = $leaveApp['hr_person_id'];
        
        // Get leavetype Id from dropbbox
        $tyop = $leaveApp['leave_type'];
        
        //query the hr table based on employeeId
        $HRpeople = HRPerson::find($employees);
        
        
         // separate day range
        $day = $leaveApp['day'];
        $dates = explode(' - ',$day);
        $start_date = str_replace('/', '-', $dates[0]);
        $start_date = strtotime($start_date);
        $end_date = str_replace('/', '-', $dates[1]);
        $end_date = strtotime($end_date);
        //return $start_date;
       
        //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
        //We add one to inlude both dates in the interval.
        $diffrenceDays = ($end_date - $start_date) / 86400 + 1;
        
        // save dates
        // calculate public holidays and weekends
        $iNonweek = 0;
        $aPublicHolidays = array();
        $aPublicHolidays = $public_holiday;
        
         # Add Easter Weekend to list of public holidays

        $iEasterSunday =  easter_date(date("Y",strtotime($end_date)));
        $aPublicHolidays[] = $iEasterSunday - (2*3600*24);
        $aPublicHolidays[] = $iEasterSunday + (3600*24);
        
//       return $aPublicHolidays;
        
        for ($i = $start_date; $i <= $end_date; $i = $i+86400){
         $aPublic = array(); 
            
            foreach($aPublicHolidays as $iKey =>$sValue)
            {
                    $sDay = date("Y",$i)."-".date("m",$sValue)."-".date("d",$sValue);
                
                    $iDay = strtotime($sDay);
                    $aPublic[$iDay] = 0;
            }
             if (((date("w",$i) == 6) || (date("w",$i) == 0))) $iNonweek++;
                if (array_key_exists($i,$aPublic) && ((date("w",$i) != 6) && (date("w",$i) != 0))) $iNonweek++;
            
            //
            if (array_key_exists($i-86400,$aPublic) && (date("w",$i) == 1))
                        if (array_key_exists($i,$aPublic)) {}
                        else $iNonweek++;  
        }
          //$iDiff = strtotime($start_date) - strtotime();
          $iDiff = $end_date - $start_date;
        
          $diffDays = ($iDiff / 86400) - $iNonweek + 1; // use this for days diffrence
          $iDays = $diffDays * 8;

        // return   $iDays; 
        // call the function
        $ApplicationDetails =  LeaveApplicationController::ApplicationDetails(0, $request->input('hr_person_id'));
               
               // return $ApplicationDetails;  

               // // if hd == hd
               // // {
               // //  status = 2
               // // }else
               // {

               // }
        $applicatiionStaus = $ApplicationDetails['status'];
       // return  $manage ;

        $levApp->status = 1;
        $levApp->start_date = $start_date;
        $levApp->end_date = $end_date;
        $levApp->leave_days = $iDays;
        $levApp->leave_type_id = $request->input('leave_type');
        $levApp->hr_id = $request->input('hr_person_id');
        $levApp->notes = $request->input('description');


        $levApp->save();

         //Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = $levApp->id . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('levApp', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->update();
            }
        }
                    // send email to manager
        Mail::to($ApplicationDetails['email'])->send(new leave_applications($ApplicationDetails['first_name'], $ApplicationDetails['surname'] ,$ApplicationDetails['email']));

  
        AuditReportsController::store('Leave', 'Leave application ', "Accessed By User", 0);
        return back();
        //return view('leave.application')->with($levTypVar);  
    }
    
    public function hours(Request $request, leave_application $levApp )
    {
      $this->validate($request, [
          
           'hr_person_id'=>'required',
           'leave_type' => 'required',
//            "day",
           'datetime' =>'required',

//           'description' => 'required',
//           'supporting_doc' => 'required',     

        ]);

         $approveDetails = array();
        $leaveApp = $request->all();

        unset($leaveApp['_token']);
     
        
        // explode left side
        $day = $leaveApp['datetime'];
        $dates = explode(" ",$day);
        $start_date = str_replace('/', '-', $dates[0]);
        $start_date = strtotime($start_date);//date
        $start_time = date('Y-m-d H:i:s',strtotime($dates[1] . ' ' . $dates[2]));// time
        $start_time = strtotime($start_time);
       //return $start_time;
        
        // explode right side
        $var = $leaveApp['datetime'];
        $days = explode(' - ',$var);
        $end_date = str_replace('/', '-', $days[0]);
        $end_time = date('Y-m-d H:i:s',strtotime($days[1])); // time
        $end_time = strtotime($end_time);
     // return $end_time;

       //Query the Holiday table and return the days
        $public_holiday = DB::table('public_holidays')->pluck('day');
        
       $diffrencetime = ($end_time - $start_time)/3600;
      // $diffrenceTime = strtotime($diffrencetime);

       // return $diffrencetime;
    //#calculate 
        // #save the start and end date
        
        //Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = $levApp->id . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('levApp', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->update();               
            }
        }
        
        $ApplicationDetails =  LeaveApplicationController::ApplicationDetails(0, $request->input('hr_person_id'));
        $levApp->leave_type_id = $request->input('leave_type');
        $levApp->hr_id = $request->input('hr_person_id');
        $levApp->notes = $request->input('description');
        $levApp->status = 1;
        $levApp->start_date = $start_date;
        $levApp->start_time = $start_time;
        $levApp->end_time = $end_time;
        $levApp->leave_hours = $diffrencetime;
        $levApp->save();    
        
        return back();
    }

//Function to accept leave applications
    public function AcceptLeave(Request $request,leave_application $id , leave_history $levHist , leave_credit $credit , leave_configuration $leave_conf)
    {
        // get the user application  details
        $iD = $id->id;
        $levTyp =$id->leave_type_id;

        //return $levTyp;
      
          $leave_appDetails = leave_application::where('id',$iD )->first();

          // #Query the the leave_config days for value
           $negDays  = leave_configuration::where('id' , 1)->first();
          // return $negDays;

         $hrID = $id['hr_id'];
         $typID = $id['leave_type_id'];  

             $Details = leave_credit::where('hr_id',$hrID )
                        ->where('leave_type_id', $typID) 
                        ->first();

             $leave_balance = $Details['leave_balance'];

            #replace null with 0 in the table
          // if( $negDays == null)
          // {
          //     $Days = 0;
          //    DB::table('leave_configuration')
          //   ->where('id', 1)
          //   ->update(['allow_negative_days' => $Days]);
          // }

          if($levTyp == 1)
          {
             $anualdays = $negDays->allow_annual_negative_days * 8;
              $daysApplied =  $id['leave_days']; 
                $bal = $daysApplied + $anualdays;
                $nwBal = $leave_balance - $bal;

                 DB::table('leave_credit')
                        ->where('hr_id', $hrID)
                        ->where('leave_type_id', $typID) 
                        ->update(['leave_balance' => $nwBal]);

         }
         elseif ($levTyp == 5) 
         {
            $sickdays = $negDays->allow_sick_negative_days * 8;
            $daysApplied =  $id['leave_days']; 
                $bal = $daysApplied + $sickdays;
                $nwBal = $leave_balance - $bal;

                 DB::table('leave_credit')
                        ->where('hr_id', $hrID)
                        ->where('leave_type_id', $typID) 
                        ->update(['leave_balance' => $nwBal]);
         }else
          #Get the user leave balance
             $daysApplied =  $id['leave_days'];       
          #calculations
             #subract current balance from the one applied for 
        $newBalance = $leave_balance - $daysApplied  ;
        #save new leave balance 
              DB::table('leave_credit')
                    ->where('hr_id', $hrID)
                    ->where('leave_type_id', $typID) 
                    ->update(['leave_balance' => $newBalance]);


     #get hr_id
     
      // $leave_appDetails -> notes;
      // $leave_appDetails -> hr_id;
      // $leave_balance ;

      // $levHist->hr_id = $hrIDet;
      $levHist->description_action = $leave_appDetails;
      $levHist->previous_balance = $leave_balance; 
      $levHist->save();

     

        #send email to the user informing that the leave has been accepted
        // Mail::to($usedetails->email)->send(new accept_applications($usedetails->first_name, $usedetails->surname));

        AuditReportsController::store('Leave', 'leave_approval Informations accepted', "Edited by User: $levHist->hr_id", 0);
                 return back();
    }

    /*
     */
    public function reject(Request $request, leave_application $levReject)
    {
        //
        $this->validate($request, [
            'description' => 'required',
        ]);
           $leaveData = $request->all();
            unset($leaveData['_token']);

            #leave_application $id
            // $levReject = new leave_application(leaveData);
            $levReject-> reject_reason = $request->input('description');
            $levReject->save();
              AuditReportsController::store('Leave approval ', 'leave rejection  Informations Edited', "Edited by User", 0);
            return response()->json();

    }

   
}
