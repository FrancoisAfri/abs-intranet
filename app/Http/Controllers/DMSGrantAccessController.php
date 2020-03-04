<?php

namespace App\Http\Controllers;
use App\HRPerson;
use App\DmsSetup;
use App\DmsFolders;
use App\DMSGoupAdmin;
use App\DMSGoupAdminUsers;
use App\DMSCompanyAccess;
use App\DMSGroupAccess;
use App\DMSUserAccess;
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

class DMSGrantAccessController extends Controller
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
		$today = strtotime(date('Y-m-d'));
		$folders = DmsFolders::where('status',1)->whereNull('deleted')->orderBy('folder_name', 'asc')->get();
		$files = DmsFiles::where('status',1)->whereNull('deleted')->orderBy('document_name', 'asc')->get();
		$groups = DMSGoupAdmin::where('status',1)->orderBy('group_name', 'asc')->get();
        // company folder
		$companyAccessFolders = DMSCompanyAccess::where('expiry_date', '>=', $today)->where('file_id', '<', 1)->orderBy('expiry_date', 'asc')->get();
        if (!empty($companyAccessFolders)) 
			$companyAccessFolders = $companyAccessFolders->load('division','department','section','team','levelOne','companyFolder','companyAdmin');
		//return $companyAccessFolders;
		// company file
		$companyAccessFiles = DMSCompanyAccess::where('expiry_date', '>=', $today)->where('folder_id', '<', 1)->orderBy('expiry_date', 'asc')->get();
        if (!empty($companyAccessFiles))
			$companyAccessFiles = $companyAccessFiles->load('division','department','section','team','levelOne','companyAdmin','companyFile');
		// group folder
		$groupAccessFolders = DMSGroupAccess::where('expiry_date', '>=', $today)->where('file_id', '<', 1)->orderBy('expiry_date', 'asc')->get();
        if (!empty($groupAccessFolders)) 
			$groupAccessFolders = $groupAccessFolders->load('groupName','groupAdmin','groupFolder');	
		// group file
		$groupAccessFiles = DMSGroupAccess::where('expiry_date', '>=', $today)->where('folder_id', '<', 1)->orderBy('expiry_date', 'asc')->get();
        if (!empty($groupAccessFiles))
			$groupAccessFiles = $groupAccessFiles->load('groupName','groupAdmin','groupFile');
		// user folder
		$userAccessFolders = DMSUserAccess::where('expiry_date', '>=', $today)->where('file_id', '<', 1)->orderBy('expiry_date', 'asc')->get();
        if (!empty($userAccessFolders)) 
			$userAccessFolders = $userAccessFolders->load('employee','userAdmin','userFolder');
		// user file
		$userAccessFiles = DMSUserAccess::where('expiry_date', '>=', $today)->where('folder_id', '<', 1)->orderBy('expiry_date', 'asc')->get();
		if (!empty($userAccessFiles)) 
			$userAccessFiles = $userAccessFiles->load('employee','userAdmin','userFile');
		
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Access Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/grant_access', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Grant Access';
        $data['division_levels'] = $divisionLevels;
		$data['employees'] = $employees;
		$data['companyAccessFolders'] = $companyAccessFolders;
		$data['companyAccessFiles'] = $companyAccessFiles;
		$data['groupAccessFolders'] = $groupAccessFolders;
		$data['groupAccessFiles'] = $groupAccessFiles;
		$data['userAccessFolders'] = $userAccessFolders;
		$data['userAccessFiles'] = $userAccessFiles;
		$data['folders'] = $folders;
		$data['files'] = $files;
		$data['groups'] = $groups;

        AuditReportsController::store('Document Management', 'Grant Access Page Accessed', "Actioned By User", 0);
        return view('dms.grant_access')->with($data);
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
    public function storeCompanyAccess(Request $request)
    {
        $this->validate($request, [
            'division_level_5' => 'required',       
            'expiry_date' => 'required',       
            'admin_id' => 'required', 
			'folder_id' => 'required_if:access_com_type,1',			
			'file_id' => 'required_if:access_com_type,2',			
        ]);
        $comData = $request->all();
        unset($comData['_token']);
		
		$comData['expiry_date'] = str_replace('/', '-', $comData['expiry_date']);
        $comData['expiry_date'] = strtotime($comData['expiry_date']);
			
		$companyAccess = new DMSCompanyAccess();
		$companyAccess->division_level_5 = !empty($comData['division_level_5']) ? $comData['division_level_5']: 0;
		$companyAccess->division_level_4 = !empty($comData['division_level_4']) ? $comData['division_level_4']: 0;
		$companyAccess->division_level_3 = !empty($comData['division_level_3']) ? $comData['division_level_3']: 0;
		$companyAccess->division_level_2 = !empty($comData['division_level_2']) ? $comData['division_level_2']: 0;
		$companyAccess->division_level_1 = !empty($comData['division_level_1']) ? $comData['division_level_1']: 0;
		$companyAccess->folder_id = !empty($comData['folder_id']) ? $comData['folder_id']: 0;
		$companyAccess->file_id = !empty($comData['file_id']) ? $comData['file_id']: 0;
		$companyAccess->admin_id = !empty($comData['admin_id']) ? $comData['admin_id']: 0;
		$companyAccess->expiry_date = !empty($comData['expiry_date']) ? $comData['expiry_date']: 0;
		$companyAccess->status = 1;
		$companyAccess->save();

		AuditReportsController::store('Document Management', 'Company Granted Access', "Granted By User", 0);
        return response()->json();
    }
	/// group save
	public function storeGroupAccess(Request $request)
    {
        $this->validate($request, [
            'group_id' => 'required',       
            'expiry_gr_date' => 'required',       
            'admini_id' => 'required', 
			'folder_id_gr' => 'required_if:access_gr_type,1',			
			'file_id_gr' => 'required_if:access_gr_type,2',			
        ]);
        $groupData = $request->all();
        unset($groupData['_token']);
		
		$groupData['expiry_gr_date'] = str_replace('/', '-', $groupData['expiry_gr_date']);
        $groupData['expiry_gr_date'] = strtotime($groupData['expiry_gr_date']);
			
		$groupAccess = new DMSGroupAccess();
		$groupAccess->group_id = !empty($groupData['group_id']) ? $groupData['group_id']: 0;
		$groupAccess->folder_id = !empty($groupData['folder_id_gr']) ? $groupData['folder_id_gr']: 0;
		$groupAccess->file_id = !empty($groupData['file_id_gr']) ? $groupData['file_id_gr']: 0;
		$groupAccess->admin_id = !empty($groupData['admini_id']) ? $groupData['admini_id']: 0;
		$groupAccess->expiry_date = !empty($groupData['expiry_gr_date']) ? $groupData['expiry_gr_date']: 0;
		$groupAccess->status = 1;
		$groupAccess->save();

		AuditReportsController::store('Document Management', 'Group Granted Access', "Granted By User", 0);
        return response()->json();
    }
	/// user save
	public function storeUserAccess(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',       
            'expiry_usr_date' => 'required',       
            'adminusr_id' => 'required', 
			'folder_id_usr' => 'required_if:access_usr_type,1',			
			'file_id_usr' => 'required_if:access_usr_type,2',			
        ]);
        $userData = $request->all();
        unset($userData['_token']);
		
		$userData['expiry_usr_date'] = str_replace('/', '-', $userData['expiry_usr_date']);
        $userData['expiry_usr_date'] = strtotime($userData['expiry_usr_date']);
			
		$userAccess = new DMSUserAccess();
		$userAccess->hr_id = !empty($userData['employee_id']) ? $userData['employee_id']: 0;
		$userAccess->folder_id = !empty($userData['folder_id_usr']) ? $userData['folder_id_usr']: 0;
		$userAccess->file_id = !empty($userData['file_id_usr']) ? $userData['file_id_usr']: 0;
		$userAccess->admin_id = !empty($userData['adminusr_id']) ? $userData['adminusr_id']: 0;
		$userAccess->expiry_date = !empty($userData['expiry_usr_date']) ? $userData['expiry_usr_date']: 0;
		$userAccess->status = 1;
		$userAccess->save();

		AuditReportsController::store('Document Management', 'User Granted Access', "Granted By User", 0);
        return response()->json();
    }
	// company active
	public function companyAct(DMSGoupAdmin $group) 
	{
		if ($group->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$group->status = $stastus;	
		$group->update();
		AuditReportsController::store('Document Management', "Group Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
	//group active
	public function groupActive(DMSGoupAdmin $group) 
	{
		if ($group->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$group->status = $stastus;	
		$group->update();
		AuditReportsController::store('Document Management', "Group Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
	// user active 
	public function userAct(DMSGoupAdmin $group) 
	{
		if ($group->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$group->status = $stastus;	
		$group->update();
		AuditReportsController::store('Document Management', "Group Status Changed: $stastus", "Edited by User", 0);
		return back();
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
    public function update(Request $request, $id)
    {
        //
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
}
