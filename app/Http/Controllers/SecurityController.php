<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\HRPerson;
use App\module_access;
use App\modules;
use Illuminate\Http\Request;

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
		$this->middleware('password_expired');
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
}
