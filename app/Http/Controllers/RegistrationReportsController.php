<?php

namespace App\Http\Controllers;

use App\educator;
use App\Learner;
use App\programme;
use App\projects;
use App\public_reg;
use App\Registration;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegistrationReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
        $learners = Learner::where('active', 1)->where('is_registered', 0)->orWhereNull('is_registered')->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $educators = educator::where('active', 1)->where('is_registered', 0)->orWhereNull('is_registered')->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $genPubs = public_reg::where('is_registered', 0)->orWhereNull('is_registered')->orderBy('names', 'asc')->get();
        $subjects = [1 => 'Math', 2 => 'Science'];
        $genPubSubjects = [1 => 'MS Excel', 2 => 'MS Powerpoint', 3 => 'MS Word'];
        $data['page_title'] = "Registration Report";
        $data['page_description'] = "Generate a report for registered learners, educators or members of the general public";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports', 'icon' => 'fa fa-bug', 'active' => 0, 'is_module' => 1],
            ['title' => 'Registration', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'reports';
        $data['active_rib'] = 'registration';
        $data['programmes'] = $programmes;
        $data['learners'] = $learners;
        $data['educators'] = $educators;
        $data['gen_pubs'] = $genPubs;
        $data['subjects'] = $subjects;
        $data['areas'] = $genPubSubjects;
        AuditReportsController::store('Reports', 'Registration Search Page Viewed ', "Actioned By User", 0);
        return view('reports.registration_search')->with($data);
    }
    
    public function registrationReport(Request $request, $print = -1) {
        $this->validate($request, [
            'registration_type' => 'required',
        ]);
        $user = Auth::user()->load('person');
        $courseTypes = [1 => 'Year Course', 2 => 'Semester Course'];
        $regType = (int) $request->input('registration_type');
        $progID = $request->input('programme_id');
        $projID = $request->input('project_id');
        $regYear = $request->input('registration_year');
        $courseType = $request->input('course_type');
        $regSemester = $request->input('registration_semester');
        $registrations = Registration::where('registration_type', $regType)
            ->where(function ($query) use ($progID) {
                if (!empty($progID) && $progID > 0) {
                    $query->where('programme_id', (int) $progID);
                }
            })
            ->where(function ($query) use ($projID) {
                if (!empty($projID) && $projID > 0) {
                    $query->where('project_id', (int) $projID);
                }
            })
            ->where(function ($query) use ($regYear) {
                if (!empty($regYear) && $regYear > 0) {
                    $query->where('registration_year', (int) $regYear);
                }
            })
            ->where(function ($query) use ($courseType, $regType) {
                if (!empty($courseType) && $courseType > 0 && $regType != 3) {
                    $query->where('course_type', (int) $courseType);
                }
            })
            ->where(function ($query) use ($regSemester, $regType, $courseType) {
                if (!empty($regSemester) && $regSemester > 0 && (int) $regType != 3 && (int) $courseType == 2) {
                    $query->where('registration_semester', (int) $regSemester);
                }
            })
            ->get()
            ->load('client', 'programme', 'project', 'subjects');
        $strReportTypes = ['' => '', 1 => 'Learners', 2 => 'Educators', 3 => 'Members of the General Public'];

        //resubmitted fields
        $data['registration_type'] = $regType;
        $data['programme_id'] = $progID;
        $data['project_id'] = $projID;
        $data['registration_year'] = $regYear;
        $data['course_type'] = $courseType;
        $data['registration_semester'] = $regSemester;

        $data['user'] = $user;
        $data['company_name'] = 'OSIZWENI EDUCATIONAL AND DEVELOPMENT TRUST';
        $data['company_logo'] = url('/') . Storage::disk('local')->url('logos/logo.png');
        $data['date'] = date("d/m/Y");

        $data['str_report_type'] = $strReportTypes[$regType];
        $data['programme'] = !empty($progID) ? programme::find($progID)->name : '[all]';
        $data['project'] = !empty($projID) ? projects::find($projID)->name : '[all]';
        $data['registration_year'] = !empty($regYear) ? $regYear : '[all]';
        $data['str_course_type'] = !empty($courseType) ? $courseTypes[$courseType] : '[all]';
        $data['registration_semester'] = !empty($regSemester) ? $regSemester : '[all]';
        $data['registrations'] = $registrations;
        $data['page_title'] = "Registration Report";
        $data['page_description'] = "View Registrations Report";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/reports', 'icon' => 'fa fa-bug', 'active' => 0, 'is_module' => 1],
            ['title' => 'Registration', 'path' => '/reports/registration', 'active' => 0, 'is_module' => 0],
            ['title' => 'View report Report', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Reports';
        $data['active_rib'] = 'registration';
        if ($print == 1) return view('reports.registration_print')->with($data);
        else return view('reports.registration_result')->with($data);
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
