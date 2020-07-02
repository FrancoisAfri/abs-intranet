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
use App\helpDesk_setup;
use App\Province;
use App\modules;
use App\ContactCompany;
use App\module_access;
USE App\module_ribbons;
use App\doc_type_category;
use App\DivisionLevelTwo;
use App\companyidentity;
use App\product_products;
use App\System;
use App\autoRensponder;
use App\HelpDesk;
use App\helpdesk_Admin;
use App\operator;
use App\auto_escalation_settings;
use App\unresolved_tickets_settings;
use App\system_email_setup;
// use App\Http\Controllers\modules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class HelpdeskController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function systemAdd(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        $Sys = new HelpDesk();
        $Sys->name = $SysData['name'];
        $Sys->description = $SysData['description'];
        $Sys->status = 1;
        $Sys->save();
        AuditReportsController::store('Help Desk', 'Help Desk System Added', "name: $SysData[name]", 0);
        return response()->json();
    }

    public function createTicket() {
        $currentDate = time();
        $loggedInEmplID = Auth::user()->person->id;
        $user = Auth::user()->load('person')
                ->where('id', $loggedInEmplID)
                ->get();

        //$tickets = ticket::orderBy('id', 'asc')->get();
        $systems = HelpDesk::orderBy('name', 'asc')->get();
        $email = $user->first()->email;
        // return $email;
        $name = HRPerson::where('id', $loggedInEmplID)
                ->select('first_name', 'surname')
                ->get()
                ->first();
        $names = $name->first_name;
        $surname = $name->surname;

        $tickets = DB::table('ticket')
                ->select('ticket.*', 'help_desk.name as HelpDesk', 'help_desk.description as HelpDesk_Description')
                ->leftJoin('help_desk', 'ticket.helpdesk_id', '=', 'help_desk.id')
                //->where('helpdesk_Admin.helpdesk_id', $serviceID)
                ->orderBy('ticket.id')
                ->get();

        $data['page_title'] = "Help Desk";
        $data['page_description'] = "Help Desk Page";
        $data['breadcrumb'] = [
                ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-info', 'active' => 0, 'is_module' => 1],
                ['title' => 'Help Desk Page', 'active' => 1, 'is_module' => 0]
        ];

        $data['email'] = $email;
        $data['names'] = $names;
        $data['surname'] = $surname;
        $data['tickets'] = $tickets;
        $data['systems'] = $systems;
        $data['active_mod'] = 'Help Desk';
        $data['active_rib'] = 'Create Ticket';
        AuditReportsController::store('Help Desk', 'Help Desk Ticket Created', "Actioned By User", 0);
        return view('help_desk.create_ticket')->with($data);
    }

    public function editService(Request $request, HelpDesk $service) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $service->name = $request->input('name');
        $service->description = $request->input('description');
        $service->update();
        AuditReportsController::store('Help Desk', 'Help Desk Added', "Actioned By User", 0);
        return response()->json(['new_name' => $service->name, 'new_description' => $service->description], 200);
    }

    public function view_service(HelpDesk $service) {
        if ($service->status == 1) 
        {
            $serviceID = $service->id;
            $serviceName = $service->name;
            $description = $service->description;
            $systems = operator::orderBy('id', 'asc')->get();
            $employees = HRPerson::where('status', 1)->get(); 
			$helpdeskSetup = helpDesk_setup::where('helpdesk_id', $service->id)->first();
			$autoEscalationSettings = auto_escalation_settings::where('helpdesk_id', $service->id)->first();
			$unresolvedTicketsSettings = unresolved_tickets_settings::where('helpdesk_id', $service->id)->first();

            $autoRensponder = autoRensponder::where('helpdesk_id',$serviceID)->orderBy('id', 'des')->get()->first();  
             
            $emailSettings = system_email_setup::orderBy('id', 'des')->get()->first();  
               
            $operators = DB::table('operator')
                        ->select('operator.*','hr_people.first_name as firstname','hr_people.surname as surname')
                        ->leftJoin('hr_people', 'operator.operator_id', '=', 'hr_people.id')
                         ->where('operator.helpdesk_id', $serviceID)
                        ->orderBy('operator.operator_id')
                        ->get();
                        
			$unresolved_tickets_settings = unresolved_tickets_settings::where('helpdesk_id',$serviceID)->first();
           //return $unresolved_tickets_settings;
			$HelpdeskAdmin = DB::table('helpdesk_Admin')
                  ->select('helpdesk_Admin.*','hr_people.first_name as firstname','hr_people.surname as surname')
                  ->leftJoin('hr_people', 'helpdesk_Admin.admin_id', '=', 'hr_people.id')
                  ->where('helpdesk_Admin.helpdesk_id', $serviceID)
                  ->orderBy('helpdesk_Admin.helpdesk_id')
                  ->get(); 
             $data['helpdeskSetup'] = $helpdeskSetup;          
             $data['autoEscalationSettings'] = $autoEscalationSettings;          
             $data['unresolvedTicketsSettings'] = $unresolvedTicketsSettings;          
             $data['autoRensponder'] = $autoRensponder;          
             $data['products'] = $service;
             $data['emailSettings'] = $emailSettings;
             $data['HelpdeskAdmin'] = $HelpdeskAdmin;
             $data['employees']= $employees;
             $data['systems'] = $systems;
             $data['serviceID']  = $serviceID;     
             $data['serviceName'] = $serviceName;    
             $data['service'] = $service;
             $data['description'] = $description;
             $data['unresolved_tickets_settings'] = $unresolved_tickets_settings;
             $data['operators'] = $operators;
            // $data['$description'] = 'description';
             $data['page_title'] = "View Help Desk  ($serviceName) " ;
             $data['page_description'] = "Help Desk Settings page";
             $data['breadcrumb'] = [
            ['title' => 'HelpDesk', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage HelpDesk Settings', 'active' => 1, 'is_module' => 0]
            ];

              $data['active_mod'] = 'Help Desk';
              $data['active_rib'] = 'Setup';
            //AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            return view('help_desk.helpdesk_setup')->with($data);
        }
        else return back();
    } 

    public function Addoperator(Request $request, HelpDesk $serviceID) {
        $this->validate($request, [
                // 'name' => 'required',
                // 'description'=> 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);
        $help_desk = $serviceID->id;
        $operator = new operator();
        $operator->operator_id = $request->input('operator_id');
        $operator->helpdesk_id = $help_desk;
        $operator->status = 1;
        $operator->save();
        AuditReportsController::store('Help Desk', 'Help Desk Operator Added', "Actioned By User", 0);
        return response()->json();
    }
	public function editoperator(Request $request, operator $serviceID) {
        $this->validate($request, [
                // 'name' => 'required',
                // 'description'=> 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);
		
        $serviceID->operator_id = $request->input('operator_id');
        $serviceID->update();
        AuditReportsController::store('Help Desk', 'Help Desk Operator Edited', "Actioned By User", 0);
        return response()->json();
    }

    public function addAdmin(Request $request, HelpDesk $adminID) {
        $this->validate($request, [
        ]);

        $docData = $request->all();
        unset($docData['_token']);
        $help_desk = $adminID->id;
        $helpdeskadmin = new helpdesk_Admin();
        $helpdeskadmin->admin_id = $request->input('admin_id');
        $helpdeskadmin->helpdesk_id = $help_desk;
        $helpdeskadmin->status = 1;
        $helpdeskadmin->save();
        AuditReportsController::store('Help Desk', 'Help Desk Added', "Actioned By User", 0);
        return response()->json();
    }
	public function editAdmin(Request $request, helpdesk_Admin $adminID) {
        $this->validate($request, [
        ]);

        $docData = $request->all();
        unset($docData['_token']);
		
        $adminID->admin_id = $request->input('admin_id');
        $adminID->update();
        AuditReportsController::store('Help Desk', 'Help Desk Administrator Edited', "Actioned By User", 0);
        return response()->json();
    }

    public function addTicket(Request $request, ticket $tick) {
        $this->validate($request, [
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $loggedInEmplID = Auth::user()->person->id;

        $tick->name = $request->input('name');
        $tick->email = $request->input('email');
        $tick->user_id = $loggedInEmplID;
        $tick->helpdesk_id = $request->input('helpdesk_id');
        $tick->subject = $request->input('subject');
        $tick->message = $request->input('message');
        $tick->ticket_date = $currentDate = time();
        $tick->status = 1;
        $tick->save();
        // AuditReportsController::store('Help Desk', 'Help Desk Added', "Actioned By User", 0);
        return response()->json();
    }

    //client TICKET
      public function clientlTicket(Request $request, ticket $tick) {
        $this->validate($request, [
        ]);

        $docData = $request->all();
        unset($docData['_token']);

         $clientID = Auth::user()->person->id;

        $tick->name = $request->input('name');
        $tick->email = $request->input('email');
        $tick->client_id = $clientID;
        $tick->helpdesk_id = $request->input('helpdesk_id');
        $tick->subject = $request->input('subject');
        $tick->message = $request->input('message');
        $tick->ticket_date = $currentDate = time();
        $tick->status = 1;
        $tick->save();
        // AuditReportsController::store('Help Desk', 'Help Desk Added', "Actioned By User", 0);
        return response()->json();
    }

    public function viewTicket() {

        $data['page_title'] = "View Tickets";
        $data['page_description'] = "View Tickets Page";
        $data['breadcrumb'] = [
                ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-info', 'active' => 0, 'is_module' => 1],
                ['title' => 'Help Desk viewTicket Page', 'active' => 1, 'is_module' => 0]
        ];

        $helpdeskTickets = HelpDesk::orderBy('id', 'asc')->distinct()->get();
        if (!empty($helpdeskTickets))
            $helpdeskTickets->load('ticket');

        //helpdeskTickets

        $systems = HelpDesk::orderBy('name', 'asc')->get();

        $CompletedTickets = DB::table('ticket')->pluck('status');

        $ticketStatus = array('' => '', 1 => 'Pending Assignment', 2 => 'Assigned to operator', 3 => 'Completed by operator', 4 => 'Submited to Admin for review', 5 => 'resolved');

        $statusLabels = [-1 => "Rejected", 1 => "label-warning", 2 => 'label-success', 3 => 'label-info'];
        //return $helpdeskTickets;

        $data['statusLabels'] = $statusLabels;
        $data['ticketStatus'] = $ticketStatus;
        $data['helpdeskTickets'] = $helpdeskTickets;
        $data['systems'] = $systems;
        $data['active_mod'] = 'Help Desk';
        $data['active_rib'] = 'Create Request';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.create_request')->with($data);
    }

    public function setup(Request $request, helpDesk_setup $setup) {
        $this->validate($request, [
                // 'maximum_priority' => 'required',
                // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $setup->description = $request->input('description');
        $setup->maximum_priority = $request->input('maximum_priority');
		$setup->helpdesk_id = $request->input('helpdesk_id');
        $setup->save();
        return back();
    }

    public function notify_managers(Request $request, helpDesk_setup $service) {
        $this->validate($request, [
                // 'maximum_priority' => 'required',
                // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        //return $SysData;

        $time_from = $SysData['time_from'];
        $time_to = $SysData['time_to'];
        $HelpdeskID = $SysData['helpdesk_id'];

        //convert time to unix timestamp
        $start_time = strtotime($time_from);
        $end_time = strtotime($time_to);
        $service->time_from = $start_time;
        $service->time_to = $end_time;
        $service->notify_hr_email = $request->input('notify_hr_email');
        $service->notify_hr_sms_sms = $request->input('notify_hr_sms_sms');
        $service->notify_manager_email = $request->input('notify_manager_email');
        $service->notify_manager_sms = $request->input('notify_manager_sms');
        $service->helpdesk_id = $request->input('helpdesk_id');
        $service->save();
        return back();
    }

    public function auto_escalations(Request $request, auto_escalation_settings $settings) {
        $this->validate($request, [
                // 'maximum_priority' => 'required',
                // 'description' => 'required',
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        //return $SysData;
        $settings->auto_low = $request->input('auto_low');
        $settings->office_hrs_low = $request->input('office_hrs_low');
        $settings->notify_level_low = $request->input('notify_level_low');
        $settings->office_hrs_low_email = $request->input('office_hrs_low_email');
        $settings->office_hrs_low_sms = $request->input('office_hrs_low_sms');
        $settings->aftoffice_hrs_low_email = $request->input('aftoffice_hrs_low_email');
        $settings->aftoffice_hrs_low_sms = $request->input('aftoffice_hrs_low_sms');
        $settings->auto_mormal = $request->input('auto_mormal');
        $settings->office_hrs_normal = $request->input('office_hrs_normal');
        $settings->notify_level_normal = $request->input('notify_level_normal');
        $settings->office_hrs_normal_email = $request->input('office_hrs_normal_email');
        $settings->office_hrs_normal_sms = $request->input('office_hrs_normal_sms');
        $settings->aftoffice_hrs_normal_email = $request->input('aftoffice_hrs_normal_email');
        $settings->aftoffice_hrs_normal_sms = $request->input('aftoffice_hrs_normal_sms');
        $settings->auto_high = $request->input('auto_high');
        $settings->office_hrs_hihg = $request->input('office_hrs_hihg');
        $settings->notify_level_high = $request->input('notify_level_high');
        $settings->office_hrs_high_email = $request->input('office_hrs_high_email');
        $settings->office_hrs_high_sms = $request->input('office_hrs_high_sms');
        $settings->aftoffice_hrs_high_email = $request->input('aftoffice_hrs_high_email');
        $settings->aftoffice_hrs_high_sms = $request->input('aftoffice_hrs_high_sms');
        $settings->auto_critical = $request->input('auto_critical');
        $settings->office_hrs_critical = $request->input('office_hrs_critical');
        $settings->notify_level_critical = $request->input('notify_level_critical');
        $settings->office_hrs_critical_email = $request->input('office_hrs_critical_email');
        $settings->office_hrs_critical_sms = $request->input('office_hrs_critical_sms');
        $settings->aftoffice_hrs_critical_email = $request->input('aftoffice_hrs_critical_email');
        $settings->aftoffice_hrs_critical_sms = $request->input('aftoffice_hrs_critical_sms');
        $settings->helpdesk_id = $request->input('helpdesk_id');
		
        $settings->save();
        return back();
    }

    public function unresolved_tickets(Request $request, unresolved_tickets_settings $service) {
        $this->validate($request, [
                // 'maximum_priority' => 'required',
                // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		//return $SysData;
        $service->tickets_low = $request->input('tickets_low');
        $service->tickets_critical = $request->input('tickets_critical');
        $service->critical_oficehrs = $request->input('critical_oficehrs');
        $service->low_ah = $request->input('low_ah');
        $service->esc_low_email = $request->input('esc_low_email');
        $service->esc_low_sms = $request->input('esc_low_sms');
        $service->aftoffice_hrs_low_email = $request->input('aftoffice_hrs_low_email');
        $service->aftoffice_hrs_low_sms = $request->input('aftoffice_hrs_low_sms');
        $service->tickets_normal = $request->input('tickets_normal');
        $service->normal_oficehrs = $request->input('normal_oficehrs');
        $service->office_hrs_normal_email = $request->input('office_hrs_normal_email');
        $service->office_hrs_normal_sms = $request->input('office_hrs_normal_sms');
        $service->aftoffice_hrs_nomal_email = $request->input('aftoffice_hrs_nomal_email');
        $service->aftoffice_hrs_nomal_sms = $request->input('aftoffice_hrs_nomal_sms');
        $service->tickets_high = $request->input('tickets_high');
        $service->high_oficehrs = $request->input('high_oficehrs');
        $service->office_hrs_high_email = $request->input('office_hrs_high_email');
        $service->office_hrs_high_sms = $request->input('office_hrs_high_sms');
        $service->aftoffice_hrs_high_email = $request->input('aftoffice_hrs_high_email');
        $service->aftoffice_hrs_high_sms = $request->input('aftoffice_hrs_high_sms');
        $service->auto_critical = $request->input('auto_critical');
        $service->office_hrs_critical = $request->input('office_hrs_critical');
        $service->notify_level_critical = $request->input('notify_level_critical');
        $service->office_hrs_critical_email = $request->input('office_hrs_critical_email');
        $service->office_hrs_critical_sms = $request->input('office_hrs_critical_sms');
        $service->aftoffice_hrs_critical_email = $request->input('aftoffice_hrs_critical_email');
        $service->aftoffice_hrs_critical_sms = $request->input('aftoffice_hrs_critical_sms');
		$service->helpdesk_id = $request->input('helpdesk_id');
        $service->save();
		//return $service;
        return back();
    }

    public function auto_responder_messages(Request $request, autoRensponder $service) {
        $this->validate($request, [
                // 'maximum_priority' => 'required',
                // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $service->responder_messages = $request->input('responder_messages');
        $service->response_emails = $request->input('response_emails');
        $service->ticket_completion_req = $request->input('ticket_completion_req');
        $service->ticket_completed = $request->input('ticket_completed');
        $service->helpdesk_id = $request->input('helpdesk_id');
        $service->save();

        return back();
    }

    public function email_setup(Request $request, system_email_setup $service) {
        $this->validate($request, [
                // 'maximum_priority' => 'required',
                // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		
		$service->auto_processemails = $request->input('auto_processemails');
        $service->anly_processreplies = $request->input('anly_processreplies');
        $service->email_address = $request->input('email_address');
        $service->server_name = $request->input('server_name');
        $service->preferred_communication_method = $request->input('preferred_communication_method');
        $service->server_port = $request->input('server_port');
        $service->username = $request->input('username');
        $service->password = $request->input('password');
        $service->Signature_start = $request->input('Signature_start');
        $service->helpdesk_id = $request->input('helpdesk_id');
        $service->save();

         return back();
    }

    #Search

    public function viewsetup() {

        $data['page_title'] = "Help Desk";
        $data['page_description'] = "Help Desk Page";
        $data['breadcrumb'] = [
                ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-info', 'active' => 0, 'is_module' => 1],
                ['title' => 'Help Desk Page', 'active' => 1, 'is_module' => 0]
        ];

        $systems = HelpDesk::orderBy('name', 'asc')->get();

        $data['systems'] = $systems;
        $data['active_mod'] = 'Help Desk';
        $data['active_rib'] = '';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.add_system')->with($data);
    }

    public function searhTickets() {

        $helpdesk = HelpDesk::orderBy('name', 'asc')->get();

        $data['page_title'] = "Help Desk";
        $data['page_description'] = "Help Desk Page";
        $data['breadcrumb'] = [
                ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
                ['title' => 'Help Search Page', 'active' => 1, 'is_module' => 0]
        ];

        $data['helpdesk'] = $helpdesk;
        $data['active_mod'] = 'Help Desk';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.helpdesk_search')->with($data);
    }

    public function searchResults(Request $request) {

        $this->validate($request, [
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $status = $request->status;
        $TicketNumber = $request->ticket_no;
        $HelpdeskID = $request->helpdesk_id;
        $actionFrom = $actionTo = 0;
        $actionDate = $request['ticket_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        #
        $ticketStatus = array('' => '', 1 => 'Pending Assignment', 2 => 'Assigned to operator', 3 => 'Completed by operator', 4 => 'Submited to Admin for review', 5 => 'resolved');

        $tickets = DB::table('ticket')
                ->select('ticket.*', 'help_desk.name as helpdeskName')
                ->leftJoin('help_desk', 'ticket.helpdesk_id', '=', 'help_desk.id')
                ->where(function ($query) use ($actionFrom, $actionTo) {
                    if ($actionFrom > 0 && $actionTo > 0) {
                        $query->whereBetween('ticket_date', [$actionFrom, $actionTo]);
                    }
                })
                ->where(function ($query) use ($HelpdeskID) {
                    if (!empty($HelpdeskID)) {
                        $query->where('helpdesk_id', $HelpdeskID);
                    }
                })
                ->where(function ($query) use ($status) {
                    if (!empty($status)) {
                        $query->where('status', 'ILIKE', "%$status%");
                    }
                })
                ->where(function ($query) use ($TicketNumber) {
                    if (!empty($TicketNumber)) {
                        $query->where('ticket_number', 'ILIKE', "%$TicketNumber%");
                    }
                })
                ->orderBy('id')
                ->get();


        $data['ticketStatus'] = $ticketStatus;
        $data['page_title'] = "Help Desk";
        $data['page_description'] = "Help Desk Page";
        $data['breadcrumb'] = [
                ['title' => 'Help Desk', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
                ['title' => 'Help Search Page', 'active' => 1, 'is_module' => 0]
        ];
        //
        $data['tickets'] = $tickets;
        $data['active_mod'] = 'Help Desk';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('help_desk.helpdesk_results')->with($data);
    }

        public function helpdeskAct(HelpDesk $desk) {
        if ($desk->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $desk->status = $stastus;
        $desk->update();
        return back();
    }

    public function operatorAct(operator $desk) {
        if ($desk->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $desk->status = $stastus;
        $desk->update();
        return back();
    }

    public function help_deskAdmin(helpdesk_Admin $desk) {
        if ($desk->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $desk->status = $stastus;
        $desk->update();
        return back();
    }
}