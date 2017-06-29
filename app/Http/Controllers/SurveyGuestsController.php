<?php

namespace App\Http\Controllers;

use App\AppraisalSurvey;
use App\CompanyIdentity;
use App\HRPerson;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

use App\Http\Requests;

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
        //[TODO]check if the Appraisal module is acrivce
        
        //$consultantID = $eid;
        try {
            $consultantID = decrypt($eid);
        } catch (DecryptException $e) {
            $consultantID = null;
        }
        //$consultantID = decrypt($eid);
        $companyDetails = CompanyIdentity::systemSettings();
        $employees = ((int) $consultantID) ? HRPerson::where('status', 1)->where('id', $consultantID)->orderBy('first_name')->orderBy('surname')->get() : null;
        $isEmpFound = (count($employees) > 0) ? true : false;

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
            'attitude_enthusiasm' => 'bail|integer|min:0|max:5',
            'expertise' => 'bail|integer|min:0|max:5',
            'efficiency' => 'bail|integer|min:0|max:5',
            'attentive_listening' => 'bail|integer|min:0|max:5',
            'general_overall_assistance' => 'bail|integer|min:0|max:5',
        ]);

        $feedbackData = $request->all();
        foreach ($feedbackData as $key => $value) {
            if (empty($feedbackData[$key])) {
                unset($feedbackData[$key]);
            }
        }

        $clientFeedback = new AppraisalSurvey($feedbackData);
        $clientFeedback->feedback_date = strtotime(date('Y-m-d'));
        //return $clientFeedback;
        $clientFeedback->save();

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
