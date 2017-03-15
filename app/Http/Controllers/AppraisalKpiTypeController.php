<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\appraisalsKpis;
use App\appraisalsKpiRange;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AppraisalKpiTypeController extends Controller
{ 
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function kpiRange(appraisalsKpis $kpi)
    {
        if ($kpi->status == 1) 
		{
			$ranges = $kpi->load('kpiranges');
			$ranges = DB::table('appraisals_kpi_ranges')
			->select('appraisals_kpi_ranges.*')
			->leftJoin('appraisals_kpis', 'appraisals_kpi_ranges.kpi_id', '=', 'appraisals_kpis.id')
			->where('appraisals_kpi_ranges.kpi_id', $kpi->id)
			->orderBy('appraisals_kpi_ranges.kpi_id')
			->get();
			$data['page_title'] = "KPI Ranges";
			$data['page_description'] = "KPI Ranges";
			$data['breadcrumb'] = [
				['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Templates', 'active' => 1, 'is_module' => 0]];
			$data['ranges'] = $ranges;
			$data['kpi'] = $kpi;
			$data['active_mod'] = 'Performance Appraisal';
			$data['active_rib'] = 'Templates';
			AuditReportsController::store('Performance Appraisal', 'KPI Ranges Details Page Accessed', "Accessed by User", 0);
			return $data;
			return view('appraisals.kpi_range')->with($data);
		}
		else 
		{
			AuditReportsController::store('Performance Appraisal', 'KPI Ranges Details Page Accessed', "Accessed by User", 0);
			return back();
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function kpiAddRange(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewRange($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function kpiEditRange($id)
    {
        //
    }
}
