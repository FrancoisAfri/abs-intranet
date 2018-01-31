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
        $vehicleTypes = Vehicle_managemnt::orderBy('status', 1)->get();
        $vehicle = vehicle_maintenance::orderBy('status', 1)->get();

//        $vehiclebookinglog = DB::table('vehicle_details')
//            ->select('vehicle_booking.*', 'vehicle_make.name as vehicleMake',
//                'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType',
//                'hr_people.first_name as firstname', 'hr_people.surname as surname',
//                'vehicle_collect_documents.document as collectDoc',
//                'vehicle_return_documents.document as returnDoc')
//            ->leftJoin('hr_people', 'vehicle_booking.driver_id', '=', 'hr_people.id')
//            ->leftJoin('vehicle_make', 'vehicle_booking.vehicle_make', '=', 'vehicle_make.id')
//            ->leftJoin('vehicle_model', 'vehicle_booking.vehicle_model', '=', 'vehicle_model.id')
//            ->leftJoin('vehicle_managemnet', 'vehicle_booking.vehicle_type', '=', 'vehicle_managemnet.id')
//            ->leftJoin('vehicle_collect_documents', 'vehicle_booking.id', '=', 'vehicle_collect_documents.bookingID')
//            ->leftJoin('vehicle_return_documents', 'vehicle_booking.id', '=', 'vehicle_return_documents.bookingID')
//            ->orderBy('vehicle_booking.id', 'desc')
//            ->where('vehicle_booking.vehicle_id', $vehicleID)
//            ->get();



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
        return $request;

        $actionFrom = $actionTo = 0;
        $userID = $request['hr_person_id'];
        $fleetNo = $request['fleet_no'];
        $Description = $request['description'];
        $actionDate = $request['action_date'];
        $expiryDate = $request['expiry_date'];
        $vehicleType = $request['vehicle_type'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $historyAudit = DB::table('vehicle_documets')
            ->select('vehicle_documets.*')
//            ->leftJoin('hr_people', 'leave_history.hr_id', '=', 'hr_people.id')
//            ->leftJoin('leave_types', 'leave_history.leave_type_id', '=', 'leave_types.id')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('vehicle_documets.upload_date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($vehicleType) {
                if (!empty($vehicleType)) {
                    $query->where('vehicle_documets.type', $vehicleType);
                }
            })
            ->where(function ($query) use ($Description) {
                if (!empty($Description)) {
                    $query->where('vehicle_documets.description', 'ILIKE', "%$Description%");
                }
            })
            ->orderBy('leave_history.id')
            ->get();

    }

    public function image_search(Request $request)
    {

    }
}
