<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\vehicle_maintenance;
use App\Vehicle_managemnt;
use App\HRPerson;
use App\vehicle_detail;
use App\vehicle;
use App\vehiclemake;
use App\vehiclemodel;
use App\fleet_licence_permit;
use Illuminate\Http\Request;
use App\Mail\confirm_collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class VehicleReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vehicle_maintenance = vehicle_maintenance::orderBy('id', 'asc')->get();
        $licence = permits_licence::orderby('status', 1)->get();

        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.index')->with($data);
    }

    public function general()
    {
        $vehicle = vehicle::orderBy('id', 'asc')->get();
        $Vehicle_types = Vehicle_managemnt::orderBy('id', 'asc')->get();
        $vehiclemake = vehiclemake::orderBy('id', 'asc')->get();
        $vehiclemodel = vehiclemodel::orderBy('id', 'asc')->get();
        $hrDetails = HRPerson::where('status', 1)->get();
        $licence = $permitlicence = fleet_licence_permit::orderBy('id', 'asc')->get();

        $vehicledetail = DB::table('vehicle_details')
            ->select('vehicle_details.*', 'vehicle_make.name as vehicle_make',
                'vehicle_model.name as vehicle_model', 'vehicle_managemnet.name as vehicle_type')
            ->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
            ->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
            ->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
            ->orderBy('vehicle_details.id', 'desc')
            ->get();


        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['licence'] = $licence;
        $data['vehicledetail'] = $vehicledetail;
        $data['hrDetails'] = $hrDetails;
        $data['Vehicle_types'] = $Vehicle_types;
        $data['licence'] = $licence;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.generalreport_search')->with($data);
    }

    public function generaldetails(Request $request)
    {

        $this->validate($request, [

        ]);

        $reportData = $request->all();
        unset($reportData['_token']);

        $actionFrom = $actionTo = 0;
        $reportID = $reportData['report_id'];
        $reportType = $reportData['report_type'];
        $vehicleType = $reportData['vehicle_type'];
        $licenceType = $reportData['licence_type'];
        $vehicleID = $reportData['vehicle_id'];
        $driverID = $reportData['driver_id'];

        //return $reportID;

        if($reportID  == 1){
            $actionDate = $request['action_date'];
            if (!empty($actionDate)) {
                $startExplode = explode('-', $actionDate);
                $actionFrom = strtotime($startExplode[0]);
                $actionTo = strtotime($startExplode[1]);
            }

            $Policies = DB::table('policy')
                ->select('policy.*')
                ->where(function ($query) use ($actionFrom, $actionTo) {
                    if ($actionFrom > 0 && $actionTo > 0) {
                        $query->whereBetween('policy.date', [$actionFrom, $actionTo]);
                    }
                })
//            ->where(function ($query) use ($name) {
//                if (!empty($name)) {
//                    $query->where('policy.name', 'ILIKE', "%$name%");
//                }
//            })
                ->limit(100)
                ->orderBy('policy.id')
                ->get();

            $data['Policies']= $Policies;
            $data['page_title'] = " Fleet Management ";
            $data['page_description'] = "Fleet Cards Report ";
            $data['breadcrumb'] = [
                ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
            ];

            $data['active_mod'] = 'Fleet Management';
            $data['active_rib'] = 'Reports';

            AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
            return view('Vehicles.Reports.bookinglog_results')->with($data);
        }elseif ($reportID == 2){

        }



    }

    public function jobcard()
    {
        $data['page_title'] = " Fleet Management ";
        $data['page_description'] = "Fleet Cards Report ";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/vehicle_management/vehicle_reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Vehicle Report ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('Vehicles.Reports.index')->with($data);
    }
}
