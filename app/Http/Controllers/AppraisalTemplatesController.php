<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\JobTitle;
use App\appraisalTemplates;
use App\appraisalCategories;
use App\appraisalKpas;
use App\appraisalsKpis;
use App\JobCategory;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AppraisalTemplatesController extends Controller
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
	# View all templates
    public function viewTemlates()
    {
		$Templates = appraisalTemplates::orderBy('template', 'asc')->get();
		if (!empty($Templates))
			$Templates = $Templates->load('jobTitle');
		$JobTitles = JobTitle::orderBy('name', 'asc')->get();
        $data['page_title'] = "Templates";
        $data['page_description'] = "Manage Templates";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Templates', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'Templates';
        $data['Templates'] = $Templates;
        $data['JobTitles'] = $JobTitles;
		//return $data;
		AuditReportsController::store('Performance Appraisal', 'Templates Page Accessed', "Actioned By User", 0);
        return view('appraisals.templates')->with($data);
    }

	# Act/deac Templates
	public function templateAct(appraisalTemplates $template) 
	{
		if ($template->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$template->status = $stastus;	
		$template->update();
		AuditReportsController::store('Performance Appraisal', "Template Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
	
	# Save Templates 
    public function temlateSave(Request $request)
	{
		$this->validate($request, [
            'template' => 'required',       
            'job_title_id' => 'unique:appraisal_templates,job_title_id',       
        ]);
		$templateData = $request->all();
		unset($templateData['_token']);
		$category = new appraisalTemplates($templateData);
		$category->status = 1;
		$category->template = $templateData['template'];
		$category->job_title_id = $templateData['job_title_id'];
        $category->save();
		$newtemplate = $templateData['template'];
		AuditReportsController::store('Performance Appraisal', 'Template Added', "Category Name: $templateData[template]", 0);
		return response()->json(['new_template' => $newtemplate], 200);
    }
	
	public function editTemplate(Request $request, appraisalTemplates $template)
	{
        $this->validate($request, [
            'template' => 'required',       
            'job_title_id' => 'bail|required|integer|min:0',       
        ]);

        $template->template = $request->input('template');
        $template->job_title_id = $request->input('job_title_id');
        $template->update();
		$newtemplate = $request->input('template');
        AuditReportsController::store('Performance Appraisal', 'Template Informations Edited', "Edited by User", 0);
        return response()->json(['new_template' => $newtemplate], 200);
    }
	# View Template/kpi
	public function viewTemlate(appraisalTemplates $template) 
	{
        if ($template->status == 1) 
		{
			$KpiTypeArray = array(1 => 'Range', 2 => 'Number', 3 => 'From 1 To 10');
			$KpiUploadTypeArray = array(1 => 'General', 2 => 'Clock In', 3 => 'Query Report ');
			
			$kpis = DB::table('appraisals_kpis')
			->select('appraisals_kpis.*','appraisal_categories.name as cat_name', 'appraisal_kpas.name as kpa_name')
			///->leftJoin('appraisal_templates', 'appraisals_kpis.template_id', '=', 'appraisal_templates.id')
			->leftJoin('appraisal_categories', 'appraisals_kpis.category_id', '=', 'appraisal_categories.id')
			->leftJoin('appraisal_kpas', 'appraisals_kpis.kpa_id', '=', 'appraisal_kpas.id')
			->where('appraisals_kpis.template_id', $template->id)
			->orderBy('appraisals_kpis.category_id')
			->orderBy('appraisals_kpis.kpa_id')
			->get();
			$kpaCategories = appraisalCategories::where('status', 1)->orderBy('name', 'desc')->get();
			$kpas = appraisalKpas::where('status', 1)->orderBy('name', 'desc')->get();
			$existingKpis = appraisalsKpis::whereNull('existing_kpi_id')->orWhere('existing_kpi_id', '<', 1)->orderBy('indicator')->get();
			//return $existingKpis;
			
			$data['page_title'] = "KPIs Informations";
			$data['page_description'] = "KPIs Informations";
			$data['breadcrumb'] = [
				['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Templates', 'active' => 1, 'is_module' => 0]];
			$data['kpis'] = $kpis;
			$data['template'] = $template; //
			$data['kpaCategories'] = $kpaCategories; //
			$data['kpas'] = $kpas; //
			$data['KpiTypeArray'] = $KpiTypeArray; //
			$data['KpiUploadTypeArray'] = $KpiUploadTypeArray; //
			$data['existingKpis'] = $existingKpis; //
			$data['active_mod'] = 'Performance Appraisal';
			$data['active_rib'] = 'Templates';
			//return $data;
			AuditReportsController::store('Performance Appraisal', 'KPIS Details Page Accessed', "Accessed by User", 0);
	
			return view('appraisals.kpis')->with($data);
		}
		else 
		{
			AuditReportsController::store('Performance Appraisal', 'KPIS Details Page Accessed', "Accessed by User", 0);
			return back();
		}
    }
	# Act/deac kpi
	public function kpiAct(appraisalsKpis $kpi) 
	{
		if ($kpi->status == 1) $stastus = 0;
		else $stastus = 1;
		$kpi->status = $stastus;	
		$kpi->update();
		AuditReportsController::store('Performance Appraisal', "KPI Status Changed: $stastus", "Edited by User", 0);
		return back();
    }
	
	# Save kpi 
    public function kpiSave(Request $request)
	{
		$this->validate($request, [       
            //'indicator' => 'required',       
            'kpa_id' => 'bail|required|integer|min:0',       
            'category_id' => 'bail|required|integer|min:0',       
            'template_id' => 'bail|required|integer|min:1',       
            //'kpi_type' => 'bail|required|integer|min:1',       
            'is_upload' => 'bail|required|integer|min:0',       
        ]);
		$kpiData = $request->all();
		unset($kpiData['_token']);
		if(!empty($kpiData['existing_kpi_id']))
			$kpis = appraisalsKpis::where('id', $kpiData['existing_kpi_id'])->first();
		
		$kpi = new appraisalsKpis();
		$kpi->status = 1;
		$kpi->measurement = !empty($kpiData['existing_kpi_id']) ? $kpis->measurement : $kpiData['measurement'];
		$kpi->weight = !empty($kpiData['existing_kpi_id']) ? $kpis->weight : $kpiData['weight'];
		$kpi->existing_kpi_id = !empty($kpiData['existing_kpi_id']) ? $kpiData['existing_kpi_id'] : 0;
		$kpi->source_of_evidence = !empty($kpiData['existing_kpi_id']) ? $kpis['source_of_evidence'] : $kpiData['source_of_evidence'] ;
		$kpi->indicator = !empty($kpiData['existing_kpi_id']) ? $kpis['indicator'] : $kpiData['indicator'] ;
		$kpi->category_id = !empty($kpiData['existing_kpi_id']) ? $kpis['category_id'] : $kpiData['category_id'];
		$kpi->kpa_id = !empty($kpiData['existing_kpi_id']) ? $kpis['kpa_id'] : $kpiData['kpa_id'];
		$kpi->kpi_type = !empty($kpiData['existing_kpi_id']) ? $kpis['kpi_type'] : $kpiData['kpi_type'];
		$kpi->is_upload = !empty($kpiData['existing_kpi_id']) ? $kpis['is_upload'] : $kpiData['is_upload'];
		$kpi->template_id = $kpiData['template_id'];
		$kpi->upload_type = !empty($kpiData['existing_kpi_id']) ? $kpis['upload_type'] : $kpiData['upload_type'];
		$kpi->is_task_kpi = !empty($kpiData['existing_kpi_id']) ? $kpis['is_task_kpi'] : $kpiData['is_task_kpi'];
		$kpi->kpi_task_type = !empty($kpiData['existing_kpi_id']) ? $kpis['kpi_task_type'] : (array_key_exists('kpi_task_type', $kpiData) && !empty($kpiData['kpi_task_type'])) ? $kpiData['kpi_task_type'] : null;

		$newkpi = !empty($kpiData['existing_kpi_id']) ? $kpis['indicator'] : $kpiData['indicator'];
        $kpi->save();
		AuditReportsController::store('Performance Appraisal', 'KPI Added', "KPI Details: $kpiData[indicator]", 0);
		return response()->json(['new_kp8' => $newkpi], 200);
    }
	# Edit kpi 
	public function editKpi(Request $request, appraisalsKpis $kpi)
	{
        $this->validate($request, [       
            'indicator' => 'required',       
            'kpa_id' => 'bail|required|integer|min:0',       
            'category_id' => 'bail|required|integer|min:0',       
            'kpi_type' => 'bail|required|integer|min:0',        
            'is_upload' => 'bail|required|integer|min:0',
            'kpi_task_type' => 'bail|required_if:is_task_kpi,1',
            'upload_type' => 'bail|required_if:is_upload,1',
        ]);
		$kpi->measurement = $request->input('measurement');
		$kpi->weight = $request->input('weight');
		$kpi->source_of_evidence = $request->input('source_of_evidence');
		$kpi->indicator = $request->input('indicator');
		$kpi->category_id = $request->input('category_id');
		$kpi->kpa_id = $request->input('kpa_id');
		$kpi->kpi_type = $request->input('kpi_type');
		$kpi->is_upload = $request->input('is_upload');
		$kpi->upload_type = ((int) $request->input('is_upload') === 1) ? $request->input('upload_type') : null;
		$kpi->is_task_kpi = ($request->input('is_upload') == 2) ? !empty($request->input('is_task_kpi')) ? $request->input('is_task_kpi') : null : null;
		$kpi->kpi_task_type = ($request->input('is_upload') == 2 && $request->input('is_task_kpi') == 1) ? $request->input('kpi_task_type') : null;
		$kpi->update();
		$newtemplate = $request->input('indicator');
        AuditReportsController::store('Performance Appraisal', 'KPI Informations Edited', "Edited by User", 0);
        return response()->json(['new_template' => $newtemplate], 200);
    }
		
}