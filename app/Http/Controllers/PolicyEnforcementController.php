<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\ContactCompany;
use App\DivisionLevel;
use App\DivisionLevelFive;
use App\HRPerson;
use App\Policy_Category;
use App\Http\Requests;
use App\Mail\createPolicy;
use App\modules;
use App\Policy;
use App\Policy_users;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
class PolicyEnforcementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $categories = Policy_Category::where('status', 1)->get();
		if (!empty($categories)) $categories = $categories->load('policies');
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Categories";
        $data['breadcrumb'] = [
            ['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Add Policy';
        $data['categories'] = $categories;
        AuditReportsController::store('Policy Enforcement', 'Policy Enforcement Page Accessed', "Accessed By User", 0);

        return view('policy.create_policy_categories')->with($data);
    }
	
	///
	public function policyCat(Policy_Category $policyCat)
    {
        $policies = Policy::where('category_id',$policyCat->id)->get();
		if (!empty($policies)) $policies = $policies->load('policyCategory');
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $categories = Policy_Category::where('status', 1)->get();

        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $DivisionLevelFive = DivisionLevelFive::where('active', 1)->orderBy('id', 'desc')->get();
        $ContactCompany = ContactCompany::orderBy('id', 'asc')->get();
        $users = HRPerson::where('status', 1)->get();

        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [
            ['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Add Policy';
        $data['policyCat'] = $policyCat;
        $data['employees'] = $employees;
        $data['policies'] = $policies;
        $data['division_levels'] = $divisionLevels;
        $data['ContactCompany'] = $ContactCompany;
        $data['DivisionLevelFive'] = $DivisionLevelFive;
        $data['categories'] = $categories;
        AuditReportsController::store('Policy Enforcement', 'Policy Enforcement Page Accessed', "Accessed By User", 0);

        return view('policy.create_policy')->with($data);
    }

    public function createpolicy(Request $request)
    {
        $this->validate($request, [
            'division_level_5' => 'required',
            'category_id' => 'required',
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
        $policy->category_id = $policyData['category_id'];
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
                $fileName = time() . "_policy_documet." . $fileExt;
                $request->file('document')->storeAs('Policies/policy', $fileName);
                $policy->document = $fileName;
                $policy->update();
            }
        }
        AuditReportsController::store('Policy Enforcement', "New Policy Created Policy Name: $policy->name", "Added By User", 0);
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
            $policyUsers->status = 1;
            $policyUsers->save();

            // get user details
            $firstname = $hrID->first_name;
            $surname = $hrID->surname;
            $email = $hrID->email;
            AuditReportsController::store('Policy Enforcement', "New User Added: $hrID->first_name $hrID->surname To Policy: $policy->name", "Added By User", 0);
            #mail to user
            Mail::to($email)->send(new createPolicy($firstname, $surname, $email));
        }
        return response()->json();
    }

    public function policyAct(Policy $pol)
    {
        if ($pol->status == 1) {
            $label = "De-Activate";
            $stastus = 0;
        } else {
            $stastus = 1;
            $label = "Activate";
        }
        $pol->status = $stastus;
        $pol->update();
        AuditReportsController::store('Policy Enforcement', 'Policy Status Changed', "Changed  to $label For Policy: $pol->name", 0);
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
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
		$DivisionLevelFive = DivisionLevelFive::where('active', 1)->orderBy('id', 'desc')->get();
        
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]
        ];
        $policyID = $users->id;
        $policyname = $users->name;
        $data['policyname'] = $policyname;
        $data['policyID'] = $policyID;
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
        $data['policyUsers'] = $policyUsers;
        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Add Policy';
        AuditReportsController::store('Policy Enforcement', 'View Policy Page Accessed', "Accessedessed By User.", 0);
        return view('policy.users_list_access')->with($data);
    }

    public function editPolicy(Request $request, Policy $policy)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'date' => 'required',
			]);
        $policyData = $request->all();
        unset($policyData['_token']);


        if (isset($policyData['date'])) {
            $dates = $policyData['date'] = str_replace('/', '-', $policyData['date']);
            $dates = $policyData['date'] = strtotime($policyData['date']);
        }

        $policy->category_id = $policyData['category_id'];
        $policy->name = $policyData['name'];
        $policy->description = $policyData['description'];
        $policy->date = $dates;
        $policy->update();

        //Upload policy document
        if ($request->hasFile('document')) {
            $fileExt = $request->file('document')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('document')->isValid()) {
                $fileName = time() . "_policy_documet." . $fileExt;
                $request->file('document')->storeAs('Policies/policy', $fileName);
                $policy->document = $fileName;
                $policy->update();
            }
        }

        AuditReportsController::store('Policy Enforcement', 'Edit Policy Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    public function policyUserAct(Request $request, Policy_users $policyUser)
    {
        if ($policyUser->status == 1) {
            $label = "De-Activated";
            $stastus = 0;
        } else {
            $stastus = 1;
            $label = "Activated";
        }

        $user = HRPerson::where('id', $policyUser->user_id)->first();
        $policyUser->status = $stastus;
        $policyUser->update();
        AuditReportsController::store('Policy Enforcement', "Policy User Status Changed", "Changed  to $label For User: $user->first_name $user->surname", 0);
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

        //get users
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

        foreach ($users as $hrID) {
            $OldUser = Policy_users::where('user_id', $hrID->id)->where('policy_id', $policyData['policyID'])->first();
            # create record in policy users
            if (empty($OldUser->id)) {
                $policyUsers = new Policy_users();
                $policyUsers->user_id = $hrID->id;
                $policyUsers->policy_id = $policyData['policyID'];
                $policyUsers->date_added = time();
                $policyUsers->status = 1;
                $policyUsers->save();

                // get user details
                $firstname = $hrID->first_name;
                $surname = $hrID->surname;
                $email = $hrID->email;

                #mail to user
                Mail::to($email)->send(new createPolicy($firstname, $surname, $email));
            }
        }
        AuditReportsController::store('Policy Enforcement', 'New User Added to Policy', "Added By User", 0);
        return response()->json();
    }

    public function viewPolicies()
    {
        $policies = Policy::where('status', 1)->orderBy('name', 'asc')->get();
        if (!empty($policies)) $policies = $policies->load('policyCategory');
        $users = Auth::user()->person->id;
        $today = time();

        $policyUsers = DB::table('policy_users')
            ->select('policy_users.*', 'policy.date as expiry', 'policy.name as policy_name',
                'policy.description as policy_description', 'policy.document as policy_doc',
                'hr_people.first_name as firstname',
                'hr_people.surname as surname', 'policy_category.name as cat_name')
            ->leftJoin('hr_people', 'policy_users.user_id', '=', 'hr_people.id')
            ->leftJoin('policy', 'policy_users.policy_id', '=', 'policy.id')
            ->leftJoin('policy_category', 'policy.category_id', '=', 'policy_category.id')
            //->where('policy.date', '>', $today)
            ->where('policy_users.user_id', $users)
            ->orderBy('policy_users.id')
            ->limit(100)
            ->get();
//return $policyUsers;
        $modules = modules::where('active', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]
        ];

        $data['policyUsers'] = $policyUsers;
        $data['policies'] = $policies;
       // $data['policy'] = $policy;
        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'My Policies';
        $data['modules'] = $modules;
        $data['division_levels'] = $divisionLevels;

        AuditReportsController::store('Policy Enforcement', 'View Policies Page Accessed', "Accessed By User", 0);
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
                if ($accessLevel == 1) $accessLevelLabel = 'Read Understood';
                elseif ($accessLevel == 2) $accessLevelLabel = 'Read Not Understood';
                elseif ($accessLevel == 3) $accessLevelLabel = 'Read Not Sure';
                else $accessLevelLabel = '';
                $policyUsers = Policy_users::where('policy_id', $policyID)->where('user_id', $user)->first();
                $policyUsers->read_understood = ($accessLevel == 1) ? 1 : 0;
                $policyUsers->read_not_understood = ($accessLevel == 2) ? 1 : 0;
                $policyUsers->read_not_sure = ($accessLevel == 3) ? 1 : 0;
                $policyUsers->date_read = time();
                $policyUsers->update();
                $policyName = Policy::where('id', $policyID)->first();
                AuditReportsController::store('Policy Enforcement', 'Policy Status Updated', "Status Changed to: $accessLevelLabel on Policy: $policyName->name", 0);
            }
        }
        return back();
    }

    public function policySearchindex()
    {
		$categories = Policy_Category::where('status', 1)->get();
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [
            ['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Search Policies';
        $data['categories'] = $categories;

        AuditReportsController::store('Policy Enforcement', 'Policy Search Page Accessed', "Accessed By User", 0);
        return view('policy.policy_search')->with($data);
    }

    public function docsearch(Request $request)
    {
        $policyData = $request->all();
        unset($policyData['_token']);

        $actionFrom = $actionTo = 0;
        $name = $policyData['policy_name'];
        $categoryID = $policyData['category_id'];
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
			->where(function ($query) use ($categoryID) {
                if (!empty($categoryID)) {
                    $query->where('policy.category_id',$categoryID);
                }
            })
            ->limit(100)
            ->orderBy('policy.name')
            ->get();
			
        $data['policyUsers'] = $policyUsers;

        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [
            ['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Search Policies';

        AuditReportsController::store('Policy Enforcement', 'Policy Document Search Page Accessed', "Accessed By User", 0);
        return view('policy.policyDoc_results')->with($data);
    }
	//
	public function viewPolicy(Policy $policy)
    {
		$policy = $policy->load('policyCategory','policyUsers.employees');
		//return $policy;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
		$DivisionLevelFive = DivisionLevelFive::where('active', 1)->orderBy('id', 'desc')->get();
        
        $data['division_levels'] = $divisionLevels;
        $data['policyID'] = $policy->id;
        $data['employees'] = $employees;
        $data['DivisionLevelFive'] = $DivisionLevelFive;
        $data['policy'] = $policy;
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Search Policies';

        AuditReportsController::store('Policy Enforcement', 'Policy Users Details Page Accessed', "Accessed By User", 0);
        return view('policy.view_policy_details')->with($data);
    }
	// read Policies
	public function readPolicy(Policy_users $user)
    {
		$users = Auth::user()->person->id;
		$user = $user->load('policy');
		//return $user;
		$document = !empty($user->policy->document) ? $user->policy->document : '';
        $data['policy_documnet'] = (!empty($document)) ? Storage::disk('local')->url("Policies/policy/$document") : '';
		$data['user'] = $user;
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Read Policy ', 'active' => 1, 'is_module' => 0]];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'My Policies';

        AuditReportsController::store('Policy Enforcement', "$user->policy->name Policy Viewed", "Accessed By User", 0);
        return view('policy.read_policy')->with($data);
    }
	//
    public function reports()
    {
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $categories = Policy_Category::where('status', 1)->get();
		$policy = Policy::where('status', 1)->get();
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [
            ['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]
        ];

        $data['division_levels'] = $divisionLevels;
        $data['policy'] = $policy;
        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Reports';
        
		$data['categories'] = $categories;

        AuditReportsController::store('Policy Enforcement', 'Policy Reports Page Accessed', "Accessed By User", 0);
        return view('policy.reports_search')->with($data);
    }

    public function reportsearch(Request $request)
    {
        $policyData = $request->all();
        unset($policyData['_token']);

        $actionFrom = $actionTo = 0;
		$categoryID = $policyData['category_id'];
        $DivFive = !empty($policyData['division_level_5']) ? $policyData['division_level_5'] : 0;
        $DivFour = !empty($policyData['division_level_4']) ? $policyData['division_level_4'] : 0;
        $DivThree = !empty($policyData['division_level_3']) ? $policyData['division_level_3'] : 0;
        $DivTwo = !empty($policyData['division_level_2']) ? $policyData['division_level_2'] : 0;
        $DivOne = !empty($policyData['division_level_1']) ? $policyData['division_level_1'] : 0;
        $name = !empty($policyData['policy_name']) ? $policyData['policy_name'] : 0;
        $actionDate = $request['policy_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $policies = Policy::where(function ($query) use ($actionFrom, $actionTo) {
            if ($actionFrom > 0 && $actionTo > 0) {
                $query->whereBetween('policy.date', [$actionFrom, $actionTo]);
            }
        })
            ->where(function ($query) use ($name) {
                if (!empty($name)) {
                    $query->where('policy.id', $name);
                }
            })
			->where(function ($query) use ($categoryID) {
                if (!empty($categoryID)) {
                    $query->where('policy.category_id',$categoryID);
                }
            })
            ->orderBy('policy.name')
            ->get();
        if (!empty($policies))
            $policies = $policies->load('policyUsers','policyCategory');

        $data['policies'] = $policies;
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy Enforcement', 'Policy Reports Page Accessed', "Accessed By User", 0);
        return view('policy.reportsResults_search')->with($data);
    }

    public function viewdetails(Policy $policydetails)
    {
        $policies = DB::table('policy_users')
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
            ->get();


        $policyID = $policies->first()->policy_id;
        $policy = Policy::where('id', $policyID)->first();

        $data['policies'] = $policies;
        $data['policy'] = $policy;
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy Enforcement', 'Policy Users Details Page Accessed', "Accessed By User", 0);
        return view('policy.viewdetails_search')->with($data);

    }

    public function viewuserdetails(Policy $policydetails)
    {
        $policies = DB::table('policy_users')
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

        $PolicyID = $policies->first()->policy_id;
        $Policy = Policy::where('id', $PolicyID)->first();

        $data['policies'] = $policies;
        $data['Policy'] = $Policy;
        $data['page_title'] = "Policy Library";
        $data['page_description'] = "Policy Library";
        $data['breadcrumb'] = [['title' => 'Policy Library', 'path' => '/System/policy/create', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Policy Library ', 'active' => 1, 'is_module' => 0]];

        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Policy Enforcement', 'Policy Users Details Page Accessed', "Accessed By User", 0);
        return view('policy.viewuserdetails')->with($data);

    }

    public function viewuserprint(Policy $policydetails)
    {
        $policies = DB::table('policy_users')
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

        $PolicyID = $policies->first()->policy_id;
        $Policy = Policy::where('id', $PolicyID)->first();

        $data['policies'] = $policies;
        $data['Policy'] = $Policy;
//
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];

        $data['page_title'] = "Policy Enforcement Report";
        $data['page_description'] = "Policy Enforcement Report";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement', 'path' => '/System/policy/create', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 1], //  ['title' => 'Leave History Audit', 'path' => '/leave/Leave_History_Audit', 'icon' => 'fa fa-eye', 'active' => 0, 'is_module' => 0],
            ['title' => 'Policy Users', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Reports';
        $user = Auth::user()->load('person');
        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        AuditReportsController::store('Policy Enforcement', 'Printed Policy Users Report Results', "Printed by Users", 0);
        return view('policy.users_print')->with($data);
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
        AuditReportsController::store('Policy Enforcement', 'Email Sent to Users', "Accessed By User", 0);
        return back();
    }
	
	# View all Categories
    public function viewCategories()
    {
		$Categories = Policy_Category::orderBy('name', 'asc')->get();
        $data['page_title'] = "Categories";
        $data['page_description'] = "Manage Categories";
        $data['breadcrumb'] = [
            ['title' => 'Policy Enforcement', 'path' => '/policy/category', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Categories', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Policy Enforcement';
        $data['active_rib'] = 'Categories';
        $data['Categories'] = $Categories;
		//return $data;
		AuditReportsController::store('Policy Enforcement', 'Categories Page Accessed', "Actioned By User", 0);
        return view('policy.categories')->with($data);
    }

	# Act/deac Category
	public function categoryAct(Policy_Category $category) 
	{
		if ($category->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$category->status = $stastus;	
		$category->update();
		return back();
    }
	
	# Save Category 
    public function categorySave(Request $request)
	{
		$this->validate($request, [
            'name' => 'required',       
            'description' => 'required',       
        ]);
		$categoryData = $request->all();
		unset($categoryData['_token']);
		$category = new Policy_Category($categoryData);
		$category->status = 1;
		$category->name = $categoryData['name'];
		$category->description = $categoryData['description'];
        $category->save();
		$newname = $categoryData['name'];
		AuditReportsController::store('Policy Enforcement', 'Category Added', "Category Name: $categoryData[name]", 0);
		return response()->json(['new_category' => $newname], 200);
    }	
	public function editCategory(Request $request, Policy_Category $category)
	{
        $this->validate($request, [
            'name' => 'required',       
            'description' => 'required',       
        ]);

        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->update();
		$newtemplate = $request->input('name');
        AuditReportsController::store('Policy Enforcement', 'Category Informations Edited', "Edited by User", 0);
        return response()->json(['new_category' => $newtemplate], 200);
    }
}