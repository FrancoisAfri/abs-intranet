<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\vehiclemake;
use App\vehiclemodel;
use App\Vehicle_managemnt;
use App\programme;
use App\projects;
use App\hr_person;
use App\vehicle_maintenance;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class VehicleDocSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vehiclemaker = vehiclemake::orderBy('status', 1)->get();
        $vehiclemodeler = vehiclemodel::orderBy('status', 1)->get();
        //$vehicleTypes = Vehicle_managemnt::orderBy('status', 1)->get();
        $vehicle = vehicle_maintenance::orderBy('status', 1)->get();

        $vehicleTypes = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicleMake',
                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id', 'desc')
            ->get();

        //return $vehicleTypes;


        $data['page_title'] = "Search";
        $data['page_description'] = "Document & Image Search";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Search', 'path' => '/vehicle_management/Search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], ['title' => 'Vehicle Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Search';
        $data['vehiclemaker'] = $vehiclemaker;
        $data['vehiclemodeler'] = $vehiclemodeler;
        $data['vehicleTypes'] = $vehicleTypes;
        $data['vehicle'] = $vehicle;
        AuditReportsController::store('Leave History Audit', 'Reports page accessed', "Accessed by User", 0);
        return view('Vehicles.document_search')->with($data);
    }

    public function doc_search(Request $request)
    {
        $this->validate($request, [
        ]);
        $request = $request->all();
        unset($request['_token']);

        $actionFrom = $actionTo = 0;
        // $userID = $request['hr_person_id'];
        $fleetNo = $request['fleet_no'];
        $Description = $request['description'];
        $actionDate = $request['action_date'];
        //$expiryDate = $request['expiry_date'];
        $vehicleType = $request['vehicle_type'];

        $expiryDate = $request['expiry_date'] = str_replace('/', '-', $request['expiry_date']);
        $expiryDate = $request['expiry_date'] = strtotime($request['expiry_date']);

        // return $expiryDate;

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $vehicleDocumets = DB::table('vehicle_documets')
            ->select('vehicle_documets.*','vehicle_details.vehicle_make as make','vehicle_details.fleet_number as fleetNumber')
            ->leftJoin('vehicle_details', 'vehicle_documets.vehicleID', '=', 'vehicle_details.id')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('vehicle_documets.upload_date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_documets.vehicleID', $vehicleType);
                }
            })
            ->where(function ($query) use ($expiryDate) {
                if (!empty($expiryDate)) {
                    $query->where('vehicle_documets.exp_date', $expiryDate);
                }
            })
            ->where(function ($query) use ($Description) {
                if (!empty($Description)) {
                    $query->where('vehicle_documets.description', 'ILIKE', "%$Description%");
                }
            })
            ->orderBy('vehicle_documets.id')
            ->get();

        $data['page_title'] = "Search";
        $data['page_description'] = "Document Search";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Search', 'path' => '/vehicle_management/Search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], ['title' => 'Vehicle Search', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicleDocumets'] = $vehicleDocumets;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.vehicleDoc_search_results')->with($data);


    }

    public function image_search(Request $request)
    {
        $this->validate($request, [
        ]);
        $request = $request->all();
        unset($request['_token']);


        $actionFrom = $actionTo = 0;
        // $userID = $request['hr_person_id'];
        $fleetNo = $request['fleet_no'];
        $Description = $request['description'];
        $actionDate = $request['action_date'];
        //$expiryDate = $request['expiry_date'];
        $vehicleType = $request['vehicle_type'];
        $vehicle_make = $request['vehicle_make'];

        $expiryDate = $request['expiry_date'] = str_replace('/', '-', $request['expiry_date']);
        $expiryDate = $request['expiry_date'] = strtotime($request['expiry_date']);

        // return $expiryDate;

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }


        $vehicleImages = DB::table('vehicle_image')
            ->select('vehicle_image.*', 'hr_people.first_name as first_name', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'vehicle_image.user_name', '=', 'hr_people.id')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('vehicle_image.upload_date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_image.vehicle_maintanace', $vehicleType);
                }
            })
            ->where(function ($query) use ($fleetNo) {
                if (!empty($fleetNo)) {
                    $query->where('vehicle_image.fleetNumber', $fleetNo);
                }
            })
            ->where(function ($query) use ($fleetNo) {
                if (!empty($fleetNo)) {
                    $query->where('vehicle_image.fleetNumber', $fleetNo);
                }
            })
            ->where(function ($query) use ($Description) {
                if (!empty($Description)) {
                    $query->where('vehicle_image.description', 'ILIKE', "%$Description%");
                }
            })
            ->orderBy('vehicle_image.id')
            ->get();

      // return $vehicleImages;

        $data['page_title'] = "Search";
        $data['page_description'] = " Image Search";
        $data['breadcrumb'] = [
            ['title' => 'Vehicle Search', 'path' => '/vehicle_management/Search', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], ['title' => 'Vehicle Search', 'active' => 1, 'is_module' => 0]
        ];

        $data['vehicleImages'] = $vehicleImages;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
        return view('Vehicles.vehicleImage_search_results')->with($data);

    }
}
