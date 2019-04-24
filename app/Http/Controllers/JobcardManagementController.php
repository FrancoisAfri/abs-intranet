<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\job_maintanace;
use App\Users;
use App\Vehicle_managemnt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobcardManagementController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function JobcardManagent()
    {
        //$incidentType = incident_type::orderBy('id', 'asc')->get();

        $data['page_title'] = " Job Card Management";
        $data['page_description'] = " Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Job Cards ', 'active' => 1, 'is_module' => 0]
        ];

        // $data['incidentType'] = $incidentType;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Job Card Management';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.JobcardManagement.jobcardIndex')->with($data);
    }

    public function addJobcard()
    {
        //$jobcardMaintance = job_maintanace::orderBy('id', 'asc')->get();
        $Vehicle_managemnt = Vehicle_managemnt::orderBy('id', 'asc')->get();

        $jobcardMaintance = DB::table('job_maintanace')
            ->select('job_maintanace.*', 'vehicle_managemnet.name as vehicle_name')
            ->leftJoin('vehicle_managemnet', 'job_maintanace.vehicle', '=', 'vehicle_managemnet.id')
            ->orderBy('job_maintanace.id')
            ->get();

        $data['page_title'] = " Job Card Management";
        $data['page_description'] = " Job Card Management";
        $data['breadcrumb'] = [
            ['title' => 'Job Card  Management', 'path' => '/leave/Apply', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Job Cards ', 'active' => 1, 'is_module' => 0]
        ];

        $data['jobcardMaintance'] = $jobcardMaintance;
        $data['Vehicle_managemnt'] = $Vehicle_managemnt;
        $data['active_mod'] = 'Fleet Management';
        $data['active_rib'] = 'Job Card Management';

        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);
        return view('Vehicles.JobcardManagement.jobCard')->with($data);
    }

    public function Addmaintenance(Request $request, job_maintanace $jobmaintanace)
    {
        $this->validate($request, [
        ]);
        $jobData = $request->all();
        unset($jobData['_token']);
        $jobmaintanace->vehicle = $jobData['vehicle'];

        $jobcarddate = $jobData['job_card_date'];
        $scheduledate = $jobData['schedule_date'];
        $bookingdate = $jobData['booking_date'];
        $servicedocs = $jobData['service_docs'];


        $jobcarddate = str_replace('/', '-', $jobData['job_card_date']);
        $jobcarddate = strtotime($jobcarddate);

        $scheduledate = str_replace('/', '-', $jobData['schedule_date']);
        $scheduledate = strtotime($scheduledate);

        $bookingdate = str_replace('/', '-', $jobData['booking_date']);
        $bookingdate = strtotime($bookingdate);


        // $jobmaintanace->service_days = $jobData['service_days'];
        $jobmaintanace->job_card_date = $jobcarddate;
        $jobmaintanace->schedule_date = $scheduledate;
        $jobmaintanace->booking_date = $bookingdate;
        $jobmaintanace->supplier = $jobData['supplier'];
        $jobmaintanace->service_type = $jobData['service_type'];
        $jobmaintanace->estimated_hours = $jobData['estimated_hours'];
        // $jobmaintanace->service_docs = $jobData['service_docs'];
        $jobmaintanace->service_time = $jobData['service_time'];
        $jobmaintanace->machine_hour_metre = $jobData['machine_hour_metre'];
        $jobmaintanace->machine_odometer = $jobData['machine_odometer'];
        $jobmaintanace->last_driver = $jobData['last_driver'];
        $jobmaintanace->inspection_info = $jobData['inspection_info'];
        // $jobmaintanace->inspection_docs = $jobData['inspection_docs'];
        $jobmaintanace->mechanic = $jobData['mechanic'];
        $jobmaintanace->emails = $jobData['emails'];
        $jobmaintanace->instruction = $jobData['instruction'];
        $jobmaintanace->save();

        //Upload supporting Documents
        if ($request->hasFile('service_docs')) {
            $fileExt = $request->file('service_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('service_docs')->isValid()) {
                $fileName = time() . "service_docs." . $fileExt;
                $request->file('service_docs')->storeAs('jobData', $fileName);
                $jobData->service_docs = $fileName;
                $jobData->update();
            }
        }

        //Upload supporting Documents
        if ($request->hasFile('inspection_docs')) {
            $fileExt = $request->file('inspection_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('inspection_docs')->isValid()) {
                $fileName = time() . "_inspection_docs." . $fileExt;
                $request->file('inspection_docs')->storeAs('jobData', $fileName);
                //Update file name in hr table
                $jobData->supporting_docs = $fileName;
                $jobData->update();
            }
        }


        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

}
