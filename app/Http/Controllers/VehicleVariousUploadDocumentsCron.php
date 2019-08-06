<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\vehicle_detail;
use App\vehicle_documets;
use App\vehicle_config;
use App\jobcard_maintanance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class VehicleVariousUploadDocumentsCron extends Controller
{
    public function execute() {
        
		$fleetLocation = vehicle_config::first();
		$fireExtinguisherFrom = !empty($fleetLocation->fire_extinguisher_from) ? $fleetLocation->fire_extinguisher_from : '';
		$fireExtinguisherTo = !empty($fleetLocation->fire_extinguisher_to) ? $fleetLocation->fire_extinguisher_to : '';
		$getFitmentFrom = !empty($fleetLocation->get_fitment_from) ? $fleetLocation->get_fitment_from : '';
		$getFitmentTo = !empty($fleetLocation->get_fitment_to) ? $fleetLocation->get_fitment_to : '';
		$ldvCarInspectionFrom = !empty($fleetLocation->ldv_car_inspection_from) ? $fleetLocation->ldv_car_inspection_from : '';
		$ldvCarInspectionTo = !empty($fleetLocation->ldv_car_inspection_to) ? $fleetLocation->ldv_car_inspection_to : '';
		$ldvPreUseInspectionsFrom = !empty($fleetLocation->ldv_pre_use_inspections_from) ? $fleetLocation->ldv_pre_use_inspections_from : '';
		$ldvPreUseInspectionsTo = !empty($fleetLocation->ldv_pre_use_inspections_to) ? $fleetLocation->ldv_pre_use_inspections_to : '';
		$mechanicPlantInspectionsFrom = !empty($fleetLocation->mechanic_plant_inspections_from) ? $fleetLocation->mechanic_plant_inspections_from : '';
		$mechanicPlantInspectionsTo = !empty($fleetLocation->mechanic_plant_inspections_to) ? $fleetLocation->mechanic_plant_inspections_to : '';
		$truckTractorRigidChassisFrom = !empty($fleetLocation->truck_tractor_rigid_chassis_from) ? $fleetLocation->truck_tractor_rigid_chassis_from : '';
		$truckTractorRigidChassisTo = !empty($fleetLocation->truck_tractor_rigid_chassis_to) ? $fleetLocation->truck_tractor_rigid_chassis_to : '';
		$tyreSurveyReportsFrom = !empty($fleetLocation->tyre_survey_reports_from) ? $fleetLocation->tyre_survey_reports_from : '';
		$tyreSurveyReportsTo = !empty($fleetLocation->tyre_survey_reports_to) ? $fleetLocation->tyre_survey_reports_to : '';
		$jobCardInspectionFrom = !empty($fleetLocation->job_card_inspection_from) ? $fleetLocation->job_card_inspection_from : '';
		$jobCardInspectionTo = !empty($fleetLocation->job_card_inspection_to) ? $fleetLocation->job_card_inspection_to : '';
		
		// fire Extinguisher files
		if (!empty($fireExtinguisherFrom) && !empty($fireExtinguisherTo))
		{
			$files = scandir($fireExtinguisherFrom);
			foreach($files as $file) 
			{
				$filename =  $fireExtinguisherFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$fleetNo = $endArray[0];
					// get fleet details
					$fleetDetails = vehicle_detail::where('fleet_number',"$fleetNo")->first(); 

					if (!empty($fleetDetails))
					{
						$vehicledocumets = new vehicle_documets();
						$vehicledocumets->type = 5;
						$vehicledocumets->description = "Fire Extinguisher Report";
						$vehicledocumets->date_from = time();
						$vehicledocumets->upload_date = time();
						$vehicledocumets->vehicleID = $fleetDetails->id;
						$vehicledocumets->expiry_type = 0;
						$vehicledocumets->status = 1;
						$vehicledocumets->currentdate = time();
						$vehicledocumets->document = $file;
						$vehicledocumets->save();
						
						$sNewName = $fireExtinguisherTo.$file;
						rename($filename,$sNewName);
					}
				}
			}
		}
		// GET Fitment files
		// service files
		if (!empty($getFitmentFrom) && !empty($getFitmentTo))
		{
			$files = scandir($getFitmentFrom);
			foreach($files as $file) 
			{
				$filename =  $getFitmentFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{ 
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
							
							$sNewName = $getFitmentTo.$file;
							rename($filename,$sNewName);
						}
					}
				}
			}
		}
		///
		// ldv Car Inspection files
		if (!empty($ldvCarInspectionFrom) && !empty($ldvCarInspectionTo))
		{
			$files = scandir($ldvCarInspectionFrom);
			foreach($files as $file) 
			{
				$filename =  $ldvCarInspectionFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{ 
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$fleetNo = $endArray[0];
					// get fleet details
					$fleetDetails = vehicle_detail::where('fleet_number',"$fleetNo")->first(); 

					if (!empty($fleetDetails))
					{
						$vehicledocumets = new vehicle_documets();
						$vehicledocumets->type = 5;
						$vehicledocumets->description = "LDV Car Inspection Report";
						$vehicledocumets->date_from = time();
						$vehicledocumets->upload_date = time();
						$vehicledocumets->vehicleID = $fleetDetails->id;
						$vehicledocumets->expiry_type = 0;
						$vehicledocumets->status = 1;
						$vehicledocumets->currentdate = time();
						$vehicledocumets->document = $file;
						$vehicledocumets->save();
						
						$sNewName = $ldvCarInspectionTo.$file;
						rename($filename,$sNewName);
					}
				}
			}
		}
		////
		// ldv Pre Use Inspections files
		if (!empty($ldvPreUseInspectionsFrom) && !empty($ldvPreUseInspectionsTo))
		{
			$files = scandir($ldvPreUseInspectionsFrom);
			foreach($files as $file) 
			{
				$filename =  $ldvPreUseInspectionsFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{ 
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$fleetNo = $endArray[0];
					// get fleet details
					$fleetDetails = vehicle_detail::where('fleet_number',"$fleetNo")->first(); 

					if (!empty($fleetDetails))
					{
						$vehicledocumets = new vehicle_documets();
						$vehicledocumets->type = 5;
						$vehicledocumets->description = "LDV Pre Use Inspections Report";
						$vehicledocumets->date_from = time();
						$vehicledocumets->upload_date = time();
						$vehicledocumets->vehicleID = $fleetDetails->id;
						$vehicledocumets->expiry_type = 0;
						$vehicledocumets->status = 1;
						$vehicledocumets->currentdate = time();
						$vehicledocumets->document = $file;
						$vehicledocumets->save();
						
						$sNewName = $ldvPreUseInspectionsTo.$file;
						rename($filename,$sNewName);
					}
				}
			}
		}
		///
		// mechanic Plant Inspections files
		if (!empty($mechanicPlantInspectionsFrom) && !empty($mechanicPlantInspectionsTo))
		{
			$files = scandir($mechanicPlantInspectionsFrom);
			foreach($files as $file) 
			{
				$filename =  $mechanicPlantInspectionsFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{ 
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$fleetNo = $endArray[0];
					// get fleet details
					$fleetDetails = vehicle_detail::where('fleet_number',"$fleetNo")->first(); 

					if (!empty($fleetDetails))
					{
						$vehicledocumets = new vehicle_documets();
						$vehicledocumets->type = 5;
						$vehicledocumets->description = "Mechanic Plant Inspections Report";
						$vehicledocumets->date_from = time();
						$vehicledocumets->upload_date = time();
						$vehicledocumets->vehicleID = $fleetDetails->id;
						$vehicledocumets->expiry_type = 0;
						$vehicledocumets->status = 1;
						$vehicledocumets->currentdate = time();
						$vehicledocumets->document = $file;
						$vehicledocumets->save();
						
						$sNewName = $mechanicPlantInspectionsTo.$file;
						rename($filename,$sNewName);
					}
				}
			}
		}
		///
		// truck Tractor Rigid Chassis files
		if (!empty($truckTractorRigidChassisFrom) && !empty($truckTractorRigidChassisTo))
		{
			$files = scandir($truckTractorRigidChassisFrom);
			foreach($files as $file) 
			{
				$filename =  $truckTractorRigidChassisFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{ 
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$fleetNo = $endArray[0];
					// get fleet details
					$fleetDetails = vehicle_detail::where('fleet_number',"$fleetNo")->first(); 

					if (!empty($fleetDetails))
					{
						$vehicledocumets = new vehicle_documets();
						$vehicledocumets->type = 5;
						$vehicledocumets->description = "Truck Tractor Rigid Chassis Report";
						$vehicledocumets->date_from = time();
						$vehicledocumets->upload_date = time();
						$vehicledocumets->vehicleID = $fleetDetails->id;
						$vehicledocumets->expiry_type = 0;
						$vehicledocumets->status = 1;
						$vehicledocumets->currentdate = time();
						$vehicledocumets->document = $file;
						$vehicledocumets->save();
						
						$sNewName = $truckTractorRigidChassisTo.$file;
						rename($filename,$sNewName);
					}
				}
			}
		}
		///
		// Tyre Survey Reports files
		if (!empty($tyreSurveyReportsFrom) && !empty($tyreSurveyReportsTo))
		{
			$files = scandir($tyreSurveyReportsFrom);
			foreach($files as $file) 
			{
				$filename =  $tyreSurveyReportsFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{ 
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$fleetNo = $endArray[0];
					// get fleet details
					$fleetDetails = vehicle_detail::where('fleet_number',"$fleetNo")->first(); 

					if (!empty($fleetDetails))
					{
						$vehicledocumets = new vehicle_documets();
						$vehicledocumets->type = 5;
						$vehicledocumets->description = "Tyre Survey Reports Report";
						$vehicledocumets->date_from = time();
						$vehicledocumets->upload_date = time();
						$vehicledocumets->vehicleID = $fleetDetails->id;
						$vehicledocumets->expiry_type = 0;
						$vehicledocumets->status = 1;
						$vehicledocumets->currentdate = time();
						$vehicledocumets->document = $file;
						$vehicledocumets->save();
						
						$sNewName = $tyreSurveyReportsTo.$file;
						rename($filename,$sNewName);
					}
				}
			}
		}
		///
		// job Card Inspection files
		if (!empty($jobCardInspectionFrom) && !empty($jobCardInspectionTo))
		{
			$files = scandir($jobCardInspectionFrom);
			foreach($files as $file) 
			{
				$filename =  $jobCardInspectionFrom.$file;
				if (file_exists($filename) && $file != '.' && $file != '..') 
				{ 
					$fileArray = explode(" ",$file);
					$end =  end($fileArray);
					$endArray = explode(".",$end);
					$fleetNo = $endArray[0];
					// get fleet details
					$fleetDetails = vehicle_detail::where('fleet_number',"$fleetNo")->first(); 

					if (!empty($fleetDetails))
					{
						$vehicledocumets = new vehicle_documets();
						$vehicledocumets->type = 5;
						$vehicledocumets->description = "Job Card Inspection Report";
						$vehicledocumets->date_from = time();
						$vehicledocumets->upload_date = time();
						$vehicledocumets->vehicleID = $fleetDetails->id;
						$vehicledocumets->expiry_type = 0;
						$vehicledocumets->status = 1;
						$vehicledocumets->currentdate = time();
						$vehicledocumets->document = $file;
						$vehicledocumets->save();
						
						$sNewName = $jobCardInspectionTo.$file;
						rename($filename,$sNewName);
					}
				}
			}
		}
        AuditReportsController::store('Fleet Management', "Cron Fleet Document Ran", "Automatic Ran by Server", 0);
    }
}