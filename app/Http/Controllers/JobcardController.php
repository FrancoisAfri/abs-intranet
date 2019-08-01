<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\servicetype;
use App\HRPerson;
use App\vehicle;
use App\images;
use App\KitJoinProducts;
use App\kitProducts;
use App\product_category;
use App\JobCardHistory;
use App\jobcard_order_parts;
use App\jobcart_parts;
use App\AuditTrail;
use App\vehicle_detail;
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
use App\JobCardInstructions;
use App\HRUserRoles;
use App\modules;
use App\Mail\NoteCommunications;
use App\Mail\NextjobstepNotification;
use App\Mail\MechanicEmailJC;
use App\Mail\DeclinejobstepNotification;
use Illuminate\Http\Request;
use App\Mail\confirm_collection;
use App\Http\Controllers\BulkSMSController;
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

        AuditReportsController::store('Job Card Management', 'Job Card Settings Page Accessed', "view Jobcard Settings", 0);
        return view('job_cards.setup')->with($data);
    }

    public function servicetype()
    {
        $servicetype = servicetype::orderBy('name', 'asc')->get();
        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['servicetype'] = $servicetype;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Job Card Management', 'Job Card Management Service Type', "view Service Type", 0);
        return view('job_cards.service_type')->with($data);
    }

    public function addservicetype(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $servicetype = new servicetype($SysData);
        $servicetype->status = 1;
        $servicetype->save();

        AuditReportsController::store('Job Card Management', 'New service Type added', "Add Service Type", $servicetype->id);
        return response()->json();
    }

    public function editservicetype(Request $request, servicetype $service)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $service->name = $SysData['name'];
        $service->description = $SysData['description'];
        $service->update();
        AuditReportsController::store('Job Card Management', 'Service Type edited', "Edit Service Type", 0);
        return response()->json();
    }

    public function servicetype_act(servicetype $service)
    {
        if ($service->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $service->status = $stastus;
        $service->update();

        return back();
    }

    public function deleteservicetype(servicetype $service)
    {
        $service->delete();

        AuditReportsController::store('Job Card Management', ' service Type Deleted', "Deleted Service Type", $service->id);
        return redirect('/jobcards/servicetype');
    }
    
    public function jobcardimages(jobcard_maintanance $images){

         $jobCardimages = DB::table('vehicle_image')   
                ->select('vehicle_image.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
                ->leftJoin('hr_people', 'vehicle_image.user_name', '=', 'hr_people.id')
                ->where('jobcard_id', $images->id)
                ->get();

        $data['page_title'] = "Job Card Settings";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['card'] = $images;
        $data['jobCardimages'] = $jobCardimages;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'SetUp';

        AuditReportsController::store('Job Card Management', 'Job Card configuration Page Accessed', "Job Card Image Page Accessed", 0);
        return view('job_cards.jobcardImage')->with($data);
    }
    
    public function addcardimages(Request $request)
    {
        $this->validate($request, [
            //'name' => 'required',
            // 'description' => 'required',
            'images.*' => 'required',
            'valueID' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		$images = !empty($SysData['images']) ? $SysData['images'] : array();
        $currentDate = time(); 
		$count = 0;
		foreach ($images as $image)
		{
			$count ++;
			$imageArray = explode(".",$image);
			$vehicleImages = new images();
			$vehicleImages->name = $imageArray[0];
			
			$vehicleImages->vehicle_maintanace = $SysData['valueID'];
			$vehicleImages->upload_date = $currentDate;
			$vehicleImages->user_name = Auth::user()->person->id;
			$vehicleImages->default_image = 1;
			$vehicleImages->jobcard_id = $SysData['jobcard_id'];
			$vehicleImages->save();

			//Upload Image picture
			if ($request->hasFile('images')) {
				$fileExt = $image->extension();
				if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $image->isValid()) {
					$fileName = "image" .time(). '.' . $fileExt;
					$image->storeAs('Vehicle/images', $fileName);
					//Update file name in the database
					$vehicleImages->image = $fileName;
					$vehicleImages->update();
				}
			}
			$image = '';
		}     
         AuditReportsController::store('Job Card Management', 'Job Card Images added', "Added by User", 0);        
        return response()->json();
    }
	
	public function editImage(Request $request, images $image)
    {
        $SysData = $request->all();
        unset($SysData['_token']);

        $userLogged = Auth::user()->load('person');
		
        $image->upload_date = time();
        $image->user_name = 3;

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = "image" . time() . '.' . $fileExt;
                $request->file('image')->storeAs('Vehicle/images', $fileName);
                //Update file name in the database
                $image->image = $fileName;
                $image->update();
            }
        }
		$image->update();
        AuditReportsController::store('Fleet Management', 'Vehicle Image Edited', "Accessed By User", 0);;
        return response()->json();
    }
	
    public function configuration()
    {
        $row = jobcards_config::count();
        //return $row;
        if ($row == 0) {
            $config = new jobcards_config();
            $config->use_procurement = 0;
            $config->mechanic_sms = 0;
            $config->save();
        } elseif ($row > 1)
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
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Job Card Management', 'Job Card configuration Page Accessed',"Job Card configuration Page Accessed", 0);
        return view('job_cards.configuration')->with($data);
    }

    public function configurationSetings(Request $request, jobcards_config $config)
    {
        $SysData = $request->all();
        unset($SysData['_token']);

        $config->use_procurement = !empty($SysData['use_procurement']) ? $SysData['use_procurement'] : 0;
        $config->mechanic_sms = !empty($SysData['mechanic_sms']) ? $SysData['mechanic_sms'] : 0;
        $config->service_file_from = !empty($SysData['service_file_from']) ? $SysData['service_file_from'] : '';
        $config->service_file_to = !empty($SysData['service_file_to']) ? $SysData['service_file_to'] : '';
        $config->break_test_from = !empty($SysData['break_test_from']) ? $SysData['break_test_from'] : '';
        $config->break_test_to = !empty($SysData['break_test_to']) ? $SysData['break_test_to'] : '';
        $config->update();

        AuditReportsController::store('Job Card Management', 'Job Card configuration Page Accessed',"Job Card configuration Page Accessed", 0);
        return back();
    }

    public function procesflow()
    {
        $flow = processflow::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;
        $newstep = $flowprocee + 1;

        $processflow = processflow::all();
		$hrID = Auth::user()->id;
		$roles = DB::table('hr_roles')->where('status', 1)->orderBy('hr_roles.description', 'asc')->get();
		
        $processflow = DB::table('jobcard_process_flow')
            ->select('jobcard_process_flow.*', 'hr_roles.description as jobtitle_name')
            ->leftJoin('hr_roles', 'jobcard_process_flow.job_title', '=', 'hr_roles.id')
            ->orderBy('jobcard_process_flow.id')
            ->get();

        $data['page_title'] = "Job Card Processes";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['newstep'] = $newstep;
        $data['roles'] = $roles;
        $data['processflows'] = $processflow;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Process Flow';

       AuditReportsController::store('Job Card Management', 'Job Card configuration Page Accessed',"Job Card Proces Flow Page Accessed", 0);
       return view('job_cards.processflow')->with($data);
    }

    public function addprocessflow(Request $request)
    {
        $this->validate($request, [
            'step_name' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $flow = processflow::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;

        $processflow = new processflow();
        $processflow->step_number = $flowprocee + 1;
        $processflow->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $processflow->job_title = !empty($SysData['job_title']) ? $SysData['job_title'] : 0;
        $processflow->status = 1;
        $processflow->save();

        AuditReportsController::store('Job Card Management', 'New processflow has been added', "New proces flow Added", $processflow->id);
        return response()->json();
    }

    public function editprocessflow(Request $request, processflow $steps)
    {
        $this->validate($request, [
            'step_name' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $steps->step_number = !empty($SysData['step_number']) ? $SysData['step_number'] : '';
        $steps->step_name = !empty($SysData['step_name']) ? $SysData['step_name'] : '';
        $steps->job_title = !empty($SysData['job_title']) ? $SysData['job_title'] : 0;
        $steps->update();

        AuditReportsController::store('Job Card Management', ' process flow edited', "Proces flow Edited", $steps->id);
        return response()->json();
    }

    public function steps_act(processflow $steps)
    {
        if ($steps->status == 1)
            $stastus = 0;
        else
            $stastus = 1;
        $steps->status = $stastus;
        $steps->update();

        AuditReportsController::store('Job Card Management', ' process flow status Changed', "Proces flow Status changed", $steps->id);
        return back();
    }

    public function jobcardStatus($status = 0, $hrID = 0, $jobID = 0)
    {
        $user = Auth::user()->person->user_id;
        $hrjobtile = Auth::user()->person->position;
        // get from hrPerson where id is $hrID

        $status = DB::table('security_modules_access')
            ->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'job_cards')
            ->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $hrID)
            ->first();

        $processflow = processflow::where('job_title', $hrjobtile)->where('status', 1)->orderBy('id', 'asc')->get();
    }

    public function myjobcards()
    {
		$currentUser = Auth::user()->person->id;
		$hrID = Auth::user()->id;
		$roles = DB::table('hr_roles')->select('hr_roles.id as role_id', 'hr_roles.description as role_name'
		, 'hr_users_roles.id as user_role' , 'hr_users_roles.date_allocated')
		 ->leftjoin("hr_users_roles",function($join) use ($hrID) {
                $join->on("hr_roles.id","=","hr_users_roles.role_id")
                    ->on("hr_users_roles.hr_id","=",DB::raw($hrID));
            })
		->where('hr_roles.description', '=','Jobcard Capturer')
		->where('hr_roles.status', 1)
		->orderBy('hr_roles.description', 'asc')
		->first();

        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'job_cards')->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();

        if ((!empty($roles->role_id)) || !empty($userAccess)) {
            $jobcardmaintanance = DB::table('jobcard_maintanance')
                ->select('jobcard_maintanance.*', 'vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model',
                    'vehicle_managemnet.name as vehicle_type', 'service_type.name as servicetype',
                    'hr_people.first_name as firstname', 'hr_people.surname as surname', 'jobcard_process_flow.step_name as aStatus')
                ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
                ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
                ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
                ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
                ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                ->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
                ->where('jobcard_maintanance.user_id', $currentUser)
                ->orderBy('jobcard_maintanance.id', 'desc')
				->limit(50)
                ->get();

            $data['page_title'] = "Job Card";
            $data['page_description'] = "Management";
            $data['breadcrumb'] = [
                ['title' => 'Job Card Management', 'path' => 'jobcards/mycards', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Job Cards ', 'active' => 1, 'is_module' => 0]
            ];
            $data['jobcardmaintanance'] = $jobcardmaintanance;
            $data['active_mod'] = 'Job Card Management';
            $data['active_rib'] = 'My Job Cards';

            AuditReportsController::store('Job Card Management', 'Job Card Page Accessed', "Accessed By User", 0);
            return view('job_cards.myjob_cards')->with($data);
        } 
		else 
		{
            return redirect('/');
        }
    }
	
	public function create()
    {
		$hrID = Auth::user()->id;
		$roles = DB::table('hr_roles')->select('hr_roles.id as role_id', 'hr_roles.description as role_name'
		, 'hr_users_roles.id as user_role' , 'hr_users_roles.date_allocated')
		 ->leftjoin("hr_users_roles",function($join) use ($hrID) {
                $join->on("hr_roles.id","=","hr_users_roles.role_id")
                    ->on("hr_users_roles.hr_id","=",DB::raw($hrID));
            })
		->where('hr_roles.description', '=','Jobcard Capturer')
		->where('hr_roles.status', 1)
		->orderBy('hr_roles.description', 'asc')
		->first();

        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'job_cards')->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();

        if ((!empty($roles->role_id)) || !empty($userAccess)) {
            
			$ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
            $servicetype = servicetype::where('status', 1)->orderBy('name', 'asc')->get();
            // get mechanic
			$mechanics = DB::table('hr_people')
                ->select('hr_people.*')
                ->leftJoin('hr_positions', 'hr_people.position', '=', 'hr_positions.id')
				->where('hr_positions.name', '=','Mechanic')
                ->where('hr_people.status', 1)
				->orderBy('first_name', 'asc')
				->orderBy('surname', 'asc')
				->get();
			// get drivers
			$drivers = DB::table('hr_people')
                ->select('hr_people.*')
                ->leftJoin('hr_positions', 'hr_people.position', '=', 'hr_positions.id')
				->where('hr_positions.name', '=','Driver')
                ->where('hr_people.status', 1)
				->orderBy('first_name', 'asc')
				->orderBy('surname', 'asc')
				->get();
			// get all fleet
            $vehicledetails = DB::table('vehicle_details')
                ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                    'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                    'division_level_fives.name as company', 'division_level_fours.name as Department')
                ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
                ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
                ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
                ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
                ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
                ->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
				->orderBy('vehicle_details.fleet_number', 'ASC')
				->where('vehicle_details.status', 1)
				->get();

            $data['page_title'] = "Job Card";
            $data['page_description'] = "Creation";
            $data['breadcrumb'] = [
                ['title' => 'Job Card Management', 'path' => 'jobcards/mycards', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'create', 'active' => 1, 'is_module' => 0]
            ];

            $data['current_date'] = strtotime(date("Y-m-d"));
            $data['mechanics'] = $mechanics;
            $data['drivers'] = $drivers;
            $data['ContactCompany'] = $ContactCompany;
            $data['servicetype'] = $servicetype;
            $data['vehicledetails'] = $vehicledetails;
            $data['active_mod'] = 'Job Card Management';
            $data['active_rib'] = 'My Job Cards';

            AuditReportsController::store('Job Card Management', 'Create Job Card Page Accessed', "Accessed By User", 0);
            return view('job_cards.create_job_card')->with($data);
        } 
		else 
		{
            return redirect('/');
        }
    }
	
    public function addjobcardmanagement(Request $request)
    {
        $this->validate($request, [
              'vehicle_id' => 'required',
              'card_date' => 'required',
//              'job_title' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		$message = $instructions = '';
        $processflow = processflow::orderBy('id', 'asc')->first();
        $jobtitle = !empty($processflow->job_title) ? $processflow->job_title : 0;

        $carddate = $SysData['card_date'] = str_replace('/', '-', $SysData['card_date']);
        $carddate = $SysData['card_date'] = strtotime($SysData['card_date']);

        $scheduledate = $SysData['schedule_date'] = str_replace('/', '-', $SysData['schedule_date']);
        $scheduledate = $SysData['schedule_date'] = strtotime($SysData['schedule_date']);

        $bookingdate = $SysData['booking_date'] = str_replace('/', '-', $SysData['booking_date']);
        $bookingdate = $SysData['booking_date'] = strtotime($SysData['booking_date']);
        $communicationType = !empty($SysData['communication_type']) ? $SysData['communication_type'] : 0;

        $flow = jobcard_maintanance::orderBy('id', 'desc')->latest()->first();
        $flowprocee = !empty($flow->jobcard_number) ? $flow->jobcard_number : 0;

        $stadisplay = DB::table('jobcard_process_flow')->where('step_number', 1)->first();
        $statusdisplay = !empty($stadisplay->step_name) ? $stadisplay->step_name : '';

        $jobcardmaintanance = new jobcard_maintanance($SysData);
        $jobcardmaintanance->vehicle_id = !empty($SysData['vehicle_id']) ? $SysData['vehicle_id'] : 0;
        $jobcardmaintanance->card_date = !empty($carddate) ? $carddate : 0;
        $jobcardmaintanance->schedule_date = !empty($scheduledate) ? $scheduledate : 0;
        $jobcardmaintanance->booking_date = !empty($bookingdate) ? $bookingdate : 0;
        $jobcardmaintanance->supplier_id = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
        $jobcardmaintanance->service_type = !empty($SysData['service_type']) ? $SysData['service_type'] : 0;
        $jobcardmaintanance->estimated_hours = !empty($SysData['estimated_hours']) ? $SysData['estimated_hours'] : 0;
        $jobcardmaintanance->machine_hour_metre = !empty($SysData['machine_hour_metre']) ? $SysData['machine_hour_metre'] : 0;
        $jobcardmaintanance->machine_odometer = !empty($SysData['machine_odometer']) ? $SysData['machine_odometer'] : 0;
        $jobcardmaintanance->last_driver_id = !empty($SysData['last_driver_id']) ? $SysData['last_driver_id'] : 0;
        $jobcardmaintanance->mechanic_id = !empty($SysData['mechanic_id']) ? $SysData['mechanic_id'] : 0;
        $jobcardmaintanance->jobcard_number = $flowprocee + 1;
        $jobcardmaintanance->status = 1;
        $jobcardmaintanance->date_default = time();
        $jobcardmaintanance->user_id = Auth::user()->person->id;
        $jobcardmaintanance->save();
		
		# Instructions
        $numInstruction = $index = 0;
        $totalFiles = !empty($SysData['total_files']) ? $SysData['total_files'] : 0;
        while ($numInstruction != $totalFiles) {
            $index++;
            $instruction = $request->instruction[$index];
			if (!empty($instruction))
			{
				$JobCardInstructions = new JobCardInstructions();
				$JobCardInstructions->instruction_details = $instruction;
				$JobCardInstructions->job_card_id = $jobcardmaintanance->id;
				$JobCardInstructions->status = 1;
				$JobCardInstructions->save();
				$instructions .= $instruction.";";
			}
            $numInstruction++;
        }
		// add to jobcard history 
        $JobCardHistory = new JobCardHistory();
        $JobCardHistory->job_card_id = $jobcardmaintanance->id;
        $JobCardHistory->user_id = Auth::user()->person->id;
        $JobCardHistory->status = 1;
        $JobCardHistory->comment = "Job Card Created";
        $JobCardHistory->action_date = time();
        $JobCardHistory->save();

	   // send emails
        $users = HRUserRoles::where('role_id', $jobtitle)->pluck('hr_id');
        foreach ($users as $manID) {
            $usedetails = HRPerson::where('id', $manID)->select('first_name', 'surname', 'email')->first();
			if (!empty($usedetails->emaill))
				Mail::to($usedetails->email)->send(new NextjobstepNotification($usedetails->first_name, $usedetails->surname, $usedetails->email));
        }
		if (!empty($SysData['mechanic_id']))
		{
			$mechanicdetails = HRPerson::where('id', $SysData['mechanic_id'])->select('first_name', 'surname', 'email', 'cell_number')->first();
			
			if (!empty($communicationType) && $communicationType == 1)
			{
				$jcAttachment = $this->viewjobcard($jobcardmaintanance, true);
				//// Send email to mechanic
				if (!empty($mechanicdetails->email))
					Mail::to($mechanicdetails->email)->send(new MechanicEmailJC($mechanicdetails->first_name, $jcAttachment));
			}
			elseif (!empty($communicationType) && $communicationType == 2)
			{
				//// Send sms to mechanic
				if (!empty($mechanicdetails->cell_number))
				{
					// get fleet details
					$fleet = vehicle_detail::where('id',$jobcardmaintanance->vehicle_id)->first();	
			$message = <<<HTML
<p>
New JobCard Opened,
Fleet No: $fleet->fleet_number
JobCard No: $jobcardmaintanance->id
Instructions:
$instructions
</p>
HTML;
					$mobileArray[] = $this->formatCellNo($mechanicdetails->cell_number);
					$message = str_replace("<br>", "", $message);
					//$message = str_replace(">", "-", $message);
					//$message = str_replace("<", "-", $message);
					BulkSMSController::send($mobileArray, $message);
				}
			}
			elseif (!empty($communicationType) && $communicationType == 3)
			{
				$jcAttachment = $this->viewjobcard($jobcardmaintanance, true);
				//// Send sms and email to mechanic
				if (!empty($mechanicdetails->email))
					Mail::to($mechanicdetails->email)->send(new MechanicEmailJC($mechanicdetails->first_name, $jcAttachment));
				//// Send sms to mechanic 
				if (!empty($mechanicdetails->cell_number))
				{
					// get fleet details
					$fleet = vehicle_detail::where('id',$jobcardmaintanance->vehicle_id)->first();	
			$message = <<<HTML
<p>
New JobCard Opened,
Fleet No: $fleet->fleet_number
JobCard No: $jobcardmaintanance->id
Instructions:
$instructions
</p>
HTML;
					$mobileArray[] = $this->formatCellNo($mechanicdetails->cell_number);
					$message = str_replace("<br>", "", $message);
					//$message = str_replace(">", "-", $message);
					//$message = str_replace("<", "-", $message);
					BulkSMSController::send($mobileArray, $message);
				}
			}
		}
        AuditReportsController::store('Job Card Management', ' Job card created', "New Job Card Created", $jobcardmaintanance->id);
        return redirect("/jobcards/mycards")->with('success_sent', "New Job Card Have Been Successfully Opened.");
    }
	
	function formatCellNo($sCellNo)
    {
        # Remove the following characters from the phone number
        $cleanup_chr = array("+", " ", "(", ")", "\r", "\n", "\r\n");

        # clean phone number
        $sCellNo = str_replace($cleanup_chr, '', $sCellNo);

        #Internationalise  the number
        if ($sCellNo{0} == "0") $sCellNo = "27" . substr($sCellNo, 1);

        return $sCellNo;
    }
	
    public function updateJobCard(Request $request, jobcard_maintanance $jobCard)
    {
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
        $jobCard->card_date = !empty($carddate) ? $carddate : 0;
        $jobCard->schedule_date = !empty($scheduledate) ? $scheduledate : 0;
        $jobCard->booking_date = !empty($bookingdate) ? $bookingdate : 0;
        $jobCard->supplier_id = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
        $jobCard->service_type = !empty($SysData['service_type']) ? $SysData['service_type'] : 0;
        $jobCard->estimated_hours = !empty($SysData['estimated_hours']) ? $SysData['estimated_hours'] : 0;
        $jobCard->service_time = !empty($SysData['service_time']) ? $SysData['service_time'] : 0;
        $jobCard->machine_hour_metre = !empty($SysData['machine_hour_metre']) ? $SysData['machine_hour_metre'] : 0;
        $jobCard->machine_odometer = !empty($SysData['machine_odometer']) ? $SysData['machine_odometer'] : 0;
        $jobCard->last_driver_id = !empty($SysData['last_driver_id']) ? $SysData['last_driver_id'] : 0;
        $jobCard->mechanic_id = !empty($SysData['mechanic_id']) ? $SysData['mechanic_id'] : 0;
        $jobCard->completion_date = $completiondate;
        $jobCard->date_default = time();
        $jobCard->user_id = Auth::user()->person->id;
        $jobCard->update();

		# Instructions
        $numInstruction = $index = 0;
        $totalFiles = !empty($SysData['total_files']) ? $SysData['total_files'] : 0;
        while ($numInstruction != $totalFiles) {
            $index++;
            $instruction = $request->instruction[$index];
			if (!empty($instruction))
			{
				$JobCardInstructions = new JobCardInstructions();
				$JobCardInstructions->instruction_details = $instruction;
				$JobCardInstructions->job_card_id = $jobCard->id;
				$JobCardInstructions->status = 1;
				$JobCardInstructions->save();
			}
			$numInstruction++;
        }
		//Jobcard history
		$JobCardHistory = new JobCardHistory();
        $JobCardHistory->job_card_id = $jobCard->id;
        $JobCardHistory->user_id = Auth::user()->person->id;
        $JobCardHistory->status = 1;
        $JobCardHistory->comment = "New Job Card Updated";
        $JobCardHistory->action_date = time();
        $JobCardHistory->save();
        AuditReportsController::store('Job Card Management', ' Job card Updated', "Job Card Edited", $jobCard->id);
        return response()->json();

    }
	// close JC
	public function completeJobcard(Request $request, jobcard_maintanance $card)
    {
        $this->validate($request, [
          //'completion_date' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $flow = processflow::orderBy('id', 'desc')->latest()->first();
		$card->completion_comment = !empty($SysData['completion_comment']) ? $SysData['completion_comment']: '';
		$card->status = $flow->step_number;
        $card->update();

		//Jobcard history
		$JobCardHistory = new JobCardHistory();
        $JobCardHistory->job_card_id = $card->id;
        $JobCardHistory->user_id = Auth::user()->person->id;
        $JobCardHistory->status = $flow->step_number;
        $JobCardHistory->comment = "Job Card Closed";
        $JobCardHistory->action_date = time();
        $JobCardHistory->save();
        AuditReportsController::store('Job Card Management', ' Job card Closed', "Closed by User");
        return response()->json();
    }
	// document proccessing JC
	public function documnentJobcard(Request $request, jobcard_maintanance $card)
    {
        $this->validate($request, [
          'service_file_upload' => 'required',
          'completion_date' => 'required',
          'service_time' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		$completiondate = $SysData['completion_date'] = str_replace('/', '-', $SysData['completion_date']);
		$completiondate = $SysData['completion_date'] = strtotime($SysData['completion_date']);
		//Upload supporting document
        if ($request->hasFile('service_file_upload')) {
            $fileExt = $request->file('service_file_upload')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'tiff']) && $request->file('service_file_upload')->isValid()) {
                $fileName = time() . "_service_file_upload." . $fileExt;
                $request->file('service_file_upload')->storeAs('Jobcard/servicefileupload', $fileName);
                //Update file name in the table
				$flow = processflow::where('step_number', '>', $card->status)->where('status', 1)->orderBy('step_number', 'asc')->first();
				$card->completion_date = $completiondate;
				$card->service_time = $SysData['service_time'];
				$card->status = $flow->step_number;
                $card->service_file_upload = $fileName;
                $card->update();
            }
        }
		if (!empty($flow))
		{
			//Jobcard history
			$JobCardHistory = new JobCardHistory();
			$JobCardHistory->job_card_id = $card->id;
			$JobCardHistory->user_id = Auth::user()->person->id;
			$JobCardHistory->status = $flow->step_number;
			$JobCardHistory->comment = "Job Card Document Added";
			$JobCardHistory->action_date = time();
			$JobCardHistory->save();
			//send email to the next person the step
			$users = HRUserRoles::where('role_id', $flow->job_title)->pluck('hr_id');
			foreach ($users as $manID) {
				$usedetails = HRPerson::where('id', $manID)->select('first_name', 'surname', 'email')->first();
				$email = $usedetails->email;
				$firstname = $usedetails->first_name;
				$surname = $usedetails->surname;
				$email = $usedetails->email;
				Mail::to($email)->send(new NextjobstepNotification($firstname, $surname, $email));
			}
		}
        AuditReportsController::store('Job Card Management', ' Job card Updated', "Job Card Edited");
        return response()->json();
    }
	public function mechanicomplete(Request $request, jobcard_maintanance $card)
    {
        $this->validate($request, [
          //'completion_date' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
		//return $jobcards;
        unset($results['_token']);
        
        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
		$count = 0;
		foreach ($results as $sKey => $sValue) 
		{
			if (strlen(strstr($sKey, 'status_'))) 
			{
				list($sUnit, $iID) = explode("_", $sKey);
				if(!empty($iID))
				{
					$instructions = JobCardInstructions::where('id', $iID)->first();
					if (!empty($instructions->status) && $instructions->status != 2)
					{
						$instructions->status = 2;
						$instructions->completion_date = strtotime(date("Y-m-d"));
						$instructions->completion_time = date("h:i:sa");
						$instructions->update();
					}
					$count ++;
				}
            }
        }
		//Update JC
		$card->mechanic_comment = !empty($results['mechanic_comment']) ? $results['mechanic_comment']: '';
		$instructions = JobCardInstructions::where('job_card_id', $card->id)->get();
        $rowcolumn = $instructions->count();
		if($count == $rowcolumn)
		{
			$status = !empty($results['job_status']) ? $results['job_status']: 0;
			$processflow = processflow::where('step_number', '>', $status)->where('status', 1)->orderBy('step_number', 'asc')->first();

			$card->status = $processflow->step_number;
			//Jobcard history
			$JobCardHistory = new JobCardHistory();
			$JobCardHistory->job_card_id = $card->id;
			$JobCardHistory->user_id = Auth::user()->person->id;
			$JobCardHistory->status = $processflow->step_number;
			$JobCardHistory->comment = "Mechanic Feedback Completed And Moved to Next Step.";
			$JobCardHistory->action_date = time();
			$JobCardHistory->save();
			//send email to the next person the step
			$users = HRUserRoles::where('role_id', $processflow->job_title)->pluck('hr_id');
			foreach ($users as $manID) {
				$usedetails = HRPerson::where('id', $manID)->select('first_name', 'surname', 'email')->first();
				$email = $usedetails->email;
				$firstname = $usedetails->first_name;
				$surname = $usedetails->surname;
				$email = $usedetails->email;
				Mail::to($email)->send(new NextjobstepNotification($firstname, $surname, $email));
			}
		}
		else
		{
			//Jobcard history
			$JobCardHistory = new JobCardHistory();
			$JobCardHistory->job_card_id = $card->id;
			$JobCardHistory->user_id = Auth::user()->person->id;
			$JobCardHistory->status = $card->status;
			$JobCardHistory->comment = "Mechanic Feedback Incomplete.";
			$JobCardHistory->action_date = time();
			$JobCardHistory->save();	
		}
		$card->update();
		AuditReportsController::store('Job Card Management', ' Job card Updated', "Job Card Edited");
	    return redirect("/jobcards/viewcard/$card->id");
    }
	public function nextStep(jobcard_maintanance $card)
    {
		$processflow = processflow::where('step_number', '>', $card->status)->where('status', 1)->orderBy('step_number', 'asc')->first();
		$card->status = $processflow->step_number;
		$card->update();
		//Jobcard history
		$JobCardHistory = new JobCardHistory();
		$JobCardHistory->job_card_id = $card->id;
		$JobCardHistory->user_id = Auth::user()->person->id;
		$JobCardHistory->status = $processflow->step_number;
		$JobCardHistory->comment = "Job Card Moved to Document Processing.";
		$JobCardHistory->action_date = time();
		$JobCardHistory->save();
		//send email to the next person the step
		$users = HRUserRoles::where('role_id', $processflow->job_title)->pluck('hr_id');
		foreach ($users as $manID) {
			$usedetails = HRPerson::where('id', $manID)->select('first_name', 'surname', 'email')->first();
			$email = $usedetails->email;
			$firstname = $usedetails->first_name;
			$surname = $usedetails->surname;
			$email = $usedetails->email;
			Mail::to($email)->send(new NextjobstepNotification($firstname, $surname, $email));
		}
		AuditReportsController::store('Job Card Management', ' Job card Status Updated', "Updated by User");
	    return redirect("/jobcards/viewcard/$card->id");
    }
    public function cardsearch()
    {
        $processflow = processflow::orderBy('id', 'asc')->get();
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];
        $data['processflow'] = $processflow;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Job Card Management', 'Job Card card search Page Accessed', "Job Card card search Page Accessed", 0);
        return view('job_cards.search')->with($data);
    }

    public function jobcardsearch(Request $request)
    {
        $SysData = $request->all();
        unset($SysData['_token']);
        $jobcard = $request['jobcard_id'];
        $fleetnumber = $request['fleet_number'];
        $registrationNo = $request['registration_no'];
        $status = $request['status'];
        $servicetypeID = $request['service_type_id'];
        $mechanicID = $request['mechanic_id'];

        $actionFrom = $actionTo = 0;
        $actionDate = $request['date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $jobcardmaintanance = DB::table('jobcard_maintanance')
            ->select('jobcard_maintanance.*', 'vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'service_type.name as servicetype',
                'hr_people.first_name as firstname', 'hr_people.surname as surname', 'jobcard_process_flow.step_name as aStatus')
            ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
            ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
            ->where(function ($query) use ($jobcard) {
                if (!empty($jobcard)) {
                    $query->where('jobcard_maintanance.jobcard_number', $jobcard);
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
                    $query->where('jobcard_process_flow.id', $status);
                }
            })
            ->orderBy('jobcard_maintanance.id', 'asc')
            ->get();
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['jobcardmaintanance'] = $jobcardmaintanance;
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Job Card Management', 'Job Card Search Page Accessed', "Job Card card search Page Accessed", 0);
        return view('job_cards.search_results')->with($data);
    }

    public function jobcardsApprovals()
    {
		$roleArray = array();
		$hrID = Auth::user()->id;
		$roles = DB::table('hr_roles')->select('hr_roles.id as role_id')
		 ->leftjoin("hr_users_roles",function($join) use ($hrID) {
                $join->on("hr_roles.id","=","hr_users_roles.role_id")
                    ->on("hr_users_roles.hr_id","=",DB::raw($hrID));
            })
		->where('hr_roles.status', 1)
		->orderBy('hr_roles.description', 'asc')
		->get();
		
		foreach ($roles as &$role) {
			$roleArray[] = $role->role_id;
		}
        $user_id = Auth::user()->person->user_id;
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'job_cards')
            ->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $user_id)
            ->pluck('user_id')->first();

        $processflow = processflow::where('status', 1)
		->Where(function ($query) use ($roleArray) {
			if (!empty($roleArray)) {
				$query->whereIn('job_title', $roleArray);
			}
        })
		->orderBy('id', 'asc')
		->get();

        $lastProcess = processflow::where('status', 1)->orderBy('id', 'desc')->first();
        $lastStepNumber = !empty($lastProcess->step_number) ? $lastProcess->step_number : 0;

        $statuses = array();
        $status = '';
        $rowcolumn = $processflow->count();
        if ($rowcolumn > 0) 
		{
            if (empty($userAccess))
			{
                foreach ($processflow as $process) {
                    $status .= $process->step_number . ',';
                }
                $status = rtrim($status, ",");
                $statuses = (explode(",", $status));
            }

            $jobcardmaintanance = DB::table('jobcard_maintanance')
                ->select('jobcard_maintanance.*', 'vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                    'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'service_type.name as servicetype',
                    'hr_people.first_name as firstname', 'hr_people.surname as surname', 'jobcard_process_flow.step_name as aStatus')
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
                        $query->whereIn('jobcard_maintanance.status', $statuses);
                    }
                })
                ->where(function ($query) use ($lastStepNumber) {
                    if (!empty($lastStepNumber)) {
                        $query->where('jobcard_maintanance.status', '!=', $lastStepNumber);
                    }
                })
                ->where('jobcard_maintanance.status', '>=',1)
				//->whereNotNull('jobcard_maintanance.status')
                ->orderBy('jobcard_maintanance.id', 'asc')
                ->get();

            $steps = processflow::latest()->first();
            $stepnumber = !empty($steps->step_number) ? $steps->step_number : 0;

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

            AuditReportsController::store('Job Card Management', 'Job Card Approvals Page Accessed', "Accessed By User", 0);
            return view('job_cards.job_card_approval')->with($data);
        }
		else return back()->with('success_edit', "The are not permitted to view this page.");
    }

    public function appovecards(Request $request)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
		//return $jobcards;
        unset($results['_token']);
        
        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'cardappprove'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $cardsID = $aValue[1];
				$jobCard = jobcard_maintanance::where('id', $cardsID)->first();
                $processflow = processflow::where('step_number', '>', $sValue)->where('status', 1)->orderBy('step_number', 'asc')->first();
				$jobCard->status = $processflow->step_number;
				$jobCard->update();
				
				//Jobcard history
				$JobCardHistory = new JobCardHistory();
				$JobCardHistory->job_card_id = $jobCard->id;
				$JobCardHistory->user_id = Auth::user()->person->id;
				$JobCardHistory->status = $processflow->step_number;
				$JobCardHistory->comment = "New Job Card Status Updated";
				$JobCardHistory->action_date = time();
				$JobCardHistory->save();
				
                //send email to the next person the step
				$users = HRUserRoles::where('role_id', $processflow->job_title)->pluck('hr_id');
                foreach ($users as $manID) {
                    $usedetails = HRPerson::where('id', $manID)->select('first_name', 'surname', 'email')->first();
                    $email = $usedetails->email;
                    $firstname = $usedetails->first_name;
                    $surname = $usedetails->surname;
                    $email = $usedetails->email;
                    Mail::to($email)->send(new NextjobstepNotification($firstname, $surname, $email));
                }
            }
            // decline
        }
		
		foreach ($results as $sKey => $sValue) 
		{
			if (strlen(strstr($sKey, 'declined_'))) 
			{
				list($sUnit, $iID) = explode("_", $sKey);
				if ($sUnit == 'declined' && !empty($sValue)) {
					$jobCard = jobcard_maintanance::where('id', $iID)->first();  // when declined move back to the last step
					$statusflow = $jobCard->status;
					if ($statusflow === 0) {
						// status 0 means declined
						$jobCard->status = 0;
					} elseif ($statusflow === 1) {
						$jobCard->status = 0;
					} else
						$jobCard->status = $statusflow - 1;
					$jobCard->reject_reason = $sValue;
					$jobCard->reject_timestamp = time();
					$jobCard->rejector_id = Auth::user()->person->id;
					$jobCard->update();
					
					//jobCard history
					$JobCardHistory = new JobCardHistory();
					$JobCardHistory->job_card_id = $jobCard->id;
					$JobCardHistory->user_id = Auth::user()->person->id;
					$JobCardHistory->status = $jobCard->status;
					$JobCardHistory->comment = "New Job Card Status Updated";
					$JobCardHistory->action_date = time();
					$JobCardHistory->save();
					
					if ($statusflow != 0) {
						$processflow = processflow::where('step_number', $statusflow - 1)->where('status', 1)->orderBy('step_number', 'asc')->first();
						if (!empty($processflow->job_title))
						{
							$user = HRUserRoles::where('role_id', $processflow->job_title)->pluck('hr_id');
							foreach ($user as $manID) 
							{
								$usedetails = HRPerson::where('id', $manID)->select('first_name', 'surname', 'email')->first();
								$email = $usedetails->email;
								$firstname = $usedetails->first_name;
								$surname = $usedetails->surname;
								$email = $usedetails->email;
								$reason = $sValue;
								Mail::to($email)->send(new DeclinejobstepNotification($firstname, $surname, $email, $reason));
							}
						}
					}
				}
            }
        }

        AuditReportsController::store('Job Card Management', 'Job card Approvals Page', "Accessed By User",0);
        return back();
    }

	public function editjobcardinstructions(JobCardInstructions $instruction)
    {
        $data['instruction'] = $instruction;
        $data['page_title'] = "Job Card Instructions";
        $data['page_description'] = "Job Card instruction Edit";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/mycards', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card instruction Edit', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';

        AuditReportsController::store('Job Card Management', 'Job Card Instructions Edit Page Accessed', "Accessed By User");
        return view('job_cards.edit_jobcards_instructions')->with($data);
    }
	public function editInstruction(Request $request, JobCardInstructions $instruction)
    {
		$this->validate($request, [

        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $instruction->instruction_details = !empty($SysData['instruction_details']) ? $SysData['instruction_details'] : '';
        $instruction->update();
		$flow = jobcard_maintanance::where('id', $instruction->job_card_id)->first();
		//Jobcard history
		$JobCardHistory = new JobCardHistory();
		$JobCardHistory->job_card_id = $instruction->job_card_id;
		$JobCardHistory->user_id = Auth::user()->person->id;
		$JobCardHistory->status = $flow->status;
		$JobCardHistory->comment = "Job Card instruction Updated";
		$JobCardHistory->action_date = time();
		$JobCardHistory->save();
						
        AuditReportsController::store('Job Card Management', 'Job Card Instructions Edit Page Accessed', "Accessed By User");
        return redirect("/jobcards/viewcard/$instruction->job_card_id");
    }

    public function viewjobcard(jobcard_maintanance $card, $isPDF = false)
    {
		$userID = Auth::user()->id;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'job_cards')->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $userID)->pluck('user_id')->first();
		$roles = DB::table('hr_roles')->select('hr_roles.id as role_id', 'hr_roles.description as role_name'
		, 'hr_users_roles.id as user_role' , 'hr_users_roles.date_allocated')
		 ->leftjoin("hr_users_roles",function($join) use ($userID) {
                $join->on("hr_roles.id","=","hr_users_roles.role_id")
                    ->on("hr_users_roles.hr_id","=",DB::raw($userID));
            })
		->where('hr_roles.description', '=','Mechanic')
		->where('hr_roles.status', 1)
		->orderBy('hr_roles.description', 'asc')
		->first();

        $ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $servicetype = servicetype::where('status', 1)->get();
        $users = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $flow = processflow::where('status', 1)->orderBy('id', 'desc')->latest()->first();
		
		$instructions = JobCardInstructions::where('job_card_id',$card->id)->orderBy('instruction_details', 'asc')->get();
        $vehicledetails = DB::table('vehicle_details')
			->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
				'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
				'division_level_fives.name as company', 'division_level_fours.name as Department')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
			->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
			->orderBy('vehicle_details.fleet_number', 'ASC')
			->where('vehicle_details.status', 1)
			->get();
		//
		
		//
        $jobcard = DB::table('jobcard_maintanance')
            ->select('jobcard_maintanance.*', 'vehicle_details.*',
                'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'service_type.name as servicetype',
                'hr_people.first_name as me_firstname', 'hr_people.surname as me_surname',
                'hrp.first_name as dr_firstname', 'hrp.surname as dr_surname','jobcard_process_flow.step_name as aStatus'
				,'jobcard_process_flow.job_title as job_title')
            ->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
            ->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
            ->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
            ->leftJoin('hr_people as hrp', 'jobcard_maintanance.last_driver_id', '=', 'hrp.id')
            ->where('jobcard_maintanance.id', $card->id)
            ->orderBy('jobcard_maintanance.id', 'asc')
            ->get();
		if (!empty($card)) $card->load('jcNotes');
        $configuration = jobcards_config::first();
		$data['current_date'] = strtotime(date("Y-m-d"));
        $data['configuration'] = $configuration;
        $data['roles'] = $roles;
        $data['users'] = $users;
        $data['flow'] = $flow;
        $data['userAccess'] = $userAccess;
        $data['instructions'] = $instructions;
        $data['jobcard'] = $jobcard;
        $data['ContactCompany'] = $ContactCompany;
        $data['servicetype'] = $servicetype;
        $data['vehicledetails'] = $vehicledetails;
        $data['card'] = $card;
        $data['page_title'] = "Job Card Search";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';
		// JC history
		$JobCardHistory = new JobCardHistory();
		$JobCardHistory->job_card_id = $card->id;
		$JobCardHistory->user_id = Auth::user()->person->id;
		$JobCardHistory->status = $card->status;
		$JobCardHistory->comment = "JC opened";
		$JobCardHistory->action_date = time();
		$JobCardHistory->save();
		if ($isPDF === true) 
		{
			AuditReportsController::store('Job Card Management', 'Job Cards Printed', "Accessed By User");
			$companyDetails = CompanyIdentity::systemSettings();
			$companyName = $companyDetails['company_name'];
			$user = Auth::user()->load('person');

			$data['support_email'] = $companyDetails['support_email'];
			$data['company_name'] = $companyName;
			$data['full_company_name'] = $companyDetails['full_company_name'];
			$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
			$data['date'] = date("d-m-Y");
			$data['user'] = $user;
			$data['file_name'] = 'JobCard';
            $view = view('job_cards.jobcard_view_print', $data)->render();
            $pdf = resolve('dompdf.wrapper');
            $pdf->getDomPDF()->set_option('enable_html5_parser', true);
            $pdf->loadHTML($view);
			return $pdf->output();
        }
		else 
		{
			AuditReportsController::store('Job Card Management', 'View Job Cards Page Accessed', "Accessed By User");
			return view('job_cards.job_card_details')->with($data);
        }

    }
	public function jobcardhistory(jobcard_maintanance $card)
    {
        $card = $card->load('JobCardHistory.userName');
		
		$data['page_title'] = 'View JobCard History';
		$data['page_description'] = 'JobCard History';
		$data['breadcrumb'] = [
			['title' => 'JobCard Management', 'path' => '/jobcards/mycards', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
			['title' => 'JobCard History', 'active' => 1, 'is_module' => 0]
		];
		
		$data['card'] = $card;
		$data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';
		AuditReportsController::store('Fleet Management', 'Vehicle History Page Accessed', 'Accessed by User', 0);
		return view('job_cards.job_card_history')->with($data);
    }
	
	public function mechanicFeedback(jobcard_maintanance $card)
    {
        $card = $card->load('JobCardHistory.userName');
		$instructions = JobCardInstructions::where('job_card_id',$card->id)->orderBy('instruction_details', 'asc')->get();
        
		$data['page_title'] = 'JobCard';
		$data['page_description'] = 'Mechanic Feedback';
		$data['breadcrumb'] = [
			['title' => 'JobCard Management', 'path' => '/jobcards/mycards', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
			['title' => 'JobCard History', 'active' => 1, 'is_module' => 0]
		];
		
		$data['card'] = $card;
		$data['instructions'] = $instructions;
		$data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';
		AuditReportsController::store('Fleet Management', 'Vehicle History Page Accessed', 'Accessed by User', 0);
		return view('job_cards.mechanic_feedback')->with($data);
    }
	
	public function jobcardHistoriesPrint(jobcard_maintanance $card)
    {
		$card = $card->load('JobCardHistory.userName');
		$data['page_title'] = 'View JobCard History';
		$data['page_description'] = 'JobCard History';
		$data['breadcrumb'] = [
			['title' => 'JobCard Management', 'path' => '/jobcards/mycards', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
			['title' => 'JobCard History', 'active' => 1, 'is_module' => 0]
		];
		
		$data['card'] = $card;
		$data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'My Job Cards';
		
		$companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;
		
		AuditReportsController::store('Fleet Management', 'Vehicle History Page Printed', 'Accessed by User', 0);
		return view('job_cards.job_card_history_print')->with($data);
    }

    public function viewjobcardnotes(jobcard_maintanance $card)
    {
        $jobcardnote = DB::table('jobcard_notes')
            ->select('jobcard_notes.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'jobcard_notes.user_id', '=', 'hr_people.id')
            ->where('jobcard_id', $card->id)
            ->Orderby('jobcard_notes.id', 'asc')
            ->get();
        $data['card'] = $card;
        $data['jobcardnote'] = $jobcardnote;
        $data['page_title'] = "Job Card";
        $data['page_description'] = "Notes";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Notes', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Job Card Management', 'view Jobcardnotes', "Accessed By User", $card->id);
        return view('job_cards.add_jocard_notes')->with($data);
    }

    public function addjobcardnotes(Request $request)
    {
        $this->validate($request, [
            'note' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $jobcardnote = new jobcardnote();
        $jobcardnote->note_details = !empty($SysData['note']) ? $SysData['note'] : '';
        $jobcardnote->vehicle_id = !empty($SysData['vehicle_id']) ? $SysData['vehicle_id'] : 0;
        $jobcardnote->jobcard_id = !empty($SysData['jobcard_id']) ? $SysData['jobcard_id'] : 0;
        $jobcardnote->user_id = Auth::user()->person->id;
        $jobcardnote->date_default = time();
        $jobcardnote->save();
		// send email to other user
		$histories = jobcardnote::where('jobcard_id', $jobcardnote->jobcard_id)->distinct('user_id')->get();
        foreach ($histories as $history) {
            $usedetails = HRPerson::where('id', $history->user_id)->select('first_name', 'surname', 'email')->first();
            $email = $usedetails->email;
            $firstname = $usedetails->first_name;
            $email = $usedetails->email;
            Mail::to($email)->send(new NoteCommunications($firstname,$SysData['note']));
        }
		
		$flow = jobcard_maintanance::where('id', $jobcardnote->jobcard_id)->first();
		//Jobcard history
		$JobCardHistory = new JobCardHistory();
		$JobCardHistory->job_card_id = $jobcardnote->jobcard_id;
		$JobCardHistory->user_id = Auth::user()->person->id;
		$JobCardHistory->status = $flow->status;
		$JobCardHistory->comment = "New Note Added.";
		$JobCardHistory->action_date = time();
		$JobCardHistory->save();
		
        AuditReportsController::store('Job Card Management', ' Job card note created', "Accessed By User", $jobcardnote->id);
        return response()->json();
    }
	
    public function viewparts(jobcard_maintanance $jobcardparts)
    {
        $parts = DB::table('jobcard__order_parts') 
            ->select('Product_products.*', 'jobcard__order_parts.no_of_parts_used', 'product_Category.name as cat_name')
            ->leftJoin('Product_products', 'jobcard__order_parts.product_id', '=', 'Product_products.id')
            ->leftJoin('product_Category', 'jobcard__order_parts.category_id', '=', 'product_Category.id')
            ->where('jobcard__order_parts.jobcard_card_id', $jobcardparts->id)
            ->orderBY('cat_name')
            ->orderBY('Product_products.name')
            ->get();

        $data['parts'] = $parts;
        $data['jobcardparts'] = $jobcardparts;
        $data['page_title'] = "Job Cards";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Parts';

        AuditReportsController::store('Job Card Management', 'view Job card view parts ', "Accessed By User", $jobcardparts->id);
        return view('job_cards.add_parts')->with($data);
    }
	public function addparts(jobcard_maintanance $jobcardpart)
    {
        $productCategories = product_category::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get(); 
        $kits = kitProducts::where('status',1)->orderBy('name', 'asc')->get(); 

        $data['productCategories'] = $productCategories;
        $data['jobcardpart'] = $jobcardpart;
        $data['kits'] = $kits;
        $data['page_title'] = "Job Cards";
        $data['page_description'] = "Add Part(s) To Job Card";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Parts ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Parts';

        AuditReportsController::store('Job Card Management', 'view Job card view parts ', "Accessed By User", $jobcardpart->id);
        return view('job_cards.add_job_parts')->with($data);
    }

    public function addjobparts(Request $request, jobcard_maintanance $jobcardpart)
    {
		//Validation
        $validator = Validator::make($request->all(), [
            'category_id' => 'bail|required_if:part_type,1',
            'product_id' => 'bail|required_if:part_type,1',
            'kit_id' => 'bail|required_if:part_type,2',
            'no_of_parts_used' => 'bail|required_if:part_type,1',
        ]);
        $validator->after(function ($validator) use($request) {
            $categoryID = $request->input('category_id');
            $productID = $request->input('product_id');
            $noOfPartsWanted = $request->input('no_of_parts_used');
            $partType = $request->input('part_type');
            $isAvailable = 0;
			if (!empty($categoryID) && !empty($productID))
			{
				$stock = stock::where('category_id', $categoryID)->where('product_id', $productID)->first();
				$availblebalance = !empty($stock->avalaible_stock) ? $stock->avalaible_stock : 0;	
				if ($noOfPartsWanted <= $availblebalance) $isAvailable = 1;
				
				if (empty($isAvailable))
					$validator->errors()->add('no_of_parts_used', "Sorry you cannot request more than: $availblebalance. Please your Request.");
			}
			
            if (empty($noOfPartsWanted) && $partType == 1)
                $validator->errors()->add('no_of_parts_used', 'Please enter number of parts needed');
        });
        if ($validator->fails()) {
            return redirect("/jobcard/addparts/$jobcardpart->id")
                ->withErrors($validator)
                ->withInput();
        }

        $SysData = $request->all();
        unset($SysData['_token']);
		
		$kitID = !empty($SysData['kit_id']) ? $SysData['kit_id']: 0;
		if ($kitID > 0)
		{
			$products = KitJoinProducts::where('kit_id', $kitID)->where('status', 1)->get();
			foreach ($products as $product) {

				$stock = stock::where('category_id', $product->category_id)->where('product_id', $product->product_id)->first();
				$availblebalance = !empty($stock->avalaible_stock) ? $stock->avalaible_stock : 0;
				$transactionbalance = $availblebalance - $product->amount_required;

				$currentparts = new jobcard_order_parts();
				$currentparts->category_id = $product->category_id;
				$currentparts->product_id = $product->product_id;
				$currentparts->no_of_parts_used = $product->amount_required;
				$currentparts->jobcard_card_id = $jobcardpart->id;
				$currentparts->avalaible_transaction = $transactionbalance;
				$currentparts->vehicle_id = $jobcardpart->vehicle_id;
				$currentparts->created_by = Auth::user()->person->id;
				$currentparts->date_created = time();
				$currentparts->status = 1;
				$currentparts->save();

				// Update stock availble balance
				$stock->avalaible_stock = $transactionbalance;
				$stock->update();
				// Update stock history
				$history = new stockhistory();
				$history->product_id = $product->product_id;
				$history->category_id = $product->category_id;
				$history->avalaible_stock = $transactionbalance;
				$history->action_date = time();
				$history->balance_before = $availblebalance;
				$history->balance_after = $transactionbalance;
				$history->action = 'Stock Items Out';
				$history->user_id = Auth::user()->person->id;
				$history->user_allocated_id = 0;
				$history->vehicle_id = $jobcardpart->vehicle_id;
				$history->save();
			}

			//add to jobcard history 
			$JobCardHistory = new JobCardHistory();
			$JobCardHistory->job_card_id = $jobcardpart->id;
			$JobCardHistory->user_id = Auth::user()->person->id;
			$JobCardHistory->status = $jobcardpart->status;
			$JobCardHistory->comment = "Parts Allocated";
			$JobCardHistory->action_date = time();
			$JobCardHistory->save();
		}
		else
		{
			$stock = stock::where('category_id', $SysData['category_id'])->where('product_id', $SysData['product_id'])->first();
			$availblebalance = !empty($stock->avalaible_stock) ? $stock->avalaible_stock : 0;
			$transactionbalance = $availblebalance - $SysData['no_of_parts_used'];

			$currentparts = new jobcard_order_parts();
			$currentparts->category_id = !empty($SysData['category_id']) ? $SysData['category_id'] : 0;
			$currentparts->product_id = !empty($SysData['product_id']) ? $SysData['product_id'] : 0;
			$currentparts->no_of_parts_used = !empty($SysData['no_of_parts_used']) ? $SysData['no_of_parts_used'] : 0;
			$currentparts->jobcard_card_id = $jobcardpart->id;
			$currentparts->avalaible_transaction = $transactionbalance;
			$currentparts->vehicle_id = $jobcardpart->vehicle_id;
			$currentparts->created_by = Auth::user()->person->id;
			$currentparts->date_created = time();
			$currentparts->status = 1;
			$currentparts->save();

			// Update stock availble balance
			$stock->avalaible_stock = $transactionbalance;
			$stock->update();
			// Update stock history
			$history = new stockhistory();
			$history->product_id = !empty($SysData['product_id']) ? $SysData['product_id'] : 0;
			$history->category_id = !empty($SysData['category_id']) ? $SysData['category_id'] : 0;
			$history->avalaible_stock = $transactionbalance;
			$history->action_date = time();
			$history->balance_before = $availblebalance;
			$history->balance_after = $transactionbalance;
			$history->action = 'Stock Updated';
			$history->user_id = Auth::user()->person->id;
			$history->user_allocated_id = 0;
			$history->vehicle_id = $jobcardpart->vehicle_id;
			$history->save();
			
			//add to jobcard history 
			$JobCardHistory = new JobCardHistory();
			$JobCardHistory->job_card_id = $jobcardpart->id;
			$JobCardHistory->user_id = Auth::user()->person->id;
			$JobCardHistory->status = $jobcardpart->status;
			$JobCardHistory->comment = "Parts Allocated";
			$JobCardHistory->action_date = time();
			$JobCardHistory->save();
		}
		AuditReportsController::store('Job Card Management', ' Job card parts added to jobcard', "Accessed By User", 0);
		return redirect("/jobcard/parts/$jobcardpart->id")->with('success_add', "part Has been Successfully Added To jobCard.");
    }

    public function printcards(Request $request, jobcard_maintanance $print)
    {
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
		$note = !empty($results['jobcards_notes']) ? $results['jobcards_notes']: 0;
		$part = !empty($results['jobcards_parts']) ? $results['jobcards_parts']: 0;
		$all = !empty($results['jobcards_all']) ? $results['jobcards_all']: 0;

		$vehiclemaintenance = DB::table('jobcard_maintanance')
			->select('jobcard_maintanance.*', 'vehicle_details.*',
				'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
				'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'service_type.name as servicetype',
				'hr_people.first_name as me_firstname', 'hr_people.surname as me_surname', 'jobcard_process_flow.step_name as aStatus'
				,'hrp.first_name as dr_firstname', 'hrp.surname as dr_surname')
			->leftJoin('service_type', 'jobcard_maintanance.service_type', '=', 'service_type.id')
			->leftJoin('hr_people', 'jobcard_maintanance.mechanic_id', '=', 'hr_people.id')
			->leftJoin('vehicle_details', 'jobcard_maintanance.vehicle_id', '=', 'vehicle_details.id')
			->leftJoin('contact_companies', 'jobcard_maintanance.supplier_id', '=', 'contact_companies.id')
			->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
			->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
			->leftJoin('jobcard_process_flow', 'jobcard_maintanance.status', '=', 'jobcard_process_flow.step_number')
			->leftJoin('hr_people as hrp', 'jobcard_maintanance.last_driver_id', '=', 'hrp.id')
			->where('jobcard_maintanance.id', $print->id)
			->orderBy('jobcard_maintanance.id', 'asc')
			->get();
		//return $vehiclemaintenance;
		$instructions = JobCardInstructions::where('job_card_id',$print->id)->orderBy('instruction_details', 'asc')->get();
		if ($note > 0 || $all > 0)
		{
			$jobcardnotes = DB::table('jobcard_notes')
            ->select('jobcard_notes.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'jobcard_notes.user_id', '=', 'hr_people.id')
            ->where('jobcard_id', $print->id)
            ->Orderby('jobcard_notes.id', 'asc')
            ->get();
			$data['jobcardnotes'] = $jobcardnotes;
		}
		if ($part > 0 || $all > 0)
		{
			$parts = DB::table('jobcard__order_parts') 
				->select('Product_products.*', 'jobcard__order_parts.no_of_parts_used', 'product_Category.name as cat_name')
				->leftJoin('Product_products', 'jobcard__order_parts.product_id', '=', 'Product_products.id')
				->leftJoin('product_Category', 'jobcard__order_parts.category_id', '=', 'product_Category.id')
				->where('jobcard__order_parts.jobcard_card_id', $print->id)
				->orderBY('cat_name')
				->orderBY('Product_products.name')
				->get();
			$data['parts'] = $parts;
		}
		$data['vehiclemaintenance'] = $vehiclemaintenance;
		$data['note'] = $note;
		$data['all'] = $all;
		$data['part'] = $part;
		$data['instructions'] = $instructions;
		$data['page_title'] = " Fleet Management ";
		$data['page_description'] = "Fleet Cards Report ";
		$data['breadcrumb'] = [
			['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
			['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
		];

		$companyDetails = CompanyIdentity::systemSettings();
		$companyName = $companyDetails['company_name'];
		$user = Auth::user()->load('person');

		$data['support_email'] = $companyDetails['support_email'];
		$data['company_name'] = $companyName;
		$data['full_company_name'] = $companyDetails['full_company_name'];
		$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;

		AuditReportsController::store('Job Card Management', 'Job Card Printed', "Accessed By User", 0);
		return view('job_cards.jobcard_report_print')->with($data);
    }
    
    public function printAudit(Request $request){
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
       
        $cardID = $results['card_id'];
        
        $audits = DB::table('audit_trail')
					  ->select('audit_trail.*','hr_people.first_name as firstname', 'hr_people.surname as surname')
					  ->leftJoin('hr_people', 'audit_trail.user_id', '=', 'hr_people.id')
					  ->where('reference_id' ,$cardID)
					  ->where('module_name', 'Job Card Management')
					  ->Orderby('audit_trail.id', 'asc')
					  ->get();              

		$data['audits'] = $audits;
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

		AuditReportsController::store('Job Card Management', 'Job Card print Audit Page Accessed', "Accessed By User", 0);
		return view('job_cards.audit_print')->with($data);
    }

    public function canceljobcardnotes(jobcard_maintanance $card)
    {
        $hrID = Auth::user()->person->user_id;
        $hrjobtile = Auth::user()->person->position;
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'job_cards')
            ->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $hrID)
            ->pluck('user_id')->first();

        $processflow = processflow::where('job_title', $hrjobtile)->where('status', 1)->orderBy('id', 'asc')->get();
        $lastProcess = processflow::where('job_title', $hrjobtile)->where('status', 1)->orderBy('id', 'desc')->first();

        // return $userAccess;
        $statuses = array();
        $status = '';

        $processss = processflow::take(1);
        $rowcolumn = $processflow->count();
        if ($rowcolumn > 0 || !empty($userAccess)) {

            if (!empty($userAccess)) $statuses = array();
            else {
                foreach ($processflow as $process) {
                    $status .= $process->step_number . ',';
                }
                $status = rtrim($status, ",");;
                $statuses = (explode(",", $status));
            }
            $jobcardmaintanance = DB::table('jobcard_maintanance')
                ->select('jobcard_maintanance.*', 'vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                    'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make',
                    'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'service_type.name as servicetype',
                    'hr_people.first_name as firstname', 'hr_people.surname as surname', 'jobcard_process_flow.step_name as aStatus')
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
                ->where('jobcard_maintanance.status', '!=', $lastProcess->step_number)
                ->orderBy('jobcard_maintanance.id', 'asc')
                ->get();
        }
    }

    public function viewreport()
    {
        $processflow = processflow::orderBy('id', 'asc')->get();
        $productsCategories = product_category::orderBy('id', 'asc')->get();

        $jobcardmaintanance = DB::table('jobcard_maintanance')
            ->select('jobcard_maintanance.*', 'vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
                'contact_companies.name as Supplier', 'vehicle_make.name as vehicle_make', 'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type', 'service_type.name as servicetype',
                'hr_people.first_name as firstname', 'hr_people.surname as surname', 'jobcard_process_flow.step_name as aStatus')
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

        $users = HRPerson::orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();

        $vehicledetails = DB::table('vehicle_details')
            ->select('vehicle_details.id as fleet_id','vehicle_details.fleet_number as fleet_number',
			'vehicle_details.vehicle_registration as vehicle_registration', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->get();

        $data['productsCategories'] = $productsCategories;
        $data['vehicledetails'] = $vehicledetails;
        $data['processflow'] = $processflow;
        $data['users'] = $users;
        $data['page_title'] = "Job Cards";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Job Card Management', 'view Job card view reports ', "Accessed By User", 0);
        return view('job_cards.search_report_index')->with($data);
    }

	public function cards(Request $request){

        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);
         
        $applicationType = $SysData['application_type'];
        $status = $SysData['status'];
        $vehicleID = $SysData['vehicle_id'];
        $application_type = $SysData['application_type'];
        $actionDate = $SysData['action_date'];
                    
		$actionFrom = $actionTo = 0;
		$actionDate = $request['actions_date'];
		if (!empty($actionDate)) {
			$startExplode = explode('-', $actionDate);
			$actionFrom = strtotime($startExplode[0]);
			$actionTo = strtotime($startExplode[1]);
		}    
		$jobCards = jobcard_maintanance::
			select('jobcard_maintanance.*','vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
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
			->where(function ($query) use ($status) {
				if (!empty($status)) {
					 $query->where('jobcard_process_flow.step_name','ILIKE', "%$status%");
					 
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
		if (!empty($jobCards)) $jobCards->load('JCinstructions');
        $data['actionFrom'] = $actionFrom;
		$data['actionTo'] = $actionTo;
        $status = $SysData['status'];
        $vehicleID = $SysData['vehicle_id'];
		$data['status'] = $status;
		$data['vehicleID'] = $vehicleID;  
		$data['jobCards'] = $jobCards;
		$data['page_title'] = "Job Card";
        $data['page_description'] = "Reports";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Reports';
		AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('job_cards.jobcard_card')->with($data);    
   }
   
   public function printscard( Request $request ){
             $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);

        $SysData = $request->all();
         unset($SysData['_token']);
          
        $status = $SysData['status'];
        $vehicleID = $SysData['vehicle_id'];
        $actionFrom = $SysData['action_from'];
        $actionTo = $SysData['action_to'];

        $jobCards = jobcard_maintanance::
			select('jobcard_maintanance.*','vehicle_details.fleet_number as fleet_number', 'vehicle_details.vehicle_registration as vehicle_registration',
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
			->where(function ($query) use ($status) {
				if (!empty($status)) {
					$query->where('jobcard_process_flow.step_name','ILIKE', "%$status%");	 
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
		if (!empty($jobCards)) $jobCards->load('JCinstructions');  

        $data['jobCards'] = $jobCards;
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

        AuditReportsController::store('Job Card Management', 'Job Card print card Page Accessed', "Accessed By User", 0);
        return view('job_cards.card_print')->with($data);

    }

    public function parts(Request $request)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $categoryID = $SysData['category_id'];
        $productID = $SysData['product_id'];
        $actionDate = $SysData['action_date'];
        $vehicleID = $SysData['vehicle_id'];
        $actionFrom = $actionTo = 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

		$parts = DB::table('jobcard_maintanance')
		->select('jobcard_maintanance.*', 'jobcard__order_parts.*', 'Product_products.name as product_name',
			'hr_people.first_name as firstname', 'hr_people.surname as surname', 'vehicle_details.fleet_number as fleet_no',
			'vehicle_details.vehicle_registration as vehicleregistration', 'service_type.name as servicetype')
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
		->where(function ($query) use ($vehicleID) {
			if (!empty($vehicleID)) {
				$query->where('jobcard_maintanance.vehicle_id', $vehicleID);
			}
		})
		->where(function ($query) use ($actionFrom, $actionTo) {
			if ($actionFrom > 0 && $actionTo > 0) {
				$query->whereBetween('jobcard__order_parts.date_created', [$actionFrom, $actionTo]);
			}
		})
		->where('jobcard__order_parts.jobcard_card_id', '>', 0)
		->where('jobcard__order_parts.product_id', '>', 0)
		->OrderBy('vehicle_details.id', 'asc')
		->OrderBy('jobcard_maintanance.id', 'asc')
		->OrderBy('jobcard__order_parts.id', 'asc')
		->get();
		
        $data['vehicleID'] = $vehicleID;
        $data['actionFrom'] = $actionFrom;
		$data['actionTo'] = $actionTo;
        $data['categoryID'] = $categoryID;
		$data['productID'] = $productID;
	
        $data['parts'] = $parts;
        $data['page_title'] = "Job Card Processes";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Settings ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Job Card Management', 'Job Card print parts Page Accessed', "Accessed By User", 0);
        return view('job_cards.jobcard_parts')->with($data);
    }
    
    public function printsparts(Request $request){
       $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $categoryID = $SysData['product_id'];
        $productID = $SysData['category_id'];
        $actionFrom = $SysData['action_from'];
        $actionTo = $SysData['action_to'];
		$vehicleID = $SysData['vehicle_id'];
		
        $parts = DB::table('jobcard_maintanance')
		->select('jobcard_maintanance.*', 'jobcard__order_parts.*', 'Product_products.name as product_name',
			'hr_people.first_name as firstname', 'hr_people.surname as surname', 'vehicle_details.fleet_number as fleet_no',
			'vehicle_details.vehicle_registration as vehicleregistration', 'service_type.name as servicetype')
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
		->where(function ($query) use ($vehicleID) {
			if (!empty($vehicleID)) {
				$query->where('jobcard__order_parts.vehicle_id', $vehicleID);

			}
		})
		->where(function ($query) use ($actionFrom, $actionTo) {
			if ($actionFrom > 0 && $actionTo > 0) {
				$query->whereBetween('jobcard__order_parts.date_created', [$actionFrom, $actionTo]);
			}
		})
		->where('jobcard__order_parts.jobcard_card_id', '>', 0)
		->where('jobcard__order_parts.product_id', '>', 0)
		->OrderBy('vehicle_details.id', 'asc')
		->OrderBy('jobcard_maintanance.id', 'asc')
		->OrderBy('jobcard__order_parts.id', 'asc')
		->get();
            
        $data['parts'] = $parts;
        $data['page_title'] = "Job Report";
        $data['page_description'] = "Job Card Parts Report";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Parts Report ', 'active' => 1, 'is_module' => 0]
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

        AuditReportsController::store('Job Card Management', 'Job Card print parts Page Accessed', "Accessed By User", 0);
        return view('job_cards.parts_print')->with($data);
    }

    public function notes(Request $request)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        $noteDetails = $SysData['note_details'];
        $userID = $SysData['user_id'];
        $vehicleID = $SysData['vehicle_id'];
        $actionFrom = $actionTo = 0;
        $actionDate = $request['note_date'];
   
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $notes = DB::table('jobcard_notes')
            ->select('jobcard_notes.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'vehicle_details.fleet_number as fleet_no',
                'vehicle_details.vehicle_registration as vehicleregistration')
            ->leftJoin('jobcard_maintanance', 'jobcard_notes.jobcard_id', '=', 'jobcard_maintanance.id')
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
              ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('jobcard_notes.date_default', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($vehicleID) {
                if (!empty($vehicleID)) {
                    $query->where('jobcard_notes.vehicle_id', $vehicleID);
                }
            })
            ->OrderBy('jobcard_notes.vehicle_id', 'asc')
			->OrderBy('jobcard_notes.jobcard_id', 'asc')
			->OrderBy('jobcard_notes.id', 'asc')
            ->get();

        $data['actionFrom'] = $actionFrom;
        $data['actionTo'] = $actionTo;
        $data['noteDetails'] = $noteDetails;
        $data['userID'] = $userID;
        $data['vehicleID'] = $vehicleID;
        $data['notes'] = $notes;
        $data['page_title'] = "Job Card Processes";
        $data['page_description'] = "Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card Management', 'path' => 'jobcards/set_up', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Notes ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Job Card Management', 'Job Card print notes Page Accessed', "Accessed By User", 0);
        return view('job_cards.jobcard_notes')->with($data);
    }
    
    public function printnotes(Request $request){
       $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $actionFrom = $SysData['action_from'];
        $actionTo = $SysData['action_to'];
        $noteDetails = $SysData['note_details'];
        $userID = $SysData['user_id'];
        $vehicleID = $SysData['vehicle_id'];

        $notes = DB::table('jobcard_notes')
            ->select('jobcard_notes.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'vehicle_details.fleet_number as fleet_no',
                'vehicle_details.vehicle_registration as vehicleregistration')
            ->leftJoin('jobcard_maintanance', 'jobcard_notes.jobcard_id', '=', 'jobcard_maintanance.id')
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
              ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('jobcard_notes.date_default', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($vehicleID) {
                if (!empty($vehicleID)) {
                    $query->where('jobcard_notes.vehicle_id', $vehicleID);
                }
            })
            ->OrderBy('jobcard_notes.vehicle_id', 'asc')
			->OrderBy('jobcard_notes.jobcard_id', 'asc')
			->OrderBy('jobcard_notes.id', 'asc')
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

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Job Card Management', 'Job Card print parts Page Accessed', "Accessed By User", 0);
        return view('job_cards.notes_print')->with($data);
    }
	// Delete Jobcard
	public function deleteJobcard(Request $request, jobcard_maintanance $jobcard)
    {
		$this->validate($request, [
             'reason' => 'required',
        ]);
		$SysData = $request->all();
        unset($SysData['_token']);
		// get fleet details
		$fleet = vehicle_detail::where('id',$jobcard->vehicle_id)->first();
        $reason = $SysData['reason'];
        AuditReportsController::store('Job Card Management', "Job card Deleted on Fleet No :($fleet->fleet_number)", "Reason: $reason", 0);
        $jobcard->delete();
        return response()->json();
    }
}