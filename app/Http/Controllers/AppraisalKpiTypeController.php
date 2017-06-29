<?php

namespace App\Http\Controllers;

use App\AppraisalKPIIntRange;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\appraisalsKpis;
use App\appraisalsKpiRange;
use App\appraisalsKpiNumber;
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
	// Range View
    public function kpiRange(appraisalsKpis $kpi)
    {
        if ($kpi->status == 1) 
		{
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
			//return $data;
			return view('appraisals.kpi_range')->with($data);
		}
		else 
		{
			AuditReportsController::store('Performance Appraisal', 'KPI Ranges Details Page Accessed', "Accessed by User", 0);
			return back();
		}
    }

    // Range Save
    public function kpiAddRange(Request $request)
    {
        $this->validate($request, [      
            'range_to' => 'bail|required|integer|min:0',       
            'range_from' => 'bail|required|integer|min:0',       
            'percentage' => 'bail|required|integer|min:-50',       
            'kpi_id' => 'bail|required|integer|min:0',       
        ]);
		$rangeData = $request->all();
		unset($rangeData['_token']);
        
		$range = new appraisalsKpiRange($rangeData);
		$range->status = 1;
        $range->save();
		AuditReportsController::store('Performance Appraisal', 'Range Added', "By user", 0);
		return response()->json();
    }

    //Range Edit
    public function kpiEditRange(Request $request, appraisalsKpiRange $range)
    {
        $this->validate($request, [       
           'range_to' => 'bail|required|integer|min:0',       
            'range_from' => 'bail|required|integer|min:0',       
            'percentage' => 'bail|required|integer|min:0',        
        ]);
		$range->range_to = $request->input('range_to');
		$range->range_from = $request->input('range_from');
		$range->percentage = $request->input('percentage');
		
        $range->update();
        AuditReportsController::store('Performance Appraisal', 'KPI Range Informations Edited', "Edited by User", 0);
        return response()->json();
    }
	//Range Act/deact
	public function rangeAct(appraisalsKpiRange $range) 
	{
		if ($range->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$range->status = $stastus;	
		$range->update();
		AuditReportsController::store('Performance Appraisal', "KPI Range Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
	// View Number type
	public function kpiNumber(appraisalsKpis $kpi)
    {
        if ($kpi->status == 1) 
		{
			$numbers = DB::table('appraisals_kpi_numbers')
			->select('appraisals_kpi_numbers.*')
			->leftJoin('appraisals_kpis', 'appraisals_kpi_numbers.kpi_id', '=', 'appraisals_kpis.id')
			->where('appraisals_kpi_numbers.kpi_id', $kpi->id)
			->orderBy('appraisals_kpi_numbers.kpi_id')
			->get();
			$data['page_title'] = "KPI Ranges";
			$data['page_description'] = "KPI Ranges";
			$data['breadcrumb'] = [
				['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Templates', 'active' => 1, 'is_module' => 0]];
			$data['numbers'] = $numbers;
			$data['kpi'] = $kpi;
			$data['active_mod'] = 'Performance Appraisal';
			$data['active_rib'] = 'Templates';
			AuditReportsController::store('Performance Appraisal', 'KPI Number Details Page Accessed', "Accessed by User", 0);
			//return $data;
			return view('appraisals.kpi_number')->with($data);
		}
		else 
		{
			AuditReportsController::store('Performance Appraisal', 'KPI Ranges Details Page Accessed', "Accessed by User", 0);
			return back();
		}
    }
	// Number Save
    public function kpiAddNumber(Request $request)
    {
        $this->validate($request, [      
            'min_number' => 'bail|required|integer|min:0',       
            'max_number' => 'bail|required|integer|min:0',      
            'kpi_id' => 'bail|required|integer|min:0',       
        ]);
		$numberData = $request->all();
		unset($numberData['_token']);
		$number = new appraisalsKpiNumber($numberData);
		$number->status = 1;
        $number->save();
		AuditReportsController::store('Performance Appraisal', 'Number Added', "By user", 0);
		return response()->json();
    }
	//Number Edit
	public function kpiEditNumber(Request $request, appraisalsKpiNumber $number)
    {
        $this->validate($request, [       
          'min_number' => 'bail|required|integer|min:0',       
          'max_number' => 'bail|required|integer|min:0', 	        
        ]);
		$number->min_number = $request->input('min_number');
		$number->max_number = $request->input('max_number');
		
        $number->update();
        AuditReportsController::store('Performance Appraisal', 'KPI Number Informations Edited', "Edited by User", 0);
        return response()->json();
    }
	//Number Act/deact
	public function numberAct(appraisalsKpiNumber $number) 
	{
		if ($number->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$number->status = $stastus;	
		$number->update();
		AuditReportsController::store('Performance Appraisal', "KPI Number Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
    // View Integer Score Range
    public function kpiIntegerRange(appraisalsKpis $kpi)
    {
        if ($kpi->status == 1)
        {
            $kpi->load('kpiIntScore', 'kpiTemplate');
            $data['page_title'] = "KPI Integer Score";
            $data['page_description'] = "KPI Integer Range Score";
            $data['breadcrumb'] = [
                ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Templates', 'active' => 1, 'is_module' => 0]
            ];
            $data['kpi'] = $kpi;
            $data['active_mod'] = 'Performance Appraisal';
            $data['active_rib'] = 'Templates';
            AuditReportsController::store('Performance Appraisal', 'KPI Integer Score Range Details Page Accessed', "Accessed by User", 0);
            return view('appraisals.kpi_int_range')->with($data);
        }
        else
        {
            AuditReportsController::store('Performance Appraisal', 'KPI Integer Score Range Details Page Accessed', "Accessed by User", 0);
            return back();
        }
    }
    //Save Integer Score Range
    public function kpiAddIntegerScoreRange(Request $request, appraisalsKpis $kpi)
    {
        $this->validate($request, [
            'score' => 'bail|required|integer|min:0',
            'percentage' => 'bail|required|numeric|min:0',
        ]);
        $scoreData = $request->all();
        $score = new AppraisalKPIIntRange($scoreData);
        $score->status = 1;
        $kpi->addKPIIntRange($score);
        AuditReportsController::store('Performance Appraisal', 'KPI Integer Score Added', "By user", 0);
        return response()->json(['scoreID' => $score->id]);
    }
    //update integer score range
    public function kpiEditIntegerScoreRange(Request $request, AppraisalKPIIntRange $score)
    {
        $this->validate($request, [
            'score' => 'bail|required|integer|min:0',
            'percentage' => 'bail|required|numeric|min:0',
        ]);
        $scoreData = $request->all();
        $score->update($scoreData);
        AuditReportsController::store('Performance Appraisal', 'KPI Integer Score Information Edited', "Edited by User", 0);
        return response()->json();
    }
    //Integer Score Act/deactivate
    public function actIntegerScoreRange(AppraisalKPIIntRange $score)
    {
        $status = ($score->status === 1) ? 0 : 1;

        $score->status = $status;
        $score->update();
        AuditReportsController::store('Performance Appraisal', "KPI Integer Score Status Changed: $status", "Edited by User", 0);
        return back();
    }
}
