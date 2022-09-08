<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\AuditReportsController;
use App\Models\AssetFiles;
use App\Models\Assets;
use App\Models\AssetType;
use App\Models\LicensesType;
use App\Models\StoreRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\BreadCrumpTrait;
use App\Traits\StoreImageTrait;
use App\Traits\uploadFilesTrait;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Facades\Datatables;

class AssetManagementController extends Controller
{

    use BreadCrumpTrait, StoreImageTrait , uploadFilesTrait;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $assetType = AssetType::all();

        $asserts = Assets::with('AssetType')
            ->where(['asset_status' => 'In Use',
                'status' => 1])
            ->get();

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
        $data['asserts'] = $asserts;

        AuditReportsController::store(
            'Asset Management',
            'Asset Management Page Accessed',
            "Actioned By User",
            0
        );

        return view('assets.manageAssets.create-asset')->with($data);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        die('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asset = Assets::create($request->all());

        //add image
        $formInput['picture'] = $this->verifyAndStoreImage('assets/images', 'picture', $asset, $request);

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function storeFile(Request $request){
        $asset = AssetFiles::create($request->all());

        $formInput['document'] = $this->uploadFile($request,'document', 'assets/files' , $asset );

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
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
       $asset = Assets::findByUuid($id);
       $licenceType = LicensesType::all();
       // will have to clean this
       $assetTransfare = Assets::with('AssetType')
            ->where('status' , 1)
            ->get();

        $assetFiles = AssetFiles::getAllFiles($asset->id);

        $data = $this->breadCrump(
            "Asset Management",
            "Asset View", "fa fa-lock",
            "Asset Management View" ,
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management View "
        );

        $data['assetTransfare'] = $assetTransfare;
        $data['asset'] = $asset;
        $data['assetFiles'] = $assetFiles;
        $data['licenceType'] = $licenceType;

        AuditReportsController::store(
            'Asset Management',
            'Asset Management Page Accessed',
            "Actioned By User",
            0
        );

        return view('assets.manageAssets.index')->with($data);
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
     * @param \Illuminate\Http\Request $request
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

    public function destroy(Assets $assets): RedirectResponse
    {
        //dd($assets);
        $assets->delete();

        return redirect()->route('index')->with('status', 'Asset Deleted!');
    }

    /**
     * @return void
     */
    public function setUp()
    {

        $data = $this->breadCrump(
            "Asset Management",
            "Setup", "fa fa-lock",
            "Asset Management Set Up",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Set Up"
        );

        AuditReportsController::store('Asset Management', 'Asset ManagementSettings Page Accessed', "view Asset Management Settings", 0);
        return view('assets.setup')->with($data);

    }

    /**
     * @param Assets $type
     * @return RedirectResponse
     */
    public function activate(Assets $type): RedirectResponse
    {
        $type->status == 1 ? $stastus = 0 : $stastus = 1;
        $type->status = $stastus;
        $type->update();

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('Asset Management', 'Asset t  Type Status Changed', "Asset News Type  Changed", 0);
        return back();
    }

}
