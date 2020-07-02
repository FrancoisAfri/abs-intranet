<?php

namespace App\Http\Controllers;
use App\HRPerson;
use App\DmsSetup;
use App\DmsFolders;
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
class DMSFoldersController extends Controller
{
	/// only allow log in users to access this page
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
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $folders = DmsFolders::whereNull('parent_id')->where('status',1)->whereNull('deleted')->get();
		if (!empty($folders)) 
		$folders = $folders->load('employee','division');
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $folder_image = Storage::disk('local')->url('DMS Image/folder_image.png');

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Directory";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folders'] = $folders;
        $data['folder_image'] = $folder_image;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;
		//return $folders;
        AuditReportsController::store('Document Management', 'Folders Page Accessed', "Actioned By User", 0);
        return view('dms.folders')->with($data);
    }
	public function subfolders(DmsFolders $folder)
    {
		//return $folder;
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $folders = DmsFolders::where('parent_id',$folder->id)->where('status',1)->whereNull('deleted')->get();
		if (!empty($folders)) 
			$folders = $folders->load('employee','division','parentDetails');
		$file_size = 0;
		foreach ($folders as $directory)
		{
			$folder_path = storage_path('app')."/".$directory->path."/";
			foreach( File::allFiles("$folder_path") as $file)
			{
				$file_size += $file->getSize();
			}
			if (!empty($file_size))
			{
				$totalSize = number_format($file_size / 1048576,2)." MB";
				$directory->total_size = $totalSize;
			}
		}
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $folder_image = Storage::disk('local')->url('DMS Image/folder_image.png');
		// get files
		$files = DmsFiles::where('folder_id',$folder->id)->where('status',1)->whereNull('deleted')->get();
		if (!empty($files)) 
			$files = $files->load('employee');
		//return $files;
		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Directory";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['folders'] = $folders;
        $data['files'] = $files;
        $data['folder_image'] = $folder_image;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;
		//return $folders;
        AuditReportsController::store('Document Management', 'Folders Page Accessed', "Actioned By User", 0);
        return view('dms.view_folder')->with($data);
    }
	/////
	public function myFolders()
    {
        $dmsSetup = DmsSetup::where('id', 1)->first();

        $data['page_title'] = "Document Management";
        $data['page_description'] = "DMS Set Up ";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'setup';
        $data['dmsSetup'] = $dmsSetup;
		//return $dmsSetup;
        AuditReportsController::store('Document Management', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('dms.setup')->with($data);
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
            'folder_name' => 'required',       
            'visibility' => 'required',       
            'responsable_person' => 'required',       
            'division_level_5' => 'required',       
        ]);
        $folderData = $request->all();
        unset($folderData['_token']);
		
		$folderName = $folderData['folder_name'];
		$DmsFolders = new DmsFolders($folderData);
		$DmsFolders->division_5 = !empty($folderData['division_level_5']) ? $folderData['division_level_5']: 0;
		$DmsFolders->division_4 = !empty($folderData['division_level_4']) ? $folderData['division_level_4']: 0;
		$DmsFolders->division_3 = !empty($folderData['division_level_3']) ? $folderData['division_level_3']: 0;
		$DmsFolders->division_2 = !empty($folderData['division_level_2']) ? $folderData['division_level_2']: 0;
		$DmsFolders->division_1 = !empty($folderData['division_level_1']) ? $folderData['division_level_1']: 0;
		$DmsFolders->status = 1;
		$DmsFolders->path = "DMS Master File/$folderName";
		$DmsFolders->save();
		/// create folder
		$response = Storage::makeDirectory("DMS Master File/$folderName");
		AuditReportsController::store('Document Management', 'New Folder Created', "Accessed By User", 0);
        return response()->json();
    }
	// this function will store sub folders
	public function storeSubfolders(Request $request, DmsFolders $folder)
    {
        $this->validate($request, [
            'folder_name' => 'required',       
            'visibility' => 'required',       
            'responsable_person' => 'required',       
            'division_level_5' => 'required',       
        ]);
        $folderData = $request->all();
        unset($folderData['_token']);
		
		$folderName = $folder->path."/".$folderData['folder_name'];
		$DmsFolder = new DmsFolders($folderData);
		$DmsFolder->parent_id = $folder->id;
		$DmsFolder->division_5 = !empty($folderData['division_level_5']) ? $folderData['division_level_5']: 0;
		$DmsFolder->division_4 = !empty($folderData['division_level_4']) ? $folderData['division_level_4']: 0;
		$DmsFolder->division_3 = !empty($folderData['division_level_3']) ? $folderData['division_level_3']: 0;
		$DmsFolder->division_2 = !empty($folderData['division_level_2']) ? $folderData['division_level_2']: 0;
		$DmsFolder->division_1 = !empty($folderData['division_level_1']) ? $folderData['division_level_1']: 0;
		$DmsFolder->status = 1;
		$DmsFolder->path = $folderName;
		$DmsFolder->save();
		/// create folder
		$response = Storage::makeDirectory("$folderName");
		AuditReportsController::store('Document Management', 'New Subfolder Created', "Accessed By User", 0);
        return response()->json();
    }
	
	// this function will store files into a folder
	public function storeFile(Request $request, DmsFolders $folder)
    {
        $this->validate($request, [
            'document_name' => 'required',      
        ]);
        $fileData = $request->all();
        unset($fileData['_token']);
		
		$filePath = $folder->path."/";
		$docName =  $fileData['document_name'];
		$docName =  str_replace(" ","_",$docName);
		
		//Upload supporting document
        if ($request->hasFile('documents')) {
            $fileExt = $request->file('documents')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc', 'xlsx']) && $request->file('documents')->isValid()) {
                $fileName = time()."_".$docName.".". $fileExt;
                $request->file('documents')->storeAs($folder->path, $fileName);
                //Add file name in the table
                $DmsFile = new DmsFiles();
				$DmsFile->folder_id = $folder->id;
				$DmsFile->document_name = $fileData['document_name'];
				$DmsFile->responsable_person = $folder->responsable_person;
				$DmsFile->file_name = $fileName;
				$DmsFile->path = $filePath;
				$DmsFile->file_extension = $fileExt;
				$DmsFile->current_version = "Version 1";
				$DmsFile->status = 1;
				$DmsFile->description = !empty($fileData['description']) ? $fileData['description'] : '';
                $DmsFile->save();
				// Save file version
				$DmsFileVersion  = new DmsFilesVersions();
				$DmsFileVersion->file_id = $DmsFile->id;
				$DmsFileVersion->file_name = $fileName;
				$DmsFileVersion->path = $filePath;
				$DmsFileVersion->status = 1;
				$DmsFileVersion->version_number = 1;
            }
        }
		
		AuditReportsController::store('Document Management', "New File: $DmsFile->document_name Added to $folder->id", "Accessed By User", 0);
        return response()->json();
    }
	// manage folders
	public function manageFolder(DmsFolders $folder)
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        if (!empty($folder)) 
			$folder = $folder->load('employee','division','parentDetails','department','section','team');

		// get folder size
		$file_size = 0;
		$folder_path = storage_path('app')."/".$folder->path."/";
		foreach( File::allFiles("$folder_path") as $file)
		{
			$file_size += $file->getSize();
		}
		if (!empty($file_size))
		{
			$totalSize = number_format($file_size / 1048576,2)." MB";
			$folder->total_size = $totalSize;
		}
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Folder management Page Accessed', "Actioned By User", 0);
        return view('dms.manage_folder')->with($data);
    }
	// manage files 
	public function manageFile(DmsFiles $file)
    {
		$file_size = 0;
		/*
			$folder_path = storage_path('app')."/".$file->path."/";
			$file_size += $file->getSize();
			if (!empty($file_size))
			{
				$totalSize = number_format($file_size / 1048576,2)." MB";
				$directory->total_size = $totalSize;
			}
		}*/
		// get folder details 
		$folder = DmsFolders::where('id',$file->folder_id)->first();
		// get files
		if (!empty($file)) 
			$file = $file->load('employee','folderList','fileVersions');
		return $file;
		$data['page_title'] = "Document Management";
        $data['page_description'] = "File Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['file'] = $file;
		//return $folders;
        AuditReportsController::store('Document Management', 'Folders Page Accessed', "Actioned By User", 0);
        return view('dms.manage_file')->with($data);
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
    public function update(Request $request, DmsFolders $folder)
    {
        $this->validate($request, [
            'folder_name' => 'required',       
            'visibility' => 'required',       
            'responsable_person' => 'required',       
            'division_level_5' => 'required',       
        ]);
        $folderData = $request->all();
        unset($folderData['_token']);
		
		$folderName = $folderData['folder_name'];
		$DmsFolders = new DmsFolders($folderData);
		$folder->division_5 = !empty($folderData['division_level_5']) ? $folderData['division_level_5']: 0;
		$folder->division_4 = !empty($folderData['division_level_4']) ? $folderData['division_level_4']: 0;
		$folder->division_3 = !empty($folderData['division_level_3']) ? $folderData['division_level_3']: 0;
		$folder->division_2 = !empty($folderData['division_level_2']) ? $folderData['division_level_2']: 0;
		$folder->division_1 = !empty($folderData['division_level_1']) ? $folderData['division_level_1']: 0;
		$folder->folder_name = !empty($folderData['folder_name']) ? $folderData['folder_name']: '';
		$folder->visibility = !empty($folderData['visibility']) ? $folderData['visibility']: 0;
		$folder->responsable_person = !empty($folderData['responsable_person']) ? $folderData['responsable_person']: 0;
		$folder->size = !empty($folderData['size']) ? $folderData['size']: 0;
		$folder->update();
		
		AuditReportsController::store('Document Management', 'New Folder Created', "Accessed By User", 0);
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DmsFolders $folder)
    {
        $folder->status = 2;
        $folder->deleted = 1;
        $folder->update();
		
		AuditReportsController::store('Document Management', 'Folder Deleted', "Folder has been deleted", 0);
        return back();
    }
	// company folder access
	public function companyFolderAccess(DmsFolders $folder)
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        if (!empty($folder)) 
			$folder = $folder->load('employee','division','parentDetails','department','section','team');

		// get folder size
		$file_size = 0;
		$folder_path = storage_path('app')."/".$folder->path."/";
		foreach( File::allFiles("$folder_path") as $file)
		{
			$file_size += $file->getSize();
		}
		if (!empty($file_size))
		{
			$totalSize = number_format($file_size / 1048576,2)." MB";
			$folder->total_size = $totalSize;
		}
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Folder management Page Accessed', "Actioned By User", 0);
        return view('dms.manage_folder')->with($data);
    }
	// group folder access
	public function groupFolderAccess(DmsFolders $folder)
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        if (!empty($folder)) 
			$folder = $folder->load('employee','division','parentDetails','department','section','team');

		// get folder size
		$file_size = 0;
		$folder_path = storage_path('app')."/".$folder->path."/";
		foreach( File::allFiles("$folder_path") as $file)
		{
			$file_size += $file->getSize();
		}
		if (!empty($file_size))
		{
			$totalSize = number_format($file_size / 1048576,2)." MB";
			$folder->total_size = $totalSize;
		}
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Folder management Page Accessed', "Actioned By User", 0);
        return view('dms.manage_folder')->with($data);
    }
	// user folder access
	public function userFolderAccess(DmsFolders $folder)
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        if (!empty($folder)) 
			$folder = $folder->load('employee','division','parentDetails','department','section','team');

		// get folder size
		$file_size = 0;
		$folder_path = storage_path('app')."/".$folder->path."/";
		foreach( File::allFiles("$folder_path") as $file)
		{
			$file_size += $file->getSize();
		}
		if (!empty($file_size))
		{
			$totalSize = number_format($file_size / 1048576,2)." MB";
			$folder->total_size = $totalSize;
		}
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Folder management Page Accessed', "Actioned By User", 0);
        return view('dms.manage_folder')->with($data);
    }
	// company file access
	public function companyFileAccess(DmsFolders $folder)
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        if (!empty($folder)) 
			$folder = $folder->load('employee','division','parentDetails','department','section','team');

		// get folder size
		$file_size = 0;
		$folder_path = storage_path('app')."/".$folder->path."/";
		foreach( File::allFiles("$folder_path") as $file)
		{
			$file_size += $file->getSize();
		}
		if (!empty($file_size))
		{
			$totalSize = number_format($file_size / 1048576,2)." MB";
			$folder->total_size = $totalSize;
		}
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Folder management Page Accessed', "Actioned By User", 0);
        return view('dms.manage_folder')->with($data);
    }
	// group file access
	public function groupFileAccess(DmsFolders $folder)
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        if (!empty($folder)) 
			$folder = $folder->load('employee','division','parentDetails','department','section','team');

		// get folder size
		$file_size = 0;
		$folder_path = storage_path('app')."/".$folder->path."/";
		foreach( File::allFiles("$folder_path") as $file)
		{
			$file_size += $file->getSize();
		}
		if (!empty($file_size))
		{
			$totalSize = number_format($file_size / 1048576,2)." MB";
			$folder->total_size = $totalSize;
		}
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Folder management Page Accessed', "Actioned By User", 0);
        return view('dms.manage_folder')->with($data);
    }
	// user file access
	public function userFileAccess(DmsFolders $folder)
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        if (!empty($folder)) 
			$folder = $folder->load('employee','division','parentDetails','department','section','team');

		// get folder size
		$file_size = 0;
		$folder_path = storage_path('app')."/".$folder->path."/";
		foreach( File::allFiles("$folder_path") as $file)
		{
			$file_size += $file->getSize();
		}
		if (!empty($file_size))
		{
			$totalSize = number_format($file_size / 1048576,2)." MB";
			$folder->total_size = $totalSize;
		}
		
		$employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();

		$data['page_title'] = "Document Management";
        $data['page_description'] = "Folder Management";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/folders', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Folders';
        $data['folder'] = $folder;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Folder management Page Accessed', "Actioned By User", 0);
        return view('dms.manage_folder')->with($data);
    }
}
