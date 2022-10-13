<?php

namespace App\Http\Controllers\Assets;

use App\HRPerson;
use App\Http\Controllers\TaskLibraryController;
use App\Http\Requests\AssetComponentRequest;
use App\Http\Requests\AssetFilesRequest;
use App\Http\Requests\AssetTransferRequest;
use App\leave_application;
use App\leave_configuration;
use App\Mail\LeaveBalanceReminder;
use App\Mail\managerReminder;
use App\Models\Assets;
use App\Models\AssetFiles;
use App\Models\AssetImagesTransfers;
use App\Models\AssetTransfers;
use App\Models\AssetType;
use App\Models\AssetComponents;
use App\Models\LicensesType;
use App\Models\StoreRoom;
use Carbon\Carbon;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Http\Controllers\AuditReportsController;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Facades\Datatables;
use GuzzleHttp;

class AssetManagementController extends Controller
{

    use BreadCrumpTrait, StoreImageTrait, uploadFilesTrait;

    /**
     * @return Factory|Application|View
     */
    public function index(Request $request)
    {

        unset($request['_token']);


        $status = !empty($request['status_id']) ? $request['status_id'] : 'In Use';

        $asset_type = $request['asset_type_id'];
        $assetType = AssetType::all();
        $asserts = Assets::getAssetsByStatus($status, $asset_type);

        $data = $this->breadCrump(
            "Asset Management",
            "Manage Assets", "fa fa-lock",
            "Asset Management Set Up",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Set Up"
        );

        $data['assetType'] = $assetType;
        $data['asserts'] = $asserts;
        $data['info'] = 'info';

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

        AssetTransfers::create(
            [
                $request->all(),
                'asset_id' => $asset->id
            ]
        );

        $imageTransfare = new AssetImagesTransfers();
        $imageTransfare->asset_id = $asset->id;
        $imageTransfare->status = 1;
        $imageTransfare->save();

        $this->verifyAndStoreImage('assets/images', 'picture', $asset, $request);

        $this->verifyAndStoreImage('files/images', 'picture', $imageTransfare, $request);

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param AssetFilesRequest $request
     * @return JsonResponse
     */
    public function storeFile(AssetFilesRequest $request): JsonResponse
    {

        $asset = AssetFiles::create($request->all());

        $this->uploadFile($request, 'document', 'assets/files', $asset);

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @return void
     */
    public function storeComponent(AssetComponentRequest $request)
    {
        $component = AssetComponents::create($request->all());

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeTransfer(AssetTransferRequest $request): JsonResponse
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
                }
            }
            //AssetTransfers

            //check
            ($request['transfer_to'] == 1) ? ($status = 'In Use') : ($status = 'In Store');

            ($request['transfer_to'] == 1) ? ($user = $request['user_id']) : ($user = 0);
            ($request['transfer_to'] == 2) ? ($store = $request['store_id']) : ($store = 0);


            AssetTransfers::create([
                $request->all(),
                'name' => $request['name'],
                'asset_id' => $request['asset_id'],
                'asset_status' => $status,
                'user_id' => $user,
                'store_id' => $store,
                'transaction_date' => date('Y-m-d H:i:s'),
                'transfer_date' => $request['transfer_date'],
                'asset_image_transfer_id' => $AssetImagesTransfers->id
            ]);

            $assets = Assets::find($request['asset_id']);
            $assets->asset_status = $status;
            $assets->user_id = $user;
            $assets->update();

        }

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    public function viewImages($assets)
    {
        $transfer = AssetTransfers::findByUuid($assets);
        $date = $transfer->created_at;
        $asset_id = $transfer->asset_id;

        $assetUiid = Assets::where('id', $transfer->asset_id)->first();

        $image = AssetImagesTransfers::getImagesById($asset_id, $date);

        $data = $this->breadCrump(
            "Asset Management",
            "Manage Assets", "fa fa-lock",
            "Asset Management Set Up",
            "Asset Management",
            "assets",
            "Asset Management",
            "Asset Management Tranfer Images"
        );


        $data['image'] = $image;
        $data['transfer'] = $assetUiid->uuid;
        $data['name'] = $assetUiid->name;

        AuditReportsController::store(
            'Asset Management',
            'Asset Management Page Accessed',
            "Actioned By User",
            0
        );

        return view('assets.manageAssets.transfer-images')->with($data);

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
        $activeTransfer = $activeInfo = $activeCom = $activeFile = ''; //
//		if (!empty($tab) && $tab == 'file')
//			$activeFile = 'active';
//		elseif (!empty($tab) && $tab == 'component')
//			$activeCom = 'active';
//		elseif (!empty($tab) && $tab == 'info')
//			$activeInfo = 'active';
//		elseif (!empty($tab) && $tab == 'transfer')
//			$activeTransfer = 'active';

        $stores = StoreRoom::all();

        $asset = Assets::findByUuid($id);

        $data['view_by_admin'] = 1;

        $licenceType = LicensesType::all();
        // will have to clean this
        $assetTransfare = Assets::getAssetsTypes();

        $assetFiles = AssetFiles::getAllFiles($asset->id);

        $assetComponents = AssetComponents::getAssetComponents($asset->id);

        $Transfers = AssetTransfers::getAssetsTransfares($asset->id);

        $data = $this->breadCrump(
            "Asset Management",
            "Manage Assets", "fa fa-lock",
            "Asset Management View",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management View "
        );

//        $data['activeFile'] = $activeFile;
//        $data['activeCom'] = $activeCom;
//        $data['activeInfo'] = $activeInfo;
//        $data['activeTransfer'] = $activeTransfer;
        $data['assetTransfare'] = $assetTransfare;
        $data['asset'] = $asset;
        $data['assetComponents'] = $assetComponents;
        $data['assetFiles'] = $assetFiles;
        $data['licenceType'] = $licenceType;
        $data['stores'] = $stores;
        $data['users'] = $users;
        $data['Transfers'] = $Transfers;
        $data['view_by_admin'] = 1;

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
     * @param Request $request
     * @param $asset
     * @return JsonResponse
     */
    public function update(Request $request, $asset)
    {
        $Assets = Assets::find($asset);
        $Assets->update($request->all());

        //TODO FIX THE UPLOAD

        $this->verifyAndStoreImage('assets/images', 'picture', $Assets, $request);

        Alert::toast('Record Updated Successfully ', 'success');

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @param AssetComponents $asset
     * @return JsonResponse
     */
    public function componentUpdate(Request $request, AssetComponents $asset): JsonResponse
    {
        $asset->update($request->all());

        Alert::toast('Record Updated Successfully ', 'success');

        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param Request $request
     * @param $asset
     * @return JsonResponse
     */
    public function AssetStatusUpdate(Request $request, $asset)
    {
        $Assets = Assets::find($asset);
        $Assets->update($request->all());

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
