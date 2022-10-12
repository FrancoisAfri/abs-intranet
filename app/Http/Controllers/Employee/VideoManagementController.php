<?php

namespace App\Http\Controllers\Employee;

use App\DivisionLevel;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Models\AssetTransfers;
use App\Models\Video;
use App\Traits\BreadCrumpTrait;
use App\Traits\uploadFilesTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class VideoManagementController extends Controller
{
    use BreadCrumpTrait, uploadFilesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $videos = Video::all();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $videoType = [
            1 => 'General',
            2 => 'Specific_dept'
        ];

        $data = $this->breadCrump(
            "Employee Records",
            "Video Management", "fa fa-lock",
            "Video Management",
            "Video Management",
            "/hr",
            "Video Management",
            "Video Management"
        );

        $data['videos'] = $videos;
        $data['videoType'] = $videoType;
        $data['division_levels'] = $divisionLevels;

        return view('Employees.video_management')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assign(Request $request)
    {


        $video =  Video::where('id', $request['video_id'])->update(
            [
                'video_type' => !empty( $request['allocation_type']) ?  $request['allocation_type'] : 0 ,
                'division_level_5' => !empty( $request['division_level_5']) ?  $request['division_level_5'] : 0,
                'division_level_4' => !empty( $request['division_level_4']) ?  $request['division_level_4'] : 0,
                'division_level_3' => !empty( $request['division_level_3']) ?  $request['division_level_3'] : 0,
                'division_level_2' => !empty( $request['division_level_2']) ?  $request['division_level_2'] : 0,
                'division_level_1' => !empty( $request['division_level_1']) ?  $request['division_level_1'] : 0,
            ]
        );

        return back();
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


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'path' => 'required|max:50000',
        ]);

        $video_name = $request->file('path');
        $File_ex = $video_name->extension();
        $filePath = 'emp_vid' . ' ' . str_random(16) . '.' . $File_ex;
        $isFileUploaded = Storage::disk('public')->put('videos/' . $filePath,
            file_get_contents($request->file('path')));

        // File URL to access the video in frontend
        $url = Storage::disk('public')->url($filePath);

        if ($isFileUploaded) {

            $video_file = Video::create(
                [
                    'name' => $request['name'],
                    'description' => $request['description'],
                    'path' => $filePath,
                    'video_type' => 1,
                ]
            );

            AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
            return response()->json();
        }


        AuditReportsController::store('Asset Management', 'Asset Management Page Accessed', "Accessed By User", 0);;
        return response()->json();
    }

    /**
     * @param $id
     * @return Factory|Application|View
     */
    public function show($id)
    {
        $video = Video::getNameByUuid($id);

        $videoType = [
            1 => 'General',
            2 => 'Specific_dept'
        ];


        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();


        $data = $this->breadCrump(
            "Employee Records",
            "Video Management", "fa fa-lock",
            "Video Management",
            "Video Management",
            "/hr",
            "Video Management",
            "Video Management"
        );


        $data['video'] = $video;
        $data['videoType'] = $videoType;
        $data['division_levels'] = $divisionLevels;

        return view('Employees.video_show')->with($data);
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
    public function destroy($id)
    {
        //
    }

    /**
     * @param Video $videos
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(Video $videos)
    {

        $videos->status == 1 ? $stastus = 0 : $stastus = 1;
        $videos->status = $stastus;
        $videos->update();


        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('Video Management', 'Video  Status Changed', "Video  Changed", 0);
        return back();
    }
}
