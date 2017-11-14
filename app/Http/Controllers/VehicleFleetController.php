<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\jobcardMaintance;
Use App\permits_licence;
use App\FleetType;
use App\Vehicle_managemnt;
use App\fleet_licence_permit;
use App\vehicle;
Use App\job_maintanace;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\modules;
use App\vehicle_maintenance;
use App\vehiclemake;
use App\fleet_documentType;
use App\keytracking;
use App\safe;
use App\reminders;
use App\vehicle_documets;
use App\images;
use App\fleet_fillingstation;
use App\module_access;
use App\DivisionLevelFive;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class VehicleFleetController extends Controller
{
    public function document(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();
        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');
        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
       ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;

            $vehicleDocumets = DB::table('vehicle_documets')
                ->select('vehicle_documets.*')
                ->orderBy('vehicle_documets.id')
                ->get();

            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
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
            $data['vehicleDocumets'] = $vehicleDocumets;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.document')->with($data);
        } else
            return back();

    }

    public function contracts(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $keytracking = keytracking::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $vehicleDocumets = DB::table('vehicle_documets')
                ->select('vehicle_documets.*')
                ->orderBy('vehicle_documets.id')
                ->get();

            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
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
            $data['vehicleDocumets'] = $vehicleDocumets;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.contracts')->with($data);
        } else
            return back();

    }

    public function addnotes(vehicle_maintenance $maintenance)
    {

        $ID = $maintenance->id;

        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $vehicledetail = vehicle_detail::orderBy('id', 'asc')->get();
        $vehicle_maintenance = vehicle_maintenance::where('id', $ID)->get()->first();
        $vehicle_image = images::orderBy('id', 'asc')->get();
        $keytracking = keytracking::orderBy('id', 'asc')->get();
        $safe = safe::orderBy('id', 'asc')->get();

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;
			$vehiclenotes = DB::table('notes')
			->select('notes.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
			->leftJoin('hr_people', 'notes.captured_by', '=', 'hr_people.id')
            		->orderBy('notes.id')
			->get();
		
            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
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
            $data['vehiclenotes'] = $vehiclenotes;
            $data['vehicle_image'] = $vehicle_image;
            $data['vehicle_maintenance'] = $vehicle_maintenance;
            $data['vehicle'] = $vehicle;
            $data['Vehicle_types'] = $Vehicle_types;
            $data['vehiclemodel'] = $vehiclemodel;
            $data['divisionLevels'] = $divisionLevels;
            $data['vehicledetail'] = $vehicledetail;
            $data['vehiclemake'] = $vehiclemake;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.notes')->with($data);
        } else
            return back();
    }

    public function reminders(vehicle_maintenance $maintenance){
    	 $ID = $maintenance->id;

        

        $employees = HRPerson::where('status', 1)->orderBy('id', 'desc')->get();

        $keyStatus = array(1 => 'In Use', 2 => 'Reallocated', 3 => 'Lost', 4 => 'In Safe',);
        $IssuedTo = array(1 => 'Employee', 2 => 'Safe');

        $currentDate = time();
        ################## WELL DETAILS ###############
        $vehiclemake = vehiclemake::where('id', $maintenance->vehicle_make)->get()->first();
        $vehiclemaker = $vehiclemake->name;

        $vehicle_model = vehiclemodel::where('id', $maintenance->vehicle_model)->get()->first();
        $vehiclemodeler = $vehicle_model->name;

        $vehicleType = Vehicle_managemnt::where('id', $maintenance->vehicle_type)->get()->first();
        $vehicleTypes = $vehicleType->name;
        ################## WELL DETAILS ###############

        $loggedInEmplID = Auth::user()->person->id;
        $Employee = HRPerson::where('id', $loggedInEmplID)->orderBy('id', 'desc')->get()->first();
        $name = $Employee->first_name . ' ' . $Employee->surname;
        ###################>>>>>################# 

        if ($maintenance->status == 1) {
            $ID = $maintenance->id;
            //return $ID;


            $reminders = DB::table('vehicle_reminders')
                ->select('vehicle_reminders.*')
                ->orderBy('vehicle_reminders.id')
                ->get();


            //return $vehicleDocumets;


            $data['page_title'] = " View Fleet Details";
            $data['page_description'] = "FleetManagement";
            $data['breadcrumb'] = [
                ['title' => 'Fleet  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Fleet ', 'active' => 1, 'is_module' => 0]
            ];

            $data['name'] = $name;
            $data['IssuedTo'] = $IssuedTo;
            $data['keyStatus'] = $keyStatus;
            $data['employees'] = $employees;
            $data['vehiclemaker'] = $vehiclemaker;
            $data['vehicleTypes'] = $vehicleTypes;
            $data['vehiclemodeler'] = $vehiclemodeler;
            $data['reminders'] = $reminders;
            $data['maintenance'] = $maintenance;
            $data['active_mod'] = 'Vehicle Management';
            $data['active_rib'] = 'Manage Fleet';
            AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            //return view('products.products')->with($data);
            return view('Vehicles.FleetManagement.reminders')->with($data);
        } else
            return back();
    }
}
