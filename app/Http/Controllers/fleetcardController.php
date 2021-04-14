<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\DivisionLevel;
use App\fleetcard_type;
use App\FleetType;
use App\HRPerson;
use App\CompanyIdentity;
use App\VehicleHistory;
use App\Http\Requests;
use App\Mail\confirm_collection;
use App\Mail\FleetRejection;
use App\Mail\VehicleApproved;
use App\Users;
use App\vehicle_detail;
use App\vehicle_fleet_cards;
use App\vehicle_maintenance;
use App\Vehicle_managemnt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class fleetcardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $Vehiclemanagemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $hrDetails = HRPerson::where('status', 1)->get();
        $fleetcardtype = FleetType::orderBy('id', 'desc')->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_detail = vehicle_detail::orderBy('id', 'desc')->get();

        $data['page_title'] = "Fleet Cards";
        $data['page_description'] = "Fleet Cards Search";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/fleet_cards', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicle_detail'] = $vehicle_detail;
        $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fleet Cards';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Fleet_cards.search_fleet_cards')->with($data);
    }

    /**
     * Search Fleet cards.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function fleetcardSearch(Request $request)
    {
        $this->validate($request, [
            // 'required_from' => 'bail|required',
            // 'required_to' => 'bail|required',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $cardtype = $request['card_type_id'];
        $fleetnumber = $request['fleet_number'];
        $company = $request['company_id'];
        $holder = $vehicleData['holder_id'];
        $status = $vehicleData['status'];

        $Vehiclemanagemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $hrDetails = HRPerson::where('status', 1)->get();
        $fleetcardtype = FleetType::orderBy('id', 'desc')->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_detail = vehicle_detail::orderBy('id', 'desc')->get();

        $vehiclefleetcards = vehicle_fleet_cards::orderBy('id', 'asc')->get();

        $fleetcards = DB::table('vehicle_fleet_cards')
            ->select('vehicle_fleet_cards.*', 'contact_companies.name as Vehicle_Owner'
                , 'hr_people.first_name as first_name', 'hr_people.surname as surname'
                , 'fleet_type.name as type_name', 'vehicle_details.fleet_number as fleetnumber')
            ->leftJoin('contact_companies', 'vehicle_fleet_cards.company_id', '=', 'contact_companies.id')
            ->leftJoin('hr_people', 'vehicle_fleet_cards.holder_id', '=', 'hr_people.id')
            ->leftJoin('fleet_type', 'vehicle_fleet_cards.card_type_id', '=', 'fleet_type.id')
            ->leftJoin('vehicle_details', 'vehicle_fleet_cards.fleet_number', '=', 'vehicle_details.id')
            ->where(function ($query) use ($cardtype) {
                if (!empty($cardtype)) {
                    $query->where('vehicle_fleet_cards.card_type_id', $cardtype);
                }
            })
            ->where(function ($query) use ($fleetnumber) {
                if (!empty($fleetnumber)) {
                    $query->where('vehicle_fleet_cards.fleet_number', $fleetnumber);
                }
            })
            ->where(function ($query) use ($company) {
                if (!empty($company)) {
                    $query->where('vehicle_fleet_cards.company_id', $company);
                }
            })
            ->where(function ($query) use ($holder) {
                if (!empty($holder)) {
                    $query->where('vehicle_fleet_cards.holder_id', $holder);
                }
            })
            ->where(function ($query) use ($status) {
                if (!empty($status)) {
                    $query->where('vehicle_fleet_cards.status', $status);
                }
            })
            ->orderBy('vehicle_fleet_cards.id', 'asc')
            ->get();

        $status = array(1 => ' Active', 2 => ' InActive');

        $data['vehicle_detail'] = $vehicle_detail;
        $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['status'] = $status;
        $data['fleetcards'] = $fleetcards;
        $data['page_title'] = "Fleet Management";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/fleet_cards', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'View Vehicle Search Results', "view Audit Results", 0);

        return view('Vehicles.Fleet_cards.fleetcard_results')->with($data);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function Addfleetcard(Request $request)
    {
        $this->validate($request, [
            'cvs_number' => 'bail|required|max:3',
            'issued_date' => 'required',
            'expiry_date' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);
        //convert dates to unix time stamp
        if (isset($docData['issued_date'])) {
            $docData['issued_date'] = str_replace('/', '-', $docData['issued_date']);
            $docData['issued_date'] = strtotime($docData['issued_date']);
        }
        if (isset($docData['expiry_date'])) {
            $docData['expiry_date'] = str_replace('/', '-', $docData['expiry_date']);
            $docData['expiry_date'] = strtotime($docData['expiry_date']);
        }

        $vehiclefleetcards = new vehicle_fleet_cards();
        $vehiclefleetcards->card_type_id = !empty($docData['card_type_id']) ? $docData['card_type_id'] : 0;
        $vehiclefleetcards->fleet_number = !empty($docData['fleet_number']) ? $docData['fleet_number'] : 0;
        $vehiclefleetcards->company_id = !empty($docData['company_id']) ? $docData['company_id'] : 0;
        $vehiclefleetcards->holder_id = !empty($docData['holder_id']) ? $docData['holder_id'] : 0;
        $vehiclefleetcards->card_number = $docData['card_number'];
        $vehiclefleetcards->cvs_number = $docData['cvs_number'];
        $vehiclefleetcards->issued_date = $docData['issued_date'];
        $vehiclefleetcards->expiry_date = $docData['expiry_date'];
        $vehiclefleetcards->status = $docData['status'];
        $vehiclefleetcards->save();

        AuditReportsController::store('Fleet Management', 'Add Vehicle Fleet Card', "Add Vehicle Fleet Card", 0);
        return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editfleetcard(Request $request, vehicle_fleet_cards $vehiclefleetcards)
    {
        $this->validate($request, [
            //'holder_id' => 'bail|required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);
        //convert dates to unix time stamp
        if (isset($docData['issued_date'])) {
            $docData['issued_date'] = str_replace('/', '-', $docData['issued_date']);
            $docData['issued_date'] = strtotime($docData['issued_date']);
        }
        if (isset($docData['expiry_date'])) {
            $docData['expiry_date'] = str_replace('/', '-', $docData['expiry_date']);
            $docData['expiry_date'] = strtotime($docData['expiry_date']);
        }

        $vehiclefleetcards->card_type_id = !empty($docData['card_type_id']) ? $docData['card_type_id'] : 0;
        $vehiclefleetcards->fleet_number = !empty($docData['fleet_number']) ? $docData['fleet_number'] : 0;
        $vehiclefleetcards->company_id = !empty($docData['company_id']) ? $docData['company_id'] : 0;
        $vehiclefleetcards->holder_id = !empty($docData['holder_id']) ? $docData['holder_id'] : 0;
        $vehiclefleetcards->card_number = $docData['card_number'];
        $vehiclefleetcards->cvs_number = $docData['cvs_number'];
        $vehiclefleetcards->issued_date = $docData['issued_date'];
        $vehiclefleetcards->status = $docData['status'];
        $vehiclefleetcards->update();

        AuditReportsController::store('Fleet Management', 'Update Vehicle Fleet Card', "Update Vehicle Fleet Card", 0);
        return response()->json();
        // return redirect()->to('/vehicle_management/fleet_card_search');

    }

    /**
     *Driver Admin Page
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function driverAdmin()
    {

        $Vehiclemanagemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $hrDetails = HRPerson::where('status', 1)->get();
        $fleetcardtype = fleetcard_type::orderBy('id', 'desc')->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_detail = vehicle_detail::orderBy('id', 'desc')->get();

        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Cards Search";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicle_detail'] = $vehicle_detail;
        $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Driver Administration';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Driver Admin.search_drivers')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function driversearch(Request $request)
    {
        $this->validate($request, [
            // 'driver_id' => 'bail|required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $Company = !empty($docData['division_level_5']) ? $docData['division_level_5'] : 0;
        $Department = !empty($docData['division_level_4']) ? $docData['division_level_4'] : 0;
        $employee = $docData['employee'];
        $status = $docData['status'];

        $users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();

        $drverdetails = DB::table('drver_details')
            ->select('drver_details.*', 'division_level_fives.name as company', 'division_level_fours.name as Department',
                'hr_people.first_name as first_name', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'drver_details.hr_person_id', '=', 'hr_people.id')
            ->leftJoin('division_level_fives', 'drver_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'drver_details.division_level_4', '=', 'division_level_fours.id')
            ->where(function ($query) use ($status) {
                if (!empty($status)) {
                    $query->where('drver_details.status', $status);
                }
            })
            ->orderBy('drver_details.id')
            ->get();

        $status = array(1 => ' Active', 2 => ' InActive');
        $data['status'] = $status;
        $data['drverdetails'] = $drverdetails;
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Driver Administration';
        AuditReportsController::store('Fleet Management', 'View Vehicle Search Results', "view Audit Results", 0);

        return view('Vehicles.Driver Admin.drivers_results')->with($data);
    }

    public function vehicle_approval(Request $request)
    {
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_maintenance = vehicle_maintenance::orderBy('id', 'asc')->get();

        $Vehiclemanagemnt = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'division_level_fives.name as company', 'division_level_fours.name as Department'
                , 'vehicle_model.name as vehiclemodel')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
			->whereIn('vehicle_details.status', [2, 3])
			->orderByRaw('LENGTH(vehicle_details.fleet_number) asc')
			->orderBy('vehicle_details.fleet_number', 'ASC')
            ->get();

        $vehicleConfigs = DB::table('vehicle_configuration')->pluck('new_vehicle_approval');
        $vehicleConfig = $vehicleConfigs->first();


        $data['page_title'] = "Vehicle Approval";
        $data['page_description'] = "Vehicle Approvals";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Approvals ', 'active' => 1, 'is_module' => 0]
        ];

        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Fleet Approval/rejection';

        AuditReportsController::store('Vehicle Approvals', 'Vehicle Approvals Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Vehicle Approvals.vehicle_approvals')->with($data);
    }

    public function vehicleApprovals(Request $request, vehicle_maintenance $vehicle_maintenance)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
		$vehicleConfigs = DB::table('vehicle_configuration')->pluck('new_vehicle_approval');
        $vehicleConfig = $vehicleConfigs->first();
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'vehicleappprove'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $vehicleID = $aValue[1];
                if (count($sValue) > 1) {
                    $status = $sValue[1];
                } else $status = $sValue[0];
                $vehicle_maintenance->updateOrCreate(['id' => $vehicleID], ['status' => $status]);
				// add to vehicle history 
				$VehicleHistory = new VehicleHistory();
				$VehicleHistory->vehicle_id = $vehicleID;
				$VehicleHistory->user_id = Auth::user()->person->id;
				$VehicleHistory->status = 1;
				$VehicleHistory->comment = "New Vehicle Approved";
				$VehicleHistory->action_date = time();
				$VehicleHistory->save();
				
				# Send email to admin and person who added the vehicle
				if ($vehicleConfig == 1) {
					
					$vehicle_maintenance = vehicle_maintenance::where('id', $vehicleID)->first();
					if (!empty($vehicle_maintenance->author_id))
					{
						$authoretails = HRPerson::where('id', $vehicle_maintenance->author_id)->select('first_name', 'surname', 'email')->first();
						$email = !empty($authoretails->email) ? $authoretails->email : ''; 
						$firstname = !empty($authoretails->first_name) ? $authoretails->first_name : ''; 
						$surname = !empty($authoretails->surname) ? $authoretails->surname : '';
						if (!empty($authoretails->email))
									Mail::to($email)->send(new VehicleApproved($firstname, $surname, $email, $vehicleID));
					}
				}
            }
        }
		// Reject Code
        foreach ($results as $sKey => $sValue) {
            if (strlen(strstr($sKey, 'declined_'))) {
                list($sUnit, $iID) = explode("_", $sKey);
                if ($sUnit == 'declined' && !empty($sValue)) {
                    if (empty($sValue)) $sValue = $sReasonToReject;

                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['status' => 3]);
                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['reject_reason' => $sValue]);
                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['reject_timestamp' => time()]);
                    $vehicle_maintenance->updateOrCreate(['id' => $iID], ['rejector_id' => Auth::user()->person->id]);
					// add to vehicle history 
					$VehicleHistory = new VehicleHistory();
					$VehicleHistory->vehicle_id = $iID;
					$VehicleHistory->user_id = Auth::user()->person->id;
					$VehicleHistory->status = 3;
					$VehicleHistory->comment = "New Vehicle Rejected, Reason:".$sValue;
					$VehicleHistory->action_date = time();
					$VehicleHistory->save();
                    # Send email to admin and person who added the vehicle
					if ($vehicleConfig == 1) {
					$managerIDs = DB::table('security_modules_access')
					   ->select('security_modules_access.*','security_modules.*') 
					   ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
					   ->where('code_name', 'vehicle')
					   ->where('access_level','>=', 4)
					   ->pluck('user_id');
				 
					   foreach ($managerIDs as $manID) {
								$usedetails = HRPerson::where('user_id', $manID)->select('first_name', 'surname', 'email')->first();
								$email = !empty($usedetails->email) ? $usedetails->email : ''; 
								$firstname = !empty($usedetails->first_name) ? $usedetails->first_name : ''; 
								$surname = !empty($usedetails->surname) ? $usedetails->surname : '';
								if (!empty($email))
									Mail::to($email)->send(new FleetRejection($firstname, $surname, $email, $iID));

						}
						$vehicle_maintenance = vehicle_maintenance::where('id', $iID)->first();
						if (!empty($vehicle_maintenance->author_id))
						{
							$authoretails = HRPerson::where('id', $vehicle_maintenance->author_id)->select('first_name', 'surname', 'email')->first();
							$email = !empty($authoretails->email) ? $authoretails->email : ''; 
							$firstname = !empty($authoretails->first_name) ? $authoretails->first_name : ''; 
							$surname = !empty($authoretails->surname) ? $authoretails->surname : '';
							if (!empty($authoretails->email))
										Mail::to($email)->send(new FleetRejection($firstname, $surname, $email, $iID));
						}
					}
					
                }
            }
        }
        $sReasonToReject = '';
        AuditReportsController::store('Fleet Management', 'Approve Vehicle ', "Vehicle has been Approved", 0);
        return back();
    }
	/// Fleet approval / reject single
	
	public function vehicleApprovalsSingle(Request $request, vehicle_maintenance $fleet)
    {
		$fleet->status = 1;
		$fleet->update();
		// add to vehicle history 
		$VehicleHistory = new VehicleHistory();
		$VehicleHistory->vehicle_id = $fleet->id;
		$VehicleHistory->user_id = Auth::user()->person->id;
		$VehicleHistory->status = 1;
		$VehicleHistory->comment = "New Vehicle Approved";
		$VehicleHistory->action_date = time();
		$VehicleHistory->save();
		
		$vehicleConfigs = DB::table('vehicle_configuration')->pluck('new_vehicle_approval');
        $vehicleConfig = $vehicleConfigs->first();
		# Send email to admin and person who added the vehicle
		if ($vehicleConfig == 1) {
			
			if (!empty($fleet->author_id))
			{
				$authoretails = HRPerson::where('id', $fleet->author_id)->select('first_name', 'surname', 'email')->first();
				$email = !empty($authoretails->email) ? $authoretails->email : ''; 
				$firstname = !empty($authoretails->first_name) ? $authoretails->first_name : ''; 
				$surname = !empty($authoretails->surname) ? $authoretails->surname : '';
				if (!empty($authoretails->email))
							Mail::to($email)->send(new VehicleApproved($firstname, $surname, $email, $fleet->id));
			}
		}
		
		AuditReportsController::store('Fleet Management', 'Approve Vehicle ', "Vehicle has been Approved", 0);
        return back();
    }
	public function vehicleRejectsSingle(Request $request, vehicle_maintenance $fleet)
    {
        $this->validate($request, [
             'rejection_reason' => 'required',
        ]);
		$vehicleData = $request->all();
        unset($vehicleData['_token']);

		// Reject Code
		$fleet->status = 3;	
		$fleet->reject_timestamp = time();	
		$fleet->reject_reason = $vehicleData['rejection_reason'];	
		$fleet->rejector_id = Auth::user()->person->id;	
		$fleet->update();
		// Send Email to relevant people
		$managerIDs = DB::table('security_modules_access')
		   ->select('security_modules_access.*','security_modules.*') 
		   ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
		   ->where('code_name', 'vehicle')
		   ->where('access_level','>=', 4)
		   ->pluck('user_id');
		// add to vehicle history 
		$VehicleHistory = new VehicleHistory();
		$VehicleHistory->vehicle_id = $fleet->id;
		$VehicleHistory->user_id = Auth::user()->person->id;
		$VehicleHistory->status = 3;
		$VehicleHistory->comment = "New Vehicle Rejected Reason:".$vehicleData['rejection_reason'];
		$VehicleHistory->action_date = time();
		$VehicleHistory->save();
		
		foreach ($managerIDs as $manID) {
				$usedetails = HRPerson::where('user_id', $manID)->select('first_name', 'surname', 'email')->first();
				$email = !empty($usedetails->email) ? $usedetails->email : ''; 
				$firstname = !empty($usedetails->first_name) ? $usedetails->first_name : ''; 
				$surname = !empty($usedetails->surname) ? $usedetails->surname : '';
				if (!empty($email))
					Mail::to($email)->send(new FleetRejection($firstname, $surname, $email, $fleet->id));

		}
		$vehicle_maintenance = vehicle_maintenance::where('id', $fleet->id)->first();
		if (!empty($vehicle_maintenance->author_id))
		{
			$authoretails = HRPerson::where('id', $vehicle_maintenance->author_id)->select('first_name', 'surname', 'email')->first();
			$email = !empty($authoretails->email) ? $authoretails->email : ''; 
			$firstname = !empty($authoretails->first_name) ? $authoretails->first_name : ''; 
			$surname = !empty($authoretails->surname) ? $authoretails->surname : '';
			if (!empty($authoretails->email))
						Mail::to($email)->send(new FleetRejection($firstname, $surname, $email, $fleet->id));
		}
        AuditReportsController::store('Fleet Management', 'Reject Vehicle ', "Vehicle has been Rejected", 0);
        return response()->json();
    }

    public function rejectReason(Request $request, vehicle_maintenance $reason)
    {
        $this->validate($request, [
            //'description' => 'numeric',
        ]);
        $vehicleData = $request->all();
        unset($vehicleData['_token']);

        $reason->rejector_id = Auth::user()->person->id;
        $reason->reject_reason = $vehicleData['description'];
        $reason->reject_timestamp = $currentDate = time();
        $reason->status = 3;
        $reason->update();
        AuditReportsController::store('Fleet Management', 'Reject Vehicle', "Vehicle has been Declined", 0);
        return response()->json();
    }
	
	public function vehicleHistories(vehicle_detail $fleet)
    {
		$fleet = $fleet->load('vehicleHistory.userName');
		
		$data['page_title'] = 'View Vehicle History';
		$data['page_description'] = 'Vehicle History';
		$data['breadcrumb'] = [
			['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle/Search', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
			['title' => 'Manage Fleet', 'active' => 1, 'is_module' => 0]
		];
		
		$data['fleet'] = $fleet;
		$data['active_mod'] = 'Fleet Management';
		$data['active_rib'] = 'Manage Fleet';
		AuditReportsController::store('Fleet Management', 'Vehicle History Page Accessed', 'Accessed by User', 0);
		return view('Vehicles.vehicle_history')->with($data);
    }
	
	public function vehicleHistoriesPrint(vehicle_detail $fleet)
    {
		$fleet = $fleet->load('vehicleHistory.userName');
		
		$data['page_title'] = 'View Vehicle History';
		$data['page_description'] = 'Vehicle History';
		$data['breadcrumb'] = [
			['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle/Search', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
			['title' => 'Manage Fleet', 'active' => 1, 'is_module' => 0]
		];
		
		$data['fleet'] = $fleet;
		$data['active_mod'] = 'Fleet Management';
		$data['active_rib'] = 'Manage Fleet';
		
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
		return view('Vehicles.vehicle_history_print')->with($data);
    }
}
