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


        AuditReportsController::store('Policy Enforcement', 'Policy Enforcement Page Accessed', "Accessed By User", 0);

        return view('policy.create_policy')->with($data);
    }

    public function createpolicy(Request $request)
    {
        $this->validate($request, [
            'division_level_5' => 'required',
			'name' => 'required|unique:policy,name',
            'description' => 'required',
            'date' => 'required',
            'document' => 'required',
        ]);
        $policyData = $request->all();
        unset($policyData['_token']);


        if (isset($policyData['date'])) {
            $dates = $policyData['date'] = str_replace('/', '-', $policyData['date']);
            $dates = $policyData['date'] = strtotime($policyData['date']);
        }

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
            $policyUsers->date_added = time();
//            $policyUsers->read_not_understood = ;
//            $policyUsers->read_understood = 0;
//            $policyUsers->read_not_sure = 0;
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
            ['title' => 'Policy Enforcement', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $policyID = $users->id;
        $policyname = $users->name;
        $data['policyname'] = $policyname;
        $data['policyID'] = $policyID;
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
        $data['policyUsers'] = $policyUsers;
        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'View Policies';
        AuditReportsController::store('Policy Enforcement', 'View Policy Page Accessed', "Accessed By User", 0);
        return view('policy.users_list_access')->with($data);
    }

    public function editPolicy(Request $request, Policy $policy)
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


        if (isset($policyData['date'])) {
            $dates = $policyData['date'] = str_replace('/', '-', $policyData['date']);
            $dates = $policyData['date'] = strtotime($policyData['date']);
        }

        $policy->name = $policyData['name'];
        $policy->description = $policyData['description'];
        $policy->date = $dates;
        $policy->update();

        //Upload policy document
        if ($request->hasFile('document')) {
            $fileExt = $request->file('document')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('document')->isValid()) {
                $fileName = $policy->id . "_policy_documet." . $fileExt;
                $request->file('document')->storeAs('Policies/policy', $fileName);
                $policy->document = $fileName;
                $policy->update();
            }
        }

        AuditReportsController::store('Policy', 'Edit policy Page Accessed', "Accessed By User", 0);
        return response()->json();
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
            $policyUsers->updateOrCreate(['policy_id' => $policyData['policyID']], ['user_id' => $hrID->id], ['date_added' => time()]);
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
            ->limit(100)
            ->get();

        //  return $policyUsers;


        $modules = modules::where('active', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]
        ];

        $data['policyUsers'] = $policyUsers;
        $data['policies'] = $policies;
        $data['policy'] = $policy;
        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'View Policies';
        $data['modules'] = $modules;
        $data['division_levels'] = $divisionLevels;

        AuditReportsController::store('Policy', 'View Policy  Page Accessed', "Accessed By User", 0);
        return view('policy.policy_list_access')->with($data);

    }

    public function updatestatus(Request $request)
    {
        $this->validate($request, [
            'docread' => 'required',
        ]);
        $policyData = $request->all();
        unset($policyData['_token']);
        unset($policyData['emp-list-table_length']);

        $status = $policyData['docread'];
        if (count($status) > 0) {
            foreach ($status as $policyID => $levels) {

                $Acess = explode('-', $levels);
                $accessLevel = $Acess[0];
                $user = $Acess[1];

                $policyUsers = Policy_users::where('policy_id', $policyID)->where('user_id', $user)->first();
                $policyUsers->read_understood = ($accessLevel == 1) ? 1 : 0;
                $policyUsers->read_not_understood = ($accessLevel == 2) ? 1 : 0;
                $policyUsers->read_not_sure = ($accessLevel == 3) ? 1 : 0;
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
            ->limit(100)
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
        $data['active_rib'] = 'Search Policies';

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

        $DivFive = !empty($policyData['division_level_5']) ? $policyData['division_level_5'] : 0;
        $DivFour = !empty($policyData['division_level_4']) ? $policyData['division_level_4'] : 0;
        $DivThree = !empty($policyData['division_level_3']) ? $policyData['division_level_3'] : 0;
        $DivTwo = !empty($policyData['division_level_2']) ? $policyData['division_level_2'] : 0;
        $DivOne = !empty($policyData['division_level_1']) ? $policyData['division_level_1'] : 0;


        $name = !empty($policyData['policy_name']) ? $policyData['policy_name'] : 0;
        $actionDate = $request['action_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }


        $Policiereports = DB::table('policy')
            ->select('policy.*')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('policy.date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($name) {
                if (!empty($name)) {
                    $query->where('policy.id', $name);
                }
            })
            ->limit(100)
            ->orderBy('policy.id')
            ->get();

        $val = count($Policiereports);
        if ($val > 1) {
            $Policies = Policy::orderby('id', 'asc')->get();
            if (!empty($Policies))
                $Policies = $Policies->load('policyUsers');
        } elseif (!empty($Policiereports)) {
            $ID = $Policiereports->first()->id;
            $Policies = Policy::where('id', $ID)->get();
            if (!empty($Policies))
                $Policies = $Policies->load('policyUsers');
        }


       
      
        $data['Policies'] = $Policies;
        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]];

        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy Reports Page Accessed', "Accessed By User", 0);
        return view('policy.reportsResults_search')->with($data);
    }

    public function viewdetails(Policy $policydetails)
    {

        $Policies = DB::table('policy_users')
            ->select('policy_users.*', 'policy.date as Expiry', 'policy.name as policyName',
                'policy.description as policyDescription', 'policy.document as policyDoc',
                'hr_people.first_name as firstname', 'hr_people.surname as surname',
                'hr_people.division_level_5 as company', 'hr_people.division_level_4 as Department',
                'division_level_fives.name as company', 'division_level_fours.name as Department'
            )
            ->leftJoin('hr_people', 'policy_users.user_id', '=', 'hr_people.id')
            ->leftJoin('policy', 'policy_users.policy_id', '=', 'policy.id')
            ->leftJoin('division_level_fives', 'hr_people.division_level_5', '=', 'division_level_fives.id')
            ->leftJoin('division_level_fours', 'hr_people.division_level_4', '=', 'division_level_fours.id')
            ->where('policy_users.policy_id', $policydetails->id)
            ->orderBy('policy_users.id')
            ->limit(100)
            ->get();

        $PolicyID = $Policies->first()->policy_id;
        $Policy = Policy::where('id', $PolicyID)->first();

         $readunderstood = array(0 => '', 1 => 'Read and Understood');
        $readntstood = array(0 => '', 1 => 'Read and not Understood');
        $readntsure = array(0 => '', 1 => 'Read but not Sure');
        

        $data['readunderstood'] = $readunderstood;
        $data['readntstood'] = $readntstood;
        $data['readntsure'] = $readntsure;

        $data['Policies'] = $Policies;
        $data['Policy'] = $Policy;
        $data['page_title'] = "Policy Enforcement System";
        $data['page_description'] = "Policy Enforcement System";
        $data['breadcrumb'] = [['title' => 'Policy Enforcement System', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Enforcement System ', 'active' => 1, 'is_module' => 0]];

        $data['active_mod'] = 'Policy';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy', 'Policy View Details Page Accessed', "Accessed By User", 0);
        return view('policy.viewdetails_search')->with($data);

    }

    public function viewpolicyUsers(Request $request)
    {
        $results = $request->all();
        unset($results['_token']);
        unset($results['emp-list-table_length']);

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'userID'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $userID = $aValue[1];

                $users = HRPerson::where('user_id', $userID)->orderBy('id', 'desc')->first();
                $firstname = $users->first_name;
                $surname = $users->surname;
                $email = $users->email;
                #mail to user
                Mail::to($email)->send(new createPolicy($firstname, $surname, $email));

            }
        }
        AuditReportsController::store('Policy Enforcement', 'Policy Enforcement Page Accessed', "Accessed By User", 0);
        return back();
    }
}
