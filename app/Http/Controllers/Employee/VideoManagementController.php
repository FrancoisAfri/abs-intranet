<?php

namespace App\Http\Controllers\Employee;

use App\DivisionLevel;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Models\AssetTransfers;
use App\Models\Video;
use App\TrainingDocuments;
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
		$documents = TrainingDocuments::get();
		$documents = $documents->load('division','department','section');
		//return $documents;
        $videoType = [
            1 => 'General',
            2 => 'Specific_dept'
        ];

        $data = $this->breadCrump(
            "Employee Records",
            "Training and Videos", "fa fa-lock",
            "Training and Videos",
            "Training and Videos",
            "/hr",
            "Training and Videos",
            "Training and Videos"
        );

        $data['videos'] = $videos;
        $data['videoType'] = $videoType;
        $data['division_levels'] = $divisionLevels;
        $data['documents'] = $documents;

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
            'path' => 'required|max:51200',
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
            AuditReportsController::store('Employee  Records', 'New Video Added', "Added By User", 0);
            return response()->json();
        }
        AuditReportsController::store('Employee Records', 'New Video Added', "Added By User", 0);
        return response()->json();
    }
	// store documents 
	public function storeDocs(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'document' => 'required|max:51200',
            'division_level_5' => 'required',
        ]);
		$div5 = !empty($request['division_level_5']) ? $request['division_level_5'] : 0;
		$div4 = !empty($request['division_level_4']) ? $request['division_level_4'] : 0;
		$div3 = !empty($request['division_level_3']) ? $request['division_level_3'] : 0;
		
        $training = TrainingDocuments::create(
			[
				'name' => $request['name'],
				'description' => $request['description'],
				'division_level_5' => $div5 ,
				'division_level_4' => $div4,
				'division_level_3' => $div3,
				'status' => 1,
			]
		);
		//Upload supporting Documents
        if ($request->hasFile('document')) {
            $fileExt = $request->file('document')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('document')->isValid()) {
                $fileName = time() . "_supporting_docs." . $fileExt;
                $request->file('document')->storeAs('Employee/training', $fileName);
                //Update 
                $training->document = $fileName;
                $training->update();
            }
        }
		
		AuditReportsController::store('Employee  Records', 'New Training Documents Added', "Added By User", 0);
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
            "Training and Videos", "fa fa-lock",
            "Training and Videos",
            "Training and Videos",
            "/hr",
            "Training and Videos",
            "Training and Videos"
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

        AuditReportsController::store('Employee Records', 'Video  Status Changed', "Video  Changed", 0);
        return back();
    }
	
	public function activateDocs(TrainingDocuments $docs)
    {

        $docs->status == 1 ? $stastus = 0 : $stastus = 1;
        $docs->status = $stastus;
        $docs->update();


        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('Employee Records', 'Video  Status Changed', "Video  Changed", 0);
        return back();
    }
}
