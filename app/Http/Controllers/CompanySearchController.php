<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\contacts_company;
use App\HRPerson;

class CompanySearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['page_title'] = "Companies Search";
        $data['page_description'] = "Companies Search";
        $data['breadcrumb'] = [
            ['title' => 'Client', 'path' => '/contacts', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Companies', 'path' => '/partners/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Companies Search', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Search Company';
        return view('contacts.company_search')->with($data);
    }
	
	public function companySearch(Request $request)
    { 
		$name =$request->company_name; 
		$regNo =$request->reg_no; 
		$VatNo =$request->vat_no; 
		$companies = DB::table('contact_companies')
		->where(function ($query) use ($name) {
			if (!empty($name)) {
				$query->where('name', 'ILIKE', "%$name%");
			}
		})
		->where(function ($query) use ($regNo) {
			if (!empty($regNo)) {
				$query->where('registration_number', 'ILIKE', "%$regNo%");
			}
		})
		->where(function ($query) use ($VatNo) {
			if (!empty($VatNo)) {
				$query->where('vat_number', 'ILIKE', "%$VatNo%");
			}
		})
		->orderBy('contact_companies.name')
		->get();
		$data['companies'] = $companies;
        $data['page_title'] = "Companies Search Results";
        $data['page_description'] = "Companies Search";
        $data['breadcrumb'] = [
            ['title' => 'Companies', 'path' => '/contacts/general_search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/contacts/general_search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
		$companyTypeArray = array(1 => 'Service Provider', 2 => 'School', 3 => 'Sponsor');
		$data['companyTypeArray'] = $companyTypeArray;
		 $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Search Company';
		//return $data;
        return view('contacts.company_search_results')->with($data);
    }
}