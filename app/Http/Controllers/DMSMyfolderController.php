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
		
		// get company folder ids
		$compArray = array();
		$compArray = $this->getCompanyFoldersID($employeeID,$today,1,$division_level_5);
		
		// get group folder id
		$groupArray = array();
		$groupArray = $this->getGroupFoldersID($employeeID,$today,1,$division_level_5);
		
		// get user folder id
		$userArray = array();
		$userArray = $this->getUserFoldersID($employeeID,$today,1,$division_level_5);
		
		// get folders
		$folders = DmsFolders::where('status',1)
					->whereNull('deleted')
					->where('visibility',1)
					->Where(function ($query) use ($compArray) {
							if (!empty($compArray)) {
								$query->whereNotIn('id', $compArray);
							}
						})
					->Where(function ($query) use ($groupArray) {
						if (!empty($groupArray)) {
							$query->whereNotIn('id', $groupArray);
						}
					})
					->Where(function ($query) use ($userArray) {
						if (!empty($userArray)) {
							$query->whereNotIn('id', $userArray);
						}
					})		
					->orderBy('folder_name', 'asc')
					->get();
					
		// get company file ids
		$compFileArray = array();
		$compFileArray = $this->getCompanyFoldersID($employeeID,$today,2,$division_level_5);
		
		// get group file id
		$groupFileArray = array();
		$groupFileArray = $this->getGroupFoldersID($employeeID,$today,2);
		
		// get user file id
		$userFileArray = array();
		$userFileArray = $this->getUserFoldersID($employeeID,$today,2);
		
		// get files
		$files = DmsFiles::where('status',1)
				->whereNull('deleted')
				->where('visibility',1)
				->Where(function ($query) use ($compFileArray) {
							if (!empty($compFileArray)) {
								$query->whereNotIn('id', $compFileArray);
							}
						})
					->Where(function ($query) use ($groupFileArray) {
						if (!empty($groupFileArray)) {
							$query->whereNotIn('id', $groupFileArray);
						}
					})
					->Where(function ($query) use ($userFileArray) {
						if (!empty($userFileArray)) {
							$query->whereNotIn('id', $userFileArray);
						}
					})
				->orderBy('document_name', 'asc')
				->get();
		$groups = DMSGoupAdmin::where('status',1)->orderBy('group_name', 'asc')->get();
       
		
		//return $this->getCompanyFoldersID($employeeID,$today,1,$division_level_5);;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		$folder_image = Storage::disk('local')->url('DMS Image/folder_image.png');

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Access Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/grant_access', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'My Folders';
        $data['division_levels'] = $divisionLevels;
		$data['employees'] = $employees;
		$data['companyAccessFolders'] = $this->getCompanyFolders($employeeID,$today,1,$division_level_5);
		$data['companyAccessFiles'] = $this->getCompanyFolders($employeeID,$today,2,$division_level_5);
		$data['groupAccessFolders'] = $this->getGroupFolders($employeeID,$today,1);
		$data['groupAccessFiles'] = $this->getGroupFolders($employeeID,$today,2);
		$data['userAccessFolders'] = $this->getUserFolders($employeeID,$today,1);
		$data['userAccessFiles'] = $this->getUserFolders($employeeID,$today,2);
		$data['generalFolders'] = $this->getGeneralFolders($employeeID,1);
		$data['generalFiles'] = $this->getGeneralFolders($employeeID,2);
		$data['folders'] = $folders;
		$data['files'] = $files;
		$data['groups'] = $groups;
		$data['folder_image'] = $folder_image;

        AuditReportsController::store('Document Management', 'Grant Access Page Accessed', "Actioned By User", 0);
        return view('dms.my_foldes')->with($data);
    }
	// get company folders
	
    public function getCompanyFolders($employeeID,$today,$type,$division_level_5)
    {
		// company folder
		if($type == 1)
		{
			$companyAccessFolders = DMSCompanyAccess::where('expiry_date', '>=', $today)
								->where('file_id','<',1)
								->where(function ($query) use ($division_level_5) {
									if (!empty($division_level_5)) {
										$query->where('division_level_5', $division_level_5);
									}
								})
								->orderBy('expiry_date', 'asc')
								->get();
			if (!empty($companyAccessFolders))
				$companyAccessFolders = $companyAccessFolders->load('division','department','companyFolder');
		
			return $companyAccessFolders;
		}
		else
		{
			// company file
			$companyAccessFiles = DMSCompanyAccess::
									where('expiry_date', '>=', $today)
									->where('folder_id','<',1)
									->where(function ($query) use ($division_level_5) {
										if (!empty($division_level_5)) {
											$query->where('division_level_5', $division_level_5);
										}
									})
									->orderBy('expiry_date', 'asc')
									->get();
			if ($companyAccessFiles) 
				$companyAccessFiles = $companyAccessFiles->load('division','department','companyFile');
		
			return $companyAccessFiles;
		}
		
    }
	// get general folders/files
	public function getGeneralFolders($employeeID,$type)
    {
		if ($type == 1)
		{
			$folders = DmsFolders::where('status',1)
						->whereNull('deleted')
						->where('visibility',2)
						->get();
			if (!empty($folders)) 
				$folders = $folders->load('employee','division');
			
			return $folders;
			
		}
		else
		{
			$files = DmsFiles::
					where('status',1)
					->whereNull('deleted')
					->where('visibility',2)
					->get();
			if (!empty($files)) 
				$files = $files->load('employee');
			
			return $files;
		}
        
    }
	
	// get group folder
	public function getGroupFolders($employeeID,$today,$type)
    {
        $groupArray = array();

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
		
		if ($type == 1)
		{
			// group folder
			$groupAccessFolders = DMSGroupAccess::
									where('expiry_date', '>=', $today)
									->where('file_id','<',1)
									->Where(function ($query) use ($groupArray) {
										if (!empty($groupArray)) {
											$query->whereIn('group_id', $groupArray);
										}
									})
									->orderBy('expiry_date', 'asc')
									->get();
			if ($groupAccessFolders) 
				$groupAccessFolders = $groupAccessFolders->load('groupName','groupFolder');	
			
			return $groupAccessFolders;
		}
		else
		{
			// group file
			$groupAccessFiles = DMSGroupAccess::
								where('expiry_date', '>=', $today)
								->where('folder_id','<',1)
								->Where(function ($query) use ($groupArray) {
										if (!empty($groupArray)) {
											$query->whereIn('group_id', $groupArray);
										}
									})
								->orderBy('expiry_date', 'asc')
								->get();
			if (!empty($groupAccessFiles))
				$groupAccessFiles = $groupAccessFiles->load('groupName','groupAdmin','groupFile');
			
			return $groupAccessFiles;
		}
		
    }
	
	// get user foldder
	public function getUserFolders($employeeID,$today,$type)
    {
		if ($type == 1)
		{
			// user folder
			$userAccessFolders = DMSUserAccess::where('expiry_date', '>=', $today)
									->where('file_id','<',1)
									->where(function ($query) use ($employeeID) {
										if (!empty($employeeID)) {
											$query->where('hr_id', $employeeID);
										}
									})
									->orderBy('expiry_date', 'asc')
									->get();
			if ($userAccessFolders)
				$userAccessFolders = $userAccessFolders->load('employee','userAdmin','userFolder');
			
			return $userAccessFolders;
		}
        else
		{
			// user file
			$userAccessFiles = DMSUserAccess::
								where('expiry_date', '>=', $today)
								->where('folder_id','<',1)
								->where(function ($query) use ($employeeID) {
										if (!empty($employeeID)) {
											$query->where('hr_id', $employeeID);
										}
									})
								->orderBy('expiry_date', 'asc')
								->get();
			if (!empty($userAccessFiles)) 
				$userAccessFiles = $userAccessFiles->load('employee','userAdmin','userFile');
			
			return $userAccessFiles;
		}
		
    }
	// get id folder/file in company access
	public function getCompanyFoldersID($employeeID,$today,$type,$division_level_5)
    {
		// company folder
		if($type == 1)
		{
			$companyAccessFolders = DMSCompanyAccess::where('expiry_date', '>=', $today)
								->where('file_id','<',1)
								->where(function ($query) use ($division_level_5) {
									if (!empty($division_level_5)) {
										$query->where('division_level_5', $division_level_5);
									}
								})
								->pluck('folder_id');
								
			return $companyAccessFolders;
		}
		else
		{
			// company file
			$companyAccessFiles = DMSCompanyAccess::
									where('expiry_date', '>=', $today)
									->where('folder_id','<',1)
									->where(function ($query) use ($division_level_5) {
										if (!empty($division_level_5)) {
											$query->where('division_level_5', $division_level_5);
										}
									})
									->pluck('file_id');
			return $companyAccessFiles;
		}
		
    }
	// get id folder/file in group access
	public function getGroupFoldersID($employeeID,$today,$type)
    {
		$groupArray = array();

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
		
		// company folder
		if($type == 1)
		{
			$companyAccessFolders = DMSGroupAccess::
									where('expiry_date', '>=', $today)
									->where('file_id','<',1)
									->Where(function ($query) use ($groupArray) {
										if (!empty($groupArray)) {
											$query->whereIn('group_id', $groupArray);
										}
									})
								->pluck('folder_id');
								
			return $companyAccessFolders;
		}
		else
		{
			// company file
			$companyAccessFiles = DMSGroupAccess::
									where('expiry_date', '>=', $today)
									->where('folder_id','<',1)
									->Where(function ($query) use ($groupArray) {
											if (!empty($groupArray)) {
												$query->whereIn('group_id', $groupArray);
											}
										})
									->pluck('file_id');
			return $companyAccessFiles;
		}
		
    }
	// get id folder/file in user access
	public function getUserFoldersID($employeeID,$today,$type)
    {
		// company folder
		if($type == 1)
		{
			$companyAccessFolders = DMSUserAccess::where('expiry_date', '>=', $today)
									->where('file_id','<',1)
									->whereIn('status',[1, 2])
									->where(function ($query) use ($employeeID) {
										if (!empty($employeeID)) {
											$query->where('hr_id', $employeeID);
										}
									})
									->pluck('folder_id');
								
			return $companyAccessFolders;
		}
		else
		{
			// company file
			$companyAccessFiles = DMSUserAccess::
									where('expiry_date', '>=', $today)
									->where('folder_id','<',1)
									->whereIn('status',[1, 2])
									->where(function ($query) use ($employeeID) {
											if (!empty($employeeID)) {
												$query->where('hr_id', $employeeID);
											}
										})
									->pluck('file_id');
			return $companyAccessFiles;
		}
		
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
