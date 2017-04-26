<?php

namespace App\Http\Controllers;

use App\AppraisalKPIResult;
use App\DivisionLevel;
use App\HRPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class AppraisalReportsController extends Controller
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
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name')->orderBy('surname')->get();
        $data['division_levels'] = $divisionLevels;
        # Get kpi from
        /*$kpis = DB::table('appraisals_kpis')
            ->select('appraisals_kpis.measurement','appraisals_kpis.id')
            ->where('appraisals_kpis.is_upload', 1)
            ->orderBy('appraisals_kpis.measurement')
            ->get();*/

        //$data['kpis'] = $kpis;
        $data['employees'] = $employees;
        $data['page_title'] = "Appraisal Reports";
        $data['page_description'] = "Generate Employees Appraisal Reports";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Reports';
        AuditReportsController::store('Performance Appraisal', 'Reports page accessed', "Accessed by User", 0);
        return view('appraisals.appraisal_report_index')->with($data);
    }

    //Displays the report
    public function getReport(Request $request){
        $this->validate($request, [
            'report_type' => 'required',
            'hr_person_id' => 'required_if:report_type,1',
            'date_from' => 'date_format:"F Y"',
            'date_to' => 'date_format:"F Y"',
            'ranking_limit' => 'integer|required_if:report_type,2|min:1',
        ]);

        $reportType = $request->input('report_type');
        //return $request->all();
        if ($reportType == 1) { //return emp appraisal report
            $dateFrom = trim($request->input('date_from'));
            $dateTo = trim($request->input('date_to'));
            if ($dateFrom == '' && $dateTo == '') {
                $dateFrom = Carbon::now()->day(15)->month(1);
                $dateTo = Carbon::now()->day(15);
            }
            elseif ($dateFrom != '' && $dateTo == '') {
                $dateFrom = Carbon::createFromFormat('d F Y', '15 ' . $dateFrom);
                $dateTo = $dateFrom->copy()->month((int) date('m'));
            }
            elseif ($dateFrom == '' && $dateTo != '') {
                $dateTo = Carbon::createFromFormat('d F Y', '15 ' . $dateTo);
                $dateFrom = $dateTo->copy()->month(1);
            }
            elseif ($dateFrom != '' && $dateTo != '') {
                $dateFrom = Carbon::createFromFormat('d F Y', '15 ' . $dateFrom);
                $dateTo = Carbon::createFromFormat('d F Y', '15 ' . $dateTo);
            }
            $dateFrom->setTime(0, 0, 0);
            $dateTo->setTime(0, 0, 0);

            $empIDs = $request->input('hr_person_id');
            $empsResult = [];
            foreach ($empIDs as $empID) {
                $empResult = (object) [];
                $emp = HRPerson::find($empID);
                $empResult->emp_name = $emp->full_name;

                $rangeResult = [];
                while ($dateFrom->lte($dateTo)) {
                    $monthResult = (object) [];
                    $monthResult->month = $dateFrom->format('M Y');
                    $monthResult->result = AppraisalKPIResult::getEmpMonthAppraisal($empID, $dateFrom->format('M Y'));
                    $rangeResult[] = $monthResult;
                    $dateFrom->addMonth();
                }
                $empResult->apprasail_result = $rangeResult;
                $empsResult[] = $empResult;
            }
            return $empsResult;
        }
        elseif ($reportType == 2) { //return ranking report
            $rankingLimit = $request->input('ranking_limit');
            $rankingType = $request->input('ranking_type');
            $divLevel = $divID = 0;
            $empsResult = [];
            if ($request->input('division_level_1') && $request->input('division_level_1') > 0) {
                $divLevel = 1;
                $divID = $request->input('division_level_1');
            }
            elseif ($request->input('division_level_2') && $request->input('division_level_2') > 0) {
                $divLevel = 2;
                $divID = $request->input('division_level_2');
            }
            elseif ($request->input('division_level_3') && $request->input('division_level_3') > 0) {
                $divLevel = 3;
                $divID = $request->input('division_level_3');
            }
            elseif ($request->input('division_level_4') && $request->input('division_level_4') > 0) {
                $divLevel = 4;
                $divID = $request->input('division_level_4');
            }
            elseif ($request->input('division_level_5') && $request->input('division_level_5') > 0) {
                $divLevel = 5;
                $divID = $request->input('division_level_5');
            }

            if ($rankingType == 1){
                $empsResult = AppraisalGraphsController::empGroupPerformance($divID, $divLevel, true, true, false, [], $rankingLimit);
            }
            elseif ($rankingType == 1) {
                $empsResult = AppraisalGraphsController::empGroupPerformance($divID, $divLevel, true, false, true, [], $rankingLimit);
            }
            //return $empsResult;
            $data['empsResult'] = $empsResult;
            $data['page_title'] = "Appraisal Reports";
            $data['page_description'] = "Generate Employees Appraisal Reports";
            $data['breadcrumb'] = [
                ['title' => 'Performance Appraisal', 'path' => '/appraisal/search', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Reports', 'active' => 1, 'is_module' => 0]
            ];
            $data['active_mod'] = 'Performance Appraisal';
            $data['active_rib'] = 'Reports';
            return view('appraisals.appraisal_report_top_bottom')->with($data);
        }
        elseif ($reportType == 3) { //return divisions report
            if ($request->input('division_level_1') && $request->input('division_level_1') > 0) {

            }
            elseif ($request->input('division_level_2') && $request->input('division_level_2') > 0) {

            }
            elseif ($request->input('division_level_3') && $request->input('division_level_3') > 0) {

            }
            elseif ($request->input('division_level_4') && $request->input('division_level_4') > 0) {

            }
            elseif ($request->input('division_level_5') && $request->input('division_level_5') > 0) {

            }
        }
        //return $request;
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
