<?php

namespace App\Http\Controllers;

use App\educator;
use App\Learner;
use App\programme;
use App\projects;
use App\Province;
use App\public_reg;
use App\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EnrolmentController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $programmes = DB::table('programmes')->where('status', 2)->orderBy('name', 'asc')->get();
        $learners = Learner::where('active', 1)->where('is_registered', 0)->orWhereNull('is_registered')->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $educators = educator::where('active', 1)->where('is_registered', 0)->orWhereNull('is_registered')->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        $genPubs = public_reg::where('is_registered', 0)->orWhereNull('is_registered')->orderBy('names', 'asc')->get();
        $subjects = [1 => 'Math', 2 => 'Science'];
        $genPubSubjects = [1 => 'MS Excel', 2 => 'MS Powerpoint', 3 => 'MS Word'];
        $data['page_title'] = "Programmes";
        $data['page_description'] = "Enrol a Learner, Educator or Member of the General Public";
        $data['breadcrumb'] = [
            ['title' => 'Registration', 'path' => '/education/registration/search', 'icon' => 'fa fa-list-alt', 'active' => 0, 'is_module' => 1],
            ['title' => 'Register', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'registration';
        $data['active_rib'] = 'register';
        $data['programmes'] = $programmes;
        $data['learners'] = $learners;
        $data['educators'] = $educators;
        $data['gen_pubs'] = $genPubs;
        $data['subjects'] = $subjects;
        $data['areas'] = $genPubSubjects;
		AuditReportsController::store('Registration', 'Enrolment Page Viewed ', "Actioned By User", 0);
        return view('enrolment.enrol')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'registration_type' => 'required',
            'programme_id' => 'required',
            'learner_id' => 'required_if:registration_type,1',
            'educator_id' => 'required_if:registration_type,2',
            'gen_public_id' => 'required_if:registration_type,3',
            'registration_year' => 'required',
            'registration_semester' => 'required_if:course_type,2',
            'gen_pub_reg_fee' => 'bail|required_if:registration_type,3|numeric|min:0.1',
            'subject_id.*' => 'required_if:registration_type,1',
            'module_name.*' => 'required_if:registration_type,2',
            'module_fee.*' => 'bail|required_if:registration_type,2|numeric|min:0.1',
            'area_id.*' => 'required_if:registration_type,3',
        ]);
        $validator->after(function ($validator) use($request) {
            $regType = $request->input('registration_type');
            $learnerID = $request->input('learner_id');
            $educatorID = $request->input('educator_id');
            $genPubID = $request->input('gen_public_id');
            $isRegistered = 0;
            $isSubjectEntered = 1;
            $regPersonFieldName = $regSubjectFieldName = '';
            if ($regType == 1) {
                $isRegistered = ($learnerID > 0) ? Learner::find($learnerID)->is_registered : 0;
                $regPersonFieldName = 'learner_id';
                $isSubjectEntered = ($request->input('subject_id')) ? 1 : 0;
                $regSubjectFieldName = 'subject_id';
            }
            elseif ($regType == 2) {
                $isRegistered = ($educatorID > 0) ? educator::find($educatorID)->is_registered : 0;
                $regPersonFieldName = 'educator_id';
            }
            elseif ($regType == 3) {
                $isRegistered = ($genPubID > 0) ? public_reg::find($genPubID)->is_registered : 0;
                $regPersonFieldName = 'gen_public_id';
                $isSubjectEntered = ($request->input('area_id')) ? 1 : 0;
                $regSubjectFieldName = 'area_id';
            }
            if ($isRegistered === 1) {
                $validator->errors()->add($regPersonFieldName, 'This person has already been registered to a Programme/Project');
            }
            if ($isSubjectEntered === 0) {
                $validator->errors()->add($regSubjectFieldName, 'Please select at least one subject/area');
            }
        });
        if ($validator->fails()) {
            return redirect('/education/registration')
                ->withErrors($validator)
                ->withInput();
        }
        $regData = $request->all();

        //Exclude empty fields from query
        foreach ($regData as $key => $value)
        {
            if (empty($regData[$key])) {
                unset($regData[$key]);
            }
        }

        //save registration data
        $regType = (int) $regData['registration_type'];
        $registration = new Registration($regData);
        $registration->registration_type = $regType;
        $registration->save();

        //return $regData;
        if ($regType === 1) {
            //save registration subjects and update the lerner's reg status to registered
            $registration->addSubjects($regData['subject_id']);
            $learner = Learner::find($regData['learner_id']);
            $learner->is_registered = 1;
            //$learner->update();
        }
        elseif ($regType === 2) {
            //save registration modules and update the educator's reg status to registered
            $registration->addModules($regData['module_name'], $regData['module_fee']);
            $educator = educator::find($regData['educator_id']);
            $educator->is_registered = 1;
            //$educator->update();
        }
        elseif ($regType === 3) {
            //save registration areas and update the gen pub person's reg status to registered
            $registration->addAreas($regData['area_id']);
            $person = public_reg::find($regData['gen_public_id']);
            $person->is_registered = 1;
            //$person->update();
        }
		AuditReportsController::store('Reports', 'Student Enroled', "Actioned By User", 0);
        return redirect('/education/registration')->with('success_add', "The person has been successfully registered.");
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
    
    public function projectDD(Request $request){
        $programmeID = $request->input('option');
        $loadAll = $request->input('load_all');
        $projects = [];
        if ($programmeID > 0 && $loadAll == -1)	$projects = projects::projectsFromProgramme($programmeID);
        elseif ($loadAll == 1) {
            $projects = projects::where('status', 2)->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }
        return $projects;
    }
    public function yearDD(Request $request){
        $programmeID = $request->input('option');
        $loadAll = $request->input('load_all');
        $years = [];
        if ($programmeID > 0 && $loadAll == -1){
            $programme = programme::find($programmeID);
            $startDate = !empty($programme->start_date) ? $programme->start_date : 0;
            $endDate = !empty($programme->end_date) ? $programme->end_date : 0;
            $startYear = ($startDate > 0) ? Carbon::createFromTimestamp($startDate)->year : 0;
            $endYear = ($endDate > 0) ? Carbon::createFromTimestamp($endDate)->year : 0;
            if ($startYear > 0 && $endYear > 0) {
                for ($i = $startYear; $i <= $endYear; $i++) {
                    $years[$i] = $i;
                }
            }
            elseif ($startYear > 0 && !($endYear > 0)) {
                $endYear = Carbon::now()->year;
                for ($i = $startYear; $i <= $endYear; $i++) {
                    $years[$i] = $i;
                }
            }elseif (!($startYear > 0)) {
                $thisYear = Carbon::now()->year;
                $years[$thisYear] = $thisYear;
            }
        }
        /*
        elseif ($loadAll == 1) {
            $years = projects::where('status', 2)->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        } */
        return $years;
    }
}
