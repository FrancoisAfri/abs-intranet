<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\AuditReportsController;
use App\Models\LicensesType;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Http\Requests\AssetsRequest;
use RealRashid\SweetAlert\Facades\Alert;

class LicenceTypeController extends Controller
{
    use BreadCrumpTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $licence = LicensesType::all();

        $data = $this->breadCrump(
            "Licenses Type Management",
            "Setup", "fa fa-lock",
            "Licenses Type  Management Set Up",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Set Up"
        );

        $data['licenceType'] = $licence;

        AuditReportsController::store(
            'Asset Management',
            'Asset Management Page Accessed',
            "Actioned By User",
            0
        );

        return view('assets.licenseType.create')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
    public function store(AssetsRequest $request): JsonResponse
    {
        $licenceType = LicensesType::create($request->all());
        AuditReportsController::store('licenceType Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param LicensesType $type
     * @return Response
     */
    public function update(Request $request , LicensesType $licence)
    {
        $licence->update($request->all());

        AuditReportsController::store('licenceType Management', 'licenceType  Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param LicensesType $type
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(LicensesType $licence): RedirectResponse
    {
        $licence->delete();
        return redirect()->route('licence.index')->with('status', 'Company Deleted!');
    }

    /**
     * @param LicensesType $type
     * @return RedirectResponse
     */
    public function activate(LicensesType $licence){

        $licence->status == 1 ? $stastus = 0 : $stastus = 1;
        $licence->status = $stastus;
        $licence->update();

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('Asset Management', 'Asset t  Type Status Changed', "Asset News Type  Changed", 0);
        return back();
    }
}
