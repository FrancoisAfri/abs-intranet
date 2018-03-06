<?php

namespace App\Http\Controllers;

use App\Loan;
use App\module_access;
use App\module_ribbons;
use App\modules;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data['page_title'] = "Dashboard";
        $data['breadcrumb'] = [
            ['title' => 'Dashboard', 'path' => '/users', 'icon' => 'fa fa-dashboard', 'active' => 1, 'is_module' => 1]
        ];
        return view('layouts.main_layout')->with($data);
    }

    public function testPage() {
        /*
        $user = Auth::user();
        $modules = modules::whereHas('moduleRibbon', function ($query) {
            $query->where('active', 1);
        })->where('active', 1)
            ->whereIn('id', module_access::select('module_id')->where(function ($query) use ($user) {
                $query->where('user_id', $user->id);
                $query->whereNotNull('access_level');
                $query->where('access_level', '>', 0);
            })->get())
            ->with(['moduleRibbon' => function ($query) use($user) {
                $query->whereIn('id', module_ribbons::select('security_modules_ribbons.id')->join('security_modules_access', function($join) use($user) {
                    $join->on('security_modules_ribbons.module_id', '=', 'security_modules_access.module_id');
                    $join->on('security_modules_access.user_id', '=', DB::raw($user->id));
                    $join->on('security_modules_ribbons.access_level', '<=', 'security_modules_access.access_level');
                })->get());
                $query->orderBy('sort_order', 'ASC');
            }])
            ->orderBy('name', 'ASC')->get();

        return $modules;
        */
        //->orderBy('security_modules.name', 'asc')
          //  ->orderBy('security_modules_ribbons.sort_order', 'asc')
/*
        $activeLoans = Loan::whereHas('loanStatus', function($query){
            $query->where('status', 4);
        })->get();
        return $activeLoans;
*/
        $data['page_title'] = "Test Page";
        $data['tasks'] = [
            [
                'name' => 'Design New Dashboard',
                'progress' => '87',
                'color' => 'danger'
            ],
            [
                'name' => 'Create Home Page',
                'progress' => '76',
                'color' => 'warning'
            ],
            [
                'name' => 'Some Other Task',
                'progress' => '32',
                'color' => 'success'
            ],
            [
                'name' => 'Start Building Website',
                'progress' => '56',
                'color' => 'info'
            ],
            [
                'name' => 'Develop an Awesome Algorithm',
                'progress' => '10',
                'color' => 'success'
            ]
        ];
        //return view('multistep_test')->with($data);
        return view('test')->with($data);
    }
}
