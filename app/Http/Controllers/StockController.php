<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\HRPerson;
use App\Http\Requests;
use App\Mail\confirm_collection;
use App\product_category;
use App\KitJoinProducts;
use App\stock;
use App\stockLevelFive;
use App\stockhistory;
use App\kitProducts;
use App\JobCategory;
use App\product_packages;
use App\product_products;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function kitIndex()
    {
		$Products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
        $kitProducts = kitProducts::orderBy('name', 'asc')->get();

        $data['kitProducts'] = $kitProducts;
        $data['page_title'] = 'Product Packages';
        $data['page_description'] = 'Manage Product Packages';
        $data['breadcrumb'] = [
            ['title' => 'Stock', 'path' => '/stock/kit_management', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Kit', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Kit Management';
        $data['Products'] = $Products;

        AuditReportsController::store('Stock Management', 'Product Kit Page Accessed', 'Accessed By User', 0);
        return view('stock.product_kit')->with($data);
    }
	public function mystock()
    {
		$stockLevelFives = stockLevelFive::where('active',1)->orderBy('name','asc')->get();
        $jobCategories = product_category::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('id', 'asc')->get();
        $data['jobCategories'] = $jobCategories;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Add Stock';
		$data['stockLevelFives'] = $stockLevelFives;
		
        AuditReportsController::store('Stock Management', 'view Stock Add Page', "Accessed By User", 0);
        return view('stock.search_product')->with($data);
    }

    public function stock(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'bail|required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
		$stockLevelFives = stockLevelFive::where('active',1)->orderBy('name','asc')->get();
        $CategoryID = $SysData['category_id'];
		$productID = isset($SysData['product_id']) ? $SysData['product_id'] : array();
        $stockType = $SysData['stock_type'];

        $stocks = DB::table('Product_products')
            ->select('Product_products.*')
            ->where(function ($query) use ($CategoryID) {
                if (!empty($CategoryID)) {
                    $query->where('Product_products.category_id', $CategoryID);
                }
            })
			->Where(function ($query) use ($productID) {
				if (!empty($productID)) {
                    $query->whereIn('Product_products.id', $productID);
				}
            })
            ->where(function ($query) use ($stockType) {
                if (!empty($stockType)) {
                    $query->where('Product_products.stock_type', $stockType);
                }
            })
            ->orderBy('Product_products.name', 'asc')
            ->get();
        $data['stocks'] = $stocks;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add Stock', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Add Stock';
		$data['stockLevelFives'] = $stockLevelFives;

        AuditReportsController::store('Stock Management', 'Stock Search Page', "Accessed By User", 0);
        return view('stock.stock_results')->with($data);
    }

	public function add_stock(Request $request)
    {
        $this->validate($request, [
        ]);
        $results = $request->all();
        //Exclude empty fields from query

        unset($results['_token']);
        unset($results['emp-list-table_length']);

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }

        foreach ($results as $sKey => $sValue) {
            if (strlen(strstr($sKey, 'newstock_'))) {
				
                list($sUnit, $productID, $CategoryID) = explode("_", $sKey);
                $store = 'storeid' . '_' . $productID;           
                $storeID = isset($request[$store]) ? $request[$store] : 0;
                $row = stock::where('product_id', $productID)->where('store_id', $storeID)->count();
                if ($row > 0) {
					// update stock item
                    $currentstock = stock::where('product_id', $productID)->where('store_id', $storeID)->first();
                    $available = !empty($currentstock->avalaible_stock) ? $currentstock->avalaible_stock : 0;
					$currentstock->avalaible_stock = $available + $sValue;
					$currentstock->update();
                   // Saved product history
                    $history = new stockhistory();
                    $history->product_id = $productID;
                    $history->category_id = $CategoryID;
                    $history->avalaible_stock = $available + $sValue;
                    $history->action_date = time();
                    $history->balance_before = $available;
                    $history->balance_after = $available + $sValue;
                    $history->action = 'Stock Item Adjusted';
                    $history->user_id = Auth::user()->person->id;
                    $history->user_allocated_id = 0;
                    $history->vehicle_id = 0;
                    $history->save();
                }
				else
				{
					// Add stock
					$storck = new stock();
                    $storck->avalaible_stock = $sValue;
					$storck->category_id = $CategoryID;
					$storck->product_id = $productID;
					$storck->store_id = $storeID;
					$storck->status = 1;
					$storck->date_added = time();
					$storck->save();
					// Saved product history
					$history = new stockhistory();
					$history->product_id = $productID;
					$history->category_id = $CategoryID;
					$history->avalaible_stock = $sValue;
					$history->balance_before = 0;
					$history->balance_after = $sValue;
					$history->action_date = time();
					$history->user_id = Auth::user()->person->id;
					$history->action = 'New Stock Item Added';
					$history->user_allocated_id = 0;
					$history->vehicle_id = 0;
					$history->save();
				}
			}
        }
        AuditReportsController::store('Stock Management', 'Stock Item Added ', "Accessed By User", 0);
        return redirect('stock/storckmanagement')->with('success_stock', "Stock's items have been successfully Added To Your Stock.");
    }

    public function takeout()
    {
		$productCategories = product_category::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('id', 'asc')->get();
        $data['productCategories'] = $productCategories;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Allocate Stock';

        AuditReportsController::store('Stock Management', 'view Stock takeout Page', "Accessed By User", 0);
        return view('stock.search_product_out')->with($data);
    }

    public function stockout(Request $request)
    {
         $this->validate($request, [
            'category_id' => 'bail|required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);

        $categoryID = $results['category_id'];
        $productID = isset($results['product_id']) ? $results['product_id'] : array();
		$stockType = $results['stock_type'];
        $user = HRPerson::where('status', 1)->get();
        
		$vehicle = DB::table('vehicle_details')
		->select('vehicle_details.*', 'vehicle_make.name as vehicleMake',
			'vehicle_model.name as vehicleModel', 'vehicle_managemnet.name as vehicleType')
		->leftJoin('vehicle_make', 'vehicle_details.vehicle_make', '=', 'vehicle_make.id')
		->leftJoin('vehicle_model', 'vehicle_details.vehicle_model', '=', 'vehicle_model.id')
		->leftJoin('vehicle_managemnet', 'vehicle_details.vehicle_type', '=', 'vehicle_managemnet.id')
		->orderBy('vehicle_details.id', 'asc')
		->get();

        $stocks = DB::table('stock')
            ->select('stock.*', 'Product_products.*')
            ->leftJoin('Product_products', 'stock.product_id', '=', 'Product_products.id')
            ->where(function ($query) use ($categoryID) {
                if (!empty($categoryID)) {
                    $query->where('stock.category_id', $categoryID);
                }
            })
            ->Where(function ($query) use ($productID) {
				 if (!empty($productID)) {
					$query->whereIn('Product_products.id', $productID);
				 }
            })
			 ->where(function ($query) use ($stockType) {
                if (!empty($stockType)) {
                    $query->where('Product_products.stock_type', $stockType);
                }
            })
            ->get();

        $data['vehicle'] = $vehicle;
        $data['stocks'] = $stocks;
        $data['user'] = $user;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = "Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Allocate Stock';

        AuditReportsController::store('Stock Management', 'view Stock takeout Page', "Accessed By User", 0);
        return view('stock.stock_out')->with($data);
    }

    public function takestockout(Request $request, product_category $category)
    {
        $this->validate($request, [

        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        unset($results['emp-list-table_length']);

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }

        foreach ($results as $sKey => $sValue) {

            if (strlen(strstr($sKey, 'stock_' ))) {
                list($sUnit, $productID,$CategoryID  ) = explode("_", $sKey);
                $user = 'userid' . '_' . $productID;
                $veh = 'vehicle' . '_' . $productID;           
                $UserID = isset($request[$user]) ? $request[$user] : 0;
                $vehicleID = isset($request[$veh]) ? $request[$veh] : 0;

                $currentstock = stock::where('product_id', $productID)->first();
                $available = !empty($currentstock->avalaible_stock) ? $currentstock->avalaible_stock : 0;
                
                if ($available >= $sValue)
				{
					$currentstock->avalaible_stock = $available - $sValue;
					$currentstock->user_id = $UserID;
					$currentstock->vehicle_id = $vehicleID;
					$currentstock->update();
				   
					$history = new stockhistory();
					$history->product_id = $productID;
					$history->category_id = $CategoryID;
					$history->avalaible_stock = $available - $sValue;
					$history->action_date = time();
					$history->user_id = Auth::user()->person->id;
					$history->user_allocated_id = $UserID;
					$history->balance_before = $available;
					$history->balance_after = $available - $sValue;
					$history->action = 'stock taken out';
					$history->vehicle_id = $vehicleID;
					$history->save();
                }
           } 
        }
        AuditReportsController::store('Stock Management', 'Stock taken out', "Accessed By User", 0);
        return redirect('stock/stock_allocation')->with('success_stock', "Items have been successfully Deducted From Your Stock.");
    }

    public function viewreports()
    {
        $parts = stock::Orderby('id', 'asc')->get();
        $productCategories = product_category::orderBy('id', 'asc')->get();
        $history = stockhistory::orderBy('id', 'asc')->get();
        $data['productCategories'] = $productCategories;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Stock Management', 'view Stock takeout Page', "Accessed By User", 0);
        return view('stock.search_reports')->with($data);
    }

    public function searchreport(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'bail|required',
        ]);
        $search = $request->all();
        unset($search['_token']);

        $actionFrom = $actionTo = 0;
        $product = '';
        $productArray = isset($search['product_id']) ? $search['product_id'] : array();
        $actionDate = $request['action_date'];
        $CategoryID = $search['category_id'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $stock = stockhistory::select('stock_history.*','vehicle_details.id as id','vehicle_details.fleet_number as fleet_number'
		,'vehicle_details.vehicle_registration as vehicle_registration' ,'Product_products.name as product_name', 'hr_people.first_name as name',
            'hr_people.surname as surname', 'hr.first_name as allocated_firstname', 'hr.surname as allocated_surname'
               )
            ->leftJoin('hr_people', 'stock_history.user_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hr', 'stock_history.user_allocated_id', '=', 'hr.id')
            ->leftJoin('Product_products', 'stock_history.product_id', '=', 'Product_products.id')
            ->leftJoin('vehicle_details', 'stock_history.vehicle_id', '=', 'vehicle_details.id')
            ->where(function ($query) use ($CategoryID) {
                if (!empty($CategoryID)) {
                    $query->where('stock_history.category_id', $CategoryID);
                }
            })
			->Where(function ($query) use ($productArray) {
				 if (!empty($productArray)) {
					$query->whereIn('stock_history.product_id', $productArray);
				 }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('stock_history.action_date', [$actionFrom, $actionTo]);
                }
            })
            ->orderBy('product_name', 'asc')
            ->orderBy('stock_history.id', 'asc')
            ->get();

        for ($i = 0; $i < count($productArray); $i++) {
            $product .= $productArray[$i] . ',';
        }

        $productsID = rtrim($product, ",");
        $data['product_id'] = rtrim($product, ",");
        $data['CategoryID'] = $CategoryID;
        $data['productsID'] = $productsID;
        $data['action_date'] = $actionDate;
        $data['stock'] = $stock;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Stock Management', 'view Stock takeout Page', "Accessed By User", 0);
        return view('stock.stock_out_results')->with($data);
    }

    public function printreport(Request $request)
    {
        $search = $request->all();
        unset($search['_token']);
        $actionFrom = $actionTo = 0;

        $product = isset($search['product_id']) ? $search['product_id'] : array();
        $productArray = (explode(",", $product));
        $actionFrom = $actionTo = 0;
        $product = '';

        $actionDate = $request['action_date'];
        $CategoryID = $search['category_id'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $stock = stockhistory::select('stock_history.*','vehicle_details.id as id','vehicle_details.fleet_number as fleet_number'
		,'vehicle_details.vehicle_registration as vehicle_registration' ,'Product_products.name as product_name', 'hr_people.first_name as name',
            'hr_people.surname as surname', 'hr.first_name as allocated_firstname', 'hr.surname as allocated_surname'
               )
            ->leftJoin('hr_people', 'stock_history.user_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hr', 'stock_history.user_allocated_id', '=', 'hr.id')
            ->leftJoin('Product_products', 'stock_history.product_id', '=', 'Product_products.id')
            ->leftJoin('vehicle_details', 'stock_history.vehicle_id', '=', 'vehicle_details.id')
            ->where(function ($query) use ($CategoryID) {
                if (!empty($CategoryID)) {
                    $query->where('stock_history.category_id', $CategoryID);
                }
            })
			->Where(function ($query) use ($productArray) {
				 if (!empty($productArray)) {
					$query->whereIn('stock_history.product_id', $productArray);
				 }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('stock_history.action_date', [$actionFrom, $actionTo]);
                }
            })
             ->orderBy('product_name', 'asc')
            ->orderBy('stock_history.id', 'asc')
            ->get();

        $data['stock'] = $stock;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Reports';

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];
        $user = Auth::user()->load('person');

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
        $data['date'] = date("d-m-Y");
        $data['user'] = $user;

        AuditReportsController::store('Stock Management', 'view Stock takeout Page', "Accessed By User", 0);
        return view('stock.stock_report_print')->with($data);
    }
	public function kitSave(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'product_id.*' => 'required',
        ]);

        $kitData = $request->all();
        unset($kitData['_token']);

		$kit = new kitProducts();
        $kit->name = !empty($kitData['name']) ? $kitData['name'] : '';
        $kit->date_added = strtotime(date("Y-m-d"));
        $kit->status = 1;
        $kit->save();
	
        AuditReportsController::store('Stock Management', 'Products Added to kit', 'Added by User', 0);
        return response()->json();
    }
	
	public function kitUpdate(Request $request, kitProducts $kit)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $kitData = $request->all();
        unset($kitData['_token']);

        $kit->name = !empty($kitData['name']) ? $kitData['name'] : '';
        $kit->update();
				
        AuditReportsController::store('Stock Management', 'Kit details  updated', 'Added by User', 0);
        return response()->json();
    }
	public function kitAct(kitProducts $kit)
    {
        if ($kit->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $kit->status = $status;
        $kit->update();
        AuditReportsController::store('Stock Management', 'Kit status changed', 'Edited by User', 0);
        return back();
    }
	// View Products kit
	public function viewKitProducts(kitProducts $kit)
    {
        if ($kit->status == 1) {
            $products = DB::table('kin_join_products')
                ->select('kin_join_products.*', 'Product_products.name as prod_name'
				, 'product_Category.name as cat_name'
				,'stock.avalaible_stock','Product_products.product_code')
                ->leftJoin('product_Category', 'kin_join_products.category_id', '=', 'product_Category.id')
                ->leftJoin('Product_products', 'kin_join_products.product_id', '=', 'Product_products.id')
                ->leftJoin('stock', 'stock.product_id', '=', 'Product_products.id')
				->where('kin_join_products.kit_id', $kit->id)
                ->orderBy('Product_products.name')
                ->get();

			$categories = product_category::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
            $data['page_title'] = 'Manage Products Kit';
            $data['page_description'] = 'Products Kit';
            $data['breadcrumb'] = [
                ['title' => 'Products Kit', 'path' => '/stock/kit_management', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Product Kit', 'active' => 1, 'is_module' => 0]
            ];
            $data['products'] = $products;
            $data['kit'] = $kit;
            $data['categories'] = $categories;

            $data['active_mod'] = 'Stock Management';
            $data['active_rib'] = 'Kit Management';
            AuditReportsController::store('Stock Management', 'Kit Product Accessed', 'Accessed by User', 0);
            return view('stock.kit_product')->with($data);
        }
		else 
		{
            return back();
        }
    }
	
	public function kitProductAct(KitJoinProducts $product)
    {
        if ($product->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $product->status = $status;
        $product->update();
        AuditReportsController::store('Stock Management', 'Product Kit status changed', 'Edited by User', 0);
        return back();
    }
	
	public function addProductToKit(Request $request, kitProducts $kit)
    {
		$validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric|min:0.1',
            'number_required' => 'required|numeric|min:0.1',
			'product_id' => [
				'required',
				Rule::unique('kin_join_products')->where(function ($query) use ($kit) {
					return $query->where('kit_id', $kit->id);
				}),
			],   
        ]);
		if ($validator->fails())
			return response()->json(['product_id' => 'This product has already been added to this kit. Please choose another one.'], 422);
        
		$kitData = $request->all();
        unset($kitData['_token']);

		$KitJoinProducts = new KitJoinProducts();
		$KitJoinProducts->product_id = $kitData['product_id'];
		$KitJoinProducts->kit_id = $kit->id;
		$KitJoinProducts->category_id = $kitData['category_id'];
		$KitJoinProducts->amount_required = $kitData['number_required'];
		$KitJoinProducts->status = 1;
		$KitJoinProducts->save();
				
        AuditReportsController::store('Stock Management', 'Products Added to kit', 'Added by User', 0);
        return response()->json();
    }
	
	public function updateProductToKit(Request $request, KitJoinProducts $prod)
    {
        $this->validate($request, [
            'number_required' => 'required',
        ]);

        $kitData = $request->all();
        unset($kitData['_token']);

		$prod->amount_required = $kitData['number_required'];
		$prod->update();
	
        AuditReportsController::store('Stock Management', 'Products information updated', 'Added by User', 0);
        return response()->json();
    }
}