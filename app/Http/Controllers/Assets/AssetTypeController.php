<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\AuditReportsController;
use App\Http\Requests\AssetTypeRequest;
use App\Models\AssetType;
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
use Ramsey\Uuid\Uuid;

class AssetTypeController extends Controller
{
    use BreadCrumpTrait;

    /**
     * Display a listing of the resource.
     *
     * @return RedirectResponse
     */
    public function index()
    {
        //check if they are licence types
        $licences = LicensesType::first();

        if (is_null($licences)) {
            Alert::error('Licence Type Empty', 'Please add License Type first');
            return redirect()->route('licence.index');
        } else
            $assetType = AssetType::all();
            $licences = LicensesType::all();

        $data = $this->breadCrump(
            "Asset Management",
            "Setup", "fa fa-lock",
            "Asset Management Set Up",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Set Up"
        );

        $data['assetType'] = $assetType;
        $data['licenceType'] = $licences;

        AuditReportsController::store(
            'Asset Management',
            'Asset Management Page Accessed',
            "Actioned By User",
            0
        );

        return view('assets.assetType.create-asset-type')->with($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AssetsRequest $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $assetType = AssetType::create($request->all());
        Alert::toast('Record Added Successfully ', 'success');
        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param AssetType $type
     * @return Response
     */
    public function update(AssetsRequest $request, AssetType $type)
    {
        $type->update($request->all());

        Alert::toast('Record Updated Successfully ', 'success');

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AssetType $type
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(AssetType $type): RedirectResponse
    {
        $type->delete();

        return redirect()->route('type.index')->with('status', 'Company Deleted!');
    }

    /**
     * @param AssetType $type
     * @return RedirectResponse
     */
    public function activate(AssetType $type): RedirectResponse
    {
        $type->status == 1 ? $stastus = 0 : $stastus = 1;
        $type->status = $stastus;
        $type->update();

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('Asset Management', 'Asset t  Type Status Changed', "Asset News Type  Changed", 0);
        return back();
    }

}
