<?php

namespace App\Http\Controllers\Assets;

use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Models\AssetComponents;
use App\Models\Assets;
use App\Models\AssetTransfers;
use App\Models\AssetType;
use App\Models\StoreRoom;
use App\Traits\BreadCrumpTrait;
use BladeView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Ramsey\Uuid\Uuid;

class AssetReportsController extends Controller
{
    use BreadCrumpTrait;

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function assetsList(Request $request)
    {

        $status = !empty($request['status_id']) ? $request['status_id'] : 'all';
        $type = !empty($request['asset_type_id']) ? $request['asset_type_id'] : 'All';

        $assets = Assets::getAllAssetsByStatus($status, $type);

        $assetTypes = AssetType::all();

        $data = $this->breadCrump(
            "Asset Management",
            "List Assets ", "fa fa-lock",
            "Asset Management Reports",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Reports"
        );


        $data['assetTypes'] = $assetTypes;
        $data['assets'] = $assets;


        return view('assets.reports.list-asset')->with($data);
    }

    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function componentList(Request $request)
    {

        $assets = Assets::with('AssetType')->get();

        $type = !empty($request['asset_type_id']) ? $request['asset_type_id'] : 'all';
        $componentList = AssetComponents::getAssetComponentByStatus($type);
       // dd($componentList);

        $data = $this->breadCrump(
            "Asset Management",
            "List all Component ", "fa fa-lock",
            "Asset Management Reports",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Reports"
        );


        $data['componentList'] = $componentList;
        $data['assets'] = $assets;


        return view('assets.reports.component-asset')->with($data);
    }


    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function transferList(Request $request)
    {
        $person = !empty($request['user_id']) ? $request['user_id'] : 'all';
        $assetType = !empty($request['asset_id']) ? $request['asset_id'] : 'All';
        $location = !empty($request['store_id']) ? $request['store_id'] : 's_all';


        $users =  HRPerson::where('id', 1)->get();
        $assets =  Assets::all();
        $stores =  StoreRoom::all();

        $assetTransfer =  AssetTransfers::getAssetTransfer($person, $assetType , $location);


        $data = $this->breadCrump(
            "Asset Management",
            "Assets Transfer Report ", "fa fa-lock",
            "Asset Management Reports",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Reports"
        );

        $data['stores'] = $stores;
        $data['users'] = $users;
        $data['assets'] = $assets;
        $data['assetTransfer'] = $assetTransfer;


        return view('assets.reports.transfer-asset')->with($data);
    }


    /**
     * @param Request $request
     * @return BladeView|false|Factory|Application|View
     */
    public function Assetlocation(Request $request)
    {

        $person = !empty($request['user_id']) ? $request['user_id'] : 'all';
        $assetType = !empty($request['asset_id']) ? $request['asset_id'] : 'All';
        $location = !empty($request['store_id']) ? $request['store_id'] : 's_all';


        $users =  HRPerson::where('id', 1)->get();
        $assets =  Assets::all();
        $stores =  StoreRoom::all();

        $assetTransfer = AssetTransfers::getAssetLocation($person , $assetType , $location);

        $data = $this->breadCrump(
            "Asset Management",
            "Asset Location ", "fa fa-lock",
            "Asset Management Reports",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Reports"
        );


        $data['stores'] = $stores;
        $data['users'] = $users;
        $data['assets'] = $assets;
        $data['assetTransfer'] = $assetTransfer;


        return view('assets.reports.location-asset')->with($data);
    }


}
