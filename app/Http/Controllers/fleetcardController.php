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
        

        $data['page_title'] = "Fleet Cards";
        $data['page_description'] = "Fleet Cards Management";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Fleet Cards ', 'active' => 1, 'is_module' => 0]
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
