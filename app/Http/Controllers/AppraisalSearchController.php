<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\HRPerson;
use App\AppraisalQuery_report;
use App\AppraisalKPIResult;
use App\AppraisalClockinResults;
use App\appraisalsKpis;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Excel;
class AppraisalSearchController extends Controller
{
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
		$kpis = DB::table('appraisals_kpis')
			->select('appraisals_kpis.measurement','appraisals_kpis.id')
			->where('appraisals_kpis.is_upload', 1)
			->orderBy('appraisals_kpis.measurement')
			->get();

        $data['kpis'] = $kpis;
        $data['employees'] = $employees;
        $data['page_title'] = "Employee Appraisals";
        $data['page_description'] = "Load an Employee's Appraisals";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Appraisals', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Appraisals';
        AuditReportsController::store('Performance Appraisal', 'Search page accessed', "Accessed by User", 0);
        return view('appraisals.appraisal_search')->with($data);
    }

    // Search Results
    public function searchResults(Request $request)
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
