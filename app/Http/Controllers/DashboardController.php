<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\HRPerson;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\projects;
use App\activity;
use App\programme;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data['breadcrumb'] = [
            ['title' => 'Dashboard', 'path' => '/', 'icon' => 'fa fa-dashboard', 'active' => 1, 'is_module' => 1]
        ];
		$data['active_mod'] = 'dashboard';
        $user = Auth::user()->load('person');
		
        if ($user->type === 1 || $user->type === 3) {
            $topGroupLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first();
            $totNumEmp = HRPerson::count();
            $data['user'] = $user;
            $data['totNumEmp'] = $totNumEmp;
            $data['topGroupLvl'] = $topGroupLvl;
            $data['page_title'] = "Dashboard";
			$data['page_description'] = "This is your main Dashboard";

            return view('dashboard.admin_dashboard')->with($data); //Admin Dashboard
        }
        else {
			# Get loan status
            //$data['page_title'] = "Dashboard";
			//$data['page_description'] = "Main Dashboard";
            //return view('dashboard.client_dashboard')->with($data); //Clients Dashboard
        }
    }
}
