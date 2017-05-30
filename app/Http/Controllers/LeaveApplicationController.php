<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\LeaveHistoryAuditController;
use App\Mail\leave_applications;
use App\Mail\Accept_application;
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
        $data['page_description'] = "Leave Management";
        $data['breadcrumb'] = [
          ['title' => 'Leave Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Leave Application', 'active' => 1, 'is_module' => 0]
        ];

         #Query to get negative annual leave days for user based on userID and LeaveID
          $negativeannualDays = DB::table('leave_configuration')
                  ->select('allow_annual_negative_days' ) 
                        ->where('id', 1)
                        ->get();
           $negannualDays = $negativeannualDays->first()->allow_annual_negative_days;
           

          #Query to get negative sick leave days for user based on userID and LeaveID
             $negativesickDays = DB::table('leave_configuration')
                  ->select('allow_sick_negative_days' ) 
                        ->where('id', 1)
                        ->get();
             $negsickDays = $negativesickDays->first()->allow_sick_negative_days;
            

        $data['negannualDays'] = $negannualDays;
        $data['negsickDays'] = $negsickDays;
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
            ['title' => 'Leave Management', 'path' => 'leave/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'leave Approval', 'active' => 1, 'is_module' => 0]
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

        #
        // 

       
        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Approve';
        $data['leaveTypes'] = $leaveTypes;
        //$data['employees'] = $employees;
        $data['leaveApplication'] = $leaveApplication;

        AuditReportsController::store('Leave', 'Leave Approval Page Accessed', "Accessed By User", 0);
        return view('leave.leave_approval')->with($data);   
       
   }
             
  public function status($status=0) {

    $approvalstatus = array(1 => 'Approved' , 2 => 'require_managers_approval ', 3 => 'require_department_head_approval', 4 =>          'require_hr_approval', 5 => 'require_payroll_approval', 6 => 'Approved', 7 => 'Rejected');
        
        $rejectstatus = array(7 => 'rejectd by managers ', 8 => 'rejectd by department_head', 9 => 'rejectd by hr', 10 => 'rejectd by payroll');
       return $approvalstatus;
    }

    
            
      public function ApplicationDetails($status=0, $hrID=0){

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

            $row = HRPerson::where('id', $hrDetails->manager_id)->where('status', 1)
                  ->count();

                     if ($managerDetails == null){
                    $details = array('status' => 1,'first_name' => $hrDetails->first_name,'surname' => $hrDetails->surname,'email' => $hrDetails->email);
                        return $details;
                  }else{
                            // array to store manager details
                    $details = array('status' => 2,'first_name' => $managerDetails->firstname,'surname' => $managerDetails->surname,'email' => $managerDetails->email);
                    return $details;
                  }
          
            }
                elseif ($approvals->require_department_head_approval == 1 ) {
                    # code...  division_level_twos
                  
                    // query the hrperon  model and bring back the values of the manager

                  $Dept = DivisionLevelTwo::where('id' , $hrDetails->division_level_2 )-> get()->first();
                    $msamgerDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                    -> select('first_name' , 'surname', 'email' )
                    ->first();

                   

                    if ($msamgerDetails == null){
                    $details = array('status' => 1,'first_name' => $hrDetails->first_name,'surname' => $hrDetails->surname,'email' => $hrDetails->email);
                        return $details;
                  }else{
                            // array to store manager details
                    $details = array('status' => 3,'first_name' => $msamgerDetails->firstname,'surname' => $msamgerDetails->surname,'email' => $msamgerDetails->email);
                    return $details;
                  }
                }
                    #code here .. Require Hr
            else
            {

            $details = array('status' => 1,'first_name' => $hrDetails->first_name,'surname' => $hrDetails->surname,'email' => $hrDetails->email);
               
                return $details;
              
            }

    }
        #

          #function to get available days for user based on userID and LeaveID
            public function availableDays($hrID, $typID){
                
                 $balance = DB::table('leave_credit')
                  ->select('leave_balance' ) 
                        ->where('hr_id', $hrID)
                        ->where('leave_type_id', $typID) 
                        ->get();

                return $balance->first()->leave_balance;
            }

 
    public function day(Request $request, leave_application $levApp  )
    {
         
     
        $this->validate($request, [           
           'hr_person_id'=>'bail|required',
           "leave_type"=>'bail|required',
            'day' =>'required',
        ]);

        $leaveApp = $request->all();
        unset($leaveApp['_token']);
       
        $negDays  = leave_configuration::where('id' , 1)->first();
         $study = $negDays->document_compulsory_on_Study_leave_application;
         //return $study;
        $sickdays = $negDays->document_compulsory_when_two_sick_leave_8_weeks;
  
      // return $negDays;

        $anualdays = $negDays->allow_annual_negative_days;
        if($anualdays = null){
            $anualdays = 0;
        }else
        $anualdays = $negDays->allow_annual_negative_days * 8;

        $sickdays = $negDays->allow_sick_negative_days;
        if($sickdays = null){
         $sickdays = 0;
        }else
        $sickdays = $negDays->allow_sick_negative_days * 8;


        $ApplicationDetails = array();
        $status = array();


        $leaveApp = $request->all(); 

       
//return $leaveApp;
        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
                $hrID = $leaveApp['hr_person_id'];
                $typID = $leaveApp['leave_type'];

                   $managerDetails = HRPerson::where('id',$hrID )
                                      ->select('manager_id')
                                      ->get()->first();

                  $managerID = $managerDetails['manager_id'];

         $Details = leave_credit::where('hr_id',$hrID )
                        ->where('leave_type_id', $typID) 
                        ->first();

             $leave_balance = $Details['leave_balance'];

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
         $USername = $HRpeople->first_name;
        
        
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
          $current_balance = $leave_balance- $diffDays;

        // call the function
        $ApplicationDetails =  LeaveApplicationController::ApplicationDetails(0, $hrID);
      
        $statusnames =  LeaveApplicationController::status();

        $applicatiionStaus = $ApplicationDetails['status'];

        $levtype = $request->input('leave_type');

       // $levApp->status = 1;
        $levApp->start_date = $start_date;
        $levApp->end_date = $end_date;
        $levApp->leave_days = $iDays;
        $levApp->leave_type_id = $request->input('leave_type');
        $levApp->hr_id = $request->input('hr_person_id');
        $levApp->notes = $request->input('description');
        $levApp->status = $applicatiionStaus;
        $levApp->manager_id = $managerID;

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

  #$action='',$descriptionAction ='',$previousBalance='',$transcation='' ,$current_balance ='',$leave_type ='')
        AuditReportsController::store('Leave', 'Leave day application ', "Accessed By User", 0);
        #leave history audit
        LeaveHistoryAuditController::store("Day leave application performed by : $USername",0,$leave_balance,$iDays,$current_balance,$levtype);
        return back()->with('success_application', "leave application was successful.");
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
          $leaveApp = $request->all();
         $hrID = $leaveApp['hr_person_id'];
         $approveDetails = array();
        

        unset($leaveApp['_token']);

     $ApplicationDetails = array();
        $status = array();
        
        // explode left side
        $day = $leaveApp['datetime'];
        $dates = explode(" ",$day);
        $start_date = str_replace('/', '-', $dates[0]);
        $start_date = strtotime($start_date);//date
        $start_time = date('Y-m-d H:i:s',strtotime($dates[1] . ' ' . $dates[2]));// time
        $start_time = strtotime($start_time);
  
        
        // explode right side
        $var = $leaveApp['datetime'];
        $days = explode(' - ',$var);
        $end_date = str_replace('/', '-', $days[0]);
        $end_time = date('Y-m-d H:i:s',strtotime($days[1])); // time
        $end_time = strtotime($end_time);
    

       //Query the Holiday table and return the days
        $public_holiday = DB::table('public_holidays')->pluck('day');
        
       $diffrencetime = ($end_time - $start_time)/3600;
      // $diffrenceTime = strtotime($diffrencetime);

  
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
          $hrID = $request->input('hr_person_id');
         $managerDetails = HRPerson::where('id',$hrID )
                                      ->select('manager_id')
                                      ->get()->first();

        $managerID = $managerDetails['manager_id'];

        // $ApplicationDetails =  LeaveApplicationController::ApplicationDetails(0, $request->input('hr_person_id'));
         $ApplicationDetails =  LeaveApplicationController::ApplicationDetails(0, $hrID); 
        $statusnames =  LeaveApplicationController::status();     
        $applicatiionStaus = $ApplicationDetails['status'];
        
        // $status = $statusnames[$applicatiionStaus];
        
           $employees = $request->input('hr_person_id');
           $typID = $request->input('leave_type');
          $HRpeople = HRPerson::find($employees);
         $USername = $HRpeople->first_name;
        #

         $Details = leave_credit::where('hr_id',$employees )
                        ->where('leave_type_id', $typID) 
                        ->first();

        $leave_balance = $Details['leave_balance'];
        #
        $levApp->leave_type_id = $typID;
        // $levApp->hr_id = $request->input('hr_person_id');
        $levApp->notes = $request->input('description');
        $levApp->status = $applicatiionStaus;
        $levApp->start_date = $start_date;
        $levApp->start_time = $start_time;
        $levApp->end_time = $end_time;
        $levApp->leave_hours = $diffrencetime;
        $levApp->manager_id = $managerID;
        $levApp->save();    

        #mail
        Mail::to($ApplicationDetails['email'])->send(new leave_applications($ApplicationDetails['first_name'], $ApplicationDetails['surname'] ,$ApplicationDetails['email']));
        
         #$action='',$descriptionAction ='',$previousBalance='',$transcation='' ,$current_balance ='',$leave_type ='')
  AuditReportsController::store('Leave', 'Leave hours application ', "Accessed By User", 0);
 LeaveHistoryAuditController::store("Hours leave application performed by : $USername",0,$leave_balance,0,$leave_balance,$typID);

        return back()->with('success_application', "leave application was successful.");
    }

      //Function to accept leave applications
    public function AcceptLeave(Request $request,leave_application $id , leave_history $levHist , leave_credit $credit , leave_configuration $leave_conf)
    {
        // get the user application  details
       $loggedInEmplID = Auth::user()->person->id;
         $status = $id->status;
        $iD = $id->id;

        $LevTid = $id->leave_type_id;
        $hriD = $id->hr_id;
        #query the hr person table
        $usedetails = HRPerson::where('id' ,$hriD )
                  -> select('first_name' , 'surname', 'email' )
                  ->first();
                 
                  $firstname = $usedetails['first_name'];
                  $surname = $usedetails['surname'];
                  $email = $usedetails['email'];

        $levTyp =$id->leave_type_id;
      
        $leave_appDetails = leave_application::where('id',$iD )->first();

          // #Query the the leave_config days for value
           $negDays  = leave_configuration::where('id' , 1)->first();
          

         $hrID = $id['hr_id'];
         $typID = $id['leave_type_id'];  

             $Details = leave_credit::where('hr_id',$hrID )
                        ->where('leave_type_id', $typID) 
                        ->first();

             $leave_balance = $Details['leave_balance'];

         

# check whose in the list of approving an application b4 writing into the db

                $managerApproval = $negDays['require_managers_approval'];
                $managerApproval = $negDays['require_department_head_approval'];
                $managerApproval = $negDays['require_managers_approval'];

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
                #
              $levHist->description_action = $leave_appDetails;
              $levHist->previous_balance = $leave_balance; 

              $levHist->save();

              $approvals  =  leave_configuration::where('id' , 1)
                        ->select('require_managers_approval','require_department_head_approval') 
                        ->get()->first(); 

                  $ManHed =  $approvals->first()->require_managers_approval;     
                  $DepHead =  $approvals->first()->require_department_head_approval;

       //            $approvalstatus = array(1 => 'Approved' , 2 => 'require_managers_approval ', 3 => 'require_department_head_approval', 4 =>          'require_hr_approval', 5 => 'require_payroll_approval', 6 => 'Approved', 7 => 'Rejected');
        
       //  $rejectstatus = array(7 => 'rejectd by managers ', 8 => 'rejectd by department_head', 9 => 'rejectd by hr', 10 => 'rejectd by payroll');
       // return $approvalstatus;

                  if ($status == 1 && $ManHed)
                  {
                   DB::table('leave_application')
                        ->where('id', $iD)
                         ->update(['status' => 2]);    
                   }
                   elseif ($status == 2 && $DepHead == 1) {
                   DB::table('leave_application')
                        ->where('id', $iD)
                         ->update(['status' => 3]);
                  }
                  else{
                     DB::table('leave_application')
                         ->where('id', $iD)
                          ->update(['status' => 1]);
                  }
            
          // Mail::to($ApplicationDetails['email'])->send(new leave_applications($ApplicationDetails['first_name'], $ApplicationDetails['surname'] ,$ApplicationDetails['email']));

       
        #send email to the user informing that the leave has been accepted
         Mail::to($email)->send(new Accept_application($firstname, $surname , $email));

          #$action='',$descriptionAction ='',$previousBalance='',$transcation='' ,$current_balance ='',$leave_type ='')
 LeaveHistoryAuditController::store(" leave application Approvred by : ",0,$leave_balance,$daysApplied,$newBalance,$LevTid);
      AuditReportsController::store('Leave', 'leave_approval Informations accepted', "Edited by User: $levHist->hr_id",0);

                    return back()->with('success_application', "leave application was successful.");
    }

    /*
     */
    public function reject(Request $request, leave_application $levReject)
    {
        //
        $this->validate($request, [
             // 'description' => 'numeric',
        ]);
           $leaveData = $request->all();
            unset($leaveData['_token']);

            
            // $levReject = new leave_application(leaveData);
             $loggedInEmplID = Auth::user()->person->id;
            $levReject->reject_reason = $request->input('description');

            $levReject->update();

            AuditReportsController::store('Leave rejected by : ', 'leave rejection  Informations Edited', "Edited by User", 0);


            return response()->json();
           // return view('leave.application')->with($levTypVar);  

    }

   
}
