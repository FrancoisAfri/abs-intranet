<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\HRPerson;
use App\module_access;
use App\modules;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class SecurityController extends Controller
{
    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the user access page.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersAccess()
    {
        $modules = modules::where('active', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $data['page_title'] = "Users Access";
        $data['page_description'] = "Admin page to manage users access";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/modules', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'users access';
        $data['modules'] = $modules;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Security', 'Users Access Page Accessed', "Accessed By User", 0);
        return view('security.users_access_search')->with($data);
    }

    /**
     * Load employees based on the search result.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getEmployees(Request $request)
    {
        $this->validate($request, [
            'module_id' => 'required',
        ]);

        $divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $empName = trim($request->input('employee_name'));
        $moduleID = ($request->input('module_id')) ? $request->input('module_id') : 0;

        $moduleName = modules::find($moduleID)->name;

        $employees = HRPerson::select('hr_people.*', 'hr_people.user_id as uid', 'security_modules_access.id',
            'security_modules_access.module_id', 'security_modules_access.user_id',
            'security_modules_access.access_level')
            ->whereNotNull('hr_people.user_id')
            ->where('status', 1)->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
            if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
            if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
            if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
            if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
            if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
        })->where(function ($query) use($empName){
            if (!empty($empName)) {
                $query->where('hr_people.first_name', 'ILIKE', "%$empName%");
                $query->orWhere('hr_people.surname', 'ILIKE', "%$empName%");
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
        $data['active_rib'] = 'users access';
        $data['employees'] = $employees;
        $data['moduleID'] = $moduleID;
        $data['moduleName'] = $moduleName;
        AuditReportsController::store('Security', 'Users List Access Page Accessed', "Accessed By User", 0);
        //return $employees;

        return view('security.users_list_access')->with($data);
    }

    /**
     * Load employees based on the search result.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateRights(Request $request)
    {
        $this->validate($request, [
            'module_id' => 'required',
        ]);

        $moduleID = $request->input('module_id');
        $accessLevels = $request->input('access_level');
        //return $accessLevels;
        if (count($accessLevels) > 0) {
            foreach ($accessLevels as $userID => $accessLevel) {
                module_access::where('module_id', $moduleID)->where('user_id', $userID)->delete();
                $userRights = new module_access();
                $userRights->user_id = $userID;
                $userRights->module_id = $moduleID;
                $userRights->access_level = $accessLevel;
                $userRights->save();
                //module_access::where('module_id', $moduleID)->where('user_id', $userID)->update(['access_level' => $accessLevel]);
            }
        }
        return back()->with('changes_saved', "Your changes have been saved successfully.");
    }
	// report index page
	public function reportTo(Request $request)
    {
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $data['page_title'] = "Users Access";
        $data['page_description'] = "Admin page to manage users manager";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/reports_to', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reports To';
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Security', 'Reports To Page Accessed', "Accessed By User", 0);
        return view('security.reports_to_search')->with($data);
    }
	
	///
	public function getReportsTo(Request $request)
    {
        $divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $empName = trim($request->input('employee_name'));
        $managerId = ($request->input('manager_id')) ? $request->input('manager_id') : 0;
		$levelFive = DivisionLevel::where('active', 1)->where('level', 5)->first();
		$levelFour = DivisionLevel::where('active', 1)->where('level', 4)->first();
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
		
		$employeesList = HRPerson::select('hr_people.*', 'hr_people.id as uid'
			,'hp.first_name as manager_first_name','hp.surname as manager_surname'
			,'division_level_fives.name as div_name','division_level_fours.name as dep_name')
			->leftJoin('hr_people as hp', 'hr_people.manager_id', '=', 'hp.id')
			->leftJoin('division_level_fives', 'hr_people.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'hr_people.division_level_4', '=', 'division_level_fours.id')
            ->whereNotNull('hr_people.user_id')
            ->where('hr_people.status', 1)
			->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
				if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
				if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
				if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
				if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
				if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
			})
			->where(function ($query) use($managerId){
				if ($managerId > 0) $query->where('hr_people.manager_id', $managerId);
			})
			->orderBy('hr_people.first_name')->orderBy('hr_people.surname')
			->get();

        $data['page_title'] = "Users Access";
        $data['page_description'] = "Admin page to manage users access";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/reports_to', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports To', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reports To';
        $data['employeesList'] = $employeesList;
        $data['employees'] = $employees;
        $data['levelFive'] = $levelFive;
        $data['levelFour'] = $levelFour;
        $data['managerId'] = $managerId;
        AuditReportsController::store('Security', 'Reports To Page Accessed', "Accessed By User", 0);

        return view('security.reports_to_list')->with($data);
    }
	// Update Reports to
	public function updateReportsTo(Request $request)
    {
		$this->validate($request, [
            'manager_id' => 'required',
        ]);
        $managerID = !empty($request->input('manager_id')) ? $request->input('manager_id') : 0;
        $reportTos = $request->input('report_to');

        if (count($reportTos) > 0) {
            foreach ($reportTos as $userID => $accessLevel) {
                $employee = HRPerson::where('id',$userID)->first();
                $employee->manager_id = $managerID;
				$employee->update();
            }
        }
        return redirect("users/reports_to")->with('changes_saved', "Changes have been successfully Saved.");
    }
	
	// report index page
	public function resetPassword(Request $request)
    {
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $data['page_title'] = "Reset Password";
        $data['page_description'] = "Admin page to bulk reset user's password";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/security/password-reset', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reset Password', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reset Password';
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Security', 'Reports To Page Accessed', "Accessed By User", 0);
        return view('security.reset_password')->with($data);
    }
	// 
	public function getResetPassword(Request $request)
    {
        $divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $hrPersonID = ($request->input('hr_person_id')) ? $request->input('hr_person_id') : 0;
        
		$levelFive = DivisionLevel::where('active', 1)->where('level', 5)->first();
		$levelFour = DivisionLevel::where('active', 1)->where('level', 4)->first();
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
		
		$employeesList = HRPerson::select('hr_people.*', 'hr_people.user_id as uid'
			,'hp.first_name as manager_first_name','hp.surname as manager_surname'
			,'division_level_fives.name as div_name','division_level_fours.name as dep_name')
			->leftJoin('hr_people as hp', 'hr_people.manager_id', '=', 'hp.id')
			->leftJoin('division_level_fives', 'hr_people.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'hr_people.division_level_4', '=', 'division_level_fours.id')
            ->whereNotNull('hr_people.user_id')
            ->where('hr_people.status', 1)
			->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
				if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
				if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
				if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
				if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
				if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
			})
			->where(function ($query) use($hrPersonID){
				if ($hrPersonID > 0) $query->where('hr_people.id', $hrPersonID);
			})
			->orderBy('hr_people.first_name')->orderBy('hr_people.surname')
			->get();

        $data['page_title'] = "Reset Password Results";
        $data['page_description'] = "Admin page to reset users password";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/security/password-reset', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Reset Password';
        $data['employeesList'] = $employeesList;
        $data['employees'] = $employees;
        $data['levelFive'] = $levelFive;
        $data['levelFour'] = $levelFour;
        AuditReportsController::store('Security', 'Reports To Page Accessed', "Accessed By User", 0);

        return view('security.reset_password_list')->with($data);
    }
	// Update reset password
	public function updatePassword(Request $request)
    {
		$this->validate($request, [
            'new_password' => 'required',
        ]);
		
        $resetPassword = $request->input('reset_password');

        if (count($resetPassword) > 0) {
            foreach ($resetPassword as $userID => $accessLevel) {
				# update user password
				$user = User::where('id',$userID)->first();
                $newPassword = $request['new_password'];
				//return $user;
				$user->password = Hash::make($newPassword);
				$user->update();
            }
        }

        AuditReportsController::store('Security', 'User Password Updated', "Password Updated", 0);
        return redirect("security/password-reset")->with('changes_saved', "Changes have been successfully Saved.");
    }
	//// job titles
		// report index page
	public function assignJobTitle(Request $request)
    {
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $data['page_title'] = "Assign Job Title";
        $data['page_description'] = "Admin page to bulk assign employee job title";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/security/assign-jobtitles', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Assign Job Title', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Assign Job Titles';
        $data['employees'] = $employees;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Security', 'Reports To Page Accessed', "Accessed By User", 0);
        return view('security.assign_jobtitles')->with($data);
    }
	// 
	public function getJobTitle(Request $request)
    {
        $divLevel1 = ($request->input('division_level_1')) ? $request->input('division_level_1') : 0;
        $divLevel2 = ($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $divLevel3 = ($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $divLevel4 = ($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $divLevel5 = ($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $hrPersonID = ($request->input('hr_person_id')) ? $request->input('hr_person_id') : 0;
        $positions = DB::table('hr_positions')->where('status', 1)->get();
		$levelFive = DivisionLevel::where('active', 1)->where('level', 5)->first();
		$levelFour = DivisionLevel::where('active', 1)->where('level', 4)->first();
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
		
		$employeesList = HRPerson::select('hr_people.*', 'hr_people.id as uid'
			,'hp.first_name as manager_first_name','hp.surname as manager_surname'
			,'division_level_fives.name as div_name','division_level_fours.name as dep_name')
			->leftJoin('hr_people as hp', 'hr_people.manager_id', '=', 'hp.id')
			->leftJoin('division_level_fives', 'hr_people.division_level_5', '=', 'division_level_fives.id')
			->leftJoin('division_level_fours', 'hr_people.division_level_4', '=', 'division_level_fours.id')
            ->whereNotNull('hr_people.user_id')
            ->where('hr_people.status', 1)
			->where(function ($query) use($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5){
				if ($divLevel1 > 0) $query->where('hr_people.division_level_1', $divLevel1);
				if ($divLevel2 > 0) $query->where('hr_people.division_level_2', $divLevel2);
				if ($divLevel3 > 0) $query->where('hr_people.division_level_3', $divLevel3);
				if ($divLevel4 > 0) $query->where('hr_people.division_level_4', $divLevel4);
				if ($divLevel5 > 0) $query->where('hr_people.division_level_5', $divLevel5);
			})
			->where(function ($query) use($hrPersonID){
				if ($hrPersonID > 0) $query->where('hr_people.id', $hrPersonID);
			})
			->orderBy('hr_people.first_name')->orderBy('hr_people.surname')
			->get();

        $data['page_title'] = "Assign Job Title Results";
        $data['page_description'] = "Admin page to reset users password";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users/modules', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Users Access', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Assign Job Titles';
        $data['employeesList'] = $employeesList;
        $data['positions'] = $positions;
        $data['employees'] = $employees;
        $data['levelFive'] = $levelFive;
        $data['levelFour'] = $levelFour;
        AuditReportsController::store('Security', 'Assign Job Title Page Accessed', "Accessed By User", 0);

        return view('security.assign_job_title_list')->with($data);
    }
	// Update reset password
	public function updateJobTitle(Request $request)
    {
        $assignJobTitles = $request->input('assign_job_titles');
        if (count($assignJobTitles) > 0) {
            foreach ($assignJobTitles as $userID => $jobtile) {
				# update user password
				$user = HRPerson::where('id',$userID)->first();
				//return $user;
				if (!empty($jobtile))
				{
					$user->position = $jobtile;
					$user->update();
				}
            }
        }

        AuditReportsController::store('Security', 'User Job Title Updated', "Job Title Updated", 0);
        return redirect("security/assign-jobtitles")->with('changes_saved', "Changes have been successfully Saved.");
    }
}