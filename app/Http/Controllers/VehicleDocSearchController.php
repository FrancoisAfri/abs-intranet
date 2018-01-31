<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\vehiclemake;
use App\vehiclemodel;
use App\Vehicle_managemnt;
use App\programme;
use App\projects;
use App\hr_person;
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
        AuditReportsController::store('Leave History Audit', 'Reports page accessed', "Accessed by User", 0);
        return view('Vehicles.document_search')->with($data);
    }

    public function doc_search(Request $request){

    }
    public function image_search(Request $request){

    }
}
