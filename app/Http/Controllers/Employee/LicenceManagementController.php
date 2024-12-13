<?php

namespace App\Http\Controllers\Employee;

use App\CompanyIdentity;
use App\DivisionLevel;
use App\HRPerson;
use App\DivisionLevelFive;
use App\Http\Controllers\AuditReportsController;
use App\Models\Licences;
use App\Models\LicencesAllocation;
use App\Models\LicencesDates;
use App\Models\LicensesType;
use App\Models\Video;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class LicenceManagementController extends Controller
{
    use BreadCrumpTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request['status_id'] == null) {
            $status = 'All';
        } else $status = $request['status_id'];

        $licences = Licences::getLicencesByStatus($status);

        //$div = DivisionLevel::divisionLevelGroup();
        $div = DivisionLevel::where('active', 1)->get();  // correct
        $div5 = DivisionLevelFive::where('active', 1)->get();  // correct
        //$div->divisionLevelGroup();
        //return $div5;

        //dd($licences);

        $licence_type = LicensesType::where('status', 1)->get();
        $users = HRPerson::where('status', 1)->get();

        $data = $this->breadCrump(
            "Employee Records",
            "License Management", "fa fa-lock",
            "Licenses  Management",
            "Licenses  Management",
            "/hr",
            "License Management",
            "License Management"
        );

        $data['licences'] = $licences;
        $data['licence_type'] = $licence_type;
        $data['users'] = $users;
        return view('Employees.Licences.index')->with($data);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $licences = Licences::create([
            'name' => $request['name'],
            'details' => $request['details'],
            'serial' => $request['serial'],
            'total' => $request['total'],
            'asset_type_id' => $request['asset_type_id'],
            'purchase_date' => $request['purchase_date'],
            'order_number' => $request['order_number'],
            'purchase_cost' => $request['purchase_cost'],
            'expiration_date' => $request['expiration_date'],
            'licence_status' => 'un_allocated',
        ]);

        LicencesDates::create([
            'purchase_date' => $request['purchase_date'],
            'expiration_date' => $request['expiration_date'],
            'license_id' => $licences->id,
        ]);

        AuditReportsController::store('Employee  Records', 'Video Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $LicenceDetails = Licences::findByUuid($id);
        $users = HRPerson::where('status', 1)->get();
        $license_allocation = LicencesAllocation::with('Licenses', 'Hrpersons')->where('licence_id', $LicenceDetails->id)->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        //return $license_allocation;
        $licenseHistory = LicencesDates::where('license_id', $LicenceDetails->id)->orderBy('purchase_date')->get();

        $data = $this->breadCrump(
            "Employee Records",
            "License Management", "fa fa-lock",
            "Licenses Management",
            "Licenses Management",
            "/hr",
            "Licenses Management",
            "Licenses Management"
        );

        $data['licenseHistory'] = $licenseHistory;
        $data['division_levels'] = $divisionLevels;
        $data['license_allocation'] = $license_allocation;
        $data['users'] = $users;
        $data['LicenceDetails'] = $LicenceDetails;
        return view('Employees.Licences.show')->with($data);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * @param Licences $licence
     * @return RedirectResponse
     */
    public function activate(Licences $licence): RedirectResponse
    {
        $licence->status == 1 ? $stastus = 0 : $stastus = 1;
        $licence->status = $stastus;
        $licence->update();

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('licence Management', 'licence  Status Changed', "licence  Changed", 0);
        return back();
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function allocate(Request $request)
    {

        $users = $request['hr_person_id'];
        $division_level_5 = !empty($request->input('division_level_5')) ? $request->input('division_level_5') : 0;
        $division_level_4 = !empty($request->input('division_level_4')) ? $request->input('division_level_4') : 0;
        $division_level_3 = !empty($request->input('division_level_3')) ? $request->input('division_level_3') : 0;
        $division_level_2 = !empty($request->input('division_level_2')) ? $request->input('division_level_2') : 0;
        $division_level_1 = !empty($request->input('division_level_1')) ? $request->input('division_level_1') : 0;

        // get users according
        if (!empty($users))
            $employees = $users;
        elseif (!empty($division_level_1))
            $employees = HRPerson::where('division_level_1', $division_level_1)->where('status', 1)->pluck('hr_id');
        elseif (!empty($division_level_2))
            $employees = HRPerson::where('division_level_2', $division_level_2)->where('status', 1)->pluck('id');
        elseif (!empty($division_level_3))
            $employees = HRPerson::where('division_level_3', $division_level_3)->where('status', 1)->pluck('id');
        elseif (!empty($division_level_4))
            $employees = HRPerson::where('division_level_4', $division_level_4)->where('status', 1)->pluck('id');
        elseif (!empty($division_level_5))
            $employees = HRPerson::where('division_level_5', $division_level_5)->where('status', 1)->pluck('id');

        foreach ($employees as $key => $user) {

            $allocate = new LicencesAllocation();
            $allocate->status = 1;
            $allocate->licence_id = $request['licence_id'];
            $allocate->user_id = $user;
            $allocate->save();
        }

        $licence = Licences::where('id', $request['licence_id'])->first();
        $total_sum = $licence['total'] - count($employees);

        Licences::where('id', $request['licence_id'])->update(
            [
                'total' => $total_sum,
                'licence_status' => 'allocated'
            ]
        );

        return response()->json();

    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function renewal(Request $request)
    {

        LicencesDates::create([
            'renewal_date' => $request['renewal_date'],
            'license_id' => $request['license_id'],
            'expiration_date' => $request['expiration_date']
        ]);

        return response()->json();
    }


    /**
     * @param LicencesAllocation $licence
     * @return RedirectResponse
     */
    public function Useractivate(LicencesAllocation $licence): RedirectResponse
    {
        $licence->status == 1 ? $stastus = 0 : $stastus = 1;
        $licence->status = $stastus;
        $licence->update();

        $totalSum = Licences::where('id', $licence->licence_id)->first();
        if ($licence->status == 1) {
            $sum = $totalSum->total - 1;
        } else {
            $sum = $totalSum->total + 1;
        }

        //go into licence and minus 1
        $licences = Licences::where([
            'id' => $licence->licence_id
        ])->update(['total' => $sum]);

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('licence Management', 'licence  Status Changed', "licence  Changed", 0);
        return back();
    }

    // license report
    public function licenseReport(Request $request)
    {
        $status = !empty($request['status_id']) ? $request['status_id'] : 1;
        $type = !empty($request['license_type']) ? $request['license_type'] : '';
        $licenseID = !empty($request['license_id']) ? $request['license_id'] : '';
        $totalCost = 0;
        $LicenceAllocations = LicencesAllocation::getAlllicenses($status, $type, $licenseID);
        //return $LicenceAllocations;
        // calculate total cost
        if (!empty($LicenceAllocations)) {
            foreach ($LicenceAllocations as $allocation) {

                //echo $allocation->Licenses->name ." plus".$allocation->Licenses->purchase_cost."</br>";
                $totalCost = $totalCost + $allocation->Licenses->purchase_cost;
            }
        }
        $LicenceAllocations = LicencesAllocation::getAlllicenses($status, $type, $licenseID);
        //return $LicenceAllocations;

        $licenseTypes = LicensesType::all();
        $licenses = Licences::all();

        $data = $this->breadCrump(
            "Employee Records",
            "License Report", "fa fa-lock",
            "Licenses  Management Report",
            "Licenses  Management",
            "/",
            "Licenses  Management",
            "Licenses  Management"
        );

        $data['licenseTypes'] = $licenseTypes;
        $data['licenses'] = $licenses;
        $data['LicenceAllocations'] = $LicenceAllocations;
        $data['totalCost'] = $totalCost;
        $data['status_id'] = $status;
        $data['license_type'] = $type;
        $data['license_id'] = $licenseID;

        return view('Employees.Report.license_report')->with($data);
    }

    // license report
    public function licenseReportPrint(Request $request)
    {
        $status = !empty($request['status_id']) ? $request['status_id'] : 1;
        $type = !empty($request['license_type']) ? $request['license_type'] : '';
        $licenseID = !empty($request['license_id']) ? $request['license_id'] : '';
        $totalCost = 0;
        $LicenceAllocations = LicencesAllocation::getAlllicenses($status, $type, $licenseID);

        // calculate total cost
        if (!empty($LicenceAllocations)) {
            foreach ($LicenceAllocations as $allocation) {

                //echo $allocation->Licenses->name ." plus".$allocation->Licenses->purchase_cost."</br>";
                $totalCost = $totalCost + $allocation->Licenses->purchase_cost;
            }
        }
        $LicenceAllocations = LicencesAllocation::getAlllicenses($status, $type, $licenseID);
        //return $LicenceAllocations;

        $licenseTypes = LicensesType::all();
        $licenses = Licences::all();

        $data = $this->breadCrump(
            "Employee Records",
            "License Report", "fa fa-lock",
            "Licenses  Management Report",
            "Licenses  Management",
            "/",
            "Licenses  Management",
            "Licenses  Management"
        );

        $data['licenseTypes'] = $licenseTypes;
        $data['licenses'] = $licenses;
        $data['LicenceAllocations'] = $LicenceAllocations;
        $data['totalCost'] = $totalCost;
        $data['status_id'] = $status;
        $data['license_type'] = $type;
        $data['license_id'] = $licenseID;
        // printing
        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        return view('Employees.Report.license_report_print')->with($data);
    }
}
