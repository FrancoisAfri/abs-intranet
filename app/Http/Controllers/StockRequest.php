<?php

namespace App\Http\Controllers;
use App\CompanyIdentity;
use App\HRPerson;
use App\Http\Requests;
use App\Mail\confirm_collection;
use App\product_category;
use App\stock;
use App\HRRoles;
use App\stockhistory;
use App\HRUserRoles;
use App\JobCategory;
use App\product_products;
use App\RequestStock;
use App\RequestStockItems;
use App\StockSettings;
use App\stockLevelFive;
use App\Stock_Approvals_level;
use App\stockLevel;
use App\Users;
use App\product_price;
use App\Mail\stockApprovals;
use App\Mail\stockRequestRejected;
use App\Mail\StockRequestApproved;
use App\Mail\stockDeliveryNote;
use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\DivisionLevelOne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

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
		
		$flow = Stock_Approvals_level::where('status',1)->orderBy('id', 'asc')->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;

		// Save
		$requestStock = new RequestStock();
        $requestStock->employee_id = !empty($stockRequest['employee_id']) ? $stockRequest['employee_id'] : 0;
        $requestStock->on_behalf_of = !empty($stockRequest['on_behalf_of']) ? $stockRequest['on_behalf_of'] : 0;
        $requestStock->on_behalf_employee_id = !empty($stockRequest['on_behalf_employee_id']) ? $stockRequest['on_behalf_employee_id'] : 0;
        $requestStock->date_created = time();
        $requestStock->status = $flowprocee;
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
		$requestStock->request_number = "ST".$requestStock->id;
		$requestStock->invoice_number = "INV".$requestStock->id;
		$requestStock->update();
		
		// get approver ID and send them email
		if (!empty($flow->employee_id))
			$ApproverDetails = HRPerson::where('id', $flow->employee_id)->where('status', 1)->first();
		else
		{
			if (!empty($flow->division_level_1) && empty($flow->employee_id))
			{
				$Dept = DivisionLevelOne::where('id', $flow->division_level_1)->first();
				$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
			}
			elseif(!empty($flow->division_level_2) && empty($flow->division_level_1) && empty($flow->employee_id))
			{
				$Dept = DivisionLevelTwo::where('id', $flow->division_level_2)->first();
				$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
			}
			elseif(!empty($flow->division_level_3) && empty($flow->division_level_2) && empty($flow->employee_id))
			{
				$Dept = DivisionLevelThree::where('id', $flow->division_level_3)->first();
				$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
			}
			elseif(!empty($flow->division_level_4) && empty($flow->division_level_3) && empty($flow->employee_id))
			{
				$Dept = DivisionLevelFour::where('id', $flow->division_level_4)->first();
				$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
			}
			elseif(!empty($flow->division_level_5) && empty($flow->division_level_4) && empty($flow->employee_id))
			{
				$Dept = DivisionLevelFive::where('id', $flow->division_level_5)->first();
				$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();
			}
		}
		if (!empty($ApproverDetails->email))
			Mail::to($ApproverDetails->email)->send(new stockApprovals($ApproverDetails->first_name, $requestStock->request_number));
        AuditReportsController::store('Stock Management', 'Stock Request Created', 'Created by User', 0);
        return response()->json();
    }
	
	// View Request items
	public function viewRequest(RequestStock $stock, $back='', $app='')
    {
		if (!empty($stock)) $stock = $stock->load('stockItems','stockItems.products','stockItems.categories','employees','employeeOnBehalf','requestStatus','rejectedPerson');
		//return $stock;
		$hrID = Auth::user()->person->id;
		$approvals =StockSettings::select('require_store_manager_approval')->orderBy('id','desc')->first();
		$stockLevelFives =stockLevelFive::where('active',1)->orderBy('name','asc')->get();
		$stockLevel =stockLevel::where('active',1)->where('level',5)->orderBy('level','asc')->first();

		$products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
		$flow = Stock_Approvals_level::where('status', 1)->orderBy('id', 'desc')->latest()->first();
		
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
			//return $stock;
		$collectionDocument = $stock->collection_document;
		$data['back'] = '';
		if (!empty($back) && empty($app)) $data['back'] = "/stock/seach_request";
		if (!empty($app)) $data['back'] = "stock";
		$data['collection_document'] = (!empty($collectionDocument)) ? Storage::disk('local')->url("Stock/Collection_document/$collectionDocument") : '';

		$data['stockLevel'] = $stockLevel;
		$data['stockLevelFives'] = $stockLevelFives;
		$data['flow'] = $flow;
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
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Search Request';
		
        AuditReportsController::store('Stock Management', 'Job Card Search Page Accessed', "Job Card card search Page Accessed", 0);
        return view('stock.search_request_result')->with($data);
    }
	// Request Approval landing page
	public function requestApprovals()
    {
		$roleArray = array();
        $user_id = Auth::user()->person->user_id;
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'stock_management')
            ->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $user_id)
            ->pluck('user_id')->first();

        $processflow = Stock_Approvals_level::where('status', 1)->Where('employee_id',$user_id)->orderBy('id', 'asc')
		->first();

        $lastProcess = Stock_Approvals_level::where('status', 1)->orderBy('id', 'desc')->first();
        $lastStepNumber = !empty($lastProcess->step_number) ? $lastProcess->step_number : 0;

        $statuses = array();
        $status = '';
        $rowcolumn = $processflow->count();
        if ($rowcolumn > 0 || !empty($userAccess)) 
		{
            if (empty($userAccess))
			{
                foreach ($processflow as $process) {
                    $status .= $process->step_number . ',';
                }
                $status = rtrim($status, ",");
                $statuses = (explode(",", $status));
            }

            $stocks = DB::table('request_stocks')
                ->select('request_stocks.*','hr_people.first_name as firstname', 'hr_people.surname as surname'
				,'hp.first_name as hp_firstname', 'hp.surname as hp_surname'
				, 'stock_approvals_levels.step_name as step_name'
				, 'stock_level_fives.name as store_name')
               ->leftJoin('hr_people', 'request_stocks.employee_id', '=', 'hr_people.id')
                ->leftJoin('hr_people as hp', 'request_stocks.on_behalf_employee_id', '=', 'hp.id')
				->leftJoin('stock_approvals_levels', 'request_stocks.status', '=', 'stock_approvals_levels.step_number')
				->leftJoin('stock_level_fives', 'request_stocks.store_id', '=', 'stock_level_fives.id')
                ->where(function ($query) use ($statuses) {
                    if (!empty($statuses)) {
                        $query->whereIn('request_stocks.status', $statuses);
                    }
                })
                ->where(function ($query) use ($lastStepNumber) {
                    if (!empty($lastStepNumber)) {
                        $query->where('request_stocks.status', '!=', $lastStepNumber);
                    }
                })
                ->orderBy('request_stocks.date_created', 'desc')
                ->get();

            $steps = Stock_Approvals_level::latest()->first();
            $stepnumber = !empty($steps->step_number) ? $steps->step_number : 0;

            $data['page_title'] = "Stock";
            $data['page_description'] = "Request Management";
            $data['breadcrumb'] = [
                ['title' => 'Stock Management', 'path' => 'stock/request_approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Request Approval ', 'active' => 1, 'is_module' => 0]
            ];

            $data['stepnumber'] = $stepnumber;
            $data['stocks'] = $stocks;
            $data['active_mod'] = 'Stock Management';
			$data['active_rib'] = 'Request Approval';
		
            AuditReportsController::store('Stock Management', 'Job Card Approvals Page Accessed', "Accessed By User", 0);
            return view('stock.stock_approval')->with($data);
        }
		else return back()->with('success_edit', "You are not permitted to view this page.");
    }
	// Approve Request
	public function appoveRequest(Request $request)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
		//return $jobcards;
        unset($results['_token']);
        
        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'stockappprove'))) {
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $stockID = $aValue[1];
				$stock = RequestStock::where('id', $stockID)->first();
				$totalPrice = 0;
				// get all product attached to this request and calculate total price pluck('product_id')
				$items = RequestStockItems::where('request_stocks_id',$stockID)->get();
			
				foreach ($items as $item) {
					$product = product_products::where('id',$item->product_id)->first();
					$productPrice = product_price::where('product_product_id',$item->product_id)
							->orderBy('id', 'desc')
							->first();
					$currentPrice = !empty($productPrice->price) 
						? $productPrice->price : !empty($product->price) ? $product->price : 0;
					$totalPrice = $totalPrice + $currentPrice;
				}
				// get max amount allow for this step
				$currentStep = Stock_Approvals_level::where('step_number', $sValue)->where('status', 1)->first();
				$maxAmount = !empty($currentStep->max_amount) ? $currentStep->max_amount : 0; 
				
				if ($totalPrice <= $maxAmount)
				{
					// Approve request
					$steps = Stock_Approvals_level::latest()->first();
					$stock->status = $steps->step_number;
					$stock->update();
					// Stock History and deduct from stock
					foreach ($items as $item) {
						
						$stockA = stock::where('product_id', $item->product_id)->where('store_id',$stock->store_id)->first();
						if (!empty($stockA->id))
						{
							$availblebalance = !empty($stockA->avalaible_stock) ? $stockA->avalaible_stock : 0;
							$transactionbalance = $availblebalance - $item->quantity;

							// Update stock availble balance
							$stockA->avalaible_stock = $transactionbalance;
							$stockA->update();
							// Update stock history
							$category = product_products::where('id',$item->product_id)->first();
							$history = new stockhistory();
							$history->product_id = $item->product_id;
							$history->category_id = $category->category_id;
							$history->avalaible_stock = $transactionbalance;
							$history->action_date = time();
							$history->balance_before = $availblebalance;
							$history->balance_after = $transactionbalance;
							$history->action = 'Stock Items Out';
							$history->user_id = Auth::user()->person->id;
							$history->user_allocated_id = 0;
							$history->save();
						}
					}
					// send email for request approval to request creator
					
					// sendemail to stock controller delivery note
					$jcAttachment = $this->viewDeliveryNote($stock);
					$role = HRRoles::where('description', 'Stock Controller')->first();
					$users = HRUserRoles::where('role_id', $role->id)->pluck('hr_id');
					foreach ($users as $userID) {
						$userDetails = HRPerson::where('id', $userID)->select('first_name', 'email')->first();
						Mail::to($userDetails->email)->send(new stockDeliveryNote($userDetails->first_name, $jcAttachment,$stock->request_number));
					}
					//// Send sms and email to mechanic
					if (!empty($stock->employee_id))
					{
						$empDetails = HRPerson::where('id', $stock->employee_id)->select('first_name', 'email')->first();
						if (!empty($empDetails->email))
						Mail::to($empDetails->email)->send(new StockRequestApproved($empDetails->first_name,$stock->request_number));
					}
				}
				else
				{
					$processflow = Stock_Approvals_level::where('step_number', '>', $sValue)->where('status', 1)->orderBy('step_number', 'asc')->first();
					$stock->status = $processflow->step_number;
					$stock->update();
							// get approver ID and send them email
					if (!empty($processflow->employee_id))
						$ApproverDetails = HRPerson::where('id', $processflow->employee_id)->where('status', 1)->first();
					else
					{
						if (!empty($processflow->division_level_1) && empty($processflow->employee_id))
						{
							$Dept = DivisionLevelOne::where('id', $processflow->division_level_1)->first();
							$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
							->select('first_name', 'surname', 'email')
							->first();
						}
						elseif(!empty($processflow->division_level_2) && empty($processflow->division_level_1) && empty($processflow->employee_id))
						{
							$Dept = DivisionLevelTwo::where('id', $processflow->division_level_2)->first();
							$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
							->select('first_name', 'surname', 'email')
							->first();
						}
						elseif(!empty($processflow->division_level_3) && empty($processflow->division_level_2) && empty($processflow->employee_id))
						{
							$Dept = DivisionLevelThree::where('id', $processflow->division_level_3)->first();
							$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
							->select('first_name', 'surname', 'email')
							->first();
						}
						elseif(!empty($processflow->division_level_4) && empty($processflow->division_level_3) && empty($processflow->employee_id))
						{
							$Dept = DivisionLevelFour::where('id', $processflow->division_level_4)->first();
							$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
							->select('first_name', 'surname', 'email')
							->first();
						}
						elseif(!empty($processflow->division_level_5) && empty($processflow->division_level_4) && empty($processflow->employee_id))
						{
							$Dept = DivisionLevelFive::where('id', $processflow->division_level_5)->first();
							$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
							->select('first_name', 'surname', 'email')
							->first();
						}
					}
					// send email to nextstep employeeID
					if (!empty($ApproverDetails->email))
						Mail::to($ApproverDetails->email)->send(new stockApprovals($ApproverDetails->first_name, $requestStock->request_number));
				}
            }
            // decline
        }
		
		foreach ($results as $sKey => $sValue) 
		{
			if (strlen(strstr($sKey, 'declined_'))) 
			{
				list($sUnit, $iID) = explode("_", $sKey);
				if ($sUnit == 'declined' && !empty($sValue)) {

					$stockReject = RequestStock::where('id', $iID)->first();
					$stockReject->status = 0;
					$stockReject->rejection_reason = $sValue;
					$stockReject->rejected_by = Auth::user()->person->id;;
					$stockReject->rejection_date = time();
					$stockReject->update();

					//// Send sms and email to mechanic
					if (!empty($stockReject->employee_id))
					{
						$empDetails = HRPerson::where('id', $stockReject->employee_id)->select('first_name', 'email')->first();
						if (!empty($empDetails->email))
							Mail::to($empDetails->email)->send(new stockRequestRejected($empDetails->first_name,$stockReject->request_number));
					}
				}
			}
		}

        AuditReportsController::store('Stock Management', 'Job card Approvals Page', "Accessed By User",0);
        return back();
    }
	
	// Approve Request
	public function appoveRequestSingle(RequestStock $stock)
    {
		$totalPrice = 0;
		// get all product attached to this request and calculate total price pluck('product_id')
		$items = RequestStockItems::where('request_stocks_id',$stock->id)->get();
	
		foreach ($items as $item) {
			$product = product_products::where('id',$item->product_id)->first();
			$productPrice = product_price::where('product_product_id',$item->product_id)
					->orderBy('id', 'desc')
					->first();
			$currentPrice = !empty($productPrice->price) 
				? $productPrice->price : !empty($product->price) ? $product->price : 0;
			$totalPrice = $totalPrice + $currentPrice;
		}
		// get max amount allow for this step
		$currentStep = Stock_Approvals_level::where('step_number', $stock->status)->where('status', 1)->first();
		$maxAmount = !empty($currentStep->max_amount) ? $currentStep->max_amount : 0; 
		
		if ($totalPrice <= $maxAmount)
		{
			// Approve request
			$steps = Stock_Approvals_level::latest()->first();
			$stock->status = $steps->step_number;
			$stock->update();
			// Stock History and deduct from stock
			foreach ($items as $item) {
				
				$stockA = stock::where('product_id', $item->product_id)->where('store_id',$stock->store_id)->first();
				if (!empty($stockA->id))
				{
					$availblebalance = !empty($stockA->avalaible_stock) ? $stockA->avalaible_stock : 0;
					$transactionbalance = $availblebalance - $item->quantity;
					// Update stock availble balance
					$stockA->avalaible_stock = $transactionbalance;
					$stockA->update();
					// Update stock history
					$category = product_products::where('id',$item->product_id)->first();
					$history = new stockhistory();
					$history->product_id = $item->product_id;
					$history->category_id = $category->category_id;
					$history->avalaible_stock = $transactionbalance;
					$history->action_date = time();
					$history->balance_before = $availblebalance;
					$history->balance_after = $transactionbalance;
					$history->action = 'Stock Items Out';
					$history->user_id = Auth::user()->person->id;
					$history->user_allocated_id = 0;
					$history->save();
				}
			}
			// send email for request approval to request creator
			
			// sendemail to stock controller delivery note
			$jcAttachment = $this->viewDeliveryNote($stock);
			$role = HRRoles::where('description', 'Stock Controller')->first();
			$users = HRUserRoles::where('role_id', $role->id)->pluck('hr_id');
			foreach ($users as $userID) {
				$userDetails = HRPerson::where('id', $userID)->select('first_name', 'email')->first();
				Mail::to($userDetails->email)->send(new stockDeliveryNote($userDetails->first_name, $jcAttachment,$stock->request_number));
			}
			//// Send sms and email to mechanic
			if (!empty($stock->employee_id))
			{
				$empDetails = HRPerson::where('id', $stock->employee_id)->select('first_name', 'email')->first();
				if (!empty($empDetails->email))
				Mail::to($empDetails->email)->send(new StockRequestApproved($empDetails->first_name,$stock->request_number));
			}
		}
		else
		{
			$processflow = Stock_Approvals_level::where('step_number', '>', $stock->status)->where('status', 1)->orderBy('step_number', 'asc')->first();
			$stock->status = $processflow->step_number;
			$stock->update();
					// get approver ID and send them email
			if (!empty($processflow->employee_id))
				$ApproverDetails = HRPerson::where('id', $processflow->employee_id)->where('status', 1)->first();
			else
			{
				if (!empty($processflow->division_level_1) && empty($processflow->employee_id))
				{
					$Dept = DivisionLevelOne::where('id', $processflow->division_level_1)->first();
					$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
					->select('first_name', 'surname', 'email')
					->first();
				}
				elseif(!empty($processflow->division_level_2) && empty($processflow->division_level_1) && empty($processflow->employee_id))
				{
					$Dept = DivisionLevelTwo::where('id', $processflow->division_level_2)->first();
					$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
					->select('first_name', 'surname', 'email')
					->first();
				}
				elseif(!empty($processflow->division_level_3) && empty($processflow->division_level_2) && empty($processflow->employee_id))
				{
					$Dept = DivisionLevelThree::where('id', $processflow->division_level_3)->first();
					$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
					->select('first_name', 'surname', 'email')
					->first();
				}
				elseif(!empty($processflow->division_level_4) && empty($processflow->division_level_3) && empty($processflow->employee_id))
				{
					$Dept = DivisionLevelFour::where('id', $processflow->division_level_4)->first();
					$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
					->select('first_name', 'surname', 'email')
					->first();
				}
				elseif(!empty($processflow->division_level_5) && empty($processflow->division_level_4) && empty($processflow->employee_id))
				{
					$Dept = DivisionLevelFive::where('id', $processflow->division_level_5)->first();
					$ApproverDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
					->select('first_name', 'surname', 'email')
					->first();
				}
			}
			// send email to nextstep employeeID
			if (!empty($ApproverDetails->email))
				Mail::to($ApproverDetails->email)->send(new stockApprovals($ApproverDetails->first_name, $stock->request_number));
		}
        return back();
    }
	// 	// Approve Request
	public function rejectRequestSingle(Request $request, RequestStock $stock)
    {
		$this->validate($request, [
			'rejection_reason' => 'required',
        ]);
        $stockRequest = $request->all();
        unset($stockRequest['_token']);

		$stock->status = 0;
		$stock->rejection_reason = $stockRequest['rejection_reason'];
		$stock->rejected_by = Auth::user()->person->id;
		$stock->rejection_date = time();
		$stock->update();

		//// Send sms and email to mechanic
		if (!empty($stock->employee_id))
		{
			$empDetails = HRPerson::where('id', $stock->employee_id)->select('first_name', 'email')->first();
			if (!empty($empDetails->email))
				Mail::to($empDetails->email)->send(new stockRequestRejected($empDetails->first_name,$stock->request_number));
		}
        return back();
    }
	//
	public function viewDeliveryNote(RequestStock $stock)
    {
		if (!empty($stock)) $stock = $stock->load('stockItems','stockItems.products','stockItems.categories','employees','employeeOnBehalf');
		
		$data['stock'] = $stock;	

		AuditReportsController::store('Stock Management', 'Delivery Note Printed', "Accessed By User");
		$companyDetails = CompanyIdentity::systemSettings();
		$companyName = $companyDetails['company_name'];
		$user = Auth::user()->load('person');

		$data['support_email'] = $companyDetails['support_email'];
		$data['company_name'] = $companyName;
		$data['full_company_name'] = $companyDetails['full_company_name'];
		$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		$data['file_name'] = 'DeliveryNote';
		$view = view('stock.delivery_note', $data)->render();
		$pdf = resolve('dompdf.wrapper');
		$pdf->getDomPDF()->set_option('enable_html5_parser', true);
		$pdf->loadHTML($view);
		return $pdf->output();
    }
	public function viewDeliveryNotePrint(RequestStock $stock, $isPDF = false)
    {
		if (!empty($stock)) $stock = $stock->load('stockItems','stockItems.products','stockItems.categories','employees','employeeOnBehalf');
		
		$data['stock'] = $stock;	
		AuditReportsController::store('Stock Management', 'Delivery Note Printed', "Accessed By User");
		$companyDetails = CompanyIdentity::systemSettings();
		$companyName = $companyDetails['company_name'];
		$user = Auth::user()->load('person');
		$data['page_title'] = 'Print ';
		$data['page_description'] = 'Delivery Note';
		$data['breadcrumb'] = [
			['title' => 'Stock Management', 'path' => '/stock/request_items', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
			['title' => 'Delivery Note', 'active' => 1, 'is_module' => 0]
		];
		
		$data['active_mod'] = 'Stock Management';
        
		$companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;
		return view('stock.delivery_note')->with($data);
    }
	// delivery/collection
	public function collectRequest()
    {
		$hrID = Auth::user()->id;
		$roles = DB::table('hr_roles')->select('hr_roles.id as role_id')
		 ->leftjoin("hr_users_roles",function($join) use ($hrID) {
                $join->on("hr_roles.id","=","hr_users_roles.role_id")
                    ->on("hr_users_roles.hr_id","=",DB::raw($hrID));
            })
		->where('hr_roles.description', '=','Stock Controller')
		->where('hr_roles.status', 1)
		->orderBy('hr_roles.description', 'asc')
		->first();
		
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'stock_management')->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();

		if ((!empty($roles->role_id)) || !empty($userAccess)) {
			
			$steps = Stock_Approvals_level::latest()->first();
			$stocks = RequestStock::where('status',$steps->step_number)
						->whereNull('request_collected')
						->orderBy('date_created', 'asc')->get();
			if (!empty($stocks)) $stocks = $stocks->load('stockItems','employees','employeeOnBehalf','requestStatus');

			$data['stocks'] = $stocks;
			$data['requestStatuses'] = $this->requestStatuses;
			$data['page_title'] = 'Items Collection';
			$data['page_description'] = 'Stock Request Collection';
			$data['breadcrumb'] = [
				['title' => 'Stock', 'path' => '/stock/request_collection', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
				['title' => 'Request Stock Collection', 'active' => 1, 'is_module' => 0]
			];
			$data['active_mod'] = 'Stock Management';
			$data['active_rib'] = 'Stock Collection';
		}
		else 
		{
            return redirect('/');
        }
        AuditReportsController::store('Stock Management', 'Create Request', 'Accessed By User', 0);
        return view('stock.stoc_collection')->with($data);
    }
	// View Request items coleection
	public function viewRequestCollection(RequestStock $stock)
    {
		if (!empty($stock)) $stock = $stock->load('stockItems','stockItems.products','stockItems.categories','employees','employeeOnBehalf','requestStatus','rejectedPerson');
		//return $stock;
		$hrID = Auth::user()->person->id;
		$approvals =StockSettings::select('require_store_manager_approval')->orderBy('id','desc')->first();
		$stockLevelFives =stockLevelFive::where('active',1)->orderBy('name','asc')->get();
		$stockLevel =stockLevel::where('active',1)->where('level',5)->orderBy('level','asc')->first();

		$products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
		$flow = Stock_Approvals_level::where('status', 1)->orderBy('id', 'desc')->latest()->first();
		
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
		
		$data['back'] = '';
		if (!empty($back) && empty($app)) $data['back'] = "/stock/seach_request";
		if (!empty($app)) $data['back'] = "stock";
		$data['stockLevel'] = $stockLevel;
		$data['stockLevelFives'] = $stockLevelFives;
		$data['flow'] = $flow;
		$data['approvals'] = $approvals;
		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
		$data['products'] = $products;
        $data['stock'] = $stock;
		$data['requestStatuses'] = $this->requestStatuses;
        $data['page_title'] = 'Items Collection';
        $data['page_description'] = 'Request Stock Collection';
        $data['breadcrumb'] = [
            ['title' => 'Stock', 'path' => '/stock/request_collection', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Request Stock Collection', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Stock Collection';
        AuditReportsController::store('Stock Management', 'View Request', 'Accessed By User', 0);
        return view('stock.stoc_collected')->with($data);
    }
	// close Request
	public function closeRequest(Request $request, RequestStock $stock)
    {
		$this->validate($request, [
			 'document' => 'required',
        ]);
		$user = Auth::user();
		$closeData = $request->all();

        //Exclude empty fields from query
        foreach ($closeData as $key => $value)
        {
            if (empty($closeData[$key])) {
                unset($closeData[$key]);
            }
        }
        //Upload task doc
        if ($request->hasFile('document')) {
            $fileExt = $request->file('document')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'xlsx', 'doc', 'xltm']) && $request->file('document')->isValid()) {
                $fileName = time() . "_close_request" . '.' . $fileExt;
                $request->file('document')->storeAs('Stock/Collection_document', $fileName);
                //Update 
				$stock->request_collected = 1;
				$stock->collection_document = $fileName;
				$stock->collection_note = !empty($closeData['comment']) ? $closeData['comment'] : '';
				$stock->update();
            }
        }
		AuditReportsController::store('Stock Management', "Task Ended", "Edited by User", 0);
		return response()->json();
    }
}