<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Users;
use App\HRPerson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FleetManagementUploadDocumentsCron extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function execute() {
        $location = jobcards_config::first();
		$serviceFileFrom = !empty($location->service_file_from) ? $location->service_file_from : '';
		$serviceFileTo = !empty($location->service_file_to) ? $location->service_file_to : '';
		$breakTestFrom = !empty($location->break_test_from) ? $location->break_test_from : '';
		$breakTestTo = !empty($location->break_test_to) ? $location->break_test_to : '';
		// service files
		if (!empty($serviceFileFrom) && !empty($serviceFileTo))
		{
			$files = $files = scandir($serviceFileFrom);
			foreach($files as $file) {

				$filename =  $serviceFileFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') { 

					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$jc = $endArray[0];
					if (strpos($jc, 'JC') !== false) 
					{
						$jcNumber = str_replace("JC","",$jc);
						$jobCard = jobcard_maintanance::where('id',$jcNumber)->first();
						if (!empty($jobCard))
						{
							$jobCard->service_file_upload = $file;
							$jobCard->update();
							
							$sNewName = $serviceFileTo.$file;
							rename($filename,$sNewName);
						}
					}
				}
			}
		}
		// break Test files
		/*if (!empty($breakTestFrom) && !empty($breakTestTo))
		{
			$files = $files = scandir($breakTestFrom);
			foreach($files as $file) {

				$filename =  $breakTestFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$br = $endArray[0];
					if (strpos($br, 'JC') !== false) 
					{
						$jcNumber = str_replace("JC","",$br);
						$jobCard = jobcard_maintanance::where('id',$jcNumber)->first();
						if (!empty($jobCard))
						{
							$jobCard->service_file_upload = $file;
							$jobCard->update();
							
							$sNewName = $breakTestTo.$file;
							rename($filename,$sNewName);
						}
					}
				}
			}
		}*/
        AuditReportsController::store('Job Card Management', "Cron Job Card Document Ran", "Automatic Ran by Server", 0);
    }
}