<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\HRPerson;
use App\vehicle;
use App\vehicle_config;
use App\servicetype;
use App\vehicle_detail;
use App\jobcard_maintanance;
use App\ContactCompany;
use App\processflow;
use App\jobcards_config;
use App\CompanyIdentity;
use App\module_access;
use App\module_ribbons;
use App\modules;
use App\Mail\NextjobstepNotification;
use App\Mail\DeclinejobstepNotification;
use Illuminate\Http\Request;
use App\Mail\confirm_collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;


class JobcardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function jobcard_settings()
    {
        
        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['incidentType'] = $incidentType;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.setup')->with($data);
    }
    
    public function servicetype(){
        
       
        
        $servicetype = servicetype::orderBy('name',1)->get();
        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['servicetype'] = $servicetype;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Setup';
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.service_type')->with($data);  
    }
    
    public function addservicetype(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $servicetype = new servicetype($SysData);
        $servicetype->status = 1;
        $servicetype->save();
        
        AuditReportsController::store('Job Card Management', 'New service Type added', "Accessed By User", 0);
        return response()->json();
    }
    
    public function editservicetype(Request $request, servicetype $service){
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $service->name = $SysData['name'];
        $service->description = $SysData['description'];
        $service->update();
        AuditReportsController::store('Fleet Management', ' service Type edited', "Accessed By User", 0);
        return response()->json();
    }
    
    public function servicetype_act(servicetype $service){
        if ($service->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $service->status = $stastus;
        $service->update();
        
        return back();
    }
    
    public function deleteservicetype(servicetype $service){
        $service->delete();

        AuditReportsController::store('Job Card Management', ' service Type Deleted', "Accessed By User", 0);
      
        return redirect('/jobcards/servicetype');
    
    }
    
    public function configuration(){
        
         $row = jobcards_config::count();
        //return $row;
        if ($row == 0) {
            $config = new jobcards_config();
            $config->use_procurement = 0;
            $config->mechanic_sms = 0;
            $config->save();
        } elseif($row > 1)   
        $configuration = jobcards_config::first(); 
        
        
        $configuration = jobcards_config::first(); 
        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['configuration'] = $configuration;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'SetUp';
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.configuration')->with($data); 
    }
    public function configurationSetings(Request $request, jobcards_config $config){
        
        $SysData = $request->all();
        unset($SysData['_token']);
        
        $config->use_procurement = !empty($SysData['use_procurement']) ? $SysData['use_procurement'] : 0;
        $config->mechanic_sms = !empty($SysData['mechanic_sms']) ? $SysData['mechanic_sms'] : 0;
        $config->update();

        AuditReportsController::store('Job Card Management', 'configurationSetings updated', "Accessed By User", 0);
        return back();
    }
     
     public function procesflow(){
         
         $flow =processflow::orderBy('id','desc')->latest()->first();
         $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0  ;
         $newstep =   $flowprocee + 1;
         
         
        $processflow = processflow::all(); 
        $positions = DB::table('hr_positions')->where('status', 1)->get();
        
        $processflow = DB::table('jobcard_process_flow')
            ->select('jobcard_process_flow.*', 'hr_positions.name as jobtitle')
            ->leftJoin('hr_positions', 'jobcard_process_flow.job_title', '=', 'hr_positions.id')
            ->orderBy('jobcard_process_flow.id')
          //  ->where('jobcard_process_flow.status', 1)
            ->get();
        
        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['newstep'] = $newstep;
        $data['positions'] = $positions;
        $data['processflow'] = $processflow;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Process Flow';
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.processflow')->with($data);     
     }
     
     public function addprocessflow(Request $request){
         $this->validate($request, [
              'step_name' => 'required',
              'job_title' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        
         $flow = processflow::orderBy('id','desc')->latest()->first();
         $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0  ; 
         
         $processflow = new processflow();
         $processflow->step_number =  $flowprocee +  1;
         $processflow->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
         $processflow->job_title = !empty($SysData['job_title']) ? $SysData['job_title'] : 0;
         $processflow->status = 1;
         $processflow->save();
         
         AuditReportsController::store('Job Card Management', 'New service Type added', "Accessed By User", 0);
        return response()->json();
     }
     
     public function editprocessflow(Request $request , processflow $steps){
        $this->validate($request, [
              'step_name' => 'required',
              'job_title' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        
         $steps->step_number = !empty($SysData['step_number']) ? $SysData['step_number'] : '';
         $steps->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
         $steps->job_title = !empty($SysData['job_title']) ? $SysData['job_title'] : 0;
         $steps->update();
         
         AuditReportsController::store('Job Card Management', ' process flow edited', "Accessed By User", 0);
        return response()->json();
     }
     
     public function steps_act(processflow $steps){
       if ($steps->status == 1)
            $stastus = 0;
        else
            $stastus = 1;
        $steps->status = $stastus;
        $steps->update();
		
		AuditReportsController::store('Job Card Management', ' process flow status Changed', "Accessed By User", 0);		
        return back();  
     }
     
     
     //myJobcards
     
      public function jobcardStatus($status = 0, $hrID = 0, $jobID = 0) {
         
          $user = Auth::user()->person->user_id;
		  $hrjobtile = Auth::user()->person->position;
          // get from hrPerson where id is $hrID
          
             $status = DB::table('security_modules_access')
                   ->select('security_modules_access.user_id') 
                   ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
                   ->where('security_modules.code_name', 'job_cards')
                   ->where('security_modules_access.access_level','>=', 4)
                   ->where('security_modules_access.user_id', $hrID)
                   ->first();
  
           $processflow = processflow::where('job_title',$hrjobtile)->where('status' , 1)->orderBy('id','asc')->get();
								  
      }
      
     public function myjobcards(){         
       $hrID = Auth::user()->person->user_id;
       $hrjobtile = Auth::user()->person->position;
       $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id') 
                   ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
                   ->where('security_modules.code_name', 'job_cards') ->where('security_modules_access.access_level','>=', 4)
                   ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();
                    
       $processflow = processflow::where('job_title',$hrjobtile)->where('status' , 1)->orderBy('id','asc')->get();
       $processss = processflow::take(1);
	  // return $processss;
	  // return $processflow->step_number->take(1);
       $rowcolumn = $processflow->count();
      
       if(($rowcolumn >0 )){
                   
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
       
        $servicetype = servicetype::where('status',1)->get();
       
        $users = HRPerson::where('status',1)->orderBy('id', 'asc')->get();
        
        $Status = array(-1=>'Rejected',1 => 'Job Card created',
				 3=>'Completed',6=>'Procurement ',7=>'At Service',
				 8=>'Spare Dispatch',9=>' At Mechanic',10=>'Spares Dispatch Paperwork',
				 11=>'Fleet Manager',12=>'Awaiting Closure',13=>'Closed',14 =>'Pending Cancellation',15=>'Cancelled');
        
         
        $jobcardmaintanance = DB::table('jobcard_maintanance')
            ->select('jobcard_maintanance.*','vehicle_details.*','contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type','service_type.name as servicetype',
                    'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
            ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('jobcard_maintanance.id', 'asc')
            ->get();  
             
        
        $vehicledetails =  DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->get();  
        
        
        $configuration = jobcards_config::first(); 
        $data['page_title'] = "Job Cards";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/mycards', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Cards ', 'active' => 1, 'is_module' => 0]
        ];

        $data['Status'] = $Status;
        $data['users'] = $users;
        $data['ContactCompany'] = $ContactCompany;
        $data['jobcardmaintanance'] = $jobcardmaintanance;
        $data['servicetype'] = $servicetype;
        $data['vehicledetails'] = $vehicledetails;
        $data['configuration'] = $configuration;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
       return view('job_cards.myjob_cards')->with($data); 
       
       }else  {
//           return redirect('/');
            return back();
			return back()->with('success_edit', "The are not permitted to view this page.");
        }
     }
     
     public function addjobcardmanagement(Request $request){
        $this->validate($request, [
//              'step_name' => 'required',
//              'job_title' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        
        $processflow = processflow::orderBy('id', 'acs')->first();
        $jobtitle = !empty($processflow->job_title) ? $processflow->job_title : 0  ; 
        
        $carddate = $SysData['card_date'] = str_replace('/', '-', $SysData['card_date']);
        $carddate = $SysData['card_date'] = strtotime($SysData['card_date']);
        
        
        $scheduledate = $SysData['schedule_date'] = str_replace('/', '-', $SysData['schedule_date']);
        $scheduledate = $SysData['schedule_date'] = strtotime($SysData['schedule_date']);
        
        $bookingdate = $SysData['booking_date'] = str_replace('/', '-', $SysData['booking_date']);
        $bookingdate = $SysData['booking_date'] = strtotime($SysData['booking_date']);
        
        $completiondate = $SysData['completion_date'] = str_replace('/', '-', $SysData['completion_date']);
        $completiondate = $SysData['completion_date'] = strtotime($SysData['completion_date']);
        
        
        $flow = jobcard_maintanance::orderBy('id','desc')->latest()->first();
        $flowprocee = !empty($flow->jobcard_number) ? $flow->jobcard_number : 0  ; 
       
        $jobcardmaintanance = new jobcard_maintanance($SysData);
        $jobcardmaintanance->vehicle_id = !empty($SysData['vehicle_id']) ? $SysData['vehicle_id'] : 0;
        $jobcardmaintanance->card_date = !empty($carddate) ?$carddate : 0;
        $jobcardmaintanance->schedule_date = !empty($scheduledate) ?$scheduledate : 0;
        $jobcardmaintanance->booking_date = !empty($bookingdate) ? $bookingdate : 0;
        $jobcardmaintanance->supplier_id = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
        $jobcardmaintanance->service_type = !empty($SysData['service_type']) ? $SysData['service_type'] : 0;
        $jobcardmaintanance->estimated_hours = !empty($SysData['estimated_hours']) ? $SysData['estimated_hours'] : 0;
        $jobcardmaintanance->service_time = !empty($SysData['service_time']) ? $SysData['service_time'] : 0;
        $jobcardmaintanance->machine_hour_metre = !empty($SysData['machine_hour_metre']) ? $SysData['machine_hour_metre'] : 0;
        $jobcardmaintanance->machine_odometer = !empty($SysData['machine_odometer']) ? $SysData['machine_odometer'] : 0;
        $jobcardmaintanance->last_driver_id = !empty($SysData['last_driver_id']) ? $SysData['last_driver_id'] : 0;
        $jobcardmaintanance->inspection_info = !empty($SysData['inspection_info']) ? $SysData['inspection_info'] : '';
        $jobcardmaintanance->mechanic_id = !empty($SysData['mechanic_id']) ? $SysData['mechanic_id'] : 0;
        $jobcardmaintanance->instruction = !empty($SysData['instruction']) ? $SysData['instruction'] : '';
        $jobcardmaintanance->completion_date = $completiondate;
        $jobcardmaintanance->jobcard_number = $flowprocee + 1;
        $jobcardmaintanance->status = 1;
        $jobcardmaintanance->save();
        
        //Upload supporting document
        if ($request->hasFile('inspection_file_upload')) {
            $fileExt = $request->file('inspection_file_upload')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'tiff']) && $request->file('inspection_file_upload')->isValid()) {
                $fileName = $jobcardmaintanance->id . "_inspection_file_upload." . $fileExt;
                $request->file('inspection_file_upload')->storeAs('Jobcard/inspectionfileupload', $fileName);
                //Update file name in the table
                $jobcardmaintanance->inspection_file_upload = $fileName;
                $jobcardmaintanance->update();
            }
        }
        
        
        //Upload supporting document
        if ($request->hasFile('inspection_file_upload')) {
            $fileExt = $request->file('service_file_upload')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'tiff']) && $request->file('service_file_upload')->isValid()) {
                $fileName = $jobcardmaintanance->id . "_service_file_upload." . $fileExt;
                $request->file('service_file_upload')->storeAs('Jobcard/servicefileupload', $fileName);
                //Update file name in the table
                $jobcardmaintanance->service_file_upload = $fileName;
                $jobcardmaintanance->update();
            }
        }
        
           // send emails
              $users = HRPerson::where('position', $jobtitle)->pluck('user_id');
               foreach ($users as $manID) {
                 $usedetails = HRPerson::where('user_id',$manID)->select('first_name', 'surname', 'email')->first();
                 $email = $usedetails->email; $firstname = $usedetails->first_name; $surname = $usedetails->surname; $email = $usedetails->email;
                     Mail::to($email)->send(new NextjobstepNotification($firstname, $surname, $email));
               }
        
        
        AuditReportsController::store('Job Card Management', ' Job card created', "Accessed By User", 0);
        return response()->json();

     }
     
     public function jobcardsearch(){
         
//         $this->validate($request, [
//
//        ]);
//        $SysData = $request->all();
//        unset($SysData['_token']);
//        
//        $jobcard = $request['jobcard_id'];
//        $fleetnumber = $request['fleet_number'];
//        $registrationNo = $request['registration_no'];
//        $status = $request['status'];
//        $servicetypeID = $request['service_type_id'];
//        $mechanicID = $request['mechanic_id'];
//        
//        //$jobcardmaintanance =  jobcard_maintanance::orderBy('id','desc')->get();
//        
//        $actionFrom = $actionTo = 0;
//        $actionDate = $request['date'];
//        if (!empty($actionDate)) {
//            $startExplode = explode('-', $actionDate);
//            $actionFrom = strtotime($startExplode[0]);
//            $actionTo = strtotime($startExplode[1]);
//           
//        }
//        
//        //processflow  status
//        
//        $jobcardmaintanance = DB::table('jobcard_maintanance')
//            ->select('jobcard_maintanance.*','vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
//                    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
//                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type','service_type.name as servicetype',
//                    'hr_people.first_name as firstname', 'hr_people.surname as surname','jobcard_process_flow.step_name as aStatus')
//            ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
//            ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
//            ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
//            ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
//            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
//            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
//            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
//            ->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
//                ->where(function ($query) use ($actionFrom, $actionTo) {
//                        if ($actionFrom > 0 && $actionTo > 0) {
//                            $query->whereBetween('jobcard_maintanance.date', [$actionFrom, $actionTo]);
//                        }
//                    })
//                    ->where(function ($query) use ($jobcard) {
//                        if (!empty($jobcard)) {
//                            $query->where('c.jobcard_number',$jobcard);
//                        }
//                    })
//                    ->where(function ($query) use ($fleetnumber) {
//                        if (!empty($fleetnumber)) {
//                            $query->where('vehicle_details.fleet_number', 'ILIKE', "%$fleetnumber%");
//                        }
//                    })
//                    ->where(function ($query) use ($registrationNo) {
//                        if (!empty($registrationNo)) {
//                            $query->where('vehicle_details.vehicle_registration', 'ILIKE', "%$registrationNo%");
//                        }
//                    })
//                ->orderBy('jobcard_maintanance.id', 'asc')
//                ->get(); 
                    
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search Job Cards';

        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.search')->with($data); 
     }
     
     public function jobcardsApprovals(){
        
        $jobcardmaintanance = DB::table('jobcard_maintanance')
            ->select('jobcard_maintanance.*','vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type','service_type.name as servicetype',
                    'hr_people.first_name as firstname', 'hr_people.surname as surname','jobcard_process_flow.step_name as aStatus')
            ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
            ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
            ->orderBy('jobcard_maintanance.id', 'asc')
            ->get(); 
                    
                   //return $jobcardmaintanance;
        
           $steps =  processflow::latest()->first();     
           $stepnumber = !empty($steps->step_number) ? $steps->step_number : 0  ; 
           
         //  return $stepnumber;
      
          $data['page_title'] = "Job Card Approvals";
          $data['page_description'] = "Job Card Management";
          $data['breadcrumb'] = [
                ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Job Card Approval ', 'active' => 1, 'is_module' => 0]
            ];

        $data['stepnumber'] = $stepnumber;
        $data['jobcardmaintanance'] = $jobcardmaintanance;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Approvals';

        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.Job_card_approval')->with($data); 
     }
     
     public function appovecards(Request $request , jobcard_maintanance $jobcards){
         $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
      //  return $results;

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'cardappprove'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $cardID = $aValue[1];
                //return $name;
                if (count($sValue) > 1) {
                    $status = $sValue[1];
                } else $status = $sValue[0];
                $cardsID = $cardID;
                
               $getStatus = DB::table('jobcard_maintanance')->where('id',$cardsID)->get();
               $statusflow = $getStatus->first()->status;
             
               $processflow = processflow::where('step_number', '>' , $statusflow)->where('status', 1)->orderBy('step_number','asc')->first();
                
               $jobcards->updateOrCreate(['id' => $cardsID], ['status' => $processflow->step_number]);
               
               // send email to the next person the step
               $users = HRPerson::where('position', $processflow->job_title)->pluck('user_id');
               foreach ($users as $manID) {
                 $usedetails = HRPerson::where('user_id',$manID)->select('first_name', 'surname', 'email')->first();
                 $email = $usedetails->email; $firstname = $usedetails->first_name; $surname = $usedetails->surname; $email = $usedetails->email;
                     Mail::to($email)->send(new NextjobstepNotification($firstname, $surname, $email));
               }
            }
            
            // decline
                 
            foreach ($results as $sKey => $sValue) {
            if (strlen(strstr($sKey, 'declined_'))) {
                list($sUnit, $iID) = explode("_", $sKey);
                if ($sUnit == 'declined' && !empty($sValue)) {
                    if (empty($sValue)) $sValue = $sReasonToReject;

                            $getStatus = DB::table('jobcard_maintanance')->where('id',$iID)->get();
                            $statusflow = $getStatus->first()->status;
                            
                          
                    $jobcard = jobcard_maintanance::where('id' ,$iID )->first();  // when declined move back to the last step
                      if($statusflow === 0 ){
                          // status 0 means declined
                         $jobcard->status = 0;  
                      }elseif($statusflow === 1){
                          $jobcard->status = 0;
                      }else
                    $jobcard->status = $statusflow - 1;
                    $jobcard->reject_reason = $sValue;
                    $jobcard->reject_timestamp = time();
                    $jobcard->rejector_id =  Auth::user()->person->id;
                    $jobcard->update();
                    // $vehicle_maintenance->where('id',$iID)->update(['status' => 3],['reject_reason' => $sValue],['reject_timestamp' => time()]);
                    
                     if($statusflow != 0 ){
                     $processflow = processflow::where('step_number', $statusflow - 1)->where('status', 1)->orderBy('step_number','asc')->first();
                     $user = HRPerson::where('position', $processflow->job_title)->pluck('user_id');
                     foreach ($user as $manID) {
                        $usedetails = HRPerson::where('user_id',$manID)->select('first_name', 'surname', 'email')->first();
                        $email = $usedetails->email; $firstname = $usedetails->first_name; $surname = $usedetails->surname; $email = $usedetails->email; $reason = $sValue;
                         Mail::to($email)->send(new DeclinejobstepNotification($firstname, $surname, $email ,$reason ));
                       }
                     }else{
                         //
                     }
                }
            }
        }
        $sReasonToReject = '';
        }
        
       AuditReportsController::store('Job Card Management', 'Approve Job Card', "Accessed By User", 0);
        return back();
     }
    
     public function viewcard(jobcard_maintanance $card){
         
        $vehiclemaintenance = DB::table('jobcard_maintanance')
            ->select('jobcard_maintanance.*','vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type','service_type.name as servicetype',
                    'hr_people.first_name as firstname', 'hr_people.surname as surname','jobcard_process_flow.step_name as aStatus')
            ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
            ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
            ->where('jobcard_maintanance.id' ,$card->id )     
            ->orderBy('jobcard_maintanance.id', 'asc')
            ->get(); 
         
         return $vehiclemaintenance;
         
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search Job Cards';

        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.Job_card_details')->with($data); 
     }
     
}
