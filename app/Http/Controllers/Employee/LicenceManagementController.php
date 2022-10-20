<?php

namespace App\Http\Controllers\Employee;

use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Models\Licences;
use App\Models\LicencesAllocation;
use App\Models\LicensesType;
use App\Models\Video;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class LicenceManagementController extends Controller
{
    use BreadCrumpTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $licences = Licences::all();
        $licence_type = LicensesType::where('status', 1)->get();
        $users = HRPerson::where('status', 1)->get();

        $data = $this->breadCrump(
            "Employee Records",
            "Licence Management", "fa fa-lock",
            "Licences Management",
            "Licences Management",
            "/hr",
            "Licences Management",
            "Licences Management"
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
        Licences::create([
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


//        dd($LicenceDetails);
        $data = $this->breadCrump(
            "Employee Records",
            "Licence Management", "fa fa-lock",
            "Licences Management",
            "Licences Management",
            "/hr",
            "Licences Management",
            "Licences Management"
        );

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

        foreach ($users as $key => $user) {
            $allocate = LicencesAllocation::create([
                'licence_id' => $request['licence_id'],
                'user_id' => $user
            ]);
        }

        $licence = Licences::where('id', $request['licence_id'])->first();
        $total_sum = $licence['total'] - count($users);

        Licences::where('id', $request['licence_id'])->update(
            [
                'total' => $total_sum,
                'licence_status' => 'allocated'
            ]
        );

        return response()->json();

    }
}
