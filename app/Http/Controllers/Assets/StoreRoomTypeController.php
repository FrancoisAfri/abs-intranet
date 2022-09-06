<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\AuditReportsController;
use App\Models\AssetType;
use App\Models\StoreRoom;
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

class StoreRoomTypeController extends Controller
{
    use BreadCrumpTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $store = StoreRoom::all();

        $data = $this->breadCrump(
            "Asset Management",
            "Setup", "fa fa-lock",
            "Asset Management Set Up",
            "Asset Management",
            "assets/settings",
            "Asset Management",
            "Asset Management Set Up"
        );

        $data['store'] = $store;

        AuditReportsController::store(
            'Asset Management',
            'Asset Management Page Accessed',
            "Actioned By User",
            0
        );

        return view('assets.store.create')->with($data);
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
    public function store(AssetsRequest $request): JsonResponse
    {
        $store = StoreRoom::create($request->all());
        Alert::toast('Record Added Successfully ', 'success');
        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
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
     * @param StoreRoom $store_room
     * @return JsonResponse
     */
    public function update(Request $request, StoreRoom $store_room)
    {
        $store_room->update($request->all());
        Alert::toast('Record Updated Successfully ', 'success');
        AuditReportsController::store('Fleet Management', 'Fleet Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StoreRoom $store_room
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(StoreRoom $store_room): RedirectResponse
    {
        $store_room->delete();

        return redirect()->route('store-room.index')->with('status', 'Company Deleted!');
    }

    /**
     * @param StoreRoom $type
     * @return RedirectResponse
     */
    public function activate(StoreRoom $type): RedirectResponse
    {
        $type->status == 1 ? $stastus = 0 : $stastus = 1;
        $type->status = $stastus;
        $type->update();

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('Asset Management', 'Asset t  Type Status Changed', "Asset News Type  Changed", 0);
        return back();
    }
}