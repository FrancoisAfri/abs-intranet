<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
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
use App\leave_profile;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leave_customs = leave_custom::orderBy('hr_id', 'asc')->get();
		if (!empty($leave_customs))
            $leave_customs = $leave_customs->load('userCustom');
        
         //return $leave_customs;
        // $leaveTypes = DB::table('leave_types')->orderBy('name', 'asc')->get();
        // $employees = HRPerson::where('status', 1)->get();

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
       
       $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
        $leaveTypes = LeaveType::where('status',1)->get()->load(['leave_profle'=>function($query){
          $query->orderBy('name', 'asc');  
        }]);
       
        $leaveApplication = DB::table('leave_application')->orderBy('id', 'asc')->get();
       

        $data['active_mod'] = 'Leave Management';
        $data['active_rib'] = 'Approve';
        $data['leaveTypes'] = $leaveTypes;
        $data['employees'] = $employees;
        $data['leaveApplication'] = $leaveApplication;
      // return $leaveApplication;
       
//        $data['leave_customs']=$leave_customs;

        AuditReportsController::store('Leave', 'Leave Approval Page Accessed', "Accessed By User", 0);
        return view('leave.leave_approval')->with($data);   
       
   }
    
    public function day(Request $request, leave_application $levApp )
    {
        $this->validate($request, [           
           'hr_person_id'=>'bail|required' ,
           "leave_type"=>'required',
            'day' =>'required',
           'description' =>'required',
           //"supporting_doc",     

        ]);
      
        $leaveApp = $request->all();
        return $leaveApp;
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
        
//        return $levDays;
        
        //Query leave holiday Tables
        
        //$public_holiday = DB::table('public_holidays')->orderBy('id', 'asc')->get();
        
        $public_holiday = DB::table('public_holidays')->pluck('day');    
        
        $levApp->hr_id = $request->input('hr_person_id');
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
//        return $end_date;
       
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
        
//        return $aPublicHolidays;
        
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
        
          $iDays = ($iDiff / 86400) - $iNonweek + 1;
        
        //return $iDays;
   // #save the start and end date
        $levApp->start_date = $start_date;
        $levApp->end_date = $end_date;
        
       // return $levApp->id;
        //        //Upload supporting Documents
        
        // save notes Description
         $levApp->notes = $request->input('description');
         $levApp->status = 1;
         //$levApp->update();
            //return $levApp;
       
        $levApp->save();
        
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = $levApp->id . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('levApp', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->update();
                
//                return $levApp;
            }
        }
        
        
      
        return back();
        
//        AuditReportsController::store('Leave', 'Leave Type Page Accessed', "Accessed By User", 0);
//        return view('leave.application')->with($data);  
    }
    
    public function hours(Request $request, leave_application $levApp )
    {
      $this->validate($request, [
          
           'hr_person_id'=>'required',
           'leave_type' => 'required',
//            "day",
           'datetime' =>'required',
           'description' => 'required',
                

        ]);
        $leaveApp = $request->all();
        unset($leaveApp['_token']);
     
        
         // explode left side
        $day = $leaveApp['datetime'];
        $dates = explode(" ",$day);
        $start_date = str_replace('/', '-', $dates[0]);
        $start_date = strtotime($start_date);//date
        $start_time = date('H:i:s',strtotime($dates[1] . ' ' . $dates[2]));// time
        $start_time = strtotime($start_time);
        
                // explode right side
          $var = $leaveApp['datetime'];
         $days = explode(' - ',$var);
         $end_date = str_replace('/', '-', $days[0]);
//        $end_date = date('d-m-Y',strtotime($days[0])); //date
        $end_time = date('H:i:s',strtotime($days[1])); // time
        $end_time = strtotime($end_time);
//         return $end_time;
       // return $end_time;
         $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
       //Query the Holiday table and return the days
        $public_holiday = DB::table('public_holidays')->pluck('day');
        
        // receive values from the dropbox
        $levApp->hr_id = $request->input('hr_person_id');
        $levApp->leave_type_id = $request->input('leave_type');
        
        //$HRpeople = HRPerson::find($employees);
       $diffrenceDays = ($end_date - $start_date) / 86400 + 1;
    //#calculate 
        // #save the start and end date
        $levApp->start_date = $start_date;
        $levApp->start_time = $start_date;
        $levApp->end_time = $end_time;
        
        $levApp->notes = $request->input('description');
              $levApp->status = 1;
              $levApp->save();

             //Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = $levApp->id . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('levApp', $fileName);
                //Update file name in hr table
                $levApp->supporting_docs = $fileName;
                $levApp->updates();
                
//                return $levApp;
            }
        }
        
              
                 //$levApp->update();
                    //return $levApp;
    
            
        
        
        
        return back();
    }

//    {"hr_person_id":["2"],"application_type":"2","leave_type":"2","day":" ","date&time":"06\/04\/2017 12:00 AM - 06\/04\/2017 11:00 PM","description":"sss","supporting_doc":"","load-allocation":"Submit"}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
