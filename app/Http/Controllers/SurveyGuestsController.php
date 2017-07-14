<?php

namespace App\Http\Controllers;

use App\AppraisalSurvey;
use App\CompanyIdentity;
use App\HRPerson;
use App\SurveyQuestions;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class SurveyGuestsController extends Controller
{
    /**
     * This constructor specifies that this section of the application can be accessed by guest (unauthenticated) users
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($eid)
    {
        //[TODO]check if the Appraisal module is active
        
        //$consultantID = $eid;
        try {
            $consultantID = decrypt($eid);
        } catch (DecryptException $e) {
            $consultantID = null;
        }

        $companyDetails = CompanyIdentity::systemSettings();
        $employees = ((int) $consultantID) ? HRPerson::where('status', 1)->where('id', $consultantID)->orderBy('first_name')->orderBy('surname')->get() : null;
        $isEmpFound = (count($employees) > 0) ? true : false;

        //get employee's lowest div level
        $emp = $employees->first();
        if($emp->division_level_1 && $emp->division_level_1 > 0) {
            $empDivLevelID = $emp->division_level_1;
            $empDivLevel = 1;
        } elseif ($emp->division_level_2 && $emp->division_level_2 > 0) {
            $empDivLevelID = $emp->division_level_2;
            $empDivLevel = 2;
        } elseif ($emp->division_level_3 && $emp->division_level_3 > 0) {
            $empDivLevelID = $emp->division_level_3;
            $empDivLevel = 3;
        } elseif ($emp->division_level_4 && $emp->division_level_4 > 0) {
            $empDivLevelID = $emp->division_level_4;
            $empDivLevel = 4;
        } elseif ($emp->division_level_5 && $emp->division_level_5 > 0) {
            $empDivLevelID = $emp->division_level_5;
            $empDivLevel = 5;
        } else {
            $empDivLevelID = 0;
            $empDivLevel = 0;
        }

        //get questions from the survey question library
        $surveyQuestions = [];
        if ($empDivLevelID > 0) {
            $surveyQuestions = SurveyQuestions::where('status', 1)->where('division_level_' . $empDivLevel, $empDivLevelID)->orderBy('description')->get();
        }

        $data['page_title'] = "Rate Our Services";
        $data['page_description'] = "Please submit your review below";
        $data['breadcrumb'] = [
            ['title' => 'Feedback', 'path' => '/appraisal/search', 'icon' => 'fa fa-comments-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Review', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Review';
        $data['skinColor'] = $companyDetails['sys_theme_color'];
        $data['headerAcronymBold'] = $companyDetails['header_acronym_bold'];
        $data['headerAcronymRegular'] = $companyDetails['header_acronym_regular'];
        $data['headerNameBold'] = $companyDetails['header_name_bold'];
        $data['headerNameRegular'] = $companyDetails['header_name_regular'];
        $data['company_logo'] = $companyDetails['company_logo_url'];
        $data['employees'] = $employees;
        $data['isEmpFound'] = $isEmpFound;
        $data['consultantID'] = $consultantID;
        $data['surveyQuestions'] = $surveyQuestions;

        AuditReportsController::store('Performance Appraisal', 'Rate Our Services Page Accessed', "Accessed By Guest", 0);
        return view('appraisals.guests.rate-our-services')->with($data);
    }

    /**
     * Store a customer's feedback in the appraisal_surveys table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'hr_person_id' => 'required',
            'client_name' => 'required',
            'booking_number' => 'unique:appraisal_surveys,booking_number',
            'questions.*' => 'bail|integer|min:0|max:5',
        ]);

        $feedbackData = $request->all();
        foreach ($feedbackData as $key => $value) {
            if (empty($feedbackData[$key])) {
                unset($feedbackData[$key]);
            }
        }

        DB::transaction(function () use($feedbackData){
            //Save a new AppraisalSurvey record
            $clientFeedback = new AppraisalSurvey($feedbackData);
            $clientFeedback->feedback_date = time();
            $clientFeedback->save();

            //Save survey result for each question
            $questionIDs = $feedbackData['questions'];
            if (count($questionIDs) > 0){
                foreach ($questionIDs as $questionID => $result) {
                    if ($result > 0) $clientFeedback->surveyQuestions()->attach($questionID, ['result' => $result]);
                }
            }
        });

        //Redirect the client feedback page with a success message
        AuditReportsController::store('Performance Appraisal', 'New Customer Feedbacked', "Customer feedback added successfully", 0);
        return back()->with('success_add', "Your feedback has been successfully submitted, we value your feedback and appreciate your comments. Thank you");
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
