<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\HRPerson;
use App\vehicle_detail;
use App\vehicle;
use App\vehicle_config;
use App\servicetype;
use App\processflow;
use App\jobcards_config;
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
         //$incidentType = incident_type::orderBy('id', 'asc')->get();

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
        
        
        $servicetype = servicetype::where('status',1)->get();
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
    
    //myJobcards
     public function myjobcards(){
         
        $configuration = jobcards_config::first(); 
        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['configuration'] = $configuration;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.myjob_cards')->with($data);    
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
        
        return back();  
     }
}
