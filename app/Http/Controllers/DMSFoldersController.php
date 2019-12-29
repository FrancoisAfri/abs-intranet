<?php

namespace App\Http\Controllers;
use App\HRPerson;
use App\DmsSetup;
use App\DmsFolders;
use App\DmsFiles;
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
        $folders = DmsFolders::whereNull('parent_id')->get();
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
        $folders = DmsFolders::where('parent_id',$folder->id)->get();
		if (!empty($folders)) 
			$folders = $folders->load('employee','division');
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
		$files = DmsFiles::where('folder_id',$folder->id)->get();
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
		$DmsFolders->path = "DMS Master File/$folderName";
		$DmsFolders->save();
		/// create folder
		$response = Storage::makeDirectory("DMS Master File/$folderName");
		AuditReportsController::store('Document Management', 'New Folder Created', "Accessed By User", 0);
        return response()->json();
    }
	
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
		$DmsFolder->path = $folderName;
		$DmsFolder->save();
		/// create folder
		$response = Storage::makeDirectory("$folderName");
		AuditReportsController::store('Document Management', 'New Subfolder Created', "Accessed By User", 0);
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
