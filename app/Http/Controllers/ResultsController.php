<?php

namespace App\Http\Controllers;

use App\educator;
use App\Learner;
use App\public_reg;
use App\Registration;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResultsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //show registration search form
    public function searchRegistrations(){
        $programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();

        $data['page_title'] = "Results";
        $data['page_description'] = "Capture a Learner, Educator or Member of the General Public's Results";
        $data['breadcrumb'] = [
            ['title' => 'Results', 'path' => '/education/results/search', 'icon' => 'fa fa-percent', 'active' => 0, 'is_module' => 1],
            ['title' => 'Load Clients', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'results';
        $data['active_rib'] = 'capture results';
        $data['programmes'] = $programmes;
		AuditReportsController::store('Registration Search', 'Search Details', "Actioned By User", 0);
        return view('results.load_clients')->with($data);
    }

    //get registration search result
    public function getRegistrations(Request $request){
        //Validation
        $validator = Validator::make($request->all(), [
            'registration_type' => 'required',
            'programme_id' => 'required',
            'registration_year' => 'required',
            'course_type' => 'required',
            'registration_semester' => 'required_if:course_type,2',
        ]);
        if ($validator->fails()) {
            return redirect('/education/loadclients')
                ->withErrors($validator)
                ->withInput();
        }
        $regData = $request->all();
        $regType = (int) $regData['registration_type'];
        $programmeID = (int) $regData['programme_id'];
        $regYear = (int) $regData['registration_year'];
        $courseType = (int) $regData['course_type'];
        $regSemester = ($regData['registration_semester'] != '') ? (int) $regData['registration_semester'] : 0;
        $registrations = Registration::where('registration_type', $regType)
            ->where('programme_id', $programmeID)
            ->where('registration_year', $regYear)
            ->where('course_type', $courseType)
            ->where(function ($query) use ($courseType, $regSemester) {
                if ($courseType == 2 && $regSemester > 0) {
                    $query->where('registration_semester', $regSemester);
                }
            })
            ->get();
        if ($registrations) $registrations->load('client', 'programme', 'project', 'subjects');
        $data['page_title'] = "Results";
        $data['page_description'] = "Capture a Learner, Educator or Member of the General Public's Results";
        $data['breadcrumb'] = [
            ['title' => 'Programmes', 'path' => '/education/results/search', 'icon' => 'fa fa-percent', 'active' => 0, 'is_module' => 1],
            ['title' => 'Load Clients', 'path' => '/education/loadclients', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Capture Results', 'active' => 1, 'is_module' => 0]
        ];
        $data['registrations'] = $registrations;
        $data['active_mod'] = 'results';
        $data['active_rib'] = 'capture results';
		AuditReportsController::store('Registration Performed', 'Stuent Registered', "Actioned By User", 0);
        return view('results.show_clients')->with($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
