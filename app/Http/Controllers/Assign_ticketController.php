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
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Assign_ticketController extends Controller
{
   public function __construct()
	    {
	        $this->middleware('auth');
	    }

     public function assign_tickets(ticket $ticket) {

     	$ID = $ticket->id;
     	$helpdeskId = $ticket->helpdesk_id;

  //    	$helpdeskTickets = ticket::orderBy('id', 'asc')->get();
		// if (!empty($helpdeskTickets)) $helpdeskTickets->load('hrPeople');



          $names = DB::table('help_desk')
                  ->select('name' ) 
                       ->where('id', $ID)
                        ->get();
           $Names = $names->first()->name;


    
     	$tickets = DB::table('ticket')
                ->where('helpdesk_id', $ID)
                ->orderBy('id', 'asc')
                ->get();
            // return $tickets;
 		  $operators = DB::table('operator')
				        ->select('operator.*','hr_people.first_name as firstname','hr_people.surname as surname')
				        ->leftJoin('hr_people', 'operator.operator_id', '=', 'hr_people.id')
				        ->where('operator.helpdesk_id', $helpdeskId)
				        ->orderBy('operator.helpdesk_id')
				        ->get();

				      //  return $operators;

		$data['ID'] = $ID;
		$data['Names'] = $Names;		      // return $operators;
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
    	
        $docData = $request->all();
        unset($docData['_token']);
        //$help_desk = $serviceID->id;
        //$operator  =  new operator();
        $operatorID->operator_id = $request->input('operator_id');
        $operatorID->status = 2;
        $operatorID->update();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();

    }
}
