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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    public function day(Request $request, leave_application $levApp )
    {
        $validator = Validator::make($request->all(), [      
                  
           "hr_person_id" ,
           "leave_type",
            "day",
           "date&time",
           "description",
           "supporting_doc",     
//            
//            {"hr_person_id":["4"],"application_type":"1","leave_type":"5","day":" ","date&time":" ","description":"","supporting_doc":"","load-allocation":"Submit"}
        ]);
      
        $leaveApp = $request->all();
        
        //Exclude empty fields from query
        foreach ($leaveApp as $key => $value)
        {
            if (empty($leaveApp[$key])) {
                unset($leaveApp[$key]);
            }
        }

        $employees = HRPerson::where('status', 1)->get()->load(['leave_types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);
        
        // separate day range
        $day = $leaveApp['day'];
        $dates = explode(' - ',$day);
        $start_date = date('Y-m-d',strtotime($dates[0]));
        $end_date = date('Y-m-d',strtotime($dates[1]));
            
        // Get employeeId from dropbbox
        $employees = $leaveApp['hr_person_id'];
        
        // Get leavetype Id from dropbbox
        $tyop = $leaveApp['leave_type'];
        
        //query the hr table based on employeeId
        $HRpeople = HRPerson::find($employees);
        
            
                // save notes Description
         $levApp->notes = $request->input('description');
         $levApp->update();
            //return $levApp;

        
        //convert dates to unix time stamp
        if (isset($leaveApp['$date1'])) {
           $leaveApp['$date1'] = str_replace('/', '-', $leaveApp['$date1']);
           $leaveApp['$date1'] = strtotime($leaveApp['$date1']);
        }
        if (isset($leaveApp['$date2'])) {
            $projectData['$date2'] = str_replace('/', '-', $projectData['$date2']);
            $projectData['$date2'] = strtotime($projectData['$date2']);
        }
        
//        //Upload supporting Documents
        
        if ($request->hasFile('supporting_doc')) {
            $fileExt = $request->file('supporting_doc')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_doc')->isValid()) {
                $fileName = $levApp->id . "_supporting." . $fileExt;
                $request->file('supporting_doc')->storeAs('notes', $fileName);
                //Update file name in hr table
                $levApp->supporting_doc = $fileName;
                $levApp->update();
                
//                return $levApp;
            }
        }
        
////        return $HRpeople;
//        foreach($employees as $empID) {
//            $emp = HRPerson::find($empID);
//            //return $empID;
//           $types = LeaveType::find($tyop);
////            $pro = $emp->leave_types;
//            $profile =$emp->leave_types->where('id', $empID)->first() ;
//         
//           // $val = $profile->pivot->leave_balance;
////           return $val;
//        }
        
        return back();
    }
    
    public function hours(Request $request)
    {
        $validator = Validator::make($request->all(), [      
                  
           "hr_person_id",
//           "application_type",
           "leave_type",
            "day",
           "date&time",
           "description",
           "supporting_doc",     

        ]);
        $leaveApp = $request->all();
        unset($leaveApp['_token']);
//     return $leaveApp;
        
         // explode left side
        $day = $leaveApp['date&time'];
        $dates = explode(" ",$day);
        $start_date = date('d-m-Y',strtotime($dates[0]));//date
        $start_time = date('H:i:s',strtotime($dates[1]));// time
        //return $date2; 
        
                // explode right side
          $var = $leaveApp['date&time'];
        $days = explode(' - ',$var);
        $end_date = date('d-m-Y',strtotime($days[0])); //date
        $end_time = date('H:i:s',strtotime($days[1])); // time
        
//        return $end_time;
        
       
        
        

       
    
        
        
        
        return back();
    }

//    {"hr_person_id":["2"],"application_type":"2","leave_type":"2","day":" ","date&time":"06\/04\/2017 12:00 AM - 06\/04\/2017 11:00 PM","description":"sss","supporting_doc":"","load-allocation":"Submit"}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

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
