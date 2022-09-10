<?php

namespace App\Http\Controllers\Assets;

use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Models\AssetComponents;
use App\Models\AssetFiles;
use App\Models\AssetImagesTransfers;
use App\Models\Assets;
use App\Models\AssetTransfers;
use App\Models\AssetType;
use App\Models\LicensesType;
use App\Models\StoreRoom;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\BreadCrumpTrait;
use App\Traits\StoreImageTrait;
use App\Traits\uploadFilesTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Facades\Datatables;

class AssetManagementController extends Controller
{

    use BreadCrumpTrait, StoreImageTrait, uploadFilesTrait;

    /**
     * @return Factory|Application|View
     */
    public function index(Request $request)
    {

      //  $status = !empty($request['status_id']) ? $request['status_id'] : 'In Use';
        $status = !empty($request['status_id']) ? $request['status_id'] : 'In Use';
        $asset_type = $request['asset_type_id'];

        $assetType = AssetType::all();

        $asserts = Assets::getAssetsByStatus($status , $asset_type);

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
        $asset = $this->addAsset($request);
        //add image
        $Data['picture'] = $this->verifyAndStoreImage('assets/images', 'picture', $asset, $request);

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function storeFile(Request $request)
    {
        $asset = AssetFiles::create($request->all());

        $formInput['document'] = $this->uploadFile($request, 'document', 'assets/files', $asset);

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function storeComponent(Request $request)
    {
        $component = AssetComponents::create($request->all());

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeTransfer(Request $request): JsonResponse
    {
        $component = AssetTransfers::create($request->all());

        $fileImages = new  AssetImagesTransfers();
        $fileImages->asset_id = $request['asset_id'];
        $fileImages->save();

        if($request->has('picture')){
            foreach ($request->file('picture') as $image)
                {
                    $name = $image->getClientOriginalName();
                    $image->move(public_path().'/files/' , $name);
                    $data = $name;
                    $fileImages->picture = $data;
                    $fileImages->update();
                }
        }

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $users = HRPerson::where('status', 1)->get();

        $stores = StoreRoom::all();

        $asset = Assets::findByUuid($id);

        $licenceType = LicensesType::all();
        // will have to clean this
        $assetTransfare = Assets::getAssetsTypes();

        $assetFiles = AssetFiles::getAllFiles($asset->id);

        $assetComponents = AssetComponents::getAssetComponents($asset->id);

        $Transfers = AssetTransfers::getAssetsTransfares($asset->id);

        $data = $this->breadCrump(
            "Asset Management",
            "Asset View", "fa fa-lock",
            "Asset Management View",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management View "
        );

        $data['assetTransfare'] = $assetTransfare;
        $data['asset'] = $asset;
        $data['assetComponents'] = $assetComponents;
        $data['assetFiles'] = $assetFiles;
        $data['licenceType'] = $licenceType;
        $data['stores'] = $stores;
        $data['users'] = $users;
        $data['Transfers'] = $Transfers;

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
     * @param AssetFiles $files
     * @return RedirectResponse
     * @throws Exception
     */
    public function fileDestroy(AssetFiles $assets): RedirectResponse
    {
        $assets->delete();
        return redirect()->back();

    }

    /**
     * @param AssetFiles $files
     * @return RedirectResponse
     * @throws Exception
     */
    public function componentDestroy(AssetComponents $assets): RedirectResponse
    {
        $assets->delete();
        return redirect()->back();

    }

    /**
     * @return Factory|Application|View
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

    /***
     * Private function to store Asset data
     * @param Request $request
     * @return Assets
     */
    private function addAsset(Request $request): Assets
    {
        $asset = new Assets();
        $asset->name = $request['name'];
        $asset->description = $request['description'];
        $asset->serial_number = $request['serial_number'];
        $asset->asset_tag = $request['asset_tag'];
        $asset->model_number = $request['model_number'];
        $asset->make_number = $request['make_number'];
        $asset->asset_type_id = $request['asset_type_id'];
        $asset->price = $request['price'];
        $asset->asset_status = $request['asset_status'];
        $asset->save();

        $transfare = new AssetTransfers();
        $transfare->name = $request['name'];
        $transfare->description = $request['description'];
        $transfare->asset_id = $asset->id;
        $transfare->asset_status = $request['asset_status'];
        $transfare->save();

        return $asset;


    }

}
