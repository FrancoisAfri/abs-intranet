<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\product_category;
use App\stockhistory;
use App\HRPerson;
use App\stock;
use App\CompanyIdentity;
use App\product_products;
use App\module_access;
use App\module_ribbons;
use App\modules;
use App\Mail\NextjobstepNotification;
use App\Mail\DeclinejobstepNotification;
use Illuminate\Http\Request;
use App\Mail\confirm_collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mystock()
    {

        $parts = stock::Orderby('id', 'asc')->get();
        //return $parts
        $jobCategories = product_category::orderBy('id', 'asc')->get();

        $data['jobCategories'] = $jobCategories;
        $data['parts'] = $parts;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'My Stock';

        AuditReportsController::store('Stock Management', 'view Stock Add Page', "Accessed By User", 0);
        return view('stock.search_product')->with($data);
    }

    public function stock(Request $request)
    {

        $this->validate($request, [
            'product_id' => 'bail|required',
            'category_id' => 'bail|required',
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
       
        $CategoryID = $SysData['product_id'];
        $ProductID = $SysData['category_id'];
        $stockID = $SysData['stock_type'];

        $stocks = DB::table('Product_products')
            ->select('Product_products.*', 'stock.avalaible_stock')
            ->leftJoin('stock', 'Product_products.id', '=', 'stock.product_id')
            ->where(function ($query) use ($CategoryID) {
                if (!empty($CategoryID)) {
                    $query->where('Product_products.category_id', $CategoryID);
                }
            })
            ->where(function ($query) use ($ProductID) {
                if (!empty($ProductID)) {
                    $query->where('Product_products.id', $ProductID);
                }
            })
            ->where(function ($query) use ($stockID) {
                if (!empty($stockID)) {
                    $query->where('Product_products.stock_type', $stockID);
                }
            })
          //  ->whereIn('Product_products', 1,3)
            ->orderBy('Product_products','asc')
            ->get();
            
         // return $stocks;

       // $Category = $stocks->first()->category_id;

        $data['stocks'] = $stocks;
       // $data['Category'] = $Category;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add Stock', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Add Stock';

        AuditReportsController::store('Stock Management', 'Stock Search Page', "Accessed By User", 0);
        return view('stock.stock_results')->with($data);
    }

    public function add_stock(Request $request, product_category $category)
    {
        $this->validate($request, [

        ]);
        $results = $request->all();
        //Exclude empty fields from query

        unset($results['_token']);
        unset($results['emp-list-table_length']);
      //  $CategoryID = $category->id;

        //  return $results ;

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }


        foreach ($results as $sKey => $sValue) {
            if (strlen(strstr($sKey, 'newstock_'))) {
                list($sUnit, $iID , $cID) = explode("_", $sKey);

                
                $proID = $iID;
                $newStock = $sValue;
                $proID = isset($iID) ? $iID : array();
                $productID = $proID;

                 $CategoryID = $cID;
                $row = stock::where('product_id', $productID)->where('category_id' ,$CategoryID )->count();
            $storck = new stock();
                if ($row > 0) {

                   // return 1;
                    $currentstock = stock::where('product_id', $productID)->first();
                    $available = !empty($currentstock->avalaible_stock) ? $currentstock->avalaible_stock : 0;
                    DB::table('stock')->where('product_id', $productID)->where('category_id', $CategoryID)->update(['avalaible_stock' => $available + $newStock]);

                    $history = new stockhistory();
                    $history->product_id = $productID;
                    $history->category_id = $CategoryID;
                    $history->avalaible_stock = $available + $newStock;
                    $history->action_date = time();
                    $history->balance_before = $available;
                    $history->balance_after = $available + $newStock;
                    $history->action = 'new storck added';
                    $history->user_id = Auth::user()->person->id;
                    $history->user_allocated_id = 0;
                    $history->vehicle_id = 0;
                    $history->save();

                    //return redirect('stock/storckmanagement');
                } else

                //return 2;
                $storck->avalaible_stock = $newStock;
                $storck->category_id = $CategoryID;
                $storck->product_id = $productID;
                $storck->status = 1;
                $storck->date_added = time();
                $storck->save();

                $currentstock = stock::where('product_id', $productID)->first();
                $available = !empty($currentstock->avalaible_stock) ? $currentstock->avalaible_stock : 0;

                $history = new stockhistory();
                $history->product_id = $productID;
                $history->category_id = $CategoryID;
                $history->avalaible_stock = $available + $newStock;
                $history->balance_before = $available;
                $history->balance_after = $available + $newStock;
                $history->action_date = time();
                $history->user_id = Auth::user()->person->id;
                $history->action = 'new storck added';
                $history->user_allocated_id = 0;
                $history->vehicle_id = 0;
                $history->save();
            }
        }
        AuditReportsController::store('Stock Management', 'new Stock Added ', "Accessed By User", 0);
        return redirect('stock/storckmanagement');
    }

    public function takeout()
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
        $data['active_rib'] = 'Allocate Stock';

        AuditReportsController::store('Stock Management', 'view Stock takeout Page', "Accessed By User", 0);
        return view('stock.search_product_out')->with($data);
    }

    public function stockout(Request $request)
    {

        $this->validate($request, [

        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        $product = '';
        $categoryID = $results['product_id'];

        $productID = isset($results['category_id']) ? $results['category_id'] : array();
        $user = HRPerson::where('status', 1)->get();

        for ($i = 0; $i < count($productID); $i++) {
            $product .= $productID[$i] . ',';
        }
        $val = rtrim($product, ",");

        $stocks = DB::table('stock')
            ->select('stock.*', 'Product_products.*')
            ->leftJoin('Product_products', 'stock.product_id', '=', 'Product_products.id')
            ->where(function ($query) use ($categoryID) {
                if (!empty($categoryID)) {
                    $query->where('stock.category_id', $categoryID);
                }
            })
            ->Where(function ($query) use ($val) {
                for ($i = 0; $i < count($val); $i++) {
                    $query->whereOr('stock.product_id', '=', $val);
                }
            })
            ->get();

        $data['stocks'] = $stocks;
        $data['user'] = $user;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'My Stock';

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

            if (strlen(strstr($sKey, 'stock_'))) {
                list($sUnit, $iID, $cID) = explode("_", $sKey);

                $user = 'userid' . '_' . $iID;
                $UserID = isset($request[$user]) ? $request[$user] : 0;

                $productID = $iID;
                $newStock = $sValue;
                $CategoryID = $cID;

                $currentstock = stock::where('product_id', $productID)->first();
                $available = !empty($currentstock->avalaible_stock) ? $currentstock->avalaible_stock : 0;
                DB::table('stock')->where('product_id', $productID)->where('category_id', $CategoryID)->update(['avalaible_stock' => $available - $newStock]);
                DB::table('stock')->where('product_id', $productID)->where('category_id', $CategoryID)->update(['user_id' => $UserID]);

                $history = new stockhistory();
                $history->product_id = $productID;
                $history->category_id = $CategoryID;
                $history->avalaible_stock = $available - $newStock;
                $history->action_date = time();
                $history->user_id = Auth::user()->person->id;
                $history->user_allocated_id = $UserID;
                $history->balance_before = $available;
                $history->balance_after = $available - $newStock;
                $history->action = 'stock taken out';
                $history->vehicle_id = 0;
                $history->save();
            }
        }
        AuditReportsController::store('Stock Management', 'Stock taken out', "Accessed By User", 0);
        return redirect('stock/storckmanagement');
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
            'product_id' => 'bail|required',
            'category_id' => 'bail|required',
        ]);
        $search = $request->all();
        unset($search['_token']);

        $actionFrom = $actionTo = 0;
        $product = '';
        $productArray = isset($search['category_id']) ? $search['category_id'] : array();
        $actionDate = $request['action_date'];
        $CategoryID = $search['product_id'];
        $productID = $search['category_id'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $stock = stockhistory::select('stock_history.*', 'Product_products.name as product_name', 'hr_people.first_name as name',
            'hr_people.surname as surname', 'hr.first_name as allocated_firstname', 'hr.surname as allocated_surname')
            ->leftJoin('hr_people', 'stock_history.user_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hr', 'stock_history.user_allocated_id', '=', 'hr.id')
            ->leftJoin('Product_products', 'stock_history.product_id', '=', 'Product_products.id')
            ->where(function ($query) use ($CategoryID) {
                if (!empty($CategoryID)) {
                    $query->where('stock_history.category_id', $CategoryID);
                }
            })
            ->Where(function ($query) use ($productArray) {
                for ($i = 0; $i < count($productArray); $i++) {
                    $query->whereOr('stock_history.product_id', '=', $productArray[$i]);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('stock_history.action_date', [$actionFrom, $actionTo]);
                }
            })
            ->orderBy('id', 'desc')
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

        $stock = stockhistory::select('stock_history.*', 'Product_products.name as product_name', 'hr_people.first_name as name', 'hr_people.surname as surname')
            ->leftJoin('hr_people', 'stock_history.user_id', '=', 'hr_people.id')
            ->leftJoin('Product_products', 'stock_history.product_id', '=', 'Product_products.id')
            ->where(function ($query) use ($CategoryID) {
                if (!empty($CategoryID)) {
                    $query->where('stock_history.category_id', $CategoryID);
                }
            })
            ->Where(function ($query) use ($productArray) {
                for ($i = 0; $i < count($productArray); $i++) {
                    $query->whereOr('stock_history.product_id', '=', $productArray[$i]);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('stock_history.action_date', [$actionFrom, $actionTo]);
                }
            })
            ->orderBy('id', 'desc')
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
}
 

