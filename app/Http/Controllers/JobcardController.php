<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\servicetype;
use App\HRPerson;
use App\vehicle;
use App\product_category;
use App\jobcard_order_parts;
use App\jobcart_parts;
use App\AuditTrail;
use App\jobcard_category_parts;
use App\jobcard_maintanance;
use App\ContactCompany;
use App\processflow;
use App\jobcardnote;
use App\jobcards_config;
use App\CompanyIdentity;
use App\stock;
use App\stockhistory;
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

        AuditReportsController::store('Job Card Management', 'Job Card Settings Page Accessed', "Accessed By User", 0);
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
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Service Type', "Accessed By User",  0);
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
        
        AuditReportsController::store('Job Card Management', 'New service Type added', "Accessed By User",  $servicetype->id);
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
        AuditReportsController::store('Fleet Management', ' service Type edited', "Accessed By User",  $service->id);
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

        AuditReportsController::store('Job Card Management', ' service Type Deleted', "Accessed By User",  $service->id);
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
        
        AuditReportsController::store('Job Card Management', 'Job Card configuration Page Accessed', "Accessed By User",  0);
        return view('job_cards.configuration')->with($data); 
    }
    public function configurationSetings(Request $request, jobcards_config $config){
        
        $SysData = $request->all();
        unset($SysData['_token']);
        
        $config->use_procurement = !empty($SysData['use_procurement']) ? $SysData['use_procurement'] : 0;
        $config->mechanic_sms = !empty($SysData['mechanic_sms']) ? $SysData['mechanic_sms'] : 0;
        $config->update();

        AuditReportsController::store('Job Card Management', 'configurationSetings updated', "Accessed By User", $config->id);
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
        
        $data['page_title'] = "Job Card Processes";
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
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Process Flow Accessed', "Accessed By User", 0);
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
         
         AuditReportsController::store('Job Card Management', 'New processflow has been added', "Accessed By User", $processflow->id);
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
         
         AuditReportsController::store('Job Card Management', ' process flow edited', "Accessed By User", $steps->id);
        return response()->json();
     }
     
     public function steps_act(processflow $steps){
       if ($steps->status == 1)
            $stastus = 0;
        else
            $stastus = 1;
        $steps->status = $stastus;
        $steps->update();
		
		AuditReportsController::store('Job Card Management', ' process flow status Changed', "Accessed By User",  $steps->id);		
        return back();  
     }
 
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
		$rowcolumn = $processflow->count();
      
        if(($rowcolumn >0 || !empty($userAccess))){
                   
        $ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $servicetype = servicetype::where('status',1)->get();
		$position = DB::table('hr_positions')->where('status',1)->where('name', 'Mechanic')->first();
		if (!empty($position))
			$users  = HRPerson::where('status',1)->where('position', $position->id)->orderBy('id', 'asc')->get(); 
           
		else
			$users = $position; 
        $Status = array(-1=>'Rejected',1 => 'Job Card created',
				 3=>'Completed',6=>'Procurement ',7=>'At Service',
				 8=>'Spare Dispatch',9=>' At Mechanic',10=>'Spares Dispatch Paperwork',
                 11=>'Fleet Manager',12=>'Awaiting Closure',13=>'Closed',14 =>'Pending Cancellation',15=>'Cancelled');
                 
        $currentUser = Auth::user()->person->id;

       //return  $users = !empty($user) ? $user : 1 ;

		$jobcardmaintanance = DB::table('jobcard_maintanance')
		->select('jobcard_maintanance.*','vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
			    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make','vehicle_model.name as vehicle_model',
                            'vehicle_managemnet.name as vehicle_type','service_type.name as servicetype',
                            'hr_people.first_name as firstname', 'hr_people.surname as surname','jobcard_process_flow.step_name as aStatus')
		->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
		->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
		->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
		->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
		->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
		->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
		->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
		->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
		 ->where('jobcard_maintanance.user_id', $currentUser)   
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
		
        $data['current_date'] = time();
        $data['Status'] = $Status;
        $data['users'] = $users;
        $data['ContactCompany'] = $ContactCompany;
        $data['jobcardmaintanance'] = $jobcardmaintanance;
        $data['servicetype'] = $servicetype;
        $data['vehicledetails'] = $vehicledetails;
        $data['configuration'] = $configuration;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';
        
        AuditReportsController::store('Job Card Management', 'Job Card Page Accessed', "Accessed By User", 0);
       return view('job_cards.myjob_cards')->with($data); 
       
       }else  {
           return redirect('/');
			//return back()->with('success_edit', "The are not permitted to view this page.");
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
        
        
         $stadisplay = DB::table('jobcard_process_flow')->where('step_number', 1)->first();
         $statusdisplay = !empty($stadisplay->step_name) ? $stadisplay->step_name : '' ;
         
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
        $jobcardmaintanance->date_default =  time();
        $jobcardmaintanance->user_id = Auth::user()->person->id;
        $jobcardmaintanance->status_display = $statusdisplay;
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
            
        AuditReportsController::store('Job Card Management', ' Job card created', "Accessed By User", $jobcardmaintanance->id);
        return response()->json();
     }
	 
	 public function updateJobCard(Request $request , jobcard_maintanance $jobCard){
        $this->validate($request, [
//              'step_name' => 'required',
//              'job_title' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        
        $carddate = $SysData['card_date'] = str_replace('/', '-', $SysData['card_date']);
        $carddate = $SysData['card_date'] = strtotime($SysData['card_date']);
        
        $scheduledate = $SysData['schedule_date'] = str_replace('/', '-', $SysData['schedule_date']);
        $scheduledate = $SysData['schedule_date'] = strtotime($SysData['schedule_date']);
        
        $bookingdate = $SysData['booking_date'] = str_replace('/', '-', $SysData['booking_date']);
        $bookingdate = $SysData['booking_date'] = strtotime($SysData['booking_date']);
        
        $completiondate = $SysData['completion_date'] = str_replace('/', '-', $SysData['completion_date']);
        $completiondate = $SysData['completion_date'] = strtotime($SysData['completion_date']);
        
        $jobCard->vehicle_id = !empty($SysData['vehicle_id']) ? $SysData['vehicle_id'] : 0;
        $jobCard->card_date = !empty($carddate) ?$carddate : 0;
        $jobCard->schedule_date = !empty($scheduledate) ?$scheduledate : 0;
        $jobCard->booking_date = !empty($bookingdate) ? $bookingdate : 0;
        $jobCard->supplier_id = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
        $jobCard->service_type = !empty($SysData['service_type']) ? $SysData['service_type'] : 0;
        $jobCard->estimated_hours = !empty($SysData['estimated_hours']) ? $SysData['estimated_hours'] : 0;
        $jobCard->service_time = !empty($SysData['service_time']) ? $SysData['service_time'] : 0;
        $jobCard->machine_hour_metre = !empty($SysData['machine_hour_metre']) ? $SysData['machine_hour_metre'] : 0;
        $jobCard->machine_odometer = !empty($SysData['machine_odometer']) ? $SysData['machine_odometer'] : 0;
        $jobCard->last_driver_id = !empty($SysData['last_driver_id']) ? $SysData['last_driver_id'] : 0;
        $jobCard->inspection_info = !empty($SysData['inspection_info']) ? $SysData['inspection_info'] : '';
        $jobCard->mechanic_id = !empty($SysData['mechanic_id']) ? $SysData['mechanic_id'] : 0;
        $jobCard->instruction = !empty($SysData['instruction']) ? $SysData['instruction'] : '';
        $jobCard->completion_date = $completiondate;
        $jobCard->date_default =  time();
        $jobCard->user_id = Auth::user()->person->id;
        $jobCard->update();
        
        //Upload supporting document
        if ($request->hasFile('inspection_file_upload')) {
            $fileExt = $request->file('inspection_file_upload')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'tiff']) && $request->file('inspection_file_upload')->isValid()) {
                $fileName = $jobCard->id . "_inspection_file_upload." . $fileExt;
                $request->file('inspection_file_upload')->storeAs('Jobcard/inspectionfileupload', $fileName);
                //Update file name in the table
                $jobCard->inspection_file_upload = $fileName;
                $jobCard->update();
            }
        }
        
        //Upload supporting document
        if ($request->hasFile('inspection_file_upload')) {
            $fileExt = $request->file('service_file_upload')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'tiff']) && $request->file('service_file_upload')->isValid()) {
                $fileName = $jobCard->id . "_service_file_upload." . $fileExt;
                $request->file('service_file_upload')->storeAs('Jobcard/servicefileupload', $fileName);
                //Update file name in the table
                $jobCard->service_file_upload = $fileName;
                $jobCard->update();
            }
        }    
        
        AuditReportsController::store('Job Card Management', ' Job card Updated', "Accessed By User", $jobCard->id);
        return response()->json();

     }
     
     public function cardsearch(){
           
        $processflow = processflow::orderBy('id', 'asc')->get();
       // return  $processflow ;
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['processflow'] = $processflow;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Job Card Management', 'Job Card card search Page Accessed', "Accessed By User", 0);
        return view('job_cards.search')->with($data); 
     }
     public function jobcardsearch(Request $request){
    
       $SysData = $request->all();
       unset($SysData['_token']);
       
       
       $jobcard = $request['jobcard_id'];
       $fleetnumber = $request['fleet_number'];
       $registrationNo = $request['registration_no'];
       $status = $request['status'];
       $servicetypeID = $request['service_type_id'];
       $mechanicID = $request['mechanic_id'];
       
       //$jobcardmaintanance =  jobcard_maintanance::orderBy('id','desc')->get();
       
       $actionFrom = $actionTo = 0;
       $actionDate = $request['date'];
       if (!empty($actionDate)) {
           $startExplode = explode('-', $actionDate);
           $actionFrom = strtotime($startExplode[0]);
           $actionTo = strtotime($startExplode[1]);
          
       }

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
        //    ->where(function ($query) use ($actionFrom, $actionTo) {
        //        if ($actionFrom > 0 && $actionTo > 0) {
        //                    $query->whereBetween('jobcard_maintanance.date_default', [$actionFrom, $actionTo]);
        //                }
        //            })
                        ->where(function ($query) use ($jobcard) {
                            if (!empty($jobcard)) {
                                $query->where('jobcard_maintanance.jobcard_number',$jobcard);
                            }
                        })
                        ->where(function ($query) use ($fleetnumber) {
                            if (!empty($fleetnumber)) {
                                $query->where('vehicle_details.fleet_number', 'ILIKE', "%$fleetnumber%");
                            }
                        })
                        ->where(function ($query) use ($registrationNo) {
                            if (!empty($registrationNo)) {
                                $query->where('vehicle_details.vehicle_registration', 'ILIKE', "%$registrationNo%");
                            }
                        })
                        ->where(function ($query) use ($status) {
                            if (!empty($status)) {
                                $query->where('jobcard_process_flow.id',$status);
                            }
                        })
                        ->orderBy('jobcard_maintanance.id', 'asc')
                        ->get(); 

             //  return $jobcardmaintanance;
                    
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['jobcardmaintanance'] = $jobcardmaintanance;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Job Card Management', 'Job Card Search Page Accessed', "Accessed By User", 0);
        return view('job_cards.search_results')->with($data); 
     }
       
     public function jobcardsApprovals(){
		
		$hrID = Auth::user()->person->user_id;
		$hrjobtile = Auth::user()->person->position;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id') 
					   ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
					   ->where('security_modules.code_name', 'job_cards') 
					   ->where('security_modules_access.access_level','>=', 4)
					   ->where('security_modules_access.user_id', $hrID)
					   ->pluck('user_id')->first();
						
		$processflow = processflow::where('job_title',$hrjobtile)->where('status' , 1)->orderBy('id','asc')->get();
		$lastProcess = processflow::where('job_title',$hrjobtile)->where('status' , 1)->orderBy('id','desc')->first();
		$lastStepNumber = !empty($lastProcess->step_number) ? $lastProcess->step_number : 0;
		$statuses = array();
		$status = '';
		
		$processss = processflow::take(1);
		$rowcolumn = $processflow->count();
		if($rowcolumn >0 || !empty($userAccess)){
			
			if (!empty($userAccess)) $statuses = array();
			else 
			{
				foreach ($processflow as $process)
				{
					$status .= $process->step_number.',';
				}
				$status = rtrim($status, ",");
				$statuses = (explode(",",$status));						
			}
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
				->where(function ($query) use ($statuses) {
					if (!empty($statuses)) {
						for ($i = 0; $i < count($statuses); $i++) {
							$query->whereOr('jobcard_maintanance.status', '=', $statuses[$i]);
						}
					}
				})
				->where(function ($query) use ($lastStepNumber) {
					if (!empty($lastStepNumber)) {
						$query->where('jobcard_maintanance.status','!=', $lastStepNumber);
					}
				})			
				->orderBy('jobcard_maintanance.id', 'asc')
				->get(); 

			$steps =  processflow::latest()->first();     
			$stepnumber = !empty($steps->step_number) ? $steps->step_number : 0  ; 
			
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
		else  {
//           return redirect('/');
			//return back();
			return back()->with('success_edit', "The are not permitted to view this page.");
		}

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
               
               
               $stadisplay = DB::table('jobcard_process_flow')->where('step_number', $processflow->step_number)->first();
                $statusdisplay = !empty($stadisplay->step_name) ? $stadisplay->step_name : '' ;
         
               DB::table('jobcard_maintanance')->where('id', $cardsID)->update(['status_display' => $statusdisplay]);
                   
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
        
       AuditReportsController::store('Job Card Management', 'Job card Approvals Page', "Accessed By User", $jobcards->id);
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
         
         //return $vehiclemaintenance;
         
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['jobcard'] = $jobcard;
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search Job Cards';

        AuditReportsController::store('Job Card Management', 'My Job Card Page Accessed', "Accessed By User", $card->id);
        return view('job_cards.Job_card_details')->with($data); 
     }

     public function viewjobcard(jobcard_maintanance $card){
			
        $ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $servicetype = servicetype::where('status',1)->get();
		$position = DB::table('hr_positions')->where('status',1)->where('name', 'Mechanic')->first();
		if (!empty($position))
			$users = HRPerson::where('status',1)->where('position',$position->id)->orderBy('id', 'asc')->get(); 
		else 
			$users = $position; 
        $Status = array(-1=>'Rejected',1 => 'Job Card created',
				 3=>'Completed',6=>'Procurement ',7=>'At Service',
				 8=>'Spare Dispatch',9=>' At Mechanic',10=>'Spares Dispatch Paperwork',
                 11=>'Fleet Manager',12=>'Awaiting Closure',13=>'Closed',14 =>'Pending Cancellation',15=>'Cancelled');
		
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
        
        $jobcard = DB::table('jobcard_maintanance')
            ->select('jobcard_maintanance.*','vehicle_details.*',
                    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type','service_type.name as servicetype',
                    'hr_people.first_name as me_firstname', 'hr_people.surname as me_surname',
					'hrp.first_name as dr_firstname', 'hrp.surname as dr_surname')
            ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
            ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            //->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hrp', 'jobcard_maintanance.last_driver_id', '=', 'hrp.id')
            ->where('jobcard_maintanance.id' ,$card->id )     
            ->orderBy('jobcard_maintanance.id', 'asc')
            ->get();
		
        $configuration = jobcards_config::first(); 
	    $data['configuration'] = $configuration;
        $data['users'] = $users;
        $data['jobcard'] = $jobcard;
        $data['ContactCompany'] = $ContactCompany;
        //$data['jobcardmaintanance'] = $jobcardmaintanance;
        $data['servicetype'] = $servicetype;
        $data['vehicledetails'] = $vehicledetails;
       // $data['jobcards'] = $jobcards;
        $data['card'] =$card;
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search Job Cards';

        AuditReportsController::store('Job Card Management', 'View Job Cards Page Accessed', "Accessed By User", $card->id);
        return view('job_cards.Job_card_details')->with($data);
     }
     public function viewjobcardnotes(jobcard_maintanance $card ){
         
        //return $card;
        $jobcardnote = DB::table('jobcard_notes')
        ->select('jobcard_notes.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
        ->leftJoin('hr_people', 'jobcard_notes.user_id', '=', 'hr_people.id')
        ->where('jobcard_id', $card->jobcard_number)
        ->Orderby('jobcard_notes.id','asc')
        ->get();
       // return $jobcardnote;

                                

        $data['card'] = $card;
        $data['jobcardnote'] = $jobcardnote;
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search Job Cards';

        AuditReportsController::store('Job Card Management', 'view Jobcardnotes', "Accessed By User", $card->id);
        return view('job_cards.add_jocard_notes')->with($data);
     }

     public function addjobcardnotes(Request $request){
        $this->validate($request, [
              'notes' => 'required',
              
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $jobcardnote =  new jobcardnote();
        $jobcardnote->note_details = !empty($SysData['notes']) ? $SysData['notes'] : '';
        $jobcardnote->vehicle_id = !empty($SysData['vehicle_id']) ? $SysData['vehicle_id'] : 0;
        $jobcardnote->jobcard_id = !empty($SysData['jobcard_id']) ? $SysData['jobcard_id'] : 0;
        $jobcardnote->user_id =  Auth::user()->person->id;
        $jobcardnote->date_default = time();
        $jobcardnote->save(); 


//        $jobcard_number = $SysData['vehicle_id'];
//           // emils
//        $users = DB::table('jobcard_maintanance')
//        ->select('jobcard_maintanance.*', 'jobcard_notes.user_id as user')
//        ->leftJoin('jobcard_notes', 'jobcard_maintanance.jobcard_number', '=', 'jobcard_notes.id')
//        ->where('jobcard_number', $jobcard_number)
//        ->where('jobcard_notes.jobcard_id', $jobcard_number)
//         ->pluck('user_id');
       // ->get();
        //give me the person who created the card

        //foreach ($user as $manID) {

       // }
        AuditReportsController::store('Job Card Management', ' Job card note created', "Accessed By User", $jobcardnote->id);
        return response()->json();
     }
     
   //jobcard parts
   
   public function jobcardparts(){
     
        $parts =  jobcard_category_parts::OrderBy('id','asc')->get();
      //  return $parts;
        $data['parts'] = $parts;
        $data['page_title'] = "Job Card Catergory";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Parts';

        AuditReportsController::store('Job Card Management', 'view job card parts', "Accessed By User", 0);
        return view('job_cards.add_jobcard_category')->with($data);
   }
   
   public function addpartscatergory(Request $request){
       $this->validate($request, [
             'name' => 'required',
             'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $parts =  new jobcard_category_parts();
        $parts->name = !empty($SysData['name']) ? $SysData['name'] : '';
        $parts->description = !empty($SysData['description']) ? $SysData['description'] : '';
        $parts->status = 1;
        $parts->save(); 

        AuditReportsController::store('Job Card Management', ' new parts catergory created', "Accessed By User",  $parts->id);
        return response()->json();
   }
   
   public function editpartscatagory(Request $request ,jobcard_category_parts $parts){
      $this->validate($request, [
             
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

       //$parts =  new jobcard_category_parts();
        $parts->name = !empty($SysData['name']) ? $SysData['name'] : '';
        $parts->description = !empty($SysData['description']) ? $SysData['description'] : '';
        $parts->status = 1;
        $parts->update(); 

        AuditReportsController::store('Job Card Management', 'parts catergory  edited', "Accessed By User",  $parts->id);
        return response()->json();  
   }

   public function jobcat_act(jobcard_category_parts $parts){
       if ($parts->status == 1)
            $stastus = 0;
        else
            $stastus = 1;
        $parts->status = $stastus;
        $parts->update();
		
        AuditReportsController::store('Job Card Management', 'parts catergory  status Changed', "Accessed By User",  $parts->id);		
        return back(); 
   }
   
   public function deletepartscatergory(jobcard_category_parts $parts){ 
       $parts->delete();
      
       // delete every parts assacoited with parts_catergory
        DB::table('jobcard_parts')
                    ->where('category_id', $parts->id)
                    ->delete(); 
      
        AuditReportsController::store('Job Card Management', ' parts catergory Deleted', "Accessed By User", $parts->id);
        return back();
       // return redirect('/jobcards/servicetype');
   }


   public function viewjobcardparts(Request $request , jobcard_category_parts $parts ){
      
        $jobcartparts =  jobcart_parts::OrderBy('id','asc')->where('category_id', $parts->id)->get();
        
        $data['parts'] = $parts;
        $data['jobcartparts'] = $jobcartparts;
        $data['page_title'] = "Job Card Catergory";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Parts';

        AuditReportsController::store('Job Card Management', 'view Job card parts ', "Accessed By User",  $parts->id);
        return view('job_cards.add_jobcard_parts')->with($data); 
   }
   
   public function addjobcardparts(Request $request){
      $this->validate($request, [
             'name' => 'required',
             'description' => 'required',
             'no_of_parts_available' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

         $jobcartparts =  new jobcart_parts();
         $jobcartparts->name = !empty($SysData['name']) ? $SysData['name'] : '';
         $jobcartparts->description = !empty($SysData['description']) ? $SysData['description'] : '';
         $jobcartparts->status =  1;
         $jobcartparts->no_of_parts_available = !empty($SysData['no_of_parts_available']) ? $SysData['no_of_parts_available'] : 0;
         $jobcartparts->category_id = !empty($SysData['category_id']) ? $SysData['category_id'] : 0;
         $jobcartparts->save();
         
          AuditReportsController::store('Job Card Management', 'new job card part created', "Accessed By User", $jobcartparts->id);
        return response()->json();
   }
   
   public function editcardparts(Request $request ,jobcart_parts $parts ){
       $this->validate($request, [
             'name' => 'required',
             'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
 
         $parts->name = !empty($SysData['name']) ? $SysData['name'] : '';
         $parts->description = !empty($SysData['description']) ? $SysData['description'] : '';
         $parts->no_of_parts_available = !empty($SysData['no_of_parts_available']) ? $SysData['no_of_parts_available'] : 0;;
         $parts->update();
         
          AuditReportsController::store('Job Card Management', ' Job card parts edited', "Accessed By User", $parts->id);
        return response()->json(); 
   }
   public function parts_act(jobcart_parts $parts){
       if ($parts->status == 1)
            $stastus = 0;
        else
            $stastus = 1;
        $parts->status = $stastus;
        $parts->update();
		
        AuditReportsController::store('Job Card Management', 'Job card parts status Changed', "Accessed By User", $parts->id);		
        return back(); 
   }
   
   public function deletejobcards(jobcart_parts $parts){
       $parts->delete();
        AuditReportsController::store('Job Card Management', ' Job card parts deleted', "Accessed By User",  $parts->id);
        return back();
   }
   
   public function viewparts(jobcard_maintanance $jobcardparts ){
      
      
       // $parts = jobcard_order_parts::orderBy('id','asc')->get();
        $parts = stock::Orderby('id', 'asc')->get();
        $jobCategories = product_category::orderBy('id', 'asc')->get();
       // return $jobCategories;
       
        $parts =   DB::table('Product_products')
            ->select('Product_products.*', 'stock.avalaible_stock')
            ->leftJoin('stock', 'Product_products.id', '=', 'stock.product_id')
            ->whereBetween('Product_products.stock_type' ,[1,3] )     
            ->get();
       
        
//        $parts = DB::table('jobcard__order_parts')
//        ->select('jobcard__order_parts.*', 'jobcard_parts.*')
//        ->leftJoin('jobcard_parts', 'jobcard__order_parts.jobcard_parts_id', '=', 'jobcard_parts.id')
//        ->where('jobcard__order_parts.jobcard_card_id' , $jobcardparts->id)
//        ->get();
        

        
        $cardparts = jobcard_category_parts::orderBy('id','asc')->get();
        $jobcard_category_parts = jobcard_category_parts::orderBy('id','asc')->get()->load(['jobcart_parts_model' => function($query) {
                $query->orderBy('name', 'asc');
            }]);
       
            
        $data['jobCategories'] = $jobCategories;
        $data['parts'] = $parts;
        $data['cardparts'] = $cardparts;
        $data['jobcardparts'] = $jobcardparts;
        $data['page_title'] = "Job Card Catergory";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Parts';

        AuditReportsController::store('Job Card Management', 'view Job card parts ', "Accessed By User", $jobcardparts->id);
        return view('job_cards.add_parts')->with($data); 
   }
   
   public function addjobparts(Request $request){
        
        $SysData = $request->all();
        unset($SysData['_token']);
        
        $jobcartparts = stock::where('category_id', $SysData['product_id'])->where('product_id' , $SysData['category_id'])->first();
        $availblebalance = !empty($jobcartparts->avalaible_stock) ? $jobcartparts->avalaible_stock : 0 ;
         
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'jobcard_parts_id' => 'required',
            'no_of_parts_used' => 'bail|required|integer|min:0',
           
        ]);
        $validator->after(function ($validator) use($request) {
        
       $jobcartparts = stock::where('category_id', $SysData['product_id'])->where('product_id' , $SysData['category_id'])->first();
        $availblebalance = !empty($jobcartparts->avalaible_stock) ? $jobcartparts->avalaible_stock : 0 ;
         if ($availblebalance < $request->input('no_of_parts_used')) {
               $validator->errors()->add('no_of_parts_used', 'this field can be less than the required ');
            }   
        });
       
//        if ($validator->fails()) {
//            return redirect('/education/registration')
//                ->withErrors($validator)
//                ->withInput();
//        }
        $transactionbalance = $availblebalance - $SysData['no_of_parts_used'];
        if ($transactionbalance > 0)
		{
			$currentparts = new jobcard_order_parts();
			$currentparts->category_id =  !empty($SysData['product_id']) ? $SysData['product_id'] : 0;
			$currentparts->product_id =  !empty($SysData['category_id']) ? $SysData['category_id'] : 0;
			$currentparts->no_of_parts_used =  !empty($SysData['no_of_parts_used']) ? $SysData['no_of_parts_used'] : 0;
			$currentparts->jobcard_card_id =  !empty($SysData['jobcard_card_id']) ? $SysData['jobcard_card_id'] : 0;
			$currentparts->avalaible_transaction = $transactionbalance;
			$currentparts->created_by = Auth::user()->person->position;
			$currentparts->date_created = time();
			$currentparts->status = 1;
			$currentparts->save();
			
			// have to try to limit the user from going beyond 0
			DB::table('jobcard__order_parts')->where('jobcard_card_id', $SysData['jobcard_card_id'])->where('category_id', $SysData['product_id'])->update(['avalaible_transaction' => $transactionbalance]);
						  
			DB::table('jobcard_parts')->where('id', $SysData['category_id'])->where('category_id', $SysData['product_id'])->update(['no_of_parts_available' => $transactionbalance]);

            DB::table('stock')->where('product_id', $SysData['category_id'])->where('category_id', $SysData['product_id'])->update(['avalaible_stock' => $transactionbalance]);

                    $history = new stockhistory();
                    $history->product_id = !empty($SysData['product_id']) ? $SysData['product_id'] : 0;
                    $history->category_id = !empty($SysData['product_id']) ? $SysData['product_id'] : 0;
                    $history->avalaible_stock = $transactionbalance;
                    $history->action_date = time();
                    $history->balance_before = $availblebalance;
                    $history->balance_after =  $transactionbalance;
                    $history->action = 'new storck added';
                    $history->user_id = Auth::user()->person->id;
                    $history->user_allocated_id = 0;
                    $history->vehicle_id = 0;
                    $history->save();
					
                        
			AuditReportsController::store('Job Card Management', ' Job card parts edited', "Accessed By User", 0);
			return response()->json();
		}
		else
			return response()->json(); 
   }
   
   
   public function printcards(Request $request, jobcard_maintanance $print ){
           $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        //return $results;

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'cards'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $cardID = $aValue[1];
                //$card = $aValue[2];
              // return $cardID === 2;
                
                
                 if($cardID == 2 ){ //jobcard
                     
                $vehiclemaintenance = DB::table('jobcard_maintanance')
                        ->select('jobcard_maintanance.*','vehicle_details.*',
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
                        ->where('jobcard_maintanance.id' ,$print->id )     
                        ->orderBy('jobcard_maintanance.id', 'asc')
                        ->get();   
                  
               // return $vehiclemaintenance ;
                  
                    $data['vehiclemaintenance'] = $vehiclemaintenance;
                    $data['page_title'] = " Fleet Management ";
                    $data['page_description'] = "Fleet Cards Report ";
                    $data['breadcrumb'] = [
                        ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                        ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
                    ];

                    $data['active_mod'] = 'Fleet Management';
                    $data['active_rib'] = 'Reports';

                    $companyDetails = CompanyIdentity::systemSettings();
                    $companyName = $companyDetails['company_name'];
                    $user = Auth::user()->load('person');

                    $data['support_email'] = $companyDetails['support_email'];
                    $data['company_name'] = $companyName;
                    $data['full_company_name'] = $companyDetails['full_company_name'];
                    $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
                    $data['date'] = date("d-m-Y");
                    $data['user'] = $user;

                    AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
                    return view('job_cards.jobcard_report_print')->with($data);
                    
                }elseif($cardID == 3){ //cardsjobcardsnotes
                    
                     $vehiclemaintenance = DB::table('jobcard_maintanance')
                        ->select('jobcard_maintanance.*','vehicle_details.*',
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
                        ->where('jobcard_maintanance.id' ,$print->id )     
                        ->orderBy('jobcard_maintanance.id', 'asc')
                        ->get();   
                  
               // return $vehiclemaintenance ;
                  
                    $data['vehiclemaintenance'] = $vehiclemaintenance;
                    $data['page_title'] = " Fleet Management ";
                    $data['page_description'] = "Fleet Cards Report ";
                    $data['breadcrumb'] = [
                        ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                        ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
                    ];

                    $data['active_mod'] = 'Fleet Management';
                    $data['active_rib'] = 'Reports';

                    $companyDetails = CompanyIdentity::systemSettings();
                    $companyName = $companyDetails['company_name'];
                    $user = Auth::user()->load('person');

                    $data['support_email'] = $companyDetails['support_email'];
                    $data['company_name'] = $companyName;
                    $data['full_company_name'] = $companyDetails['full_company_name'];
                    $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
                    $data['date'] = date("d-m-Y");
                    $data['user'] = $user;

                    AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
                    return view('job_cards.jobcard_notes_report_print')->with($data);
                    //
                }elseif($cardID == 4){ //cardsaudit
                   
                     $vehiclemaintenance = AuditTrail::Orderby('id','asc')->where('module_name' ,'Job Card Management')->get();
                    // return $vehiclemaintenance;
                     
                    $data['vehiclemaintenance'] = $vehiclemaintenance;
                    $data['page_title'] = " Fleet Management ";
                    $data['page_description'] = "Fleet Cards Report ";
                    $data['breadcrumb'] = [
                        ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                        ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
                    ];

                    $data['active_mod'] = 'Fleet Management';
                    $data['active_rib'] = 'Reports';

                    $companyDetails = CompanyIdentity::systemSettings();
                    $companyName = $companyDetails['company_name'];
                    $user = Auth::user()->load('person');

                    $data['support_email'] = $companyDetails['support_email'];
                    $data['company_name'] = $companyName;
                    $data['full_company_name'] = $companyDetails['full_company_name'];
                    $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
                    $data['date'] = date("d-m-Y");
                    $data['user'] = $user;

                    AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
                    return view('job_cards.jobcard_audit_report_print')->with($data);
                }

             }
             
        }
   }

   public function canceljobcardnotes(jobcard_maintanance $card){
         
       //  return $card;
         
         $hrID = Auth::user()->person->user_id;
		$hrjobtile = Auth::user()->person->position;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id') 
					   ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
					   ->where('security_modules.code_name', 'job_cards') 
					   ->where('security_modules_access.access_level','>=', 4)
					   ->where('security_modules_access.user_id', $hrID)
					   ->pluck('user_id')->first();
						
		$processflow = processflow::where('job_title',$hrjobtile)->where('status' , 1)->orderBy('id','asc')->get();
		$lastProcess = processflow::where('job_title',$hrjobtile)->where('status' , 1)->orderBy('id','desc')->first();
                
               // return $userAccess;
		$statuses = array();
		$status = '';
		
		$processss = processflow::take(1);
		$rowcolumn = $processflow->count();
		if($rowcolumn >0 || !empty($userAccess)){
			
			if (!empty($userAccess)) $statuses = array();
			else 
			{
				foreach ($processflow as $process)
				{
					$status .= $process->step_number.',';
				}
				$status = rtrim($status, ",");;
				$statuses = (explode(",",$status));						
			}
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
				->where(function ($query) use ($statuses) {
					if (!empty($statuses)) {
						for ($i = 0; $i < count($statuses); $i++) {
							$query->whereOr('jobcard_maintanance.status', '=', $statuses[$i]);
						}
					}
				})
				->where('jobcard_maintanance.status','!=', $lastProcess->step_number)				
				->orderBy('jobcard_maintanance.id', 'asc')
				->get(); 

			return $jobcardmaintanance;
                        
                        
         // get the person who created the ticket
     }
   }
   
   public function viewreport(){
       
        $processflow = processflow::orderBy('id','asc')->get();
        $jobCategories = product_category::orderBy('id', 'asc')->get();

        $jobcardmaintanance = DB::table('jobcard_maintanance')
        ->select('jobcard_maintanance.*','vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make','vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type','service_type.name as servicetype',
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
        
        $users = HRPerson::OrderBy('id','asc')->get();
       

        $vehicledetails =  DB::table('vehicle_details')
            ->select('vehicle_details.id as id','vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->get();  

      

        $data['jobCategories'] = $jobCategories;
        $data['vehicledetails'] = $vehicledetails;
        $data['processflow'] = $processflow;
        $data['users'] = $users;
        $data['page_title'] = "Job Card Reports";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Job Card Management', 'view Job card parts ', "Accessed By User", 0);
        return view('job_cards.search_report_index')->with($data);   
   }


   public function cards(Request $request){

        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);

        $SysData = $request->all();
         unset($SysData['_token']);
         
       //  return $SysData;
         
         $applicationType = $SysData['application_type'];
         $processID = $SysData['process_id'];
         $vehicleID = $SysData['vehicle_id'];
         $application_type = $SysData['application_type'];
         $actionDate = $SysData['action_date'];
                    
           $actionFrom = $actionTo = 0;
           $actionDate = $request['date'];
           if (!empty($actionDate)) {
               $startExplode = explode('-', $actionDate);
               $actionFrom = strtotime($startExplode[0]);
               $actionTo = strtotime($startExplode[1]);
              
           }
       
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
                     ->where(function ($query) use ($processID) {
                                 if (!empty($processID)) {
                                     $query->where('jobcard_process_flow.step_name','ILIKE', "%$processID%");
                                     
                                 }
                             })
                     ->where(function ($query) use ($vehicleID) {
                                 if (!empty($vehicleID)) {
                                     $query->where('jobcard_maintanance.vehicle_id', $vehicleID);
                                 }
                             })
                    
                     ->where(function ($query) use ($actionFrom, $actionTo) {
                        if ($actionFrom > 0 && $actionTo > 0) {
                                    $query->whereBetween('jobcard_maintanance.card_date', [$actionFrom, $actionTo]);
                                }
                            })        
                            
                     ->orderBy('jobcard_maintanance.id', 'asc')
                     ->get();   

                    $data['vehiclemaintenance'] = $vehiclemaintenance;
                    $data['page_title'] = "Job Card Processes";
                    $data['page_description'] = "Job Card Management";
                    $data['breadcrumb'] = [
                        ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                        ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
                    ];

                    $data['active_mod'] = 'Job Card Management';
                    $data['active_rib'] = 'Reports';

                    $companyDetails = CompanyIdentity::systemSettings();
                    $companyName = $companyDetails['company_name'];
                    $user = Auth::user()->load('person');

                    $data['support_email'] = $companyDetails['support_email'];
                    $data['company_name'] = $companyName;
                    $data['full_company_name'] = $companyDetails['full_company_name'];
                    $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
                    $data['date'] = date("d-m-Y");
                    $data['user'] = $user;

                    AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
                    return view('job_cards.Jobcard_card')->with($data);    
   }
   
            public function printscard( jobcard_maintanance $card){
                   
//                return $card;
                
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
                
                
                    $data['vehiclemaintenance'] = $vehiclemaintenance;
                    $data['page_title'] = "Job Card Reports";
                    $data['page_description'] = "Job Card Management";
                    $data['breadcrumb'] = [
                        ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                        ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
                    ];

                    $data['active_mod'] = 'Job Card Management';
                    $data['active_rib'] = 'Reports';

                    $companyDetails = CompanyIdentity::systemSettings();
                    $companyName = $companyDetails['company_name'];
                    $user = Auth::user()->load('person');

                    $data['support_email'] = $companyDetails['support_email'];
                    $data['company_name'] = $companyName;
                    $data['full_company_name'] = $companyDetails['full_company_name'];
                    $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
                    $data['date'] = date("d-m-Y");
                    $data['user'] = $user;

                    AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
                    return view('job_cards.jobcard_print')->with($data);  
                        
            }
            
      public function parts(Request $request){
                $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);

        $SysData = $request->all();
         unset($SysData['_token']);
         
       //  return $SysData;
         
         $categoryID = $SysData['product_id'];
         $productID = $SysData['category_id'];
         $actionDate = $SysData['action_date'];
                    
           $actionFrom = $actionTo = 0;
           $actionDate = $request['date'];
           if (!empty($actionDate)) {
               $startExplode = explode('-', $actionDate);
               $actionFrom = strtotime($startExplode[0]);
               $actionTo = strtotime($startExplode[1]);
              
           }
           
           //jobcard__order_parts  
                            $parts =  DB::table('jobcard_maintanance')
                                        ->select('jobcard_maintanance.*','jobcard__order_parts.*','Product_products.name as product_name',
                                                'hr_people.first_name as firstname', 'hr_people.surname as surname' ,'vehicle_details.fleet_number as fleet_no',
                                                 'vehicle_details.vehicle_registration as vehicleregistration','service_type.name as servicetype')
                                     ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
                                     ->leftJoin('hr_people', 'jobcard_maintanance.user_id', '=', 'hr_people.id')
                                     ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id') 
                                     ->leftJoin('jobcard__order_parts', 'jobcard__order_parts.jobcard_card_id', '=', 'jobcard_maintanance.id')
                                     ->leftJoin('Product_products', 'jobcard__order_parts.product_id', '=', 'Product_products.id')
                                    ->where(function ($query) use ($categoryID) {
                                 if (!empty($categoryID)) {
                                     $query->where('jobcard__order_parts.category_id', $categoryID);
                                     
                                 }
                             })
                                ->where(function ($query) use ($productID) {
                                               if (!empty($productID)) {
                                                   $query->where('jobcard__order_parts.product_id', $productID);

                                               }
                                           })

                                ->where(function ($query) use ($actionFrom, $actionTo) {
                                      if ($actionFrom > 0 && $actionTo > 0) {
                                                  $query->whereBetween('jobcard_maintanance.date_created', [$actionFrom, $actionTo]);
                                              }
                                          })    
                                 ->where('jobcard__order_parts.jobcard_card_id','>',0)
                                 ->where('jobcard__order_parts.product_id','>',0)
                                 ->OrderBy('jobcard__order_parts.id' ,'asc')
                                 ->get();
                                          
                                           
                     //  return $parts;
                           
                    $data['parts'] = $parts;
                    $data['page_title'] = "Job Card Processes";
                    $data['page_description'] = "Job Card Management";
                    $data['breadcrumb'] = [
                        ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                        ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
                    ];

                    $data['active_mod'] = 'Job Card Management';
                    $data['active_rib'] = 'Reports';

                    $companyDetails = CompanyIdentity::systemSettings();
                    $companyName = $companyDetails['company_name'];
                    $user = Auth::user()->load('person');

                    $data['support_email'] = $companyDetails['support_email'];
                    $data['company_name'] = $companyName;
                    $data['full_company_name'] = $companyDetails['full_company_name'];
                    $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
                    $data['date'] = date("d-m-Y");
                    $data['user'] = $user;

                    AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
                    return view('job_cards.Jobcard_parts')->with($data); 
           
        }
            
             public function notes(Request $request){
                        $this->validate($request, [
                    // 'date_uploaded' => 'required',
                ]);

                    $SysData = $request->all();
              unset($SysData['_token']);

              //return $SysData;
              $noteDetails = $SysData['note_details'];
              $userID = $SysData['user_id'];
              
              $vehicleID = $SysData['vehicle'];
              

                $actionFrom = $actionTo = 0;
                $actionDate = $request['datenote_date'];
                if (!empty($actionDate)) {
                    $startExplode = explode('-', $actionDate);
                    $actionFrom = strtotime($startExplode[0]);
                    $actionTo = strtotime($startExplode[1]);

                }
                
                 $notes =  DB::table('jobcard_notes')
                         ->select('jobcard_notes.*','hr_people.first_name as firstname', 'hr_people.surname as surname' ,'vehicle_details.fleet_number as fleet_no',
                                 'vehicle_details.vehicle_registration as vehicleregistration')
                        
                         ->leftJoin('hr_people', 'jobcard_notes.user_id', '=', 'hr_people.id')
                         ->leftJoin('vehicle_details', 'jobcard_notes.vehicle_id', '=', 'vehicle_details.id')
                         ->where(function ($query) use ($userID) {
                                  if (!empty($userID)) {
                                     $query->where('jobcard_notes.user_id', $userID);
                                      }
                             })
                           ->where(function ($query) use ($noteDetails) {
                                  if (!empty($noteDetails)) {
                                     $query->where('jobcard_notes.note_details', 'ILIKE', "%$noteDetails%");
                                      }
                             })               
                           ->where(function ($query) use ($vehicleID) {
                                  if (!empty($vehicleID)) {
                                     $query->where('jobcard_notes.vehicle_id', "$vehicleID");
                                      }
                             })               
                         ->get();
                           
                  
                             
                    $data['notes'] = $notes;
                    $data['page_title'] = "Job Card Processes";
                    $data['page_description'] = "Job Card Management";
                    $data['breadcrumb'] = [
                        ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                        ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
                    ];

                    $data['active_mod'] = 'Job Card Management';
                    $data['active_rib'] = 'Reports';

                   
                    AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
                    return view('job_cards.Jobcard_notes')->with($data); 
                    
                 }
}

