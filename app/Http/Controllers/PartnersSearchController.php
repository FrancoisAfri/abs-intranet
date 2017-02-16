<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\contacts_company;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
class PartnersSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
	
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $data['page_title'] = "Partners Search";
        $data['page_description'] = "Partners Search";
        $data['breadcrumb'] = [
            ['title' => 'Client', 'path' => '/contacts', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Partners', 'path' => '/partners/search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Partners Search', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Partners';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Partners', 'Partners Search Form Viewed ', "Actioned By User", 0);
        return view('contacts.partner_search')->with($data);
    }
	
	public function companySearch(Request $request)
    {
		$companytype =$request->company_type; 
		$name =$request->company_name; 
		$regNo =$request->reg_no; 
		$VatNo =$request->vat_no; 
		$companies = DB::table('contacts_companies')
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
		->where(function ($query) use ($companytype) {
		if (!empty($companytype)) {
			$query->where('contacts_companies.company_type', $companytype);
		}
		})
		->orderBy('contacts_companies.name')
		->get();
		$data['companies'] = $companies;
        $data['page_title'] = "Partners Search Results";
        $data['page_description'] = "Partners Search";
        $data['breadcrumb'] = [
            ['title' => 'Partners', 'path' => '/contacts/general_search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/contacts/general_search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
		$companyTypeArray = array(1 => 'Service Provider', 2 => 'School', 3 => 'Sponsor');
		$data['companyTypeArray'] = $companyTypeArray;
		$data['active_mod'] = 'Partners';
        $data['active_rib'] = 'Search';
		//return $data;
		AuditReportsController::store('Partners', 'Company Search Results Accessed ', "Actioned By User", 0);
        return view('contacts.partner_search_results')->with($data);
    }
}