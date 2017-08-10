<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\hr_people;
use App\DivisionLevel;
use App\employee_documents;
use App\doc_type;
use App\User;
use App\leave_custom;
use App\ticket;
use App\business_card;
use App\Province;
use App\modules;
use App\module_access;
USE App\module_ribbons;
use App\doc_type_category;
use App\DivisionLevelTwo;
use App\companyidentity;
use App\product_products;
use App\System;
use App\helpdesk_Admin;
use App\operator;
// use App\Http\Controllers\modules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class HelpdeskController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

       public function viewsetup() {

        $data['page_title'] = "Help Desk";
        $data['page_description'] = "Help Desk Page";
        $data['breadcrumb'] = [
            ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-info', 'active' => 0, 'is_module' => 1],
            ['title' => 'Help Desk Page', 'active' => 1, 'is_module' => 0]
        ];

     //    //$user->load('person');
     //    //$avatar = $user->person->profile_pic;
  
  	 $systems = System::orderBy('name', 'asc')->get();

  	 //return $systems;
     
       
        $data['systems'] = $systems;
        $data['active_mod'] = 'Help Desk';
        $data['active_rib'] = '';
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.add_system')->with($data);
    }


       public function systemAdd(Request $request ){
		$this->validate($request, [
            'name' => 'required',
            'description' => 'required',        
        ]);
		$SysData = $request->all();
		unset($SysData['_token']);
		$Sys = new System();	
		$Sys->name = $SysData['name'];
		$Sys->description =$SysData['description'];
		$Sys->status = 1;
        $Sys->save();
		// AuditReportsController::store('Employee Records', 'Job Title Category Added', "price: $priceData[price]", 0);
		return response()->json();
    }

    public function createTicket()
    {
    	$currentDate = time();
    	 $loggedInEmplID = Auth::user()->person->id;
    	$user = Auth::user()->load('person')
		    	->where('id', $loggedInEmplID)
		    	->get();

  		//$tickets = ticket::orderBy('id', 'asc')->get();
  	    $systems = System::orderBy('name', 'asc')->get();
  	   	  $email = $user->first()->email;
  	   	  	// return $email;
  	   	$name = HRPerson::where('id', $loggedInEmplID )
  	   					->select('first_name', 'surname')
  	   					->get()
  	   					->first();
  	   					$names = $name ->first_name;
  	   					$surname =$name ->surname;
	
  	  
  	   	 $tickets = DB::table('ticket')
				  ->select('ticket.*','help_desk.name as HelpDesk','help_desk.description as HelpDesk_Description')
				  ->leftJoin('help_desk', 'ticket.helpdesk_id', '=', 'help_desk.id')
				  //->where('helpdesk_Admin.helpdesk_id', $serviceID)
				  ->orderBy('ticket.id')
				  ->get();

				 // return $tickets;

     	$data['page_title'] = "Help Desk";
        $data['page_description'] = "Help Desk Page";
        $data['breadcrumb'] = [
            ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-info', 'active' => 0, 'is_module' => 1],
            ['title' => 'Help Desk Page', 'active' => 1, 'is_module' => 0]
        ];

        $data['email'] =$email;
        $data['names'] = $names;
        $data['surname'] = $surname;
       	$data['tickets'] = $tickets;
        $data['systems'] = $systems;
        $data['active_mod'] = 'Help Desk';
        $data['active_rib'] = 'Create Ticket';
		//AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.create_ticket')->with($data);


    }

     public function editService(Request $request, System $service){
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $service->name = $request->input('name');
        $service->description = $request->input('description');
        $service->update();
        AuditReportsController::store('Employee Records', 'Category Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $service->name, 'new_description' => $service->description], 200);
    }


     public function view_service(System $service) {
        if ($service->status == 1) 
		{
			$serviceID = $service->id;
			//return $serviceID ;
			$serviceName = $service->name;
			//return $serviceName;
			$description = $service->description;
		    $systems = operator::orderBy('id', 'asc')->get();
		    // operator
		    $employees = HRPerson::where('status', 1)->get();
		     //return $employees;

		    $operators = DB::table('operator')
				        ->select('operator.*','hr_people.first_name as firstname','hr_people.surname as surname')
				        ->leftJoin('hr_people', 'operator.operator_id', '=', 'hr_people.id')
				         ->where('operator.helpdesk_id', $serviceID)
				        ->orderBy('operator.operator_id')
				        ->get();
// return $operators;
		   $HelpdeskAdmin = DB::table('helpdesk_Admin')
				  ->select('helpdesk_Admin.*','hr_people.first_name as firstname','hr_people.surname as surname')
				  ->leftJoin('hr_people', 'helpdesk_Admin.admin_id', '=', 'hr_people.id')
				  ->where('helpdesk_Admin.helpdesk_id', $serviceID)
				  ->orderBy('helpdesk_Admin.helpdesk_id')
				  ->get();

				         //return $HelpdeskAdmin;
		     $data['products'] = $service;
     		 $data['HelpdeskAdmin'] = $HelpdeskAdmin;
       		 $data['employees']= $employees;
             $data['systems'] = $systems;
		     $data['serviceID']  = $serviceID;     
		     $data['serviceName'] = $serviceName;    
     		 $data['service'] = $service;
       		 $data['description'] = $description;
             $data['operators'] = $operators;
             $data['$description'] = 'description';
			 $data['page_title'] = "View Help Desk  ($serviceName) " ;
			 $data['page_description'] = "Help Desk Settings page";
			 $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Prices', 'active' => 1, 'is_module' => 0]
       		];

			  $data['active_mod'] = 'Help Desk';
              $data['active_rib'] = 'Setup';
			//AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
			return view('help_desk.helpdesk_setup')->with($data);
		}
		else return back();
    } 

		 public function Addoperator(Request $request, System $serviceID ) {
        $this->validate($request, [
            // 'name' => 'required',
            // 'description'=> 'required',

        ]);
    	
        $docData = $request->all();
        unset($docData['_token']);
        $help_desk = $serviceID->id;
        $operator  =  new operator();
        $operator->operator_id = $request->input('operator_id');
        $operator->helpdesk_id = $help_desk;
        $operator->status = 1;
        $operator->save();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();

    }

    	 public function addAdmin(Request $request, System $adminID ) 
    	 {
        $this->validate($request, [

        ]);
    	
        $docData = $request->all();
        unset($docData['_token']);
        $help_desk = $adminID->id;
        $helpdeskadmin  =  new helpdesk_Admin();
        $helpdeskadmin->admin_id = $request->input('admin_id');
        $helpdeskadmin->helpdesk_id = $help_desk;
        $helpdeskadmin->status = 1;
        $helpdeskadmin->save();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();

    }

  
    public function addTicket(Request $request , ticket $tick) 
    	 {
        $this->validate($request, [

        ]);
    	
        $docData = $request->all();
        unset($docData['_token']);
      
        $tick->name = $request->input('name');
        $tick->email = $request->input('email');
        $tick->helpdesk_id = $request->input('helpdesk_id');
        $tick->subject = $request->input('subject');
        $tick->message = $request->input('message');
        $tick->ticket_date = $currentDate = time();
        $tick->status = 1;
        $tick->save();
       // AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();

    }


		public function viewTicket(){

        $data['page_title'] = "View Tickets";
        $data['page_description'] = "View Tickets Page";
        $data['breadcrumb'] = [
            ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-info', 'active' => 0, 'is_module' => 1],
            ['title' => 'Help Desk Page', 'active' => 1, 'is_module' => 0]
        ];


       $helpdeskTickets = system::orderBy('id', 'asc')->distinct()->get();
		if (!empty($helpdeskTickets)) $helpdeskTickets->load('ticket');

			

	// 	$attendees = DB::table('ticket')
	// 				->distinct()
	// 				->get(['helpdesk_id']);

					//return $helpdeskTickets;

		// SELECT COUNT(DISTINCT Country) FROM Customers;
		 
  	   $systems = System::orderBy('name', 'asc')->get();
  	 
  	   $ticketStatus = ['' => '', -1 => "Rejected", 1 => "Pending Assignment", 2 => 'Assigned to operator', 3 => 'Completed'];

  	     $statusLabels = [-1 => "Rejected", 1 => "label-warning", 2 => 'label-success', 3 => 'label-info'];
		//return $helpdeskTickets;
  	    $programmeStatus = ['' => '', -1 => "Rejected", 1 => "Pending General Manager's Approval", 2 => 'Approved', 3 => 'Completed'];
  	    
		$data['status_strings'] = $programmeStatus; 
 		$data['statusLabels'] = $statusLabels;			
 	 	$data['ticketStatus'] = $ticketStatus;
 		$data['helpdeskTickets'] = $helpdeskTickets;
        $data['systems'] = $systems;
        $data['active_mod'] = 'Help Desk';
             $data['active_rib'] = 'Create Request';
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.create_request')->with($data);

}

    public function setup(Request $request, System $service){
    		$this->validate($request, [
            // 'maximum_priority' => 'required',
            // 'description' => 'required',        
        ]);
		$SysData = $request->all();
		unset($SysData['_token']);

		return $SysData;
	//	$service->maximum_priority = $request->input('maximum_priority');
		//$service->save();
		return back();

    }

    public function notify_managers(Request $request, System $service){
    		$this->validate($request, [
            // 'maximum_priority' => 'required',
            // 'description' => 'required',        
        ]);
		$SysData = $request->all();
		unset($SysData['_token']);

		return $SysData;
	//	$service->maximum_priority = $request->input('maximum_priority');
		//$service->save();
		return back();

    }
    public function auto_escalations(Request $request, System $service){
    		$this->validate($request, [
            // 'maximum_priority' => 'required',
            // 'description' => 'required',        
        ]);
		$SysData = $request->all();
		unset($SysData['_token']);

		return $SysData;
	//	$service->maximum_priority = $request->input('maximum_priority');
		//$service->save();
		return back();

    }
     public function unresolved_tickets(Request $request, System $service){
    		$this->validate($request, [
            // 'maximum_priority' => 'required',
            // 'description' => 'required',        
        ]);
		$SysData = $request->all();
		unset($SysData['_token']);

		return $SysData;
	//	$service->maximum_priority = $request->input('maximum_priority');
		//$service->save();
		return back();

    }

     public function auto_responder_messages(Request $request, System $service){
    		$this->validate($request, [
            // 'maximum_priority' => 'required',
            // 'description' => 'required',        
        ]);
		$SysData = $request->all();
		unset($SysData['_token']);

		return $SysData;
	//	$service->maximum_priority = $request->input('maximum_priority');
		//$service->save();
		return back();

    }

     public function email_setup(Request $request, System $service){
    		$this->validate($request, [
            // 'maximum_priority' => 'required',
            // 'description' => 'required',        
        ]);
		$SysData = $request->all();
		unset($SysData['_token']);

		return $SysData;
	//	$service->maximum_priority = $request->input('maximum_priority');
		//$service->save();
		return back();

    }
}
