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

class DMSMyfolderController extends Controller
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
		$groupArray = array();
		$loggedInEmpl = Auth::user()->person;
		$division_level_5 = $loggedInEmpl->division_level_5;
		$employeeID = $loggedInEmpl->id;
		$today = strtotime(date('Y-m-d'));
		$groups = DMSGoupAdminUsers::
			leftJoin('d_m_s_goup_admins', 'd_m_s_goup_admin_users.group_id', '=', 'd_m_s_goup_admins.id')
			->where('d_m_s_goup_admin_users.hr_id',$employeeID)
			->where('d_m_s_goup_admin_users.status',1)
			->where('d_m_s_goup_admins.status',1)
			->pluck('d_m_s_goup_admin_users.group_id');
			
		// assign group resutls to array.
		foreach ($groups as $group) 
		{
			$groupArray[] = $group;
		}

		$folders = DmsFolders::where('status',1)->whereNull('deleted')->orderBy('folder_name', 'asc')->get();
		$files = DmsFiles::where('status',1)->whereNull('deleted')->orderBy('document_name', 'asc')->get();
		$groups = DMSGoupAdmin::where('status',1)->orderBy('group_name', 'asc')->get();
        // company folder
		$companyAccessFolders = DMSCompanyAccess::
								where('expiry_date', '>=', $today)
								->where('file_id', '<', 1)
								->where(function ($query) use ($division_level_5) {
									if (!empty($division_level_5)) {
										$query->where('division_level_5', $division_level_5);
									}
								})
								->orderBy('expiry_date', 'asc')
								->get();
        if (!empty($companyAccessFolders))
			$companyAccessFolders = $companyAccessFolders->load('division','department','companyFolder','companyAdmin');
		
		// company file
		$companyAccessFiles = DMSCompanyAccess::
								where('expiry_date', '>=', $today)
								->where('folder_id', '<', 1)
								->where(function ($query) use ($division_level_5) {
									if (!empty($division_level_5)) {
										$query->where('division_level_5', $division_level_5);
									}
								})
								->orderBy('expiry_date', 'asc')
								->get();
		if ($companyAccessFiles) 
			$companyAccessFiles = $companyAccessFiles->load('division','department','companyAdmin','companyFile');
		
		// group folder
		$groupAccessFolders = DMSGroupAccess::
								where('expiry_date', '>=', $today)
								->where('file_id', '<', 1)
								->Where(function ($query) use ($groupArray) {
									if (!empty($groupArray)) {
										$query->whereIn('group_id', $groupArray);
									}
								})
								->orderBy('expiry_date', 'asc')
								->get();
        if ($groupAccessFolders) 
			$groupAccessFolders = $groupAccessFolders->load('groupName','groupAdmin','groupFolder');	
		// group file
		$groupAccessFiles = DMSGroupAccess::
							where('expiry_date', '>=', $today)
							->where('folder_id', '<', 1)
							->Where(function ($query) use ($groupArray) {
									if (!empty($groupArray)) {
										$query->whereIn('group_id', $groupArray);
									}
								})
							->orderBy('expiry_date', 'asc')
							->get();
        if (!empty($groupAccessFiles))
			$groupAccessFiles = $groupAccessFiles->load('groupName','groupAdmin','groupFile');
		
		// user folder
		$userAccessFolders = DMSUserAccess::
								where('expiry_date', '>=', $today)
								->where('file_id', '<', 1)
								->where(function ($query) use ($employeeID) {
									if (!empty($employeeID)) {
										$query->where('hr_id', $employeeID);
									}
								})
								->orderBy('expiry_date', 'asc')
								->get();
        if ($userAccessFolders)
			$userAccessFolders = $userAccessFolders->load('employee','userAdmin','userFolder');
		
		// user file
		$userAccessFiles = DMSUserAccess::
							where('expiry_date', '>=', $today)
							->where('folder_id', '<', 1)
							->where(function ($query) use ($employeeID) {
									if (!empty($employeeID)) {
										$query->where('hr_id', $employeeID);
									}
								})
							->orderBy('expiry_date', 'asc')
							->get();
		if (!empty($userAccessFiles)) 
			$userAccessFiles = $userAccessFiles->load('employee','userAdmin','userFile');
		//return $userAccessFiles;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		$folder_image = Storage::disk('local')->url('DMS Image/folder_image.png');

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
		$data['folder_image'] = $folder_image;

        AuditReportsController::store('Document Management', 'Grant Access Page Accessed', "Actioned By User", 0);
        return view('dms.my_foldes')->with($data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewFile(DmsFiles $file)
    {
		
		/*@else
			@if($file->file_extension == 'pdf')
		<iframe src="https://docs.google.com/gview?url=http://remote.url.tld/path/to{{ $document}}&embedded=true"></iframe>
		@endif*/
		//return $file;
		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Directory";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		
		$document = !empty($file->path) && !empty($file->file_name) ? $file->path.$file->file_name: '';
        $data['document'] = (!empty($document)) ? Storage::disk('local')->url($document) : '';

        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'My Folders';
        $data['file'] = $file;
        AuditReportsController::store('Document Management', 'Folders Page Accessed', "Actioned By User", 0);
        return view('dms.view_document')->with($data);
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
