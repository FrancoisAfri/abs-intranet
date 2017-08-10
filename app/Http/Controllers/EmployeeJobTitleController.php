<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\JobTitle;
use App\JobCategory;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeJobTitleController extends Controller
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
		$jobCategories = JobCategory::orderBy('name', 'asc')->get();
		if (!empty($jobCategories))
			$jobCategories = $jobCategories->load('catJobTitle');
        $data['page_title'] = "Job Titles";
        $data['page_description'] = "Manage Job Titles";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/hr/job_titles', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Job Titles', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Job Titles';
        $data['jobCategories'] = $jobCategories;
		
		AuditReportsController::store('Employee Records', 'Job titles Page Accessed', "Actioned By User", 0);
        return view('hr.job_titles_categories')->with($data);
    }
	public function jobView(JobCategory $jobCategory) 
	{
        if ($jobCategory->status == 1) 
		{
			$jobCategory->load('catJobTitle');
			$data['page_title'] = "Manage Job Titles";
			$data['page_description'] = "Job Titles page";
			$data['breadcrumb'] = [
				['title' => 'Employee Records', 'path' => '/users/setup', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Job Titles', 'active' => 1, 'is_module' => 0]];
			$data['jobTitles'] = $jobCategory;
			$data['active_mod'] = 'Employee Records';
			$data['active_rib'] = 'Job Titles';
			AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
			return view('hr.job_titles')->with($data);
		}
		else return back();
    } 
	public function categoryAct(JobCategory $jobCategory) 
	{
		if ($jobCategory->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$jobCategory->status = $stastus;	
		$jobCategory->update();
		return back();
    }
	public function jobtitleAct(JobTitle $jobTitle) 
	{
		if ($jobTitle->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$jobTitle->status = $stastus;	
		$jobTitle->update();
		return back();
    }
    public function categorySave(Request $request)
	{
		$this->validate($request, [
            'name' => 'required',
            'description' => 'required',        
        ]);
		$categoryData = $request->all();
		unset($categoryData['_token']);
		$category = new JobCategory($categoryData);
		$category->status = 1;
		$category->name = $categoryData['name'];
		$category->description = $categoryData['description'];
        $category->save();
		$newName = $categoryData['name'];
		$newDescription = $categoryData['description'];
		AuditReportsController::store('Employee Records', 'Job Title Category Added', "Category Name: $categoryData[name]", 0);
		return response()->json(['new_name' => $newName, 'new_description' => $newDescription], 200);
    }
    

	public function addJobTitle(Request $request, JobCategory $jobCategory) {
	
		$this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        
        ]);
		$jobTitleData = $request->all();
		unset($jobTitleData['_token']);
		$jobTitle = new JobTitle($jobTitleData);
		$jobTitle->status = 1;
		$jobTitle->category_id = $jobCategory->id;
		$jobTitle->name = $jobTitleData['name'];
		$jobTitle->description = $jobTitleData['description'];
        $jobTitle->save();
		$newName = $jobTitleData['name'];
		$newDescription = $jobTitleData['description'];
		AuditReportsController::store('Employee Records', 'Job Title Added', "Job Title: $jobTitleData[name]", 0);
		return response()->json(['new_name' => $newName, 'new_description' => $newDescription], 200);
	}
	public function editCategory(Request $request, JobCategory $jobCategory)
	{
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $jobCategory->name = $request->input('name');
        $jobCategory->description = $request->input('description');
        $jobCategory->update();
        AuditReportsController::store('Employee Records', 'Category Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $jobCategory->name, 'new_description' => $jobCategory->description], 200);
    }
    public function editJobTitle(Request $request, JobTitle $jobTitle)
    {
       $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $jobTitle->name = $request->input('name');
        $jobTitle->description = $request->input('description');
        $jobTitle->update();
		AuditReportsController::store('Employee Records', 'Job Title Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $jobTitle->name, 'new_path' => $jobTitle->description], 200);
    }

    #
   
    #
}
