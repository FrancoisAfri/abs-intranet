<?php

namespace App\Http\Controllers\Assets;

use App\Models\Assets;
use App\Models\AssetFiles;
use App\Models\AssetImagesTransfers;
use App\Models\AssetTransfers;
use App\Models\AssetType;
use App\Models\AssetComponents;
use App\HRPerson;
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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Http\Controllers\AuditReportsController;
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

        $asserts = Assets::getAssetsByStatus($status, $asset_type);

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

        $transfare = AssetTransfers::create(
            [
                $request->all(),
                'asset_id' => $asset->id
            ]
        );


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

        if ($request->hasFile('picture')) {

            foreach ($request->file('picture') as $image) {
                $extension = $image->getClientOriginalExtension();
                if (in_array($extension, ['jpg', 'jpeg', 'png']) && $image->isValid()) {
                    $fileName = md5(microtime()) . "hardware." . $extension;
                    $image->storeAs('public' . '/' . 'files/images', $fileName);
                    $AssetImagesTransfers = AssetImagesTransfers::create(
                        [
                            'asset_id' => $request['asset_id'],
                            'status' => 1,
                            'picture' => $fileName,
                        ]
                    );

                    //AssetTransfers
                    AssetTransfers::create(
                        [
                            $request->all(),
                            'asset_image_transfer_id' => $AssetImagesTransfers->id
                        ]
                    );
                }
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
        //dd($Transfers);

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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param AssetType $type
     * @return Response
     */
    public function update(Request $request, Assets $type): Response
    {
        $type->update($request->all());

        Alert::toast('Record Updated Successfully ', 'success');

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
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


}
