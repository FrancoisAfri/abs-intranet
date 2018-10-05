<?php

namespace App\Http\Controllers;
use App\Http\Controllers\AuditReportsController;
use App\AppraisalSurvey;
use App\CompanyIdentity;
use App\DivisionLevel;
use App\HRPerson;
use App\SurveyQuestions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TaskLibrary;
use App\ribbons_access;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;

class SurveysController extends Controller
{
	
	public function __construct()
    {
        $this->middleware('auth');
    }
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
        $data['page_title'] = "Survey Reports";
        $data['page_description'] = "Generate Employees Survey Reports";
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
        $employees = HRPerson::where('status', 1)->get();

        $data['employees'] = $employees;
        $data['page_title'] = "Employee Rating Links";
        $data['page_description'] = "Manage Employees Rating Links";
        $data['breadcrumb'] = [
            ['title' => 'Survey', 'path' => '/survey/rating-links', 'icon' => 'fa fa-list-alt', 'active' => 0, 'is_module' => 1],
            ['title' => 'Rating Links', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Survey';
        $data['active_rib'] = 'Rating Links';
        AuditReportsController::store('Survey', 'Rating Links page accessed', "Accessed by User", 0);

        return view('survey.emp_rating_links')->with($data);
    }

	// Create questions per divisionLevels
	public function questionsLists()
    {
        $questions = DB::table('survey_questions')
		->select('survey_questions.*','division_level_fives.name as level5name'
		,'division_level_fours.name as level4name','division_level_threes.name as level3name'
		,'division_level_twos.name as level2name','division_level_ones.name as level1name')
		->leftJoin('division_level_fives', 'survey_questions.division_level_5', '=', 'division_level_fives.id')
		->leftJoin('division_level_fours', 'survey_questions.division_level_4', '=', 'division_level_fours.id')
		->leftJoin('division_level_threes', 'survey_questions.division_level_3', '=', 'division_level_threes.id')
		->leftJoin('division_level_twos', 'survey_questions.division_level_2', '=', 'division_level_twos.id')
		->leftJoin('division_level_ones', 'survey_questions.division_level_1', '=', 'division_level_ones.id')
		->orderBy('description', 'asc')
		->get();
		$divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $deptFive = DB::table('division_setup')->where('level', 5)->first();
        $deptFour = DB::table('division_setup')->where('level', 4)->first();
        $deptThree = DB::table('division_setup')->where('level', 3)->first();
        $deptTwo = DB::table('division_setup')->where('level', 2)->first();
        $deptOne = DB::table('division_setup')->where('level', 1)->first();
        $data['page_title'] = "Survey Questions";
        $data['page_description'] = "Group Questions According to Division Level";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
             ['title' => ' ', 'active' => 1, 'is_module' => 0]
        ];
		$data['division_levels'] = $divisionLevels;
        $data['active_mod'] = 'Survey';
        $data['active_rib'] = 'Survey Questions';
        $data['questions'] = $questions;
        $data['LevelFive'] = $deptFive;
        $data['LevelFour'] = $deptFour;
        $data['LevelThree'] = $deptThree;
        $data['LevelTwo'] = $deptTwo;
        $data['LevelOne'] = $deptOne;

        AuditReportsController::store('Survey', 'Questions Library Page Accessed', "Accessed By User", 0);

        return view('survey.questions_lists')->with($data);
    }
	
	// Save Questions
	public function saveQuestions(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'division_level_5' => 'bail|required|integer|min:1',
            'division_level_4' => 'bail|required|integer|min:1',
        ]);

		$questions = $request->all();
		unset($questions['_token']);
		$question = new SurveyQuestions($questions);
		$question->status = 1;
		$question->description = $request->input('description');
		$question->division_level_5 = !empty($request->input('division_level_5')) ? $request->input('division_level_5'): 0;
		$question->division_level_4 = !empty($request->input('division_level_4')) ? $request->input('division_level_4'): 0;
		$question->division_level_3 = !empty($request->input('division_level_3')) ? $request->input('division_level_3'): 0;
		$question->division_level_2 = !empty($request->input('division_level_2')) ? $request->input('division_level_2'): 0;
		$question->division_level_1 = !empty($request->input('division_level_1')) ? $request->input('division_level_1'): 0;
		$question->save();
		AuditReportsController::store('Survey', 'Question Added', "Question Description: $question->description", 0);
		return response()->json(['new_meeting_decs' => $question->description], 200);
	}
	// Save Questions
	public function updateQuestions(Request $request, SurveyQuestions $question)
    {
        $this->validate($request, [
            'description' => 'required',
            'division_level_5' => 'bail|required|integer|min:1',
            'division_level_4' => 'bail|required|integer|min:1',
        ]);

		$questions = $request->all();
		unset($questions['_token']);
		$question->description = $request->input('description');
		$question->division_level_5 = !empty($request->input('division_level_5')) ? $request->input('division_level_5'): 0;
		$question->division_level_4 = !empty($request->input('division_level_4')) ? $request->input('division_level_4'): 0;
		$question->division_level_3 = !empty($request->input('division_level_3')) ? $request->input('division_level_3'): 0;
		$question->division_level_2 = !empty($request->input('division_level_2')) ? $request->input('division_level_2'): 0;
		$question->division_level_1 = !empty($request->input('division_level_1')) ? $request->input('division_level_1'): 0;
        $question->update();
		AuditReportsController::store('Survey', 'Question Updated', "Question Description: $question->description", 0);
		return response()->json(['new_meeting_decs' => $question->description], 200);
	}
	// ActDeac Question
	public function actDeact(SurveyQuestions $question) 
    {
        if ($question->status == 1) $stastus = 0;
		else $stastus = 1;
		//echo $stastus;
		//die ;
		$question->status = $stastus;	
		$question->update();
		AuditReportsController::store('Survey', "Question Status Changed: $stastus", "Edited by User", 0);
		return back();
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
                        ->get()->load('surveyQuestions');
        //return $empRatings;

                   

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
