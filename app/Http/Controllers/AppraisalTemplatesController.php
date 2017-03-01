<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\JobTitle;
use App\appraisalTemplates;
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
		return back();
    }
	
	# Save Templates 
    public function temlateSave(Request $request)
	{
		$this->validate($request, [
            'template' => 'required',       
            'job_title_id' => 'bail|required|integer|min:0',       
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
	
	# View Template
	public function viewTemlate(appraisalTemplates $template) 
	{
        if ($template->status == 1) 
		{
			$template->load('catJobTitle');
			$data['page_title'] = "Template Informations";
			$data['page_description'] = "Template Informations";
			$data['breadcrumb'] = [
				['title' => 'Performance Appraisal', 'path' => '/appraisal/templates', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Templates', 'active' => 1, 'is_module' => 0]];
			$data['jobTitles'] = $template;
			$data['active_mod'] = 'Performance Appraisal';
			$data['active_rib'] = 'Templates';
			AuditReportsController::store('Performance Appraisal', 'Template Details Page Accessed', "Accessed by User", 0);
			return view('hr.job_titles')->with($data);
		}
		else return back();
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
}
