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
            ['title' => 'Fleet Management', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Policies';
        $data['employees'] = $employees;
        $data['Policy'] = $Policy;
        $data['division_levels'] = $divisionLevels;
        $data['ContactCompany'] = $ContactCompany;
        $data['DivisionLevelFive'] = $DivisionLevelFive;


        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
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

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->get();

        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $policyID = $users->id;
        $data['policyID'] = $policyID;
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
        $data['policyUsers'] = $policyUsers;
        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'view Policies';
        AuditReportsController::store('Security', 'Users Access Page Accessed', "Accessed By User", 0);
        return view('policy.users_list_access')->with($data);


    }

    public function policyUserAct(Request $request, $users)
    {
        $policy = Policy_users::where('id', $users)->first();
        if ($policy->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $policy->status = $stastus;
        $policy->update();
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
            $policyUsers->user_id = $hrID->id;
            $policyUsers->policy_id = $policyData['policyID'];
            $policyUsers->save();
            // get user details
            $firstname = $hrID->first_name;
            $surname = $hrID->surname;
            $email = $hrID->email;

            #mail to user
            Mail::to($email)->send(new createPolicy($firstname, $surname, $email));
        }

        return response()->json();
    }

    public function viewPolicies()
    {

        $policies = Policy::where('status', 1)->orderBy('name', 'asc')->get();
        $policy = $policies->load('policyUsers');

//        $policyreport = DB::table('policy')
//            ->select('policy.*' , 'policy_users.*')
//            ->leftJoin('policy_users', 'policy.id', '=', 'policy_users.policy_id')
////            ->leftJoin('vehicle_image', 'vehicle_details.id', '=', 'vehicle_image.vehicle_maintanace')
//            ->orderBy('policy.id')
//
//            ->get();


        $modules = modules::where('active', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Fleet Management', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];
        $data['policies'] = $policies;
        $data['policy'] = $policy;
        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'view Policies';
        $data['modules'] = $modules;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Security', 'Users Access Page Accessed', "Accessed By User", 0);
        return view('policy.policy_list_access')->with($data);

    }
}
