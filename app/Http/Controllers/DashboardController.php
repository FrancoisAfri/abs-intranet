<?php

namespace App\Http\Controllers;

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
		//$data['active_rib'] = 'apply';
        $user = Auth::user()->load('person');
		
        if ($user->type === 1 || $user->type === 3) {

            $activityStatus = ['' => '', -1 => "Rejected", 1 => "Pending Approval", 2 => 'Approved', 3 => 'Completed'];
            $statusLabels = [-1 => "label-danger", 1 => "label-warning", 2 => 'label-success', 3 => 'label-info'];

            $data['status_array'] = $activityStatus;
            $data['user'] = $user;
            $data['statusLabels'] = $statusLabels;
            $data['page_title'] = "Admin Dashboard";
			$data['page_description'] = "Administrator main Dashboard";
            
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
