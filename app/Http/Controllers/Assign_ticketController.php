<?php

namespace App\Http\Controllers;

use App\activity;
use App\contacts_company;
use App\HRPerson;
use App\hr_people;
use App\operator;
use App\programme;
use App\projects;
use App\User;
use App\System;
use App\ticket;
use App\helpdesk_Admin;
use App\AuditTrail;
use App\Mail\assignOperatorEmail;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Assign_ticketController extends Controller
{
   public function __construct()
	    {
	        $this->middleware('auth');
	    }

     public function assign_tickets(ticket $ticket) {

     	$ID = $ticket->id;
     	//return $ID;
     	$helpdeskId = $ticket->helpdesk_id;

      $tickets = $ticket->where('helpdesk_id', $ID)
                ->orderBy('id', 'asc')
                ->get();

          $names = DB::table('help_desk')
                  ->select('name' ) 
                       ->where('id', $ID)
                        ->get();
          $Names = $names->first()->name;

 		  $operators = DB::table('operator')
				        ->select('operator.*','hr_people.first_name as firstname','hr_people.surname as surname')
				        ->leftJoin('hr_people', 'operator.operator_id', '=', 'hr_people.id')
				        ->where('operator.helpdesk_id', $ID)
				        ->orderBy('operator.helpdesk_id')
				        ->get();

          		$data['ID'] = $ID;
          		$data['Names'] = $Names;		      
          		$data['operators'] = $operators;		        
           		$data['tickets'] = $tickets; 
              $data['active_mod'] = 'Help Desk';
              $data['active_rib'] = '';   
              $data['page_title'] = "Assign Ticket";
              $data['page_description'] = "Assign Help Desk  Ticket";
              $data['breadcrumb'] = [
                  ['title' => 'Assign Ticket ', 'path' => '/Help Desk', 'icon' => 'fa fa-info', 'active' => 0, 'is_module' => 1],
                  ['title' => 'Assign Ticket Page', 'active' => 1, 'is_module' => 0]
              ];

     
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.Assign_ticket')->with($data);
    }


      public function assign_operator(Request $request, ticket $operatorID){
    		$this->validate($request, [
            // 'maximum_priority' => 'required',
            // 'description' => 'required',        
        ]);
		 $this->validate($request, [

        ]);
    	
    	 $helpdeskId = $operatorID->helpdesk_id;
    	 $ticketID = $operatorID->id;
       $userID = $operatorID->user_id;
    	 $currentDate = $currentDate = time();
        $docData = $request->all();
        unset($docData['_token']);
        //$help_desk = $serviceID->id;
        $AssignOperator  = 'Assign task to Operator';
        $operator = $request->input('operator_id');
        $operatorID->operator_id = $operator;
        $operatorID->status = 2;
        $operatorID->update();

        #send email to operator
        $operators = HRPerson::where('user_id', 2)->first();
     //return $operators;
        Mail::to($operators->email)->send(new assignOperatorEmail($operators));

        #assign Operator to Task

       //  TaskManagementController::store($AssignOperator,$currentDate,$currentDate,0,$operator,$operators
      	// ,0,0,0,0,0,0,0,0,0,0 ,$helpdeskId,$ticketID);

        AuditReportsController::store('Assign operators', 'Assigned Operator to a atask', "Actioned By User", 0);
        return response()->json();

    }
}
