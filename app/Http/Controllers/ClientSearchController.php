<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\contacts_company;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
class ClientSearchController extends Controller
{
	//die('heree');
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
        $data['page_title'] = "Client Search";
        $data['page_description'] = "Client Search";
        $data['breadcrumb'] = [
            ['title' => 'Client', 'path' => '/contacts', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/contacts/general_search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Client Search', 'active' => 1, 'is_module' => 0]
        ];
		
		$schools = contacts_company::where('company_type', 2)->where('status', 1)->orderBy('name')->get();
		
        $data['schools'] = $schools;
        $data['active_mod'] = 'clients';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Clients', 'Search for a client', "view infos", 0);
        return view('contacts.client_search')->with($data);
    }
	public function educatorSearch(Request $request)
    {
		$name = $request->educator_name;
		$educatorID = $request->educator_ID;
		$cellPhone = $request->educator_number;
		
		$educators = DB::table('educators')
		->where(function ($query) use ($educatorID) {
			if (!empty($educatorID)) {
				$query->where('id_number', 'ILIKE', "%$educatorID%");
			}
		})
		->where(function ($query) use ($name) {
			if (!empty($name)) {
				$query->where('first_name', 'ILIKE', "%$name%");
				$query->orWhere('surname', 'ILIKE', "%$name%");
			}
		})
		->where(function ($query) use ($cellPhone) {
			if (!empty($cellPhone)) {
				$query->where('cell_number', 'ILIKE', "%$cellPhone%");
			}
		})
		->orderBy('educators.first_name')
		->get();
		$data['page_title'] = "Educators Search Results";
        $data['page_description'] = "Educators Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts/general_search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/contacts/general_search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['educators'] = $educators;
		//return $data;
        $data['active_mod'] = 'Clients';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Clients', 'View Educator Search Results', "view Search results", 0);
        return view('contacts.educator_search')->with($data);
    }
	public function publicSearch(Request $request)
    {
		$name = $request->public_name;
		$publicNumber = $request->public_number;
		$publicCellNumber = $request->public_cell_number;
		
		$publicReg = DB::table('public_regs')
		->where(function ($query) use ($publicNumber) {
			if (!empty($publicNumber)) {
				$query->where('id_number', 'ILIKE', "%$publicNumber%");
			}
		})
		->where(function ($query) use ($name) {
			if (!empty($name)) {
				$query->where('names', 'ILIKE', "%$name%");
			}
		})
		->where(function ($query) use ($publicCellNumber) {
			if (!empty($publicCellNumber)) {
				$query->where('cell_number', 'ILIKE', "%$publicCellNumber%");
			}
		})
		->orderBy('public_regs.names')
		->get();
		$data['publicsRegs'] = $publicReg;
        $data['page_title'] = "Public Registration Search Results";
        $data['page_description'] = "Public Registration Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts/general_search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/contacts/general_search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Clients';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Clients', 'View Public Search Results', "view Search results", 0);
        return view('contacts.public_search')->with($data);
    }
	public function groupSearch(Request $request)
    {
		$startFrom = $startTo = 0;
		$schoolID = $request->school_id;
		$dateAttended = $request->date_attended;
		if (!empty($dateAttended))
		{
			$startExplode = explode('-', $startDate);
			$startFrom = strtotime($startExplode[0]);
			$startTo = strtotime($startExplode[1]);
		}
		if (!empty($endDate))
		{
			$endExplode = explode('-', $endDate);
			$endFrom = strtotime($endExplode[0]);
			$endTo = strtotime($endExplode[1]);
		}
		$groups = DB::table('nsw_stxes')
		->leftJoin('contacts_companies', 'nsw_stxes.school_id', '=', 'contacts_companies.id')
		->where(function ($query) use ($startFrom, $startTo) {
		if ($startFrom > 0 && $startTo  > 0) {
			$query->whereBetween('nsw_stxes.date_attended', [$startFrom, $startTo]);
		}
		})
		->where(function ($query) use ($schoolID) {
		if (!empty($schoolID)) {
			$query->where('nsw_stxes.school_id', $schoolID);
		}
		})
		->orderBy('nsw_stxes.id')
		->get();
		$data['groups'] = $groups;
        $data['page_title'] = "Group Learner Search Results";
        $data['page_description'] = "Group Learner Search";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts/general_search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/contacts/general_search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Clients';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Clients', 'View Group Search Results', "view Search results", 0);
        return view('contacts.group_search')->with($data);
    }
	public function LearnerSearch(Request $request)
    {
		$name = $request->learner_name;
		$learnerID = $request->learner_id;
		$learnerCellNumber = $request->learner_number;
		
		$learners = DB::table('learners')
		->where(function ($query) use ($learnerID) {
			if (!empty($learnerID)) {
				$query->where('id_number', 'ILIKE', "%$learnerID%");
			}
		})
		->where(function ($query) use ($name) {
			if (!empty($name)) {
				$query->where('first_name', 'ILIKE', "%$name%");
				$query->orWhere('surname', 'ILIKE', "%$name%");
			}
		})
		->where(function ($query) use ($learnerCellNumber) {
			if (!empty($learnerCellNumber)) {
				$query->where('cell_number', 'ILIKE', "%$learnerCellNumber%");
			}
		})
		->orderBy('learners.first_name')
		->get();
		$data['learners'] = $learners;
		$data['page_title'] = "Learner Search Results";
        $data['page_description'] = "Learner Search";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts/general_search', 'icon' => 'fa fa-graduation-cap', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'path' => '/contacts/general_search', 'icon' => 'fa fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Clients';
        $data['active_rib'] = 'Search';
		AuditReportsController::store('Clients', 'View Learner Search Results', "view Search results", 0);
        return view('contacts.learner_search')->with($data);
    }
}
