<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\AuditReportsController;
use App\Models\AssetComponents;
use App\Models\Assets;
use App\Models\AssetTransfers;
use App\Models\AssetType;
use App\Traits\BreadCrumpTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetReportsController extends Controller
{
    use BreadCrumpTrait;

    /**
     * @return void
     */
    public function assetsList(Request $request)
    {
        $status = $request['status_id'];
        $assets = Assets::getAllAssetsByStatus($status);
        $assetTypes = AssetType::all();
        $data = $this->breadCrump(
            "Asset Management",
            "Setup", "fa fa-lock",
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
     * @return void
     */
    public function componentList(Request $request)
    {
        Assets::with('AssetType')->get();
        $componentList = AssetComponents::with(
            'AssetsList')
            ->orderBy('id', 'asc')
            ->get();

        $data = $this->breadCrump(
            "Asset Management",
            "Setup", "fa fa-lock",
            "Asset Management Reports",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Reports"
        );

        $data['componentList'] = $componentList;

        return view('assets.reports.component-asset')->with($data);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function transferList(Request $request)
    {
        $assetTransfer = AssetTransfers::with(
            'AssetTransfers',
            'AssetImages',
            'HrPeople',
            'store')
            ->get();

        $data = $this->breadCrump(
            "Asset Management",
            "Setup", "fa fa-lock",
            "Asset Management Reports",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Reports"
        );

        $data['assetTransfer'] = $assetTransfer;

        return view('assets.reports.transfer-asset')->with($data);
    }

    public function Assetlocation(Request $request)
    {
        $assetTransfer = AssetTransfers::with(
            'AssetTransfers',
            'AssetImages',
            'HrPeople',
            'store')
            ->get();

        $data = $this->breadCrump(
            "Asset Management",
            "Setup", "fa fa-lock",
            "Asset Management Reports",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Reports"
        );

        $data['assetTransfer'] = $assetTransfer;

        return view('assets.reports.location-asset')->with($data);
    }
}
