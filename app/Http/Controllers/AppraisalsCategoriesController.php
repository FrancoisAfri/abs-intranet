<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\appraisalKpas;
use App\appraisalCategories;
use App\JobCategory;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AppraisalsCategoriesController extends Controller
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
	# View all Categories
    public function viewCategories()
    {
		$Categories = appraisalCategories::orderBy('name', 'asc')->get();
        $data['page_title'] = "Categories";
        $data['page_description'] = "Manage Categories";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/categories', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Categories', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'KPA Categories';
        $data['Categories'] = $Categories;
		//return $data;
		AuditReportsController::store('Performance Appraisal', 'Categories Page Accessed', "Actioned By User", 0);
        return view('appraisals.categories')->with($data);
    }

	# Act/deac Category
	public function categoryAct(appraisalCategories $category) 
	{
		if ($category->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$category->status = $stastus;	
		$category->update();
		return back();
    }
	
	# Save Category 
    public function categorySave(Request $request)
	{
		$this->validate($request, [
            'name' => 'required',       
            'weight' => 'bail|required|integer|min:0',       
        ]);
		$categoryData = $request->all();
		unset($categoryData['_token']);
		$category = new appraisalCategories($categoryData);
		$category->status = 1;
		$category->name = $categoryData['name'];
		$category->weight = $categoryData['weight'];
        $category->save();
		$newname = $categoryData['name'];
		AuditReportsController::store('Performance Appraisal', 'Category Added', "Category Name: $categoryData[name]", 0);
		return response()->json(['new_category' => $newname], 200);
    }	
	public function editCategory(Request $request, appraisalCategories $category)
	{
        $this->validate($request, [
            'name' => 'required',       
            'weight' => 'bail|required|integer|min:0',       
        ]);

        $category->name = $request->input('name');
        $category->weight = $request->input('weight');
        $category->update();
		$newtemplate = $request->input('name');
        AuditReportsController::store('Performance Appraisal', 'Category Informations Edited', "Edited by User", 0);
        return response()->json(['new_category' => $newtemplate], 200);
    }
	# View category
	public function viewKpas(appraisalCategories $category) 
	{
        if ($category->status == 1) 
		{
			$category->load('kpascategory');
			$data['page_title'] = "KPAs Informations";
			$data['page_description'] = "KPAs Informations";
			$data['breadcrumb'] = [
				['title' => 'Performance Appraisal', 'path' => '/appraisal/categories', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
				['title' => 'Categories', 'active' => 1, 'is_module' => 0]];
			$data['categories'] = $category;
			$data['active_mod'] = 'Performance Appraisal';
			$data['active_rib'] = 'KPA Categories';
			AuditReportsController::store('Performance Appraisal', 'KPas Details Page Accessed', "Accessed by User", 0);
			return view('appraisals.kpas')->with($data);
		}
		else return back();
    }
	# Act/deac KPAs
	public function kpasAct(appraisalKpas $kpa) 
	{
		if ($kpa->status == 1) $stastus = 0;
		else $stastus = 1;
		
		$kpa->status = $stastus;	
		$kpa->update();
		return back();
    }
	
	# Save KPAs 
    public function kpasSave(Request $request, appraisalCategories $category)
	{
		$this->validate($request, [
            'name' => 'required',       
            'weight' => 'bail|required|integer|min:0',       
        ]);
		if (!empty($category->status))
		{
			$kpaData = $request->all();
			unset($kpaData['_token']);
			$kpa = new appraisalKpas($kpaData);
			$kpa->status = 1;
			$kpa->name = $kpaData['name'];
			$kpa->weight = $kpaData['weight'];
			$kpa->category_id = $category->id;
			$kpa->save();
			$newname = $kpaData['name'];
			AuditReportsController::store('Performance Appraisal', 'KPA Added', "kpa Name: $kpaData[name]", 0);
			return response()->json(['new_kpa' => $newname], 200);
		}
		return back();
    }	
	# Edit KPAs
	public function editKpas(Request $request, appraisalKpas $kpa)
	{
        $this->validate($request, [
            'name' => 'required',       
            'weight' => 'bail|required|integer|min:0',       
        ]);

        $kpa->name = $request->input('name');
        $kpa->weight = $request->input('weight');
        $kpa->update();
		$newtemplate = $request->input('name');
        AuditReportsController::store('Performance Appraisal', 'KPA Informations Edited', "Edited by User", 0);
        return response()->json(['new_category' => $newtemplate], 200);
    }
}