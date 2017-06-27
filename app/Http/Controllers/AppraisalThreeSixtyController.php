<?php

namespace App\Http\Controllers;

use App\AppraisalEmpKPIResult;
use App\AppraisalKPIResult;
use App\appraisalsKpis;
use App\AppraisalThreeSixtyPerson;
use App\HRPerson;
use App\Mail\AddedToThreeSixty;
use App\Mail\RemovedFromThreeSixty;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AppraisalThreeSixtyController extends AppraisalKPIResultsController
{
    /**
     * Display a listing of the resource.
     *
     * @return AppraisalKPIResultsController@loadEmpAppraisals
     */
    public function index()
    {
        $empID = Auth::user()->person->id;
        $appraisalMonth = Carbon::now()->format('F Y');
        return parent::loadEmpAppraisals($empID, $appraisalMonth, true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AppraisalKPIResultsController@loadEmpAppraisals
     */
    public function indexThreeSixty($empID)
    {
        //check if logged in user is allowed to rate this emp
        $appraiserID = Auth::user()->person->id;
        $strAppraisalMonth = Carbon::now()->format('F Y');
        //$appraisalMonth = time();
        $monthStart = strtotime(new Carbon("first day of $strAppraisalMonth"));
        $monthEnd = new Carbon("last day of $strAppraisalMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
        $validAppraiser = AppraisalThreeSixtyPerson::where('hr_id', $empID)->where('appraiser_id', $appraiserID)->whereBetween('appraisal_month', [$monthStart, $monthEnd])->get();
        if (count($validAppraiser) > 0) return parent::loadEmpAppraisals($empID, $strAppraisalMonth, true);
        else return redirect('/');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return
     */
    public function storeEmpAppraisals(Request $request) {
        $this->validate($request, [
            'score.*' => 'numeric|min:0',
        ]);

        $appraisalMonth = $request->input('appraisal_month');
        $monthStart = strtotime(new Carbon("first day of $appraisalMonth"));
        $monthEnd = new Carbon("last day of $appraisalMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
        $hrID = $request->input('hr_person_id');
        $appraiserID = Auth::user()->person->id;
        $kpiIDs = $request->input('kpi_id');
        $scores = $request->input('score');

        foreach ($kpiIDs as $kpiID) {
            $kpiResult = AppraisalEmpKPIResult::where('kpi_id', $kpiID)->where('hr_id', $hrID)->where('appraiser_id', $appraiserID)->whereBetween('date_uploaded', [$monthStart, $monthEnd])->get();
            if (count($kpiResult) > 0) { //update result
                $kpiResult = $kpiResult->first();
                $kpiResult->score = trim($scores[$kpiID]) != '' ? trim($scores[$kpiID]) : null;
                $kpiResult->update();
            }
            else { //insert new result
                //$kpi = appraisalsKpis::find($kpiID);
                $result = new AppraisalEmpKPIResult();
                $result->kpi_id = $kpiID;
                $result->hr_id = $hrID;
                $result->date_uploaded = strtotime('15 ' . $appraisalMonth);
                $result->score = trim($scores[$kpiID]) != '' ? trim($scores[$kpiID]) : null;
                $result->appraiser_id = $appraiserID;
                $result->save();
            }
        }
        AuditReportsController::store('Performance Appraisal', 'Appraisal result entered for ' . $appraisalMonth, "Actioned by User", 0);
        return back()->with('success_edit', "The employee's appraisals have been saved successfully."); ///appraisal/appraise-yourself
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HRPerson $hrPerson
     * @return \Illuminate\Http\Response
     */
    public function addEmpToThreeSixty(Request $request, $empID)
    {
        $this->validate($request, [
            'emp_id.*' => 'bail|required|integer|min:0',
        ]);

        $threeSixtyPeopleIDs = $request->input('emp_id');
        $strAppraisalMonth = Carbon::now()->format('F Y');
        $appraisalMonth = time();
        $monthStart = strtotime(new Carbon("first day of $strAppraisalMonth"));
        $monthEnd = new Carbon("last day of $strAppraisalMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
        $appraisedPerson = HRPerson::find($empID);

        foreach ($threeSixtyPeopleIDs as $threeSixtyPersonID) {
            $threeSixtyPerson = AppraisalThreeSixtyPerson::where('hr_id', $empID)->where('appraiser_id', $threeSixtyPersonID)->whereBetween('appraisal_month', [$monthStart, $monthEnd])->get();
            if (count($threeSixtyPerson) > 0) { //skip this occurrence
                continue;
            }
            else { //insert new ThreeSixtyPerson
                $person = new AppraisalThreeSixtyPerson();
                $person->hr_id = $empID;
                $person->appraiser_id = $threeSixtyPersonID;
                $person->appraisal_month = $appraisalMonth;
                $person->save();

                //Send email notification to added person
                $appraiser = HRPerson::find($threeSixtyPersonID);
                $appraisalLink = url("/appraisal/appraise-your-colleague/$empID");
                Mail::to($appraiser->email)->send(new AddedToThreeSixty($appraiser, $appraisedPerson, $strAppraisalMonth, $appraisalLink));

                AuditReportsController::store('Performance Appraisal', "New Person Added to Three Sixty Group, EmpID: $empID. Three-Sixty Person ID: $threeSixtyPersonID. Appraisal Month: $strAppraisalMonth.", "Actioned by User", 0);
            }
        }

        return response()->json(['emp_id' => $empID, 'msg' => '360 Person added'], 200);
    }

    /**
     * Delete a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HRPerson $hrPerson
     * @return \Illuminate\Http\Response
     */
    public function removeEmpFromThreeSixty($empID, $threeSixtyPersonID)
    {
        $strAppraisalMonth = Carbon::now()->format('F Y');
        $monthStart = strtotime(new Carbon("first day of $strAppraisalMonth"));
        $monthEnd = new Carbon("last day of $strAppraisalMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());
        $appraisedPerson = HRPerson::find($empID);

        $threeSixtyPerson = AppraisalThreeSixtyPerson::where('hr_id', $empID)->where('appraiser_id', $threeSixtyPersonID)->whereBetween('appraisal_month', [$monthStart, $monthEnd])->get();
        if ((count($threeSixtyPerson) > 0) && ($empID == Auth::user()->person->id)) {
            $person = $threeSixtyPerson->first();
            $person->delete();

            //Send email notification to removed person;
            $appraiser = HRPerson::find($threeSixtyPersonID);
            Mail::to($appraiser->email)->send(new RemovedFromThreeSixty($appraiser, $appraisedPerson, $strAppraisalMonth));

            AuditReportsController::store('Performance Appraisal', "Employee removed from Three Sixty Group EmpID: $empID. Three-Sixty Person ID: $threeSixtyPersonID. Appraisal Month: $strAppraisalMonth.", "Actioned by User", 0);

            //return response()->json(['emp_id' => $empID, 'msg' => '360 Person added'], 200);
        }
        return back();
    }
}
