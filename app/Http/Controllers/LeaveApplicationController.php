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
        // ->where('leave_application.status', '<',4)
        ->orderBy('leave_application.hr_id')
        ->get();
        // return $leaveApplication;
        #    
            //return $leave_appDetails;

     // return  $leaveApplication;

        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Approve';
        $data['leaveTypes'] = $leaveTypes;
        //$data['employees'] = $employees;
        $data['leaveApplication'] = $leaveApplication;


        
       

       
    // return $leaveApplication;


       
//        $data['leave_customs']=$leave_customs;

        AuditReportsController::store('Leave', 'Leave Approval Page Accessed', "Accessed By User", 0);
        return view('leave.leave_approval')->with($data);   
       
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

                // echo $managerDetails;

             $details = array('status' => 3,'first_name' => $managerDetails->first_name,'surname' => $managerDetails->surname,'email' => $managerDetails->email);
             // return  $details;

             // echo $managerDetails;

             // die ("dbjfjn");
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
                    $details = array('status' => 3,'first_name' => $mamgerDetails->firstname,'surname' => $mamgerDetails->surname,'email' => $mamgerDetails->email);
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

    public function reject(Request $request, leave_application $levApp) {
           $this->validate($request, [
            'reason' => 'required',

        ]);

        $leaveData = $request->all();
        unset($leaveData['_token']);
        
        $levApp->status = 1;
        $levApp->save();
        AuditReportsController::store('leave', 'leavetype Added', "leave type Name: $levApp->name", 0);
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

        // $levApp->status = '1';
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
        
       $diffrencetime = ($end_time - $start_time);
       $diffrencetime = strtotime($diffrencetime);

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
        $levApp->save();    

        // $notifConf = '';
        // if (count($EducationManager) > 0) {
        // foreach ($EducationManager as $user) {
        //  AuditReportsController::store('Programmes', 'project Approval Sent', "Sent TO $user->first_name $user->surname to approve project: $project->name", 0);
        // Mail::to($user->email)->send(new EducatorManagerMail($user->first_name, $project->id));
        // }
        // $notifConf = " \nA request for approval has been sent to the Education & Learning Manager(s).";
        // }
        // AuditReportsController::store('Programmes', 'project Created', "Created By User", 0);
        // return redirect("/project/view/$project->id")->with('success_add', "The Project has been added successfully.$notifConf");
        
        return back();
    }

//Function to accept leave applications
    public function AcceptLeaave(Request $request, $id)
    {
        // get the user application  details
         $leave_appDetails = leave_application::where('id',$id )->first();

            #get leave balance
          $Application = DB::table('leave_application')
        ->select('leave_application.*','leave_credit.leave_balance as leave_balance')  
        ->leftJoin('leave_credit', 'leave_application.hr_id', '=', 'leave_credit.hr_id' )
        ->where('leave_application.hr_id', 3)
        ->first();

         // get the leave days of the user sing the $id
          $applied_for = DB::table('leave_application')->where('id' , $id)->pluck('leave_days');
          #Get the user leave balance


          #calculations
        $leave_balance - $applied_for ;

       

    }

    /*
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
