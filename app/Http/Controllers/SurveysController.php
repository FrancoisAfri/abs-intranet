<?php

namespace App\Http\Controllers;

use App\AppraisalSurvey;
use App\CompanyIdentity;
use App\DivisionLevel;
use App\HRPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class SurveysController extends Controller
{
    /**
     * Display the report index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexReports()
    {
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $data['division_levels'] = $divisionLevels;
        $data['employees'] = $employees;
        $data['page_title'] = "Appraisal Reports";
        $data['page_description'] = "Generate Employees Appraisal Reports";
        $data['breadcrumb'] = [
            ['title' => 'Survey', 'path' => '/survey/reports', 'icon' => 'fa fa-list-alt', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Survey';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Survey', 'Reports page accessed', "Accessed by User", 0);
        return view('survey.reports.survey_report_index')->with($data);
    }

    /**
     * Display the manage rating links page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexRatingLinks()
    {
        return 'rating links';
    }

    /**
     * Prints the ratings report.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function printReport(Request $request)
    {
        return $this->getReport($request, true);
    }

    /**
     * Generates the ratings report.
     *
     * @param \Illuminate\Http\Request $request
     * @param boolean $print
     * @return \Illuminate\Http\Response
     */
    public function getReport(Request $request, $print = false)
    {
        $this->validate($request, [
            'hr_person_id' => 'required',
            'date_from' => 'date_format:"d F Y"',
            'date_to' => 'date_format:"d F Y"',
        ]);

        $empID = $request->input('hr_person_id');
        $strDateFrom = trim($request->input('date_from'));
        $strDateTo = trim($request->input('date_to'));
        $dateFrom = ($strDateFrom) ? strtotime($strDateFrom) : null;
        $dateTo = ($strDateTo) ? strtotime($strDateTo) : null;

        $empRatings = AppraisalSurvey::where('hr_person_id', $empID)
                        ->where(function ($query) use ($dateFrom, $dateTo) {
                            if ($dateFrom) $query->whereRaw('feedback_date >= ' . $dateFrom);
                            if ($dateTo) $query->whereRaw('feedback_date <= ' . $dateTo);
                        })
                        ->orderBy('feedback_date', 'asc')
                        ->get();

        $data['empRatings'] = $empRatings;
        $data['empID'] = $empID;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;
        $data['empFullName'] = HRPerson::find($empID)->full_name;
        $data['strDateFrom'] = $strDateFrom;
        $data['strDateTo'] = $strDateTo;
        $data['page_title'] = "Survey Report";
        $data['page_description'] = "Employees Rating Report";
        $data['breadcrumb'] = [
            ['title' => 'Survey', 'path' => '/survey/reports', 'icon' => 'fa fa-list-alt', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Survey';
        $data['active_rib'] = 'Reports';

        //return printable view if print = 1
        if ($print === true) {
            $data['report_name'] = 'Employee Rating Report';
            $data['user'] = Auth::user()->load('person');
            $data['company_logo'] = CompanyIdentity::systemSettings()['company_logo_url'];
            $data['date'] = Carbon::now()->format('d/m/Y');
            return view('survey.reports.print_survey_report')->with($data);
        }

        return view('survey.reports.view_survey_report')->with($data);
    }
}
