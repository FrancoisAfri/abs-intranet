<?php

namespace App\Http\Controllers;
use App\CompanyIdentity;
use App\HRPerson;
use App\Http\Requests;
use App\Mail\confirm_collection;
use App\product_category;
use App\stock;
use App\stockhistory;
use App\JobCategory;
use App\product_products;
use App\RequestStock;
use App\RequestStockItems;
use App\StockSettings;
use App\stockLevelFive;
use App\stockLevel;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StockRequest extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}
	
	public $requestStatuses = [1 => 'Awaiting Manager Approval'
	, 2 => 'Awaiting Department Head Approval',3 => 'Awaiting Store Manager Approval'
	, 4=> 'Awaiting CEO Approval Approval', 5=> 'Declined by Manager'
	, 6 => 'Declined by Head Approval',7 => 'Declined by Store Manager'
	,8 => 'Declined by CEO', 9 => 'Cancelled', 10 => 'Approved', 11 => 'Recieved'
	];
	
	// Application Status Function
	public function ApplicationDetails($status = 0, $hrID = 0, $storeID = 0)
    {
        // query the stock congif table and bring back the values
        $approvals = DB::table('stock_settings')
            ->select('require_managers_approval', 'require_store_manager_approval'
			, 'require_department_head_approval', 'require_ceo_approval')
            ->first();
        // query the hrperon  model and bring back the values of the manager
        $hrDetails = HRPerson::where('id', $hrID)->where('status', 1)->first();

        if ($approvals->require_managers_approval == 1 && $status == 0) 
		{
			if(!empty($hrDetails->manager_id))
			{
				// query the hrperon  model and bring back the values of the manager
				$managerDetails = HRPerson::where('id', $hrDetails->manager_id)->where('status', 1)
					->select('first_name', 'email')
					->first();
				if (!empty($managerDetails->email) && !empty($managerDetails->firstname)) 
				{
					$details = array('status' => 1, 'first_name' => $managerDetails->firstname, 'email' => $managerDetails->email, 'comment' => '');
					return $details;
				}
				else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'Manager details are not correct. Please start by correcting them!');
			}
			else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'No manager has been assigned to this employee. Please start by assigning one!');
        } 
		elseif ($approvals->require_department_head_approval == 1 && $status > 1)
		{
			if(!empty($hrDetails->division_level_4))
			{
				$dept = DivisionLevelFour::where('id', $hrDetails->division_level_4)->where('active', 1)->first();
				if (!empty($dept))
				{
					$dptHeadDetails = HRPerson::where('id', $dept->manager_id)->where('status', 1)
						->select('first_name', 'email')
						->first();
					if (!empty($dptHeadDetails->email) && !empty($dptHeadDetails->first_name))
					{
						$details = array('status' => 2, 'first_name' => $dptHeadDetails->first_name, 'email' => $dptHeadDetails->email, 'comment' => '');
						return $details;
					} 
					else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'User department Head are incoreect!');
				}
				else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'User department does not seem to exist Or has been de-activated!');
			}
			else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'This user has not been assigned to department. Please start by assigning to one!');
        }
		elseif ($approvals->require_store_manager_approval == 1) {
			
			$roles = DB::table('hr_roles')->select('hr_users_roles.hr_id as hr_id')
				->leftJoin('hr_users_roles', 'hr_users_roles.role_id', '=', 'hr_roles.id')
				->where('hr_roles.description', '=','Store Manager')
				->where('hr_roles.status', 1)
				->orderBy('hr_users_roles.hr_id', 'asc')
				->first();
            if ($msamgerDetails == null) {
                $details = array('status' => 1, 'first_name' => $hrDetails->first_name, 'surname' => $hrDetails->surname, 'email' => $hrDetails->email);
                return $details;
            } else {
                // array to store manager details
                $details = array('status' => 3, 'first_name' => $msamgerDetails->firstname, 'surname' => $msamgerDetails->surname, 'email' => $msamgerDetails->email);
                return $details;
            }
        }
		elseif ($approvals->require_ceo_approval == 1) 
		{
			if (!empty($hrDetails->division_level_5))
			{
				$division = DivisionLevelFive::where('id', $hrDetails->division_level_5)->where('active', 1)->first();
				if (!empty($division))
				{
					$divHeadDetails = HRPerson::where('id', $division->manager_id)->where('status', 1)
						->select('first_name', 'email')
						->first();
					if (!empty($divHeadDetails))
					{
						$details = array('status' => 2, 'first_name' => $divHeadDetails->first_name,'email' => $divHeadDetails->email);
						return $details;
					} 
					else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'User division Head are incoreect!');
				}
				else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'User division does not seem to exist Or has been de-activated!');
			}
			else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'This user has not been assigned to Diviion. Please start by assigning to one!');
        }
		else return array('status' => 0, 'first_name' => '', 'email' => '', 'comment' => 'Please go to stock setUp and set approval settings!');
    }
	// Stock Request index Page
	public function create()
    {
		$hrID = Auth::user()->person->id;
		$approvals =StockSettings::select('require_store_manager_approval')->orderBy('id','desc')->first();
		$stockLevelFives =stockLevelFive::where('active',1)->orderBy('name','asc')->get();
		$stockLevel =stockLevel::where('active',1)->where('level',5)->orderBy('level','asc')->first();

		$products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
		$stocks = RequestStock::where('employee_id',$hrID)->orderBy('date_created', 'asc')->get();
		if (!empty($stocks)) $stocks = $stocks->load('stockItems','employees','employeeOnBehalf','requestStatus');

		$employees = DB::table('hr_people')
                ->select('hr_people.*')
                ->where('hr_people.status', 1)
                ->where('hr_people.id', $hrID)
				->orderBy('first_name', 'asc')
				->orderBy('surname', 'asc')
				->get();

		$employeesOnBehalf = DB::table('hr_people')
			->select('hr_people.*')
			->where('hr_people.status', 1)
			->orderBy('first_name', 'asc')
			->orderBy('surname', 'asc')
			->get();
		$data['stockLevel'] = $stockLevel;
		$data['stockLevelFives'] = $stockLevelFives;
		$data['approvals'] = $approvals;
		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
		$data['products'] = $products;
        $data['stocks'] = $stocks;
		$data['requestStatuses'] = $this->requestStatuses;
        $data['page_title'] = 'Items Request';
        $data['page_description'] = 'Request Stock Items';
        $data['breadcrumb'] = [
            ['title' => 'Stock', 'path' => '/stock/request_items', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Request Stock Items', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Request Items';

        AuditReportsController::store('Stock Management', 'Create Request', 'Accessed By User', 0);
        return view('stock.create_request')->with($data);
    }
	
	//#validate checkboxes
    public function store(Request $request) {
        $this->validate($request, [
			'title_name' => 'required',
			'store_id' => 'required',
			'employee_id' => 'required',
			'on_behalf_employee_id' => 'required_if:on_behalf,1',
        ]);
        $stockRequest = $request->all();
        unset($stockRequest['_token']);

		// Save
		$requestStock = new RequestStock();
        $requestStock->employee_id = !empty($stockRequest['employee_id']) ? $stockRequest['employee_id'] : 0;
        $requestStock->on_behalf_of = !empty($stockRequest['on_behalf_of']) ? $stockRequest['on_behalf_of'] : 0;
        $requestStock->on_behalf_employee_id = !empty($stockRequest['on_behalf_employee_id']) ? $stockRequest['on_behalf_employee_id'] : 0;
        $requestStock->date_created = time();
        $requestStock->status = 1;
        $requestStock->title_name = !empty($stockRequest['title_name']) ? $stockRequest['title_name'] : 0;
        $requestStock->store_id = !empty($stockRequest['store_id']) ? $stockRequest['store_id'] : 0;
        $requestStock->request_remarks = !empty($stockRequest['request_remarks']) ? $stockRequest['request_remarks'] : 0;
        $requestStock->save();
		// Save Stock Items
        $numFiles = $index = 0;
        $totalFiles = !empty($stockRequest['total_files']) ? $stockRequest['total_files'] : 0;
        while ($numFiles != $totalFiles) {
            $index++;
			$productID = $request->product_id[$index];
            $quantity = $request->quantity[$index];
            $products = product_products::where('id',$productID)->first();
			$requestStockItems = new RequestStockItems();
			$requestStockItems->product_id = $productID;
			$requestStockItems->category_id = $products->category_id;
			$requestStockItems->quantity = $quantity;
			$requestStockItems->date_added = time();
			$requestStockItems->request_stocks_id = $requestStock->id;
			$requestStockItems->save();
			// next
            $numFiles++;
        }
        AuditReportsController::store('Stock Management', 'Stock Request Created', 'Created by User', 0);
        return response()->json();
    }
	
	// View Request items
	public function viewRequest(RequestStock $stock, $back='')
    {
		//echo $back;
		if (!empty($stock)) $stock = $stock->load('stockItems','stockItems.products','stockItems.categories','employees','employeeOnBehalf','requestStatus');
		//return $stock;
		$hrID = Auth::user()->person->id;
		$approvals =StockSettings::select('require_store_manager_approval')->orderBy('id','desc')->first();
		$stockLevelFives =stockLevelFive::where('active',1)->orderBy('name','asc')->get();
		$stockLevel =stockLevel::where('active',1)->where('level',5)->orderBy('level','asc')->first();

		$products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
		
		$employees = DB::table('hr_people')
                ->select('hr_people.*')
                ->where('hr_people.status', 1)
                ->where('hr_people.id', $hrID)
				->orderBy('first_name', 'asc')
				->orderBy('surname', 'asc')
				->get();

		$employeesOnBehalf = DB::table('hr_people')
			->select('hr_people.*')
			->where('hr_people.status', 1)
			->orderBy('first_name', 'asc')
			->orderBy('surname', 'asc')
			->get();
			
		if (!empty($back)) $data['back'] = "/stock/seach_request";
		else $data['back'] = '';
		$data['stockLevel'] = $stockLevel;
		$data['stockLevelFives'] = $stockLevelFives;
		$data['approvals'] = $approvals;
		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
		$data['products'] = $products;
        $data['stock'] = $stock;
		$data['requestStatuses'] = $this->requestStatuses;
        $data['page_title'] = 'Items Request';
        $data['page_description'] = 'Request Stock Items';
        $data['breadcrumb'] = [
            ['title' => 'Stock', 'path' => '/stock/request_items', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Request Stock Items', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Request Items';

        AuditReportsController::store('Stock Management', 'View Request', 'Accessed By User', 0);
        return view('stock.view_request')->with($data);
    }
	
	// Cancel Request
	public function cancelRequest(Request $request, RequestStock $stock)
    {
        if ($stock && in_array($stock->status, [2, 3, 4, 5])) {
            $this->validate($request, [
                'cancellation_reason' => 'required'
            ]);
            $user = Auth::user()->load('person');
            $stock->status = 10;
            $stock->canceller_id = $user->person->id;
            $stock->cancellation_reason = $request->input('cancellation_reason');
            $stock->update();

            return response()->json(['success' => 'Request application successfully cancelled.'], 200);
        }
    }
	//update
	public function updateRequest(Request $request, RequestStock $stock) {
        $this->validate($request, [
			'title_name' => 'required',
			'store_id' => 'required',
			'employee_id' => 'required',
			'on_behalf_employee_id' => 'required_if:on_behalf,1',
        ]);
        $stockRequest = $request->all();
        unset($stockRequest['_token']);

		// Update
        $stock->employee_id = !empty($stockRequest['employee_id']) ? $stockRequest['employee_id'] : 0;
        $stock->on_behalf_of = !empty($stockRequest['on_behalf_of']) ? $stockRequest['on_behalf_of'] : 0;
        $stock->on_behalf_employee_id = !empty($stockRequest['on_behalf_employee_id']) ? $stockRequest['on_behalf_employee_id'] : 0;
        $stock->date_created = time();
        $stock->status = 1;
        $stock->title_name = !empty($stockRequest['title_name']) ? $stockRequest['title_name'] : 0;
        $stock->store_id = !empty($stockRequest['store_id']) ? $stockRequest['store_id'] : 0;
        $stock->request_remarks = !empty($stockRequest['request_remarks']) ? $stockRequest['request_remarks'] : 0;
        $stock->update();
		// Save Stock Items
        $numFiles = $index = 0;
        $totalFiles = !empty($stockRequest['total_files']) ? $stockRequest['total_files'] : 0;
        while ($numFiles != $totalFiles) {
            $index++;
			$productID = $request->product_id[$index];
            $quantity = $request->quantity[$index];
            $products = product_products::where('id',$productID)->first();
			$requestStockItems = new RequestStockItems();
			$requestStockItems->product_id = $productID;
			$requestStockItems->category_id = $products->category_id;
			$requestStockItems->quantity = $quantity;
			$requestStockItems->date_added = time();
			$requestStockItems->request_stocks_id = $stock->id;
			$requestStockItems->save();
			// next
            $numFiles++;
        }
        AuditReportsController::store('Stock Management', 'Stock Request Updated', 'Updated by User', 0);
        return response()->json();
    }
	
	// remove items
	public function removeItems(Request $request, RequestStockItems $item)
    {
		$stockID = $item->request_stocks_id;
        $item->delete();

        AuditReportsController::store('Stock Management', 'Requested Item Removed', "Removed By User");
        return response()->json();

    }
	public function requestSearch()
    {
        $hrID = Auth::user()->person->id;
		$approvals = StockSettings::select('require_store_manager_approval')->orderBy('id','desc')->first();
		$stockLevelFives = stockLevelFive::where('active',1)->orderBy('name','asc')->get();
		$stockLevel = stockLevel::where('active',1)->where('level',5)->orderBy('level','asc')->first();
		$products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();

		$employees = DB::table('hr_people')
                ->select('hr_people.*')
                ->where('hr_people.status', 1)
				->orderBy('first_name', 'asc')
				->orderBy('surname', 'asc')
				->get();

		$employeesOnBehalf = DB::table('hr_people')
			->select('hr_people.*')
			->where('hr_people.status', 1)
			->orderBy('first_name', 'asc')
			->orderBy('surname', 'asc')
			->get();
		$data['stockLevel'] = $stockLevel;
		$data['stockLevelFives'] = $stockLevelFives;
		$data['approvals'] = $approvals;
		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
		$data['products'] = $products;
		$data['page_title'] = 'Search Request';
        $data['page_description'] = 'Search Request Items';
        $data['breadcrumb'] = [
            ['title' => 'Stock', 'path' => '/stock/seach_request', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search Request', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Search Request';

        AuditReportsController::store('Stock Management', 'Job Card card search Page Accessed', "Job Card card search Page Accessed", 0);
        return view('stock.search_request')->with($data);
    }
	// Search request
	
	// Search results
	
	public function requestResults(Request $request)
    {
        $SysData = $request->all();
        unset($SysData['_token']);
        $storeID = $request['store_id'];
        $titleName = $request['title_name'];
        $employeeID = $request['employee_id'];
        $onBehalf = $request['on_behalf_employee_id'];
        $status = $request['status'];
        $actionFrom = $actionTo = 0;
        $actionDate = $request['requested_date'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $stocks = DB::table('request_stocks')
            ->select('request_stocks.*','hr_people.first_name as firstname'
			, 'hr_people.surname as surname'
			, 'hp.first_name as hp_first_name', 'hp.surname as hp_surname'
			, 'stock_level_fives.name as store_name'
			, 'stock_approvals_levels.step_name as status_name')
            ->leftJoin('hr_people', 'request_stocks.employee_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hp', 'request_stocks.on_behalf_employee_id', '=', 'hp.id')
            ->leftJoin('stock_approvals_levels','request_stocks.status', '=', 'stock_approvals_levels.id')
            ->leftJoin('stock_level_fives', 'request_stocks.store_id', '=', 'stock_level_fives.id')
			->where(function ($query) use ($storeID) {
                if (!empty($storeID)) {
                    $query->where('request_stocks.store_id', $storeID);
                }
            })
            ->where(function ($query) use ($titleName) {
                if (!empty($titleName)) {
                    $query->where('request_stocks.title_name', 'ILIKE', "%$titleName%");
                }
            })
            ->where(function ($query) use ($employeeID) {
                if (!empty($employeeID)) {
                    $query->where('request_stocks.employee_id', $employeeID);
                }
            })
			->where(function ($query) use ($onBehalf) {
                if (!empty($onBehalf)) {
                    $query->where('request_stocks.on_behalf_employee_id', $onBehalf);
                }
            })
			->where(function ($query) use ($status) {
                if (!empty($status)) {
                    $query->where('request_stocks.status', $status);
                }
            })
			->where(function ($query) use ($actionFrom, $actionTo) {
				if ($actionFrom > 0 && $actionTo > 0) {
						$query->whereBetween('request_stocks.date_created', [$actionFrom, $actionTo]);
				}
			})
            ->orderBy('request_stocks.id', 'asc')
            ->get();
        $data['stocks'] = $stocks;
        $data['page_title'] = "Request Search Results";
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Search Request';
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/seach_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Request Search ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Job Card Management';
        $data['active_rib'] = 'Search';
//return $stocks;
        AuditReportsController::store('Job Card Management', 'Job Card Search Page Accessed', "Job Card card search Page Accessed", 0);
        return view('stock.search_request_result')->with($data);
    }
}