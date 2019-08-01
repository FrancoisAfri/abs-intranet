<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Users;
use App\Mail\approve_vehiclemail;
use App\Mail\assignUsertoAdmin;
use App\Mail\vehiclemanagerApproval;
use App\ContactCompany;
use App\DivisionLevel;
Use App\permits_licence;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\VehicleHistory;
use App\vehicle;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\vehicle_maintenance;
use App\vehicle_fire_extinguishers;
use App\vehiclemake;
use App\keytracking;
use App\safe;
use App\vehicle_milege;
use App\vehicle_documets;
use App\images;
use App\notes;
use App\DivisionLevelFive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
//use Zip;

class FleetManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function fleetManagent()
    {
        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('name', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('name', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('name', 'asc')->get();
		$divisionLevels  = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $DivisionLevelFive = DivisionLevelFive::where('active', 1)->orderBy('name', 'asc')->get();
        $images = images::orderBy('id', 'asc')->get();
        //check  vehicle_configuration table if new_vehicle_approval is active
        
        $vehicleConfigs = DB::table('vehicle_configuration')->pluck('new_vehicle_approval');
        $vehicleConfig = !empty($vehicleConfigs->first()) ? $vehicleConfigs->first() : 0;

        $vehiclemaintenance = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_image.image as vehicle_images',
                'vehicle_managemnet.name as vehicle_type', 'contact_companies.name as Vehicle_Owner ')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_image', 'vehicle_details.id', '=', 'vehicle_image.vehicle_maintanace')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('contact_companies', 'vehicle_details.vehicle_owner', '=', 'contact_companies.id')
            ->orderBy('vehicle_details.id')
            ->get();

        $data['vehicleConfig'] = $vehicleConfig;
        $data['DivisionLevelFive'] = $DivisionLevelFive;
        $data['images'] = $images;
        $data['hrDetails'] = $hrDetails;
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehicledetail'] = $vehicledetail;
        $data['division_levels'] = $divisionLevels;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['vehiclemake'] = $vehiclemake;
        $data['ContactCompany'] = $ContactCompany;

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = " FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FleetManagement.fleetIndex')->with($data);
    }

    public function addvehicle()
    {		
        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('name', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();

        $vehiclemaintenance = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_image.image as vehicle_images', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_image', 'vehicle_details.id', '=', 'vehicle_image.vehicle_maintanace')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id')
            ->get();

        $data['page_title'] = " Fleet Management";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehicledetail'] = $vehicledetail;
        $data['division_levels'] = $divisionLevels;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['vehiclemake'] = $vehiclemake;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.FleetManagement.add_vehicle')->with($data);
    }
    public function addvehicleDetails(Request $request)
    {
        $this->validate($request, [
            'vehicle_type' => 'required',
			'vehicle_registration' => 'required|unique:vehicle_details,vehicle_registration',
            // 'name' => 'required',
            // 'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        
        $vehicleConfigs = DB::table('vehicle_configuration')->pluck('new_vehicle_approval');
        $vehicleConfig = $vehicleConfigs->first();
         
        $userLogged = Auth::user()->load('person');
        $Username = $userLogged->person->first_name . " " . $userLogged->person->surname;
		if (!empty($SysData['financial_institution'])) $SysData['company'] = 0;
		if (!empty($SysData['company'])) $SysData['financial_institution'] = 0;
        $vehicle_maintenance = new vehicle_maintenance();
        $vehicle_maintenance->responsible_for_maintenance = !empty($SysData['responsible_for_maintenance']) ? $SysData['responsible_for_maintenance'] : 0;
        $vehicle_maintenance->vehicle_make = !empty($SysData['vehiclemake_id']) ? $SysData['vehiclemake_id'] : 0;
        $vehicle_maintenance->vehicle_model = !empty($SysData['vehiclemodel_id']) ? $SysData['vehiclemodel_id'] : 0;
        $vehicle_maintenance->vehicle_type = !empty($SysData['vehicle_type']) ? $SysData['vehicle_type'] : 0;
        $vehicle_maintenance->year = $SysData['year'];
        $vehicle_maintenance->vehicle_registration = $SysData['vehicle_registration'];
        $vehicle_maintenance->chassis_number = $SysData['chassis_number'];
        $vehicle_maintenance->engine_number = $SysData['engine_number'];
        $vehicle_maintenance->vehicle_color = $SysData['vehicle_color'];
        $vehicle_maintenance->metre_reading_type = !empty($SysData['promotion_type']) ? $SysData['promotion_type'] : 0;
        $vehicle_maintenance->odometer_reading = $SysData['odometer_reading'];
        $vehicle_maintenance->hours_reading = $SysData['hours_reading'];
        $vehicle_maintenance->fuel_type = $SysData['fuel_type'];
        $vehicle_maintenance->size_of_fuel_tank = $SysData['size_of_fuel_tank'];
        $vehicle_maintenance->fleet_number = $SysData['fleet_number'];
        $vehicle_maintenance->cell_number = $SysData['cell_number'];
        $vehicle_maintenance->tracking_umber = $SysData['tracking_umber'];
        $vehicle_maintenance->vehicle_owner = !empty($SysData['vehicle_owner']) ? $SysData['vehicle_owner'] : 0;
        $vehicle_maintenance->financial_institution = !empty($SysData['financial_institution']) ? $SysData['financial_institution'] : 0;
        $vehicle_maintenance->company = !empty($SysData['company']) ? $SysData['company'] : 0;
        $vehicle_maintenance->extras = $SysData['extras'];
        $vehicle_maintenance->property_type = !empty($SysData['property_type']) ? $SysData['property_type'] : 0;
        $vehicle_maintenance->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $vehicle_maintenance->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $vehicle_maintenance->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $vehicle_maintenance->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $vehicle_maintenance->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $vehicle_maintenance->currentDate = $currentDate;
        $vehicle_maintenance->title_type = !empty($SysData['title_type']) ? $SysData['title_type'] : 0;
        $vehicle_maintenance->name = $Username;
        $vehicle_maintenance->author_id = $userLogged->person->id;
        $vehicle_maintenance->booking_status = !empty($SysData['booking_status']) ? $SysData['booking_status'] : 0;
         if ($vehicleConfig == 1) {
             $vehicle_maintenance->status = 2;
        } else
            $vehicle_maintenance->status = 1;
        $vehicle_maintenance->save();

        $loggedInEmplID = Auth::user()->person->id;
        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('Vehicle/images', $fileName);
                //Update file name in the database
                $vehicle_maintenance->image = $fileName;
                $vehicle_maintenance->update();
            }
        }

        //Upload supporting document
        if ($request->hasFile('registration_papers')) {
            $fileExt = $request->file('registration_papers')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'tiff']) && $request->file('registration_papers')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('registration_papers')->storeAs('Vehicle/registration_papers', $fileName);
                //Update file name in the table
                $vehicle_maintenance->registration_papers = $fileName;
                $vehicle_maintenance->update();
            }
        }
        // add Details into vehicle Milege table
        //types //1 =  vehicle creation // 2 =  vehicle collected //3 = vehicle returned

        $Vehiclemilege = new vehicle_milege();
        $Vehiclemilege->date_created = time();
        $Vehiclemilege->vehicle_id = $vehicle_maintenance->id;
        $Vehiclemilege->odometer_reading = !empty($SysData['odometer_reading']) ? $SysData['odometer_reading'] : 0;
        $Vehiclemilege->hours_reading = !empty($SysData['hours_reading']) ? $SysData['hours_reading'] : 0;
        $Vehiclemilege->type = 1;
        $Vehiclemilege->booking_id = 0;
        $Vehiclemilege->save();
        
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
					Mail::to($email)->send(new vehiclemanagerApproval($firstname, $surname, $email));
			}
        }
		// add to vehicle history 
        $VehicleHistory = new VehicleHistory();
        $VehicleHistory->vehicle_id = $vehicle_maintenance->id;
        $VehicleHistory->user_id = Auth::user()->person->id;
		if ($vehicleConfig == 1)  $VehicleHistory->status = 2;
        else $VehicleHistory->status = 1;
        $VehicleHistory->comment = "New Vehicle Added";
        $VehicleHistory->action_date = time();
        $VehicleHistory->save();
		
        AuditReportsController::store('Fleet Management', 'New Vehicle Added', "Accessed By User", 0);;
        return response()->json();
    }

    public function editvehicleDetails(Request $request, vehicle_maintenance $vehicle_maintenance)
    {
        $this->validate($request, [
//            'vehicle_make' => 'required',
//            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		$comment = 'Vehicle Details Changed: ';
        $vehicleConfigs = DB::table('vehicle_configuration')->pluck('new_vehicle_approval');
        $vehicleConfig = $vehicleConfigs->first();
		// Comment on changes
		if ($vehicle_maintenance->title_type != $SysData['title_type']) $comment .= "1.Vehicle Owner Changed, ";
		if (!empty($SysData['responsible_for_maintenance']) && $vehicle_maintenance->responsible_for_maintenance != $SysData['responsible_for_maintenance']) $comment .= "2.Person Responsible Changed, ";
		if (!empty($SysData['vehiclemake_id']) && $vehicle_maintenance->vehicle_make != $SysData['vehiclemake_id']) $comment .= "3.Make Changed, ";
		if (!empty($SysData['vehiclemodel_id']) && $vehicle_maintenance->vehicle_model != $SysData['vehiclemodel_id']) $comment .= "4.Model Changed, ";
		if (!empty($SysData['vehicle_type']) && $vehicle_maintenance->vehicle_type != $SysData['vehicle_type']) $comment .= "5.Type Changed, ";
		if (!empty($SysData['year']) && $vehicle_maintenance->year != $SysData['year']) $comment .= "6.Year, ";
		if (!empty($SysData['vehicle_registration']) && $vehicle_maintenance->vehicle_registration != $SysData['vehicle_registration']) $comment .= "7.Registration Number Changed, ";
		if (!empty($SysData['chassis_number']) && $vehicle_maintenance->chassis_number != $SysData['chassis_number']) $comment .= "8.Chassis Number Changed, ";
		if (!empty($SysData['engine_number']) && $vehicle_maintenance->engine_number != $SysData['engine_number']) $comment .= "9.Engine Number Changed, ";
		if (!empty($SysData['vehicle_color']) && $vehicle_maintenance->vehicle_color != $SysData['vehicle_color']) $comment .= "10.Color Changed, ";
		if (!empty($SysData['promotion_type']) && $vehicle_maintenance->metre_reading_type != $SysData['promotion_type']) $comment .= "11.Metre Reading Type Changed, ";
		if (!empty($SysData['odometer_reading']) && $vehicle_maintenance->odometer_reading != $SysData['odometer_reading']) $comment .= "12.Odometer Reading Changed, ";
		if (!empty($SysData['hours_reading']) && $vehicle_maintenance->hours_reading != $SysData['hours_reading']) $comment .= "13.Hours Reading Changed, ";
		if (!empty($SysData['fuel_type']) && $vehicle_maintenance->fuel_type != $SysData['fuel_type']) $comment .= "14.Fuel Type Changed, ";
		if (!empty($SysData['size_of_fuel_tank']) && $vehicle_maintenance->size_of_fuel_tank != $SysData['size_of_fuel_tank']) $comment .= "15.Size Of Fuel Tank Changed, ";
		if (!empty($SysData['fleet_number']) && $vehicle_maintenance->fleet_number != $SysData['fleet_number']) $comment .= "16.Fleet Number Changed, ";
		if (!empty($SysData['cell_number']) && $vehicle_maintenance->cell_number != $SysData['cell_number']) $comment .= "17.Cell Number Changed, ";
		if (!empty($SysData['tracking_umber']) && $vehicle_maintenance->tracking_umber != $SysData['tracking_umber']) $comment .= "18.Tracking Number Changed, ";
		if (!empty($SysData['division_level_5']) && $vehicle_maintenance->division_level_5 != $SysData['division_level_5']) $comment .= "19.Division Level Five Changed, ";
		if (!empty($SysData['division_level_4']) && $vehicle_maintenance->division_level_4 != $SysData['division_level_4']) $comment .= "20.Division Level Four Changed, ";
		if (!empty($SysData['division_level_3']) && $vehicle_maintenance->division_level_3 != $SysData['division_level_3']) $comment .= "21.Division Level Three Changed, ";
		if (!empty($SysData['division_level_2']) && $vehicle_maintenance->division_level_2 != $SysData['division_level_2']) $comment .= "22.Division Level Two Changed, ";
		if (!empty($SysData['division_level_1']) && $vehicle_maintenance->division_level_1 != $SysData['division_level_1']) $comment .= "23.Division Level One Changed, ";
		if (!empty($SysData['property_type']) && $vehicle_maintenance->property_type != $SysData['property_type']) $comment .= "24.Tracking Nmber Changed, ";
		if (!empty($SysData['financial_institution']) && $vehicle_maintenance->financial_institution != $SysData['financial_institution']) $comment .= "25.Financial Institution Changed, ";
		if (!empty($SysData['vehicle_owner']) && $vehicle_maintenance->vehicle_owner != $SysData['vehicle_owner']) $comment .= "26.Vehicle Owner Changed, ";
		if (!empty($SysData['extras']) && $vehicle_maintenance->extras != $SysData['extras']) $comment .= "26.Extras Changed, ";
        $currentDate = time();
        $userLogged = Auth::user()->load('person');
        $Username = $userLogged->person->first_name . " " . $userLogged->person->surname;
		if (!empty($SysData['financial_institution']) && $SysData['title_type'] == 1) $SysData['company'] = 0;
		if (!empty($SysData['company'])  && $SysData['title_type'] == 2) $SysData['financial_institution'] = 0;
		 if ($vehicleConfig == 1 ) {
             $vehicle_maintenance->status = 2;
        } 
		else
            $vehicle_maintenance->status = 1;
        $vehicle_maintenance->responsible_for_maintenance = !empty($SysData['responsible_for_maintenance']) ? $SysData['responsible_for_maintenance'] : 0;;
        $vehicle_maintenance->vehicle_make = !empty($SysData['vehiclemake_id']) ? $SysData['vehiclemake_id'] : 0;
        $vehicle_maintenance->vehicle_model = !empty($SysData['vehiclemodel_id']) ? $SysData['vehiclemodel_id'] : 0;
        $vehicle_maintenance->vehicle_type = !empty($SysData['vehicle_type']) ? $SysData['vehicle_type'] : 0;
        $vehicle_maintenance->year = $SysData['year'];
        $vehicle_maintenance->vehicle_registration = $SysData['vehicle_registration'];
        $vehicle_maintenance->chassis_number = $SysData['chassis_number'];
        $vehicle_maintenance->engine_number = $SysData['engine_number'];
        $vehicle_maintenance->vehicle_color = $SysData['vehicle_color'];
        $vehicle_maintenance->metre_reading_type = $SysData['promotion_type'];
        $vehicle_maintenance->odometer_reading = $SysData['odometer_reading'];
        $vehicle_maintenance->hours_reading = $SysData['hours_reading'];
        $vehicle_maintenance->fuel_type = $SysData['fuel_type'];
        $vehicle_maintenance->size_of_fuel_tank = $SysData['size_of_fuel_tank'];
        $vehicle_maintenance->fleet_number = $SysData['fleet_number'];
        $vehicle_maintenance->cell_number = $SysData['cell_number'];
        $vehicle_maintenance->tracking_umber = $SysData['tracking_umber'];
        $vehicle_maintenance->vehicle_owner = !empty($SysData['vehicle_owner']) ? $SysData['vehicle_owner'] : 0;
        $vehicle_maintenance->financial_institution = !empty($SysData['financial_institution']) ? $SysData['financial_institution'] : 0;
        $vehicle_maintenance->company =  !empty($SysData['company']) ? $SysData['company'] : 0;
        $vehicle_maintenance->extras = $SysData['extras'];
        $vehicle_maintenance->property_type = !empty($SysData['property_type']) ? $SysData['property_type'] : 0;
        $vehicle_maintenance->division_level_5 = !empty($SysData['division_level_5']) ? $SysData['division_level_5'] : 0;
        $vehicle_maintenance->division_level_4 = !empty($SysData['division_level_4']) ? $SysData['division_level_4'] : 0;
        $vehicle_maintenance->division_level_3 = !empty($SysData['division_level_3']) ? $SysData['division_level_3'] : 0;
        $vehicle_maintenance->division_level_2 = !empty($SysData['division_level_2']) ? $SysData['division_level_2'] : 0;
        $vehicle_maintenance->division_level_1 = !empty($SysData['division_level_1']) ? $SysData['division_level_1'] : 0;
        $vehicle_maintenance->currentDate = $currentDate;
        $vehicle_maintenance->title_type = !empty($SysData['title_type']) ? $SysData['title_type'] : 0;
        $vehicle_maintenance->name = $Username;
        $vehicle_maintenance->booking_status = !empty($SysData['booking_status']) ? $SysData['booking_status'] : 0;
        $vehicle_maintenance->update();

        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('Vehicle/images', $fileName);
                //Update file name in the database
                $vehicle_maintenance->image = $fileName;
                $vehicle_maintenance->update();
            }
        }
        //Upload supporting document
        if ($request->hasFile('registration_papers')) {
            $fileExt = $request->file('registration_papers')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'tiff']) && $request->file('registration_papers')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('registration_papers')->storeAs('Vehicle/registration_papers', $fileName);
                //Update file name in the table
                $vehicle_maintenance->registration_papers = $fileName;
                $vehicle_maintenance->update();
            }
        }
		// add to vehicle history 
        $VehicleHistory = new VehicleHistory();
        $VehicleHistory->vehicle_id = $vehicle_maintenance->id;
        $VehicleHistory->user_id = Auth::user()->person->id;
		if ($vehicleConfig == 1)  $VehicleHistory->status = 2;
        else $VehicleHistory->status = 1;
        $VehicleHistory->comment = $comment;
        $VehicleHistory->action_date = time();
        $VehicleHistory->save();
		
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
					Mail::to($email)->send(new vehiclemanagerApproval($firstname, $surname, $email));
            }
        }

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }
	public function changeVehicleStatus(Request $request, vehicle_maintenance $vehicle_maintenance)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		
        $vehicle_maintenance->status = !empty($SysData['status']) ? $SysData['status'] : 0;
        $vehicle_maintenance->update();

		//add to vehicle history 
        $VehicleHistory = new VehicleHistory();
        $VehicleHistory->vehicle_id = $vehicle_maintenance->id;
        $VehicleHistory->user_id = Auth::user()->person->id;
		$VehicleHistory->status = !empty($SysData['status']) ? $SysData['status'] : 0;
        $VehicleHistory->comment = "Status Changed.";
        $VehicleHistory->action_date = time();
        $VehicleHistory->save();

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function viewDetails(vehicle_maintenance $maintenance)
    {		
        $ID = $maintenance->id;
        $hrDetails = HRPerson::where('status', 1)->get();
        $images = images::orderBy('id', 'asc')->get();
        $DivisionLevelFive = DivisionLevelFive::where('active', 1)->orderBy('name', 'asc')->get();
        $ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('name', 'asc')->get();
        $vehiclemodel = vehiclemodel::where('status', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $fueltype = array(1 => 'Unleaded', 2 => ' Lead replacement', 3 => ' Diesel');
        $status = array(1 => 'Active', 2 => 'Require Approval', 3 => 'Rejected', 4 => 'Inactive');

        $ID = $maintenance->id;
		
        $vehiclemaintenance = vehicle_detail::select('vehicle_details.*', 'vehicle_make.name as vehiclemake'
			,'vehicle_model.name as vehiclemodel', 'vehicle_managemnet.name as vehicletype'
			,'division_level_fives.name as company_name','div_fives.name as company_owner'
			, 'division_level_fours.name as Department'
			, 'hr_people.first_name as first_name', 'hr_people.surname as surname'
			,'contact_companies.name as contact_owner ')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            ->leftJoin('hr_people', 'vehicle_details.responsible_for_maintenance', '=', 'hr_people.id')
            ->leftJoin('contact_companies', 'vehicle_details.financial_institution', '=', 'contact_companies.id')
            ->leftJoin('division_level_fives as div_fives', 'vehicle_details.company', '=', 'div_fives.id')
            ->where('vehicle_details.id', $ID)
            ->orderBy('vehicle_details.id')
            ->get(); 

        $vehiclemaintenance = $vehiclemaintenance->load('vehicleOwnerName');
        $registrationPapers = $vehiclemaintenance->first()->registration_papers;
        $fleetImage = $vehiclemaintenance->first()->image;
		//return $vehiclemaintenance;
        $data['registration_papers'] = (!empty($registrationPapers)) ? Storage::disk('local')->url("Vehicle/registration_papers/$registrationPapers") : '';
        $data['fleetImage'] = (!empty($fleetImage)) ? Storage::disk('local')->url("Vehicle/images/$fleetImage") : '';
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['images'] = $images;
        $data['DivisionLevelFive'] = $DivisionLevelFive;
        $data['hrDetails'] = $hrDetails;
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehicledetail'] = $vehicledetail;
        $data['division_levels'] = $divisionLevels;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['vehiclemake'] = $vehiclemake;
        $data['status'] = $status;
        $data['fueltype'] = $fueltype;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehicle_maintenance'] = $vehicle_maintenance;
        $data['vehicle'] = $vehicle;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['vehiclemodel'] = $vehiclemodel;
        $data['divisionLevels'] = $divisionLevels;
        $data['vehicledetail'] = $vehicledetail;
        $data['ContactCompany'] = $ContactCompany;
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Vehicle Details Accessed', "Accessed by User", 0);
	
        return view('Vehicles.FleetManagement.viewfleetDetails')->with($data);
    }


    public function viewImage(vehicle_maintenance $maintenance)
    {
        $vehicle_maintenance = vehicle_maintenance::where('id', $maintenance->id)->get()->first();
        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $vehiclemaintenance = DB::table('vehicle_image')
            ->select('vehicle_image.*', 'hr_people.first_name as first_name', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_image.user_name', '=', 'hr_people.id')
            ->where('vehicle_image.vehicle_maintanace', $maintenance->id)
            ->get();

        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehiclemaintenance'] = $vehiclemaintenance;
        $data['vehicle_maintenance'] = $vehicle_maintenance;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
			
        AuditReportsController::store('Fleet Management', 'Vehicle Images Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewfleetImage')->with($data);
    }

	// upload image
	public function uploadImages(vehicle_maintenance $maintenance)
    {
        $data['page_title'] = "Upload Fleet ";
        $data['page_description'] = "Images";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Vehicle Images Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.add_vehicle_images')->with($data);
    }
	// save images
    public function addImages(Request $request, vehicle_maintenance $maintenance)
    {
        $this->validate($request, [
            //'name' => 'required',
            // 'description' => 'required',
            'images.*' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		$images = $request->file('images');
        $userLogged = Auth::user()->load('person');
		if ($request->hasFile('images')) 
		{
			$oldFile = '';
			foreach ($images as $image)
			{
			
				$imageArray = explode(".",$image);
				$fileExt = $image->extension();
				if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $image->isValid()) {
					$fileName = "image" . time() . '.' . $fileExt;
					$image->storeAs('Vehicle/images', $fileName);
					if ($oldFile != $fileName)
					{
						//Update file name in the database
						$vehicleImages = new images();
						$vehicleImages->name = $imageArray[0];
						$vehicleImages->vehicle_maintanace = $maintenance->id;
						$vehicleImages->upload_date = time();
						$vehicleImages->user_name = $userLogged->person->id;
						$vehicleImages->default_image = 1;
						$vehicleImages->image = $fileName;
						$vehicleImages->save();
					}
				}
				$oldFile = $fileName;
				$image  = $fileName = '';
					echo $image."</br>";
			}
		}	
		//die;
		/*while ($count < count($images)) 
		{
			
			$imageArray = explode(".",$images[$count]);
			//Upload Image picture
			if ($request->hasFile('images')) 
			{
				//die('do you cme here');
				$fileExt = $images[$count]->extension();
				if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $images[$count]->isValid()) {
					$fileName = "image" . time() . '.' . $fileExt;
					$images[$count]->storeAs('Vehicle/images', $fileName);
					//Update file name in the database
					$vehicleImages = new images();
					$vehicleImages->name = $imageArray[0];
					$vehicleImages->vehicle_maintanace = $maintenance->id;
					$vehicleImages->upload_date = $currentDate;
					$vehicleImages->user_name = $userLogged->person->id;
					$vehicleImages->default_image = 1;
					$vehicleImages->image = $fileName;
					$vehicleImages->save();
				}
			}
			$count ++;
			echo $count ;
			die('herehefrfrf');
		}*/
        return redirect("/vehicle_management/viewImage/$maintenance->id");
    }

    public function editImage(Request $request, images $image)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        $userLogged = Auth::user()->load('person');

        $image->name = $SysData['name'];
        $image->description = $SysData['description'];
        $image->vehicle_maintanace = $SysData['valueID'];
        $image->upload_date = $currentDate;
        $image->user_name = $userLogged->person->id;
        $image->default_image = 1;

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

        AuditReportsController::store('Fleet Management', 'Vehicle Image Edited', "Accessed By User", 0);;
        return response()->json();
    }

    public function keys(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 


        $ID = $maintenance->id;

        $keytracking = DB::table('keytracking')
            ->select('keytracking.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'hr_people.manager_id as manager', 'safe.name as safeName')
            ->leftJoin('hr_people', 'keytracking.employee', '=', 'hr_people.id')
            ->leftJoin('safe', 'keytracking.safe_name', '=', 'safe.id')
            ->orderBy('keytracking.id')
            ->where('vehicle_id', $ID)
            ->get();
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['name'] = $name;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['IssuedTo'] = $IssuedTo;
        $data['keyStatus'] = $keyStatus;
        $data['safe'] = $safe;
        $data['employees'] = $employees;
        $data['keytracking'] = $keytracking;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Vehicle Keys Accessed', "Accessed by User", 0);
        //return view('products.products')->with($data);
        return view('Vehicles.FleetManagement.key_tracking')->with($data);
    }


    public function vehiclesAct(Request $request, vehicle_maintenance $vehicleDetails)
    {
        if ($vehicleDetails->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $vehicleDetails->status = $stastus;
        $vehicleDetails->update();
		AuditReportsController::store('Fleet Management', 'Vehicle De-activated', "Accessed by User", 0);
        return back();
    }


    public function addkeys(Request $request)
    {
        $this->validate($request, [

        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $datelost = $SysData['date_lost'] = str_replace('/', '-', $SysData['date_lost']);
        $datelost = $SysData['date_lost'] = strtotime($SysData['date_lost']);

        $keytracking = new keytracking();
        $keytracking->key_number = $SysData['key_number'];
        $keytracking->key_type = !empty($SysData['key_type']) ? $SysData['key_type'] : 0;
        $keytracking->key_status = $SysData['key_status'];
        $keytracking->description = $SysData['description'];
        $keytracking->employee = $SysData['key'];
        $keytracking->date_issued = $dates;
        $keytracking->issued_by = !empty($SysData['issued_by']) ? $SysData['issued_by'] : 0;
        $keytracking->safe_name = !empty($SysData['safe_name']) ? $SysData['safe_name'] : 0;
        $keytracking->safe_controller = !empty($SysData['safe_controller']) ? $SysData['safe_controller'] : 0;
        $keytracking->issued_to = !empty($SysData['issued_to']) ? $SysData['issued_to'] : 0;
        $keytracking->date_lost = $datelost;
        $keytracking->reason_loss = $SysData['reason_loss'];
        $keytracking->vehicle_type = 0;
        $keytracking->vehicle_id = $SysData['valueID'];
        $keytracking->captured_by = $SysData['employee'];
        $keytracking->safeController = !empty($SysData['safe_controller']) ? $SysData['safe_controller'] : '';
        $keytracking->save();
        AuditReportsController::store('Fleet Management', 'Vehicle Key Added', "Accessed by User", 0);
        return response()->json();
    }

    public function editKeys(Request $request, keytracking $keytracking)
    {
//        $this->validate($request, [

//        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $datelost = $SysData['date_lost'] = str_replace('/', '-', $SysData['date_lost']);
        $datelost = $SysData['date_lost'] = strtotime($SysData['date_lost']);

        $loggedInEmplID = Auth::user()->person->id;

        $keytracking->key_number = $SysData['key_number'];
        //$keytracking->key_type = $SysData['key_type'];
        $keytracking->key_status = $SysData['key_status'];
        $keytracking->description = $SysData['description'];
        $keytracking->employee = $loggedInEmplID;
        $keytracking->date_issued = $dates;
        $keytracking->issued_by = $SysData['issued_by'];
        // $keytracking->safe_name = $SysData['safe_name'];
        ////$keytracking->safe_controller = $SysData['safe_controller'];
        // $keytracking->issued_to = $SysData['issued_to'];
        $keytracking->date_lost = $datelost;
        $keytracking->reason_loss = $SysData['reason_loss'];
        //$keytracking->vehicle_type =0 ;
        $keytracking->vehicle_id = 1;
        $keytracking->update();
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

##permits

    public function permits_licences(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;

        $companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $permitlicence = fleet_licence_permit::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        $status = array(1 => 'Active', 2 => 'InActive');

        $currentdate = time();
        $ID = $maintenance->id;

        $permits = DB::table('permits_licence')
            ->select('permits_licence.*', 'contact_companies.name as comp_name'
			, 'hr_people.first_name as firstname', 'hr_people.surname as surname'
			, 'fleet_licence_permit.name as license_name')
            ->leftJoin('hr_people', 'permits_licence.Supplier', '=', 'hr_people.id')
            ->leftJoin('contact_companies', 'permits_licence.Supplier', '=', 'contact_companies.id')
            ->leftJoin('fleet_licence_permit', 'permits_licence.permit_licence', '=', 'fleet_licence_permit.id')
            ->orderBy('permits_licence.id')
            ->where('vehicleID', $ID)
            ->get();
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];


        $data['currentdate'] = $currentdate;
        $data['permitlicence'] = $permitlicence;
        $data['status'] = $status;
        $data['name'] = $name;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['companies'] = $companies;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['employees'] = $employees;
        $data['permits'] = $permits;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Job Titles Page Accessed', "Accessed by User", 0);
        //return view('products.products')->with($data);
        return view('Vehicles.FleetManagement.permits')->with($data);
    }

    public function addPermit(Request $request)
    {
        $this->validate($request, [
            'permit_licence' => 'required',
            'supplier_id' => 'required',
            'permits_licence_no' => 'required|unique:permits_licence,permits_licence_no',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);


        $currentDate = time();
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $currentDate = time();
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);
		
		$oldPermit = permits_licence::
					where('permit_licence', $SysData['permit_licence'])
					->where('vehicleID', $SysData['valueID'])
					->where('status', 1)		
					->first();
		if (!empty($oldPermit->permit_licence))
		{
			$oldPermit->status = 2;
			$oldPermit->update();
			
			$permits = new permits_licence();
			$permits->permit_licence = !empty($SysData['permit_licence']) ? $SysData['permit_licence'] : 0;
			$permits->Supplier = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
			$permits->exp_date = $Expdate;
			$permits->date_issued = $dates;
			$permits->status = !empty($SysData['status']) ? $SysData['status'] : 1;
			$permits->permits_licence_no = !empty($SysData['permits_licence_no']) ? $SysData['permits_licence_no'] : 0;
			$permits->captured_by = $name;
			$permits->date_captured = $currentDate;
			$permits->vehicleID = $SysData['valueID'];
			$permits->save();	
		}
		else
		{
			$permits = new permits_licence();
			$permits->permit_licence = !empty($SysData['permit_licence']) ? $SysData['permit_licence'] : 0;
			$permits->Supplier = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
			$permits->exp_date = $Expdate;
			$permits->date_issued = $dates;
			$permits->status = !empty($SysData['status']) ? $SysData['status'] : 1;
			$permits->permits_licence_no = !empty($SysData['permits_licence_no']) ? $SysData['permits_licence_no'] : 0;
			$permits->captured_by = $name;
			$permits->date_captured = $currentDate;
			$permits->vehicleID = $SysData['valueID'];
			$permits->save();
		}
       

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/permits_licence', $fileName);
                //Update file name in the table
                $permits->document = $fileName;
                $permits->update();
            }
        }
        return response()->json();
    }

    public function editPermit(Request $request, permits_licence $permit)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            //'permits_licence_no' => 'required|unique:permits_licence,permits_licence_no',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;

        $dates = $SysData['date_issued'] = str_replace('/', '-', $SysData['date_issued']);
        $dates = $SysData['date_issued'] = strtotime($SysData['date_issued']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);


        $permit->permit_licence = !empty($SysData['permit_licence']) ? $SysData['permit_licence'] : 0;
        $permit->Supplier = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
        $permit->exp_date = $Expdate;
        $permit->date_issued = $dates;
        $permit->status = !empty($SysData['status']) ? $SysData['status'] : 1;
        $permit->permits_licence_no = !empty($SysData['permits_licence_no']) ? $SysData['permits_licence_no'] : 0;
        $permit->captured_by = $name;
        $permit->date_captured = $currentDate;
        $permit->update();


        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/permits_licence', $fileName);
                //Update file name in the table
                $permit->document = $fileName;
                $permit->update();
            }
        }
        AuditReportsController::store('Vehicle FleetDocumentType', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function newdocument(Request $request)
    {
        $this->validate($request, [
            'type' => 'required',
            'description' => 'required',
            'date_from' => 'required',
            'valueID' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $datefrom = $SysData['date_from'] = str_replace('/', '-', $SysData['date_from']);
        $datefrom = $SysData['date_from'] = strtotime($SysData['date_from']);
		
		if (!empty($SysData['exp_date']))
		{
			$Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
			$Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);
		}
		else $Expdate = 0;
        $currentDate = time();

        $vehicledocumets = new vehicle_documets();
        $vehicledocumets->type = !empty($SysData['type']) ? $SysData['type'] : 0;
        $vehicledocumets->description = $SysData['description'];
        $vehicledocumets->date_from = $datefrom;
        $vehicledocumets->exp_date = $Expdate;
        $vehicledocumets->upload_date = $currentDate;
        $vehicledocumets->vehicleID = $SysData['valueID'];
        $vehicledocumets->expiry_type = 0;
        $vehicledocumets->status = 1;
        $vehicledocumets->currentdate = time();
        $vehicledocumets->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/documents', $fileName);
                //Update file name in the table
                $vehicledocumets->document = $fileName;
                $vehicledocumets->update();
            }
        }
        AuditReportsController::store('Vehicle FleetDocumentType', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function editVehicleDoc(Request $request, vehicle_documets $vehicledocumets)
    {
        $this->validate($request, [
            'type' => 'required',
            'description' => 'required',
            'date_from' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $datefrom = $SysData['date_from'] = str_replace('/', '-', $SysData['date_from']);
        $datefrom = $SysData['date_from'] = strtotime($SysData['date_from']);

        $Expdate = $SysData['exp_date'] = str_replace('/', '-', $SysData['exp_date']);
        $Expdate = $SysData['exp_date'] = strtotime($SysData['exp_date']);

        $currentDate = time();

        $vehicledocumets->type = !empty($SysData['type']) ? $SysData['type'] : 0;
        $vehicledocumets->description = $SysData['description'];
        $vehicledocumets->role = !empty($SysData['role']) ? $SysData['role'] : 0;
        $vehicledocumets->date_from = $datefrom;
        $vehicledocumets->exp_date = $Expdate;
        $vehicledocumets->upload_date = $currentDate;
        $vehicledocumets->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/documents', $fileName);
                //Update file name in the table
                $vehicledocumets->document = $fileName;
                $vehicledocumets->update();
            }
        }

        AuditReportsController::store('Vehicle FleetDocumentType', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();

    }

    public function deleteDoc(Request $request, vehicle_documets $document)
    {
        $document->delete();

        AuditReportsController::store('Fleet Management', 'document  Deleted', "document has been deleted", 0);
        return response()->json();
    }

    public function ActivateDoc(vehicle_documets $documents){

        if ($documents->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $documents->status = $stastus;
        $documents->update();

        AuditReportsController::store('Fleet Management', 'Document Type Activate status', "Document status has been changed", 0);
        return back();
    }

    public function newnotes(Request $request)
    {
        $this->validate($request, [
           // 'notes' => 'required',
			'notes' => 'required|unique:notes,notes',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
		$loggedInEmplID = Auth::user()->person->id;
		
        $notes = new notes();
        $notes->date_captured = $currentDate;
        $notes->captured_by = $loggedInEmplID;
        $notes->notes = $SysData['notes'];
        $notes->vehicleID = $SysData['valueID'];
        $notes->save();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/note_documents', $fileName);
                //Update file name in the table
                $notes->documents = $fileName;
                $notes->update();
            }
        }

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function editNote(Request $request, notes $note)
    {
        $this->validate($request, [

        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        $loggedInEmplID = Auth::user()->person->id;
       // $note->date_captured = $currentDate;
        $note->captured_by = $loggedInEmplID;
        $note->notes = $SysData['notes'];
        $note->vehicleID = $SysData['valueID'];
        $note->update();

        //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_registration_papers." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/note_documents', $fileName);
                //Update file name in the table
                $note->documents = $fileName;
                $note->update();
            }
        }
        AuditReportsController::store('Fleet Managemente', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function deleteNote(notes $note)
    {

        $note->delete();

        AuditReportsController::store('Fleet Management', 'note  Deleted', "document has been deleted", 0);
        return back();
    }
    
    public function viewfireExtinguishers(vehicle_maintenance $maintenance)
    {
        $ID = $maintenance->id;
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->get();
        $safe = safe::where('status', 1)->get();

        ################## WELL DETAILS ###############
        $vehiclemaker = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemodeler = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehicleTypes = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        ################## WELL DETAILS ###############

        $ID = $maintenance->id;
        $statusArray= array(1 => 'Active', 2 => ' Allocate', 3 => 'In Use', 4 => 'Empty', 5=> 'Evacate', 6=> 'In Storage', 7=> 'Discarded', 8=> 'Rental' , 9=> 'Sold'); 
        $vehicle_details = DB::table('vehicle_details')->get();
        $fireextinguishers = DB::table('vehicle_fire_extinguisher')
            ->select('vehicle_fire_extinguisher.*'
			,'contact_companies.name as comp_name'
			,'hr_people.first_name as firstname'
			,'hr_people.surname as surname')
            ->leftJoin('vehicle_details', 'vehicle_fire_extinguisher.vehicle_id', '=', 'vehicle_details.id')
            ->leftJoin('hr_people', 'vehicle_fire_extinguisher.capturer_id', '=', 'hr_people.id')
            ->leftJoin('contact_companies', 'vehicle_fire_extinguisher.supplier_id', '=', 'contact_companies.id')
            ->orderBy('vehicle_fire_extinguisher.id')
            ->where('vehicle_id', $ID)
            ->get();
     
        $data['page_title'] = " View Fleet Details";
        $data['page_description'] = "FleetManagement";
        $data['breadcrumb'] = [
            ['title' => 'Fleet  Management', 'path' => '/vehicle_management/create_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
        ];

        $data['safe'] = $safe;
        $data['statusArray'] = $statusArray;
        $data['employees'] = $employees;
        $data['ContactCompany'] = $ContactCompany;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehiclemaker'] = $vehiclemaker;
        $data['fireextinguishers'] = $fireextinguishers;
        $data['ID'] = $ID;
        $data['vehicle_maintenance'] = $vehicle_maintenance;
        $data['maintenance'] = $maintenance;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Fleet Management', 'Vehicle Images Accessed', "Accessed by User", 0);
        return view('Vehicles.FleetManagement.viewfireextinguishers')->with($data);
        
    }
    
    public function addvehicleextinguisher(Request $request){
        
        $this->validate($request, [
            'bar_code' => 'required',
            'item_no' => 'required',
             'Weight' => 'required',
             'supplier_id' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);

        $currentDate = time();
        $userLogged = Auth::user()->load('person');
        $datepurchased = $SysData['date_purchased'] = str_replace('/', '-', $SysData['date_purchased']);
        $datepurchased = $SysData['date_purchased'] = strtotime($SysData['date_purchased']);

        $vehiclefirextinguishers = new vehicle_fire_extinguishers($SysData);
        $vehiclefirextinguishers->date_purchased = $datepurchased;
        $vehiclefirextinguishers->vehicle_id = $SysData['valueID']; 
        $vehiclefirextinguishers->capturer_id = $userLogged->id;
        $vehiclefirextinguishers->Cost = !empty($SysData['Cost']) ? $SysData['Cost'] : 0;
        $vehiclefirextinguishers->supplier_id = !empty($SysData['supplier_id']) ? $SysData['supplier_id'] : 0;
        $vehiclefirextinguishers->item_no = !empty($SysData['item_no']) ? $SysData['item_no'] : 0;
        $vehiclefirextinguishers->Weight = !empty($SysData['Weight']) ? $SysData['Weight'] : 0;
        $vehiclefirextinguishers->purchase_order = !empty($SysData['purchase_order']) ? $SysData['purchase_order'] : 0;
        $vehiclefirextinguishers->Status = 1;
        $vehiclefirextinguishers->save();

        //Upload Image picture
       if ($request->hasFile('image')) {
           $fileExt = $request->file('image')->extension();
           if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
               $fileName = "image" . time() . '.' . $fileExt;
               $request->file('image')->storeAs('Vehicle/fireextinguishers/images', $fileName);
               //Update file name in the database
               $vehiclefirextinguishers->image = $fileName;
               $vehiclefirextinguishers->update();
           }
       }
	   //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_fire_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/fireextinguishers/document', $fileName);
                //Update file name in the table
                $vehiclefirextinguishers->attachement = $fileName;
                $vehiclefirextinguishers->update();
            }
        }
        AuditReportsController::store('Fleet Management', 'Vehicle Fire Extinguishers Accessed', "Accessed by User", 0);
        return response()->json(); 
    }
    
    
    public function editeditfireexting(Request $request ,vehicle_fire_extinguishers $extinguishers){
       $this->validate($request, [
            //'name' => 'required',
            // 'description' => 'required',
           // 'image' => 'required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
  
        $datepurchased = $SysData['date_purchased'] = str_replace('/', '-', $SysData['date_purchased']);
        $datepurchased = $SysData['date_purchased'] = strtotime($SysData['date_purchased']);
        
        $userLogged = Auth::user()->load('person');
        
        $extinguishers->date_purchased = $datepurchased;
        $extinguishers->vehicle_id = $SysData['vehicle_id']; 
        $extinguishers->capturer_id = $userLogged->id;
        $extinguishers->Cost = !empty($SysData['Cost']) ? $SysData['Cost'] : 0;
        $extinguishers->rental_amount = !empty($SysData['rental_amount']) ? $SysData['rental_amount'] : 0;
        $extinguishers->supplier_id = !empty($SysData['']) ? $SysData['supplier_id'] : 0;
        $extinguishers->item_no = !empty($SysData['item_no']) ? $SysData['item_no'] : 0;
        $extinguishers->Weight = !empty($SysData['Weight']) ? $SysData['Weight'] : 0;
        $extinguishers->purchase_order = !empty($SysData['purchase_order']) ? $SysData['purchase_order'] : 0;
        $extinguishers->bar_code = !empty($SysData['bar_code']) ? $SysData['bar_code'] : '';
        $extinguishers->Serial_number = !empty($SysData['Serial_number']) ? $SysData['Serial_number'] : '';
        $extinguishers->invoice_number = !empty($SysData['invoice_number']) ? $SysData['invoice_number'] : '';
        $extinguishers->Status = 1;
        $extinguishers->update();

        //Upload Image picture
       if ($request->hasFile('image')) {
           $fileExt = $request->file('image')->extension();
           if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
               $fileName = "image" . time() . '.' . $fileExt;
               $request->file('image')->storeAs('Vehicle/fireextinguishers/images', $fileName);
               //Update file name in the database
               $extinguishers->image = $fileName;
               $extinguishers->update();
           }
       }
      	   //Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('documents')->isValid()) {
                $fileName = time() . "_fire_documents." . $fileExt;
                $request->file('documents')->storeAs('Vehicle/fireextinguishers/document', $fileName);
                //Update file name in the table
                $extinguishers->attachement = $fileName;
                $extinguishers->update();
            }
        }
        AuditReportsController::store('Fleet Management', 'Vehicle Fire Extinguishers Updated', "Accessed by User", 0);
        return response()->json(); 
        
    }

    public function changeFirestatus(Request $request, vehicle_fire_extinguishers $extinguishers){
         $this->validate($request, [
            'Status' => 'required',
            'fire_id' => 'required',
        ]);
        $sysData = $request->all();
        unset($sysData['_token']);
        $fireID = $sysData['fire_id'];
        $Status = $sysData['Status'];

		DB::table('vehicle_fire_extinguisher')
            ->where('id', $fireID)
            ->update(['Status' => $Status]);

         
        AuditReportsController::store('Fleet Management', 'Vehicle Fire Fire Extinguishers Status Chabged', "Accessed by User", 0);
        return response()->json(); 
    }

}