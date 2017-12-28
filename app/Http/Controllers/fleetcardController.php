<?php

namespace App\Http\Controllers;

use App\DivisionLevelFour;
use App\Http\Requests;
use App\Users;
use App\DivisionLevel;
use App\Vehicle_managemnt;
use App\vehicle;
Use App\vehicle_booking;
use App\HRPerson;
use App\vehicle_detail;
use App\vehiclemodel;
use App\vehicle_maintenance;
use App\vehicle_collect_documents;
use App\vehiclemake;
use App\safe;
use App\vehicle_collect_image;
use App\images;
use App\vehicle_fuel_log;
use App\fleetcard_type;
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
        $data['active_rib'] = 'Fleet Cards';

        AuditReportsController::store('Vehicle Management', 'Vehicle Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Fleet_cards.search_fleet_cards')->with($data);
    }

    /**
     * Search Fleet cards.
     *
     * @param  \Illuminate\Http\Request  $request
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

         $vehiclebooking = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_booking.require_datetime as require_date ',
                'vehicle_booking.return_datetime as return_date ', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type',
                'division_level_fives.name as company', 'division_level_fours.name as Department')
            ->leftJoin('vehicle_booking', 'vehicle_details.id', '=', 'vehicle_booking.vehicle_id')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->leftJoin('division_level_fives', 'vehicle_details.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'vehicle_details.division_level_4', '=', 'division_level_fours.id')
            // ->where(function ($query) use ($vehicletype) {
            //     if (!empty($vehicletype)) {
            //         $query->where('vehicle_details.vehicle_type', $vehicletype);
            //     }
            // })
            // ->where(function ($query) use ($Company) {
            //     if (!empty($Company)) {
            //         $query->where('vehicle_details.division_level_5', $Company);
            //     }
            // })
            // ->where(function ($query) use ($Department) {
            //     if (!empty($Department)) {
            //         $query->where('vehicle_details.division_level_4', $Department);
            //     }
            // })
            /*->where(function ($query) use ($startDate) {
                if (!empty($startDate)) {
                    $query->where('vehicle_booking.require_datetime', '!=', $startDate);
                }
            })
            ->where(function ($query) use ($EndDate) {
                if (!empty($EndDate)) {
                    $query->where('vehicle_booking.return_datetime', '!=', $EndDate);
                }
            })*/
            ->where('vehicle_details.booking_status', '!=', 1)
            // ->where('vehicle_booking.status' , '=', 12 )
            ->orderBy('vehicle_details.id')
            ->get();

       
        $data['vehiclebooking'] = $vehiclebooking;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
