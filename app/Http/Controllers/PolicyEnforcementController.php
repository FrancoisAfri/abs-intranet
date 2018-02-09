<?php

namespace App\Http\Controllers;

use App\LeaveType;
use App\Mail\createPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Users;
use App\Policy_users;
use App\Policy;
use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\DivisionLevelOne;
use App\DivisionLevel;
use App\ContactCompany;
use App\HRPerson;
use App\modules;
use App\FleetType;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class PolicyEnforcementController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
    }

    public function create()
    {

        $Policy = Policy::all();
        $employees = HRPerson::where('status', 1)->get();

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();

        $DivisionLevelFive = DivisionLevelFive::where('active', 1)->orderBy('id', 'desc')->get();

        $users = HRPerson::where('division_level_5', 1)->orderBy('id', 'desc')->get();
        // $DivFive = DivisionLevel::where('level', 5)->orderBy('id', 'desc')->get();
        // return $users;


        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Policies';
        $data['employees'] = $employees;
        $data['Policy'] = $Policy;
        $data['division_levels'] = $divisionLevels;
        $data['ContactCompany'] = $ContactCompany;
        $data['DivisionLevelFive'] = $DivisionLevelFive;


        AuditReportsController::store('Policy', 'create policy Page Accessed', "Accessed By User", 0);
        return view('policy.create_policy')->with($data);
    }

    public function createpolicy(Request $request)
    {
        $this->validate($request, [
            'division_level_5' => 'required',
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
            'hr_person_id' => 'required',
        ]);
        $policyData = $request->all();
        unset($policyData['_token']);

        $dates = $policyData['date'] = str_replace('/', '-', $policyData['date']);
        $dates = $policyData['date'] = strtotime($policyData['date']);

        $policy = new Policy();
        $policy->name = $policyData['name'];
        $policy->description = $policyData['description'];
        $policy->date = $dates;
        $policy->status = 1;
        $policy->save();

        $policyID = $policy->id;
        //Upload policy document
        if ($request->hasFile('document')) {
            $fileExt = $request->file('document')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('document')->isValid()) {
                $fileName = $policyID . "_policy_documet." . $fileExt;
                $request->file('document')->storeAs('Policies/policy', $fileName);
                $policy->document = $fileName;
                $policy->update();
            }
        }

//        // get users
        $DivOne = DivisionLevel::where('level', 1)->orderBy('id', 'desc')->first();
        $DivTwo = DivisionLevel::where('level', 2)->orderBy('id', 'desc')->first();
        $DivThree = DivisionLevel::where('level', 3)->orderBy('id', 'desc')->first();
        $DivFour = DivisionLevel::where('level', 4)->orderBy('id', 'desc')->first();
        $DivFive = DivisionLevel::where('level', 5)->orderBy('id', 'desc')->first();

        $users = 0;
        if (!empty($policyData['hr_person_id'])) {
            $users = HRPerson::wherein('id', $policyData['hr_person_id'])->orderBy('id', 'desc')->get();

        } elseif ($DivOne->active == 1 && (!empty($policyData['division_level_1']) && $policyData['division_level_1'] > 0)) {
            $users = HRPerson::where('division_level_1', ($policyData['division_level_1']))->orderBy('id', 'desc')->get();
        } elseif ($DivTwo->active == 1 && (!empty($policyData['division_level_2']) && $policyData['division_level_2'] > 0)) {
            $users = HRPerson::where('division_level_2', ($policyData['division_level_2']))->orderBy('id', 'desc')->get();
        } elseif ($DivThree->active == 1 && (!empty($policyData['division_level_3']) && $policyData['division_level_3'] > 0)) {
            $users = HRPerson::where('division_level_3', ($policyData['division_level_3']))->orderBy('id', 'desc')->get();
        } elseif ($DivFour->active == 1 && (!empty($policyData['division_level_4']) && $policyData['division_level_4'] > 0)) {
            $users = HRPerson::where('division_level_4', ($policyData['division_level_4']))->orderBy('id', 'desc')->get();
        } elseif ($DivFive->active == 1 && (!empty($policyData['division_level_5']) && $policyData['division_level_5'] > 0)) {
            $users = HRPerson::where('division_level_5', ($policyData['division_level_5']))->orderBy('id', 'desc')->get();
        }
//
        foreach ($users as $hrID) {
            # create record in policy users
            $policyUsers = new Policy_users();
            $policyUsers->user_id = $hrID->id;
            $policyUsers->policy_id = $policyID;
            $policyUsers->read_not_understood = 0;
            $policyUsers->read_understood = 0;
            $policyUsers->read_not_sure = 0;
            $policyUsers->status = 1;
            $policyUsers->save();

            // get user details
            $firstname = $hrID->first_name;
            $surname = $hrID->surname;
            $email = $hrID->email;

            #mail to user
            Mail::to($email)->send(new createPolicy($firstname, $surname, $email));
        }
        AuditReportsController::store('Policy', 'Add policy Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function policyAct(Policy $pol)
    {
        if ($pol->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $pol->status = $stastus;
        $pol->update();
        AuditReportsController::store('Policy', 'Policy Activation Page Accessed', "Accessed By User", 0);
        return back();
    }

    public function viewUsers(Policy $users)
    {

        $policyUsers = DB::table('policy_users')
            ->select('policy_users.*', 'policy.date as Expiry', 'hr_people.first_name as firstname', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'policy_users.user_id', '=', 'hr_people.id')
            ->leftJoin('policy', 'policy_users.policy_id', '=', 'policy.id')
            ->where('policy_id', $users->id)
            ->orderBy('policy_users.id')
            ->get();

        //return $policyUsers;

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->get();

        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $policyID = $users->id;
        $policyname = $users->name;
        $data['policyname'] = $policyname;
        $data['policyID'] = $policyID;
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
        $data['policyUsers'] = $policyUsers;
        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'view Policies';

        AuditReportsController::store('Policy', 'View Policy Users Page Accessed', "Accessed By User", 0);
        return view('policy.users_list_access')->with($data);


    }

    public function policyUserAct(Request $request, Policy_users $policyUser)
    {
        if ($policyUser->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $policyUser->status = $stastus;
        $policyUser->update();
        AuditReportsController::store('Policy', 'Policy User Activation  Page Accessed', "Accessed By User", 0);
        return back();
    }

    public function addpolicyUsers(Request $request)
    {
        $this->validate($request, [
//            'division_level_5' => 'required',
//            'name' => 'required',
//            'description' => 'required',
//            'date' => 'required',
//            'hr_person_id' => 'required',
        ]);
        $policyData = $request->all();
        unset($policyData['_token']);

        //        // get users
        $DivOne = DivisionLevel::where('level', 1)->orderBy('id', 'desc')->first();
        $DivTwo = DivisionLevel::where('level', 2)->orderBy('id', 'desc')->first();
        $DivThree = DivisionLevel::where('level', 3)->orderBy('id', 'desc')->first();
        $DivFour = DivisionLevel::where('level', 4)->orderBy('id', 'desc')->first();
        $DivFive = DivisionLevel::where('level', 5)->orderBy('id', 'desc')->first();

        $users = 0;
        if (!empty($policyData['hr_person_id'])) {
            $users = HRPerson::wherein('id', $policyData['hr_person_id'])->orderBy('id', 'desc')->get();

        } elseif ($DivOne->active == 1 && (!empty($policyData['division_level_1']) && $policyData['division_level_1'] > 0)) {
            $users = HRPerson::where('division_level_1', ($policyData['division_level_1']))->orderBy('id', 'desc')->get();
        } elseif ($DivTwo->active == 1 && (!empty($policyData['division_level_2']) && $policyData['division_level_2'] > 0)) {
            $users = HRPerson::where('division_level_2', ($policyData['division_level_2']))->orderBy('id', 'desc')->get();
        } elseif ($DivThree->active == 1 && (!empty($policyData['division_level_3']) && $policyData['division_level_3'] > 0)) {
            $users = HRPerson::where('division_level_3', ($policyData['division_level_3']))->orderBy('id', 'desc')->get();
        } elseif ($DivFour->active == 1 && (!empty($policyData['division_level_4']) && $policyData['division_level_4'] > 0)) {
            $users = HRPerson::where('division_level_4', ($policyData['division_level_4']))->orderBy('id', 'desc')->get();
        } elseif ($DivFive->active == 1 && (!empty($policyData['division_level_5']) && $policyData['division_level_5'] > 0)) {
            $users = HRPerson::where('division_level_5', ($policyData['division_level_5']))->orderBy('id', 'desc')->get();
        }
//
        foreach ($users as $hrID) {
            # create record in policy users
            $policyUsers = new Policy_users();
            //use updateOrCreate to avoid duplicates
            $policyUsers->updateOrCreate(['policy_id' => $policyData['policyID']], ['user_id' => $hrID->id]);
            // get user details
            $firstname = $hrID->first_name;
            $surname = $hrID->surname;
            $email = $hrID->email;
            #mail to user
            Mail::to($email)->send(new createPolicy($firstname, $surname, $email));
        }
        AuditReportsController::store('Policy', 'Policy Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function viewPolicies()
    {

        $policies = Policy::where('status', 1)->orderBy('name', 'asc')->get();
        $policy = $policies->load('policyUsers');

        $users = Auth::user()->person->id;
        $today = time();

        $policyUsers = DB::table('policy_users')
            ->select('policy_users.*', 'policy.date as Expiry', 'policy.name as policyName',
                'policy.description as policyDescription', 'policy.document as policyDoc',
                'hr_people.first_name as firstname',
                'hr_people.surname as surname')
            ->leftJoin('hr_people', 'policy_users.user_id', '=', 'hr_people.id')
            ->leftJoin('policy', 'policy_users.policy_id', '=', 'policy.id')
            ->where('policy.date', '>', $today)
            ->where('policy_users.user_id', $users)
            ->orderBy('policy_users.id')
            ->get();


        $modules = modules::where('active', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['policyUsers'] = $policyUsers;
        $data['policies'] = $policies;
        $data['policy'] = $policy;
        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'view Policies';
        $data['modules'] = $modules;
        $data['division_levels'] = $divisionLevels;

        AuditReportsController::store('Policy', 'View Policy  Page Accessed', "Accessed By User", 0);
        return view('policy.policy_list_access')->with($data);

    }

    public function updatestatus(Request $request)
    {

        $policyData = $request->all();
        unset($policyData['_token']);
        unset($policyData['emp-list-table_length']);

        $status = $policyData['docread'];

        if (count($status) > 0) {
            foreach ($status as $policyID => $levels) {

                //return $levels;

                $Acess = explode('-', $levels);
                $accessLevel = $Acess[0];
                $user = $Acess[1];

                return $accessLevel == 'read_understood';

                $policyUsers = Policy_users::where('policy_id', $policyID)->where('user_id', $user)->first();
                $policyUsers->read_understood = ($accessLevel == 'read_understood') ? 1 : 0;
                $policyUsers->read_not_understood = ($accessLevel == 'read_not_understood') ? 1 : 0;
                $policyUsers->read_not_sure = ($accessLevel == 'read_not_sure') ? 1 : 0;
                $policyUsers->date_read = time();
                $policyUsers->update();

            }
        }
        AuditReportsController::store('Policy', 'Update Policy Status  Page Accessed', "Accessed By User", 0);
        return back();


    }

    public function policySearchindex()
    {


        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Search Policies';

        AuditReportsController::store('Policy', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('policy.policy_search')->with($data);
    }

    public function docsearch(Request $request)
    {
        $policyData = $request->all();
        unset($policyData['_token']);

        $actionFrom = $actionTo = 0;
        $name = $policyData['policy_name'];
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $policyUsers = DB::table('policy')
            ->select('policy.*')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('policy.date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($name) {
                if (!empty($name)) {
                    $query->where('policy.name', 'ILIKE', "%$name%");
                }
            })
            ->orderBy('policy.id')
            ->get();

        //return $policyUsers;

        $data['policyUsers'] = $policyUsers;

        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Document Search Page Accessed', "Accessed By User", 0);
        return view('policy.policyDoc_results')->with($data);
    }


    public function reports()
    {

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $policy = Policy::where('status', 1)->get();
        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['division_levels'] = $divisionLevels;
        $data['policy'] = $policy;
        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Reports Page Accessed', "Accessed By User", 0);
        return view('policy.reports_search')->with($data);
    }

    public function reportsearch(Request $request)
    {
        $policyData = $request->all();
        unset($policyData['_token']);

        $actionFrom = $actionTo = 0;
        //$DivFive = $DivFour = $DivThree = $DivTwo = $DivOne;
        $DivFive = !empty($policyData['division_level_5']) ? $policyData['division_level_5'] : 0;
        $DivFour = !empty($policyData['division_level_4']) ? $policyData['division_level_4'] : 0;
        $DivThree = !empty($policyData['division_level_3']) ? $policyData['division_level_3'] : 0;
        $DivTwo = !empty($policyData['division_level_2']) ? $policyData['division_level_2'] : 0;
        $DivOne = !empty($policyData['division_level_1']) ? $policyData['division_level_1'] : 0;


        $name = $policyData['policy_name'];
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $policyUsers = DB::table('policy')
            ->select('policy.*')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('policy.date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($name) {
                if (!empty($name)) {
                    $query->where('policy.name', 'ILIKE', "%$name%");
                }
            })
            ->orderBy('policy.id')
            ->get();

        //return $policyUsers;

        $data['policyUsers'] = $policyUsers;

        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Reports Page Accessed', "Accessed By User", 0);
        return view('policy.policyDoc_results')->with($data);
    }
}
