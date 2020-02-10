<?php

namespace App\Http\Controllers;
use App\HRPerson;
use App\DmsSetup;
use App\DmsFolders;
use App\DMSGoupAdmin;
use App\DMSGoupAdminUsers;
use App\DmsFiles;
use App\DmsFilesVersions;
use App\DivisionLevel;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DMSGroupAdminController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groupAdmins = DMSGoupAdmin::orderBy('group_name', 'asc')->get();
		if (!empty($groupAdmins)) $groupAdmins->load('groupUsers');

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Directory";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/group_admin', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Group Admin';
        $data['groupAdmins'] = $groupAdmins;

        AuditReportsController::store('Document Management', 'Group Admin Page Accessed', "Actioned By User", 0);
        return view('dms.group_admin')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->validate($request, [
            'group_name' => 'required',       
        ]);
        $groupData = $request->all();
        unset($groupData['_token']);
		
		$group = new DMSGoupAdmin();
		$group->group_name = !empty($groupData['group_name']) ? $groupData['group_name']: '';
		$group->status = 1;
		$group->save();
		/// create folder
		AuditReportsController::store('Document Management', 'New Group Created', "Accessed By User", 0);
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DMSGoupAdmin $group)
    {
        $this->validate($request, [
            'group_name' => 'required',       
        ]);
        $groupData = $request->all();
        unset($groupData['_token']);
		
		$group->group_name = !empty($groupData['group_name']) ? $groupData['group_name']: '';
		$group->update();

		AuditReportsController::store('Document Management', 'Group Details Updated', "Accessed By User", 0);
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	# Act/deac meeting
	public function groupAct(DMSGoupAdmin $group) 
	{
		if ($group->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$group->status = $stastus;	
		$group->update();
		AuditReportsController::store('Document Management', "Group Status Changed: $stastus", "Edited by User", 0);
		return back();
    }	
	
	public function groupUsersAct(DMSGoupAdminUsers $groupUser) 
	{
		if ($groupUser->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$groupUser->status = $stastus;	
		$groupUser->update();
		AuditReportsController::store('Document Management', "Group User Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
	///
	public function groupUsers(DMSGoupAdmin $group)
    {
        $group->load('groupUsers');
		//return $group;
		$employees = DB::table('hr_people')->where('status', 1)->orderBy('first_name', 'asc')->get();

		$data['page_title'] = "Group";
		$data['page_description'] = "Users";
		$data['breadcrumb'] = [
		['title' => 'Group Admin', 'path' => '/dms/group_admin', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
		['title' => 'Group Admin Users', 'active' => 1, 'is_module' => 0]
			];
		$data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Group Admin';
		$data['group'] = $group;
		$data['employees'] = $employees;

		AuditReportsController::store('Document Management', 'Group Users Details Page Accessed', "Accessed by User", 0);
		return view('dms.group_admin_users')->with($data);
    }
	//
	public function saveGroupUsers(Request $request)
    {
        $this->validate($request, [
            'employee_id.*' => 'bail|required|integer|min:1',        
            'group_id' => 'bail|required|integer|min:1',        
        ]);
		$usersData = $request->all();
		unset($usersData['_token']);
        foreach ($usersData['employee_id'] as $employeeID) {
			$previous = DMSGoupAdminUsers::where('hr_id', '=',$employeeID)
			->where('group_id', '=',$usersData['group_id'])
			->first();
			if (empty($previous)) {
				$user = new DMSGoupAdminUsers();
				$user->hr_id = $employeeID;
				$user->group_id = $usersData['group_id'];
				$user->status = 1;
				$user->save();
			}
        }
		$attendance = $usersData['group_id'];
        AuditReportsController::store('Document Management', 'Group User(s) Added', "Added by User", 0);
        return response()->json(['new_users' => $attendance], 200);
    }
}