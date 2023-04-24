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
class DMSRecyle extends Controller
{
	/// only allow log in users to access this page
	public function __construct()
    {
        $this->middleware('auth');
    }
	
    public function index()
    {
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $folders = DmsFolders::where('deleted',1)->where('status',2)->get();
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
		$files = DmsFiles::where('deleted',1)->where('status',2)->get();
		if (!empty($files)) 
			$files = $files->load('employee');
		//return $files;
		$data['page_title'] = "Document Management";
        $data['page_description'] = "Recycle Bin";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/recycle', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Recycle Bin', 'active' => 1, 'is_module' => 0]
        ];
		//return $folder;
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Recycle Bin';
        $data['folders'] = $folders;
        $data['files'] = $files;
        $data['folder_image'] = $folder_image;
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;

        AuditReportsController::store('Document Management', 'Recycle bin Page Accessed', "Actioned By User", 0);
        return view('dms.recycel_bin')->with($data);
    }
	// restore folder
	public function restoredFolder(DmsFolders $folder)
    {
        $folder->status = 1;
        $folder->deleted = null;
        $folder->update();
		
		AuditReportsController::store('Document Management', 'Folder Restored', "Folder has been Restored", 0);
        return back();
    }
	// restore folder
	public function restoreFile(DmsFiles $file)
    {
        $file->status = 1;
        $file->deleted = null;
        $file->update();
		
		AuditReportsController::store('Document Management', 'File Restored', "File has been Restored", 0);
        return back();
    }
	// delete folder
	public function destroyFoler(DmsFolders $older)
    {
        $file->status = 1;
        $file->deleted = null;
        $file->update();
		
		AuditReportsController::store('Document Management', 'File Restored', "File has been Restored", 0);
        return back();
    }
	// delete folder
	public function destroyFile(DmsFiles $file)
    {
        $file->status = 1;
        $file->deleted = null;
        $file->update();
		
		AuditReportsController::store('Document Management', 'File Restored', "File has been Restored", 0);
        return back();
    }
	// empty recycle bin
	public function destroyFile(DmsFiles $file)
    {
        $file->status = 1;
        $file->deleted = null;
        $file->update();
		
		AuditReportsController::store('Document Management', 'File Restored', "File has been Restored", 0);
        return back();
    }
	// delte folder and file
	function deleteAll($dir, $remove = false) 
	{
		$structure = glob(rtrim($dir, "/").'/*');
		if (is_array($structure)) {
			foreach($structure as $file) {
				if (is_dir($file)) deleteAll($file,true);
				else if(is_file($file))
				unlink($file);
			}
		}
		if($remove)
			rmdir($dir);
	}
	
	/*
	// function to delete all files and subfolders from folder

 
// folder path that contains files and subfolders
$path = "./uploads";
 
// call the function
deleteAll($path);
 
echo "All files and subfolders deleted successfully.";
exit;
	*/
}
