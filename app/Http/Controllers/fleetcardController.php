<?php

namespace App\Http\Controllers;

use App\DivisionLevelFour;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\vehicle_maintenance;
use App\Vehicle_managemnt;
use App\vehicle;
Use App\vehicle_booking;
use App\HRPerson;
use App\vehicle_detail;
use App\vehicle_fleet_cards;
use App\fleetcard_type;
use App\fleetType;
use App\module_ribbons;
use App\ribbons_access;
use App\ContactCompany;
use App\vehicle_return_images;
use App\vehicle_return_documents;
use Illuminate\Http\Request;
use App\Mail\vehicle_bookings;
use App\Mail\confirm_collection;
use App\Mail\vehiclebooking_approval;
use App\Mail\vehiclebooking_cancellation;
use App\Mail\vehiclebooking_rejection;
use App\Mail\vehiclebooking_manager_notification;
use App\Mail\vehicle_confirm_collection;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

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
        $fleetcardtype = fleetType::orderBy('id', 'desc')->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_detail = vehicle_detail::orderBy('id', 'desc')->get();


        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Cards Search";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];


        $data['vehicle_detail'] = $vehicle_detail;
        $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Fleet Cards';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
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

        //return $vehicleData;
        $cardtype = $request['card_type_id'];
        $fleetnumber = $request['fleet_number'];
        $company = $request['company_id'];
        $holder = $vehicleData['holder_id'];
        $status = $vehicleData['status'];

        $Vehiclemanagemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $hrDetails = HRPerson::where('status', 1)->get();
        $fleetcardtype = fleetcard_type::orderBy('id', 'desc')->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_detail = vehicle_detail::orderBy('id', 'desc')->get();

        $vehiclefleetcards = vehicle_fleet_cards::orderBy('id', 'asc')->get();

        //return $vehiclefleetcards;

        $fleetcard = DB::table('vehicle_fleet_cards')
            ->select('vehicle_fleet_cards.*', 'contact_companies.name as Vehicle_Owner', 'hr_people.first_name as first_name', 'hr_people.surname as surname')
            ->leftJoin('contact_companies', 'vehicle_fleet_cards.company_id', '=', 'contact_companies.id')
            ->leftJoin('hr_people', 'vehicle_fleet_cards.holder_id', '=', 'hr_people.id')
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
            ->orderBy('vehicle_fleet_cards.id')
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
        $data['fleetcard'] = $fleetcard;
        $data['page_title'] = " Vehicle Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Manage Fleet';
        AuditReportsController::store('Vehicle Management', 'View Vehicle Search Results', "view Audit Results", 0);

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
            // 'holder_id' => 'bail|required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);
        $expiry = strtotime($docData['expiry_date']);
        $vehiclefleetcards = new vehicle_fleet_cards();
        $vehiclefleetcards->card_type_id = $docData['card_type_id'];
        $vehiclefleetcards->fleet_number = $docData['fleet_number'];
        $vehiclefleetcards->company_id = $docData['company_id'];
        $vehiclefleetcards->holder_id = $docData['holder_id'];
        $vehiclefleetcards->card_number = $docData['card_number'];
        $vehiclefleetcards->cvs_number = $docData['cvs_number'];
        $vehiclefleetcards->issued_date = strtotime($docData['issued_date']);
        $vehiclefleetcards->expiry_date = $expiry;
        $vehiclefleetcards->status = $docData['status'];
        $vehiclefleetcards->save();

        AuditReportsController::store('Vehicle Management', 'Add Vehicle Fleet Card', "Add Vehicle Fleet Card", 0);
        return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editfleetcard(Request $request, vehicle_fleet_cards $vehiclefleetcard)
    {
        $this->validate($request, [
            'holder_id' => 'bail|required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);
        $expiry = strtotime($docData['expiry_date']);
        $vehiclefleetcard->card_type_id = $docData['card_type_id'];
        $vehiclefleetcard->fleet_number = $docData['fleet_number'];
        $vehiclefleetcard->company_id = $docData['company_id'];
        $vehiclefleetcard->holder_id = $docData['holder_id'];
        $vehiclefleetcard->card_number = $docData['card_number'];
        $vehiclefleetcard->cvs_number = $docData['cvs_number'];
        $vehiclefleetcard->issued_date = strtotime($docData['issued_date']);
        $vehiclefleetcard->expiry_date = $expiry;
        $vehiclefleetcard->status = $docData['status'];
        $vehiclefleetcard->update();

        AuditReportsController::store('Vehicle Management', 'Update Vehicle Fleet Card', "Update Vehicle Fleet Card", 0);
        return response()->json();
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
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];


        $data['vehicle_detail'] = $vehicle_detail;
        $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Driver Administration';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
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
       // return $docData;

        $Company = !empty($docData['division_level_5']) ? $docData['division_level_5'] : 0;
        $Department = !empty($docData['division_level_4']) ? $docData['division_level_4'] : 0;
        $employee = $docData['employee'];
        $status = $docData['status'];

        $users = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();

        //
        $drverdetails = DB::table('drver_details')
            ->select('drver_details.*', 'division_level_fives.name as company', 'division_level_fours.name as Department',
             'hr_people.first_name as first_name', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'drver_details.hr_person_id', '=', 'hr_people.id')
            ->leftJoin('division_level_fives', 'drver_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'drver_details.division_level_4', '=', 'division_level_fours.id')
            // ->where(function ($query) use ($cardtype) {
            //     if (!empty($cardtype)) {
            //         $query->where('vehicle_fleet_cards.card_type_id', $cardtype);
            //     }
            // })
            // ->where(function ($query) use ($Company) {
            //     if (!empty($Company)) {
            //         $query->where('drver_details.division_level_5', $Company);
            //     }
            // })
            // ->where(function ($query) use ($Department) {
            //     if (!empty($Department)) {
            //         $query->where('drver_details.division_level_4', $Department);
            //     }
            // })
            // ->where(function ($query) use ($employee) {
            //     if (!empty($employee)) {
            //         $query->where('vehicle_fleet_cards.holder_id', $employee);
            //     }
            // })
            ->where(function ($query) use ($status) {
                if (!empty($status)) {
                    $query->where('drver_details.status', $status);
                }
            })
            ->orderBy('drver_details.id')
            ->get();

            return $drverdetails;

        $status = array(1 => ' Active', 2 => ' InActive');

        //$data['vehicle_detail'] = $vehicle_detail;
       // $data['fleetcardtype'] = $fleetcardtype;
        // $data['hrDetails'] = $hrDetails;
        // $data['contactcompanies'] = $contactcompanies;
        // $data['Vehicle_types'] = $Vehicle_types;
        // $data['division_levels'] = $divisionLevels;
        // $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['status'] = $status;
        $data['drverdetails'] = $drverdetails;
        $data['page_title'] = " Vehicle Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Driver Administration';
        AuditReportsController::store('Vehicle Management', 'View Vehicle Search Results', "view Audit Results", 0);

        return view('Vehicles.Driver Admin.drivers_results')->with($data);

    }

    public function vehicle_approval(Request $request) {

        $Vehiclemanagemnt = vehicle_maintenance::orderBy('id', 'asc')->get();
        return $Vehiclemanagemnt;
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $hrDetails = HRPerson::where('status', 1)->get();
        $fleetcardtype = fleetcard_type::orderBy('id', 'desc')->get();
        $contactcompanies = ContactCompany::where('status', 1)->orderBy('id', 'desc')->get();
        $vehicle_detail = vehicle_detail::orderBy('id', 'desc')->get();

        $data['page_title'] = "Fleet Types";
        $data['page_description'] = "Fleet Cards Search";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards Report ', 'active' => 1, 'is_module' => 0]
        ];


        $data['vehicle_detail'] = $vehicle_detail;
        $data['fleetcardtype'] = $fleetcardtype;
        $data['hrDetails'] = $hrDetails;
        $data['contactcompanies'] = $contactcompanies;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['division_levels'] = $divisionLevels;
        $data['Vehiclemanagemnt'] = $Vehiclemanagemnt;
        $data['active_mod'] = 'Vehicle Management';
        $data['active_rib'] = 'Driver Administration';

        AuditReportsController::store('Vehicle Approvals', 'Vehicle Approvals Page Accessed', "Accessed By User", 0);
        //return view('Vehicles.Vehicle Approvals.vehicle_approvals')->with($data);
        return view('Vehicles.Vehicle Approvals.vehicle_approvals')->with($data);
    }
}
