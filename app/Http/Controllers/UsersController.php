<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\DivisionLevel;
use App\Mail\ConfirmRegistration;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use App\Mail\NewUsers;
use App\Mail\UserApproval;
use App\Http\Requests;
use App\HRPerson;
use App\PublicHoliday;
use App\User;
use App\modules;
use App\LeaveType;
use App\leave_custom;
use App\module_access;
use App\module_ribbons;
use App\ribbons_access;
use App\Province;
// use App\business_card;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $data['page_title'] = "Users";
        $data['page_description'] = "Search Users";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search user', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Security';
        $data['active_rib'] = 'Search users';
		AuditReportsController::store('Security', 'Search User Page Accessed', "Accessed By User", 0);
        return view('security.search_user')->with($data);
    }
    public function create() {
        $data['page_title'] = "Users";
        $data['page_description'] = "Create a New User";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create user', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Security';
        $data['active_rib'] = 'Create user';		
				
		AuditReportsController::store('Security', 'Create User Page Accessed', "Accessed By User", 0);
        return view('security.add_user')->with($data);
    }
	public function deleteUser(User $user)
	{
		# Delete record form database
		$user->load('person');
		$name = $user->person->first_name.' '.$user->person->surname;
		AuditReportsController::store('Security', 'User Deleted', "Del: $name ", 0);
		DB::table('users')->where('id', '=', $user->id)->delete();
		DB::table('hr_people')->where('user_id', '=', $user->id)->delete();
		return redirect('/users')->with('success_delete', "User Successfully Deleted.");
	}
	public function deleteHoliday(Request $request, PublicHoliday $holidayId)
    {
        $holidayId->delete();

        AuditReportsController::store('Security', 'Public Holiday Deleted', "Deleted by user", 0);
        return redirect('/users/public-holiday');
    }
	public function modules() 
	{
        $modules = DB::table('security_modules')->orderBy('name', 'asc')->get();
		$data['page_title'] = "Security Modules";
		$data['page_description'] = "Admin page for security related settings";
		$data['breadcrumb'] = [
			['title' => 'Security', 'path' => '/users/modules', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
			['title' => 'Modules', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'Security';
        $data['active_rib'] = 'Modules';
		$data['modules'] = $modules;
		AuditReportsController::store('Security', 'Modules Setup Page Accessed', "Accessed By User", 0);
        return view('security.setup')->with($data);
    }
	public function publicHoliday() 
	{
        $holidays = DB::table('public_holidays')->select('public_holidays.*','countries.name as country')
					->leftJoin('countries', 'public_holidays.country_id', '=', 'countries.id')
					->orderBy('day', 'asc')->get();
		$countries = DB::table('countries')->orderBy('name', 'asc')->get();
		$data['page_title'] = "Public Holidays Management";
		$data['page_description'] = "Admin page for security related settings";
		$data['breadcrumb'] = [
			['title' => 'Security', 'path' => '/users/public-holiday', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
			['title' => 'Public Holidays', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'Security';
        $data['active_rib'] = 'Public Holidays Management';
		$data['holidays'] = $holidays;
		$data['countries'] = $countries;
		AuditReportsController::store('Security', 'Public Holidays Page Accessed', "Accessed By User", 0);
        return view('security.public_holiday')->with($data);
    }
	//
	public function saveHoliday(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'holiday_date' => 'required',
            'country_id' => 'required',
        ]);
		$holidayDate = $request->input('holiday_date');
		if (!empty($holidayDate)) {
            $holidayDate = str_replace('/', '-', $holidayDate);
            $holidayDate = strtotime($holidayDate);
        }
		$holiday = new PublicHoliday();
		$holiday->name = $request->input('name');
        $holiday->day = $holidayDate;
        $holiday->country_id = $request->input('country_id');
        $holiday->year = !empty($request->input('year')) ? date('Y'): 0;
        $holiday->save();
        AuditReportsController::store('Security', 'Public Holiday Saved', "Saved by User", 0);
        return response()->json(['new_name' => $holiday->name, 'new_path' => $holiday->holiday_date], 200);
    }
	
	public function updateHoliday(Request $request, PublicHoliday $holiday)
	{
        $this->validate($request, [
            'name' => 'required',
            'holiday_date' => 'required',
            'country_id' => 'required',
        ]);
		$holidayDate = $request->input('holiday_date');
		if (!empty($holidayDate)) {
            $holidayDate = str_replace('/', '-', $holidayDate);
            $holidayDate = strtotime($holidayDate);
        }
		
        $holiday->name = $request->input('name');
        $holiday->day = $holidayDate;
        $holiday->country_id = $request->input('country_id');
        $holiday->year = !empty($request->input('year_once')) ? date('Y'): 0;
        $holiday->update();
        AuditReportsController::store('Security', 'Module Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $holiday->name, 'new_path' => $holiday->path], 200);
    }
	
    public function companySetup()
    {
        $companyDetails = CompanyIdentity::first();
        $data['page_title'] = "Security Setup";
        $data['page_description'] = "Company settings that will be used by the system";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/setup', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $companyDetails;
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Setup';
        $data['companyDetails'] = $companyDetails;
        AuditReportsController::store('Security', 'Setup Page Accessed', "Accessed By User", 0);
        return view('security.company_identity')->with($data);
    }
	public function moduleAccess(User $user) 
	{
		$userID = $user->id;
		$user->load('person');
		//return $user;
        $modules = DB::table('security_modules')->select('security_modules.id as mod_id', 'security_modules.name as mod_name', 'security_modules_access.access_level')
		 ->leftjoin("security_modules_access",function($join) use ($userID) {
                $join->on("security_modules.id","=","security_modules_access.module_id")
                    ->on("security_modules_access.user_id","=",DB::raw($userID));
            })
		->where('security_modules.active', 1)
		->orderBy('security_modules.name', 'asc')
		->get();
		$data['page_title'] = "Security User Access Right";
		$data['page_description'] = "Give user access right";
		$data['breadcrumb'] = [
			['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Module Access', 'active' => 1, 'is_module' => 0]
		];
		$data['modules'] = $modules;
		$data['user'] = $user;
		AuditReportsController::store('Security', 'Module Access Page Accessed', "Accessed By User", 0);
        return view('security.module_access')->with($data);
    }
	
	public function accessSave(Request $request, User $user) {
		$AccessData =  $request->module_access;
		DB::table('security_modules_access')->where('user_id', '=', $user->id)->delete();
		//return $AccessData;
		foreach ($AccessData as $id => $value)
		{ 
			DB::table('security_modules_access')->insert([
				'active' => 1,
				'module_id' => $id,
				'access_level' => $value,
				'user_id' => $user->id,
			]);	
			AuditReportsController::store('Security', 'Access Level Saved', "for Module($id)", 0);
		}
		return back();
	}
	public function ribbonView(modules $mod) 
	{
		$aRarrayRights = array(0 => 'None', 1 => 'Read', 2 => 'Write', 3 => 'Modify', 4 => 'Admin', 5=> 'SuperUser');

        if ($mod->active == 1) 
		{
			$mod->load('moduleRibbon');
			$data['page_title'] = "Security Setup";
			$data['page_description'] = "Module ribbons page";
			$data['breadcrumb'] = [
				['title' => 'Security', 'path' => '/users/modules', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Ribbons', 'active' => 1, 'is_module' => 0]];
			$data['ribbons'] = $mod;
			$data['arrayRights'] = $aRarrayRights;
			$data['active_mod'] = 'security';
			$data['active_rib'] = 'Modules';
			AuditReportsController::store('Security', 'Ribbons Page Accessed', "Accessed by User", 0);
			return view('security.ribbons')->with($data);
		}
		else return back();
    }
	public function editModule(Request $request, modules $mod)
	{
        $this->validate($request, [
            'module_name' => 'required',
            'code_name' => 'required',
            'module_path' => 'required',
            'font_awesome' => 'required',
        ]);

        $mod->name = $request->input('module_name');
        $mod->code_name = strtolower(trim($request->input('code_name')));
        $mod->path = $request->input('module_path');
        $mod->font_awesome = $request->input('font_awesome');
        $mod->update();
        AuditReportsController::store('Security', 'Module Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $mod->name, 'new_path' => $mod->path], 200);
    }
    public function editRibbon(Request $request, module_ribbons $ribbon)
    {
        $this->validate($request, [
            'ribbon_name' => 'required',
            'ribbon_path' => 'required',
            'description' => 'required',
            'access_level' => 'required',
            'sort_order' => 'bail|required|integer|min:0'
        ]);

        $ribbon->ribbon_name = $request->input('ribbon_name');
        $ribbon->ribbon_path = $request->input('ribbon_path');
        $ribbon->description = $request->input('description');
        $ribbon->access_level = $request->input('access_level');
        $ribbon->sort_order = $request->input('sort_order');
        $ribbon->update();
		AuditReportsController::store('Security', 'Ribbons Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $ribbon->ribbon_name, 'new_path' => $ribbon->ribbon_path], 200);
    }
	public function moduleAct(modules $mod) 
	{
		if ($mod->active == 1) $stastus = 0;
		else $stastus = 1;
		
		$mod->active = $stastus;	
		$mod->update();
		return back();
    }
	public function ribbonAct(module_ribbons $rib) 
	{
		if ($rib->active == 1) $stastus = 0;
		else $stastus = 1;
		
		$rib->active = $stastus;	
		$rib->update();
		return back();
    }
	public function addmodules(Request $request) {
	
		$this->validate($request, [
            'module_name' => 'required',
            'code_name' => 'required',
            'module_path' => 'required',
            'font_awesome' => 'required',
        ]);
		$moduleData = $request->all();
		unset($moduleData['_token']);
		$module = new modules($moduleData);
		$module->active = 1;
        $module->name = $moduleData['module_name'];
		$module->code_name = strtolower(trim($moduleData['code_name']));
		$module->path = $moduleData['module_path'];
		$module->font_awesome = $moduleData['font_awesome'];
        $module->save();
		$newName = $moduleData['module_name'];
		$newPath = $moduleData['module_path'];
		AuditReportsController::store('Security', 'Module Added', "Module Name: $moduleData[module_name]", 0);
		return response()->json(['new_name' => $newName, 'new_path' => $newPath], 200);
	}
	public function addribbon(Request $request, modules $mod) {
	
		//Fix the code to use the function created under modules class
		$ribbonData = $request->all();
		unset($ribbonData['_token']);
		$ribbons = new module_ribbons($ribbonData);
		$ribbons->active = 1;
		$ribbons->module_id = $mod->id;
		$ribbons->sort_order = $ribbonData['sort_order'];
		$ribbons->ribbon_name = $ribbonData['ribbon_name'];
		$ribbons->ribbon_path = $ribbonData['ribbon_path'];
		$ribbons->access_level = $ribbonData['access_level'];
		$ribbons->description = $ribbonData['description'];
        $ribbons->save();
		AuditReportsController::store('Security', 'Ribbon Added', "Ribbon Name: $ribbonData[ribbon_name]", 0);
		return response()->json();
	}
    public function store(Request $request ) {
        $this->validate($request, [
            'email' => 'unique:users,email',
            'email' => 'unique:hr_people,email',
        ]);
		//Save usr
		$userID = Auth::user()->id;
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'security')->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $userID)->pluck('user_id')->first();

		if (!empty($userAccess)) $status = 1;
		else $status = 2;
		$compDetails = CompanyIdentity::first();
		$iduration = !empty($compDetails->password_expiring_month) ? $compDetails->password_expiring_month : 0;
		$expiredDate = !empty($iduration) ? mktime(0,0,0,date('m')+ $iduration,date('d'),date('Y')) : 0;
        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 1;
        $user->status = $status;
		$user->password_changed_at = $expiredDate;
        $user->save();

        //exclude empty fields from query
        $personData = $request->all();
        foreach ($personData as $key => $value) {
            if (empty($personData[$key])) {
                unset($personData[$key]);
            }
        }
        //Save HR record
        $person = new HRPerson($personData);
        $person->status = 1;
        $user->addPerson($person);

        //Send email
        Mail::to("$user->email")->send(new ConfirmRegistration($user, $request->password));
		AuditReportsController::store('Security', 'New User Created', "Login Details Sent To User $user->email", 0);
        // email admin for user approval
		if ($status == 2)
		{
			$admins = DB::table('security_modules_access')->select('security_modules_access.user_id')
			->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
			->where('security_modules.code_name', 'security')
			->where('security_modules_access.access_level', '>=', 4)
			->get();
			
			foreach ($admins as $admin) {
				$user = HRPerson::where('user_id', $admin->user_id)->first();
				Mail::to("$user->email")->send(new NewUsers($user->email));
			}
		}
		//Redirect to all usr view
        return redirect('/users')->with('success_add', "The user has been added successfully. \nYou can use the search menu to view the user details.");
    }

    public function edit(User $user) {
        $user->load('person');

        $avatar = $user->person->profile_pic;
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $businessCard = DB::table('business_card')->get();
        $positions = DB::table('hr_positions')->where('status', 1)->orderBy('name', 'asc')->get();
        // return $businessCard;
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();//->load('divisionLevelGroup');
        $data['page_title'] = "Users";
        $data['businessCard'] = $businessCard;
        $data['page_description'] = "View/Update user details";
        $data['back'] = "/users";
        $data['view_by_admin'] = 1;
        $data['user'] = $user;
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : '';
        $data['provinces'] = $provinces;
        $data['ethnicities'] = $ethnicities;
        $data['positions'] = $positions;
        $data['division_levels'] = $divisionLevels;
        $data['marital_statuses'] = $marital_statuses;
        $data['leave_profile'] = $leave_profile;
        $data['employees'] = $employees;
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'User details', 'active' => 1, 'is_module' => 0]
        ];
		//return $user;
		AuditReportsController::store('Security', 'User Information Edited', "On Edit Mode", 0);
        return view('security.view_user')->with($data);
    }

    public function profile() {
        $user = Auth::user()->load('person');
        $avatar = $user->person->profile_pic;
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $positions = DB::table('hr_positions')->where('status', 1)->orderBy('name', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->where('name', 1)->orderBy('name', 'asc')->get();
		$employees = HRPerson::where('status', 1)->get();
        
        $data['page_title'] = "Users";
        $data['page_description'] = "View/Update your details";
        $data['back'] = "/";
        $data['user_profile'] = 1;
        $data['user'] = $user;
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : '';
        $data['provinces'] = $provinces;
        $data['ethnicities'] = $ethnicities;
        $data['positions'] = $positions;
        $data['leave_profile']=$leave_profile ;
        $data['marital_statuses'] = $marital_statuses;
		$data['employees'] = $employees;
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'My profile', 'active' => 1, 'is_module' => 0]
        ];
		AuditReportsController::store('Security', 'User Profile Accessed', "On Profile Mode", 0);
        return view('security.view_user')->with($data);
    }

    public function update(Request $request, User $user) {
        //exclude token, method and command fields from query.
        $person = $request->all();
        unset($person['_token'], $person['_method'], $person['command']);

        //Cell number formatting
        $person['cell_number'] = str_replace(' ', '', $person['cell_number']);
        $person['cell_number'] = str_replace('-', '', $person['cell_number']);
        $person['cell_number'] = str_replace('(', '', $person['cell_number']);
        $person['cell_number'] = str_replace(')', '', $person['cell_number']);

        //exclude empty fields from query
        foreach ($person as $key => $value) {
            if (empty($person[$key])) {
                unset($person[$key]);
            }
        }

        //convert numeric values to numbers
        if (isset($person['res_postal_code'])) {
            $person['res_postal_code'] = (int) $person['res_postal_code'];
        }
        if (isset($person['res_province_id'])) {
            $person['res_province_id'] = (int) $person['res_province_id'];
        }
        if (isset($person['gender'])) {
            $person['gender'] = (int) $person['gender'];
        }
        if (isset($person['id_number'])) {
            $person['id_number'] = (int) $person['id_number'];
        }
        if (isset($person['marital_status'])) {
            $person['marital_status'] = (int) $person['marital_status'];
        }
        if (isset($person['ethnicity'])) {
            $person['ethnicity'] = (int) $person['ethnicity'];
        }
        if (isset($person['leave_profile'])) {
            $person['leave_profile'] = (int) $person['leave_profile'];
        }
        //convert date of birth to unix time stamp
        if (isset($person['date_of_birth'])) {
            $person['date_of_birth'] = str_replace('/', '-', $person['date_of_birth']);
            $person['date_of_birth'] = strtotime($person['date_of_birth']);
        }
		 //convert date joined company to unix time stamp
        if (isset($person['date_joined'])) {
            $person['date_joined'] = str_replace('/', '-', $person['date_joined']);
            $person['date_joined'] = strtotime($person['date_joined']);
        }
		 //convert date left company to unix time stamp
        if (isset($person['date_left'])) {
            $person['date_left'] = str_replace('/', '-', $person['date_left']);
            $person['date_left'] = strtotime($person['date_left']);
        }
		if (empty($person['position'])) $person['position'] = 0;
		
        //Update users and hr table
        $user->update($person);
        $user->person()->update($person);

        //Upload profile picture
        if ($request->hasFile('profile_pic')) {
            $fileExt = $request->file('profile_pic')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('profile_pic')->isValid()) {
                $fileName = time() . "_avatar_" . time() . '.' . $fileExt;
                $request->file('profile_pic')->storeAs('avatars', $fileName);
                //Update file name in hr table
                $user->person->profile_pic = $fileName;
                $user->person->update();
            }
        }
		AuditReportsController::store('Security', 'User Details Updated', "By User", 0);
        //return to the edit page
        //return redirect("/users/$user->id/edit")->with('success_edit', "The user's details have been successfully updated.");
        return back()->with('success_edit', "The user's details have been successfully updated.");
    }

    public function getSearch(Request $request) {
        $personName = trim($request->person_name);
        $personIDNum = trim($request->id_number);
        $personPassport = trim($request->passport_number);
        $email = trim($request->email);
        $aPositions = [];
        $cPositions = DB::table('hr_positions')->get();
        foreach ($cPositions as $position) {
            $aPositions[$position->id] = $position->name;
        }

        $persons = HRPerson::whereHas('user', function ($query) {
            $query->whereIn('type', [1, 3]);
        })
            ->where(function ($query) use ($personName) {
                if (!empty($personName)) {
                    $query->where('first_name', 'ILIKE', "%$personName%");
                }
            })
            ->where(function ($query) use ($personIDNum) {
                if (!empty($personIDNum)) {
                    $query->where('id_number', 'ILIKE', "%$personIDNum%");
                }
            })
            ->where(function ($query) use ($personPassport) {
                if (!empty($personPassport)) {
                    $query->where('passport_number', 'ILIKE', "%$personPassport%");
                }
            })
			->where(function ($query) use ($email) {
                if (!empty($email)) {
                    $query->where('email', 'ILIKE', "%$email%");
                }
            })
            ->orderBy('first_name')
            //->limit(100)
            ->get();
			if (!empty($persons)) $persons = $persons->load('jobTitle');
            
        $data['page_title'] = "Users";
        $data['page_description'] = "List of users found";
        $data['persons'] = $persons;
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'User search result', 'active' => 1, 'is_module' => 0]
        ];
		$data['positions'] = $aPositions;
		$data['active_mod'] = 'Security';
        $data['active_rib'] = 'Search Users';
		AuditReportsController::store('Security', 'User Search Results Accessed', "By User", 0);
        return view('security.users')->with($data);
    }

    public function updatePassword(Request $request, User $user) {
        //return response()->json(['message' => $request['current_password']]);

        $validator = Validator::make($request->all(),[
            'current_password' => 'required',
            'new_password' => 'bail|required|min:6',
            'confirm_password' => 'bail|required|same:new_password'
        ]);

        $validator->after(function($validator) use ($request, $user){
            $userPW = $user->password;

            if (!(Hash::check($request['current_password'], $userPW))) {
                $validator->errors()->add('current_password', 'The current password is incorrect, please enter the correct current password.');
            }
        });

        $validator->validate();

        //Update user password
        $newPassword = $request['new_password'];
        $user->password = Hash::make($newPassword);
        $user->update();
		AuditReportsController::store('Security', 'User Password Updated', "By Admin", 0);
        return response()->json(['success' => 'Password updated successfully.'], 200);
    }

    public function updateUserPassword(Request $request, User $user) {
        //return response()->json(['message' => $request['current_password']]);

        $validator = Validator::make($request->all(),[
            'new_password' => 'bail|required|min:6',
        ]);

        $validator->validate();

        //Update user password
        $newPassword = $request['new_password'];
        $user->password = Hash::make($newPassword);
        $user->update();
		AuditReportsController::store('Security', 'User Password Updated', "By User", 0);
        return response()->json(['success' => 'Password updated successfully.'], 200);
    }

    public function activateUsers(Request $request)
    {
        $statuses = $request->input('status');
        foreach ($statuses as $userID => $status) {
            $user = User::find($userID)->load('person');
            $user->status = $status;
            $user->update();
            if ($user->person) {
                $user->person->status = $status;
                $user->person->update();
            }
        }

        return redirect('/users')->with('changes_saved', "Your changes have been saved successfully.");
    }
	// Reset Password
	public function recoverPassword(Request $request) {

        $validator = Validator::make($request->all(),[
            'reset_email' => 'required',
        ]);

        $validator->validate();

        //find the user
        $user = User::where('email', $request['reset_email'])->first();

        //Update user password
        $randomPass = str_random(10);
        $user->password = Hash::make($randomPass);
        $user->update();

        //email new password to user
        Mail::to("$user->email")->send(new ResetPassword($user, $randomPass));
		AuditReportsController::store('Security', 'User Password Recoverd', "User Password Recoverd; ".$request['reset_email'], 0);
        return response()->json(['success' => 'Password successfully reset.'], 200);
    }
	// report index
	public function reports()
    {
        $modules = modules::where('active', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        
		$data['page_title'] = "Users Access";
        $data['page_description'] = "Admin page to manage users access";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/modules', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'users access';
        $data['modules'] = $modules;
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
		AuditReportsController::store('Audit', 'View Audit Search', "view Audit", 0);
        return view('security.users_search')->with($data);
    }
	/// get users reports
	public function getEmployeesReport(Request $request)
    {
        $this->validate($request, [
            'module_id' => 'required',
        ]);
		
		$divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $hrPersonID = ($request->input('hr_person_id')) ? $request->input('hr_person_id') : 0;
        $moduleID = ($request->input('module_id')) ? $request->input('module_id') : 0;
		$moduleName = modules::find($moduleID)->name;
		
        $employees = HRPerson::select('hr_people.*'
			, 'hr_people.user_id as uid'
			, 'security_modules_access.id',
            'security_modules_access.module_id'
			, 'security_modules_access.user_id',
            'security_modules_access.access_level')
            ->whereNotNull('hr_people.user_id')
            ->where('status', 1)->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
            if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
            if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
            if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
            if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
            if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
        })->where(function ($query) use($hrPersonID){
            if (!empty($hrPersonID)) {
                $query->where('hr_people.id',$hrPersonID);
            }
        })->leftjoin("security_modules_access",function($join) use ($moduleID) {
            $join->on("security_modules_access.module_id", "=", DB::raw($moduleID))
                ->on("security_modules_access.user_id","=", "hr_people.user_id");
        })->get();

        $data['page_title'] = "Users Access";
        $data['page_description'] = "Admin page to manage users access";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/modules', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reports';
        $data['moduleName'] = $moduleName;
        $data['employees'] = $employees;
        $data['module_id'] = $moduleID;
        $data['hr_person_id'] = $hrPersonID;
        $data['division_level_1'] = $divLevel1;
        $data['division_level_2'] = $divLevel2;
        $data['division_level_3'] = $divLevel3;
        $data['division_level_4'] = $divLevel4;
        $data['division_level_5'] = $divLevel5;
       // return $employees;
        AuditReportsController::store('Security', 'Users Report', "Accessed By User", 0);

        return view('security.users_list_access_report')->with($data);
    }
	public function getEmployeesReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
		
        $divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $hrPersonID = ($request->input('hr_person_id')) ? $request->input('hr_person_id') : 0;
        $moduleID = ($request->input('module_id')) ? $request->input('module_id') : 0;
		$moduleName = modules::find($moduleID)->name;

        $employees = HRPerson::select('hr_people.*'
			, 'hr_people.user_id as uid'
			, 'security_modules_access.id',
            'security_modules_access.module_id'
			, 'security_modules_access.user_id',
            'security_modules_access.access_level')
            ->whereNotNull('hr_people.user_id')
            ->where('status', 1)->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
            if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
            if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
            if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
            if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
            if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
        })->where(function ($query) use($hrPersonID){
            if (!empty($hrPersonID)) {
                $query->where('hr_people.id',$hrPersonID);
            }
        })->leftjoin("security_modules_access",function($join) use ($moduleID) {
            $join->on("security_modules_access.module_id", "=", DB::raw($moduleID))
                ->on("security_modules_access.user_id","=", "hr_people.user_id");
        })->get();
		
		$data['employees'] = $employees;
		$data['moduleName'] = $moduleName;
        
		$data['page_title'] = "Users Access";
        $data['page_description'] = "Admin page to manage users access";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('security.users_list_access_report_print')->with($data);
    }
	
	/// get users reports
	public function getUsersReport(Request $request)
    {
        $divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $hrPersonID = ($request->input('hr_person_id')) ? $request->input('hr_person_id') : 0;
        $leaveProfile = array(1 => 'Approved', 2 => 'Require managers approval ', 3 => 'Require department head approval', 4 => 'Require hr approval', 5 => 'Require payroll approval', 6 => 'rejected', 7 => 'rejectd_by_department_head', 8 => 'rejectd_by_hr', 9 => 'rejectd_by_payroll', 10 => 'Cancelled');

        $employees = HRPerson::select('hr_people.*'
			, 'hr_positions.name as job_title', 'hp.first_name as manager_first_name'
			, 'hp.surname as manager_surname'
			, 'leave_profile.name as profile_name')
			->leftJoin('hr_positions', 'hr_people.position', '=', 'hr_positions.id')
			->leftJoin('leave_profile', 'hr_people.leave_profile', '=', 'leave_profile.id')
			->leftJoin('hr_people as hp', 'hr_people.manager_id', '=', 'hp.id')
            ->whereNotNull('hr_people.user_id')
            ->where('hr_people.status', 1)->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
            if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
            if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
            if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
            if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
            if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
        })
		->where(function ($query) use($hrPersonID){
            if (!empty($hrPersonID)) {
                $query->where('hr_people.id',$hrPersonID);
            }
        })
		->orderBy('hr_people.first_name', 'asc')
		->orderBy('hr_people.surname', 'asc')
		->get();
		
        $data['page_title'] = "Users Report";
        $data['page_description'] = "Admin page to manage users";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reports';
        $data['employees'] = $employees;
        $data['hr_person_id'] = $hrPersonID;
        $data['division_level_1'] = $divLevel1;
        $data['division_level_2'] = $divLevel2;
        $data['division_level_3'] = $divLevel3;
        $data['division_level_4'] = $divLevel4;
        $data['division_level_5'] = $divLevel5;
       // return $employees;
        AuditReportsController::store('Security', 'Users Report', "Accessed By User", 0);
        return view('security.users_list_report')->with($data);
    }
	
	public function getUsersReportPrint(Request $request)
    {
        $reportData = $request->all();
        unset($reportData['_token']);
		
        $divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $hrPersonID = ($request->input('hr_person_id')) ? $request->input('hr_person_id') : 0;

        $employees = HRPerson::select('hr_people.*'
			, 'hr_positions.name as job_title', 'hp.first_name as manager_first_name'
			, 'hp.surname as manager_surname'
			, 'leave_profile.name as profile_name')
			->leftJoin('hr_positions', 'hr_people.position', '=', 'hr_positions.id')
			->leftJoin('leave_profile', 'hr_people.leave_profile', '=', 'leave_profile.id')
			->leftJoin('hr_people as hp', 'hp.manager_id', '=', 'hr_people.id')
            ->whereNotNull('hr_people.user_id')
            ->where('hr_people.status', 1)->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
            if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
            if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
            if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
            if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
            if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
        })->where(function ($query) use($hrPersonID){
            if (!empty($hrPersonID)) {
                $query->where('hr_people.id',$hrPersonID);
            }
        })
		->orderBy('hr_people.first_name', 'asc')
		->orderBy('hr_people.surname', 'asc')
		->get();
		
		$data['employees'] = $employees;
        
		$data['page_title'] = "Users List";
        $data['page_description'] = "Admin page to manage users access";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/reports', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Fleet Management', 'Fleet Management Search Page Accessed', "Accessed By User", 0);
        return view('security.users_list_report_print')->with($data);
    }
	public function usersApproval() {
		
		$users = User::where('status',2)->get();
		if (!empty($users)) $users = $users->load('person');
        //return $users;
		$data['users'] = $users;
		$data['page_title'] = "Security";
        $data['page_description'] = "Users Approval";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/user', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Approval', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'User Approval';
        return view('security.users_approval_access')->with($data);
    }
	// Approve Users
	public function approvalUsers(Request $request)
    {
        $approves = $request->input('approve');
        if (count($approves) > 0) {
            foreach ($approves as $userID => $app) {
                
				$user = User::where('id',$userID)->first();
				//update client approval
				$user->status = 1;
				$user->update();
				$firstName = $user->first_name;
				// email client
				 Mail::to("$user->email")->send(new UserApproval($firstName));
            }
        }
        return back()->with('changes_saved', "Your changes have been saved successfully.");
    }
}