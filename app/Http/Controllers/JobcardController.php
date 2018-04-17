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

        AuditReportsController::store('Fleet Management', ' service Type Deleted', "Accessed By User", 0);
      
        return redirect('/jobcards/servicetype');
    
    }
    
    public function configuration(){
        
        $configuration = jobcards_config::first();
        //return $configuration;
        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['configuration'] = $configuration;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Setup';
        
        AuditReportsController::store('Job Card Management', 'Job Card Management Page Accessed', "Accessed By User", 0);
        return view('job_cards.configuration')->with($data); 
    }
}
