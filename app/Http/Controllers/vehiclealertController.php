<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\CompanyIdentity;
use App\permits_licence;
use App\vehicle_maintenance;
use App\ContactCompany;
use App\Vehicle_managemnt;
use App\HRPerson;
use App\vehicle_detail;
use App\vehicle;
use App\vehicle_booking;
use App\vehiclemake;
use App\vehiclemodel;
use App\DivisionLevel;
use App\vehicle_fuel_log;
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

class vehiclealertController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        
    }
}
