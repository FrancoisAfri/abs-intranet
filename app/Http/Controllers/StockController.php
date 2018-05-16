<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\servicetype;
use App\HRPerson;
use App\stock;
use App\product_category;
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
    
    public function mystock(){
		
		$parts  =  stock::Orderby('id','asc')->get();  
		//return $parts;
      
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
    public function stock(Request $request){
        
		$this->validate($request, [
            
        ]);
        $SysData = $request->all();
        unset($SysData['_token']);
        
        $CategoryID = $SysData['product_id'];
        $ProductID = $SysData['category_id'];
        
        $stocks = DB::table('Product_products')
            ->select('Product_products.*','stock.avalaible_stock')
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

        AuditReportsController::store('Stock Management', 'Stock Search Page', "Accessed By User", 0);
        return view('stock.stock_results')->with($data); 
    }
	public function takeout(){
		
		$parts  =  stock::Orderby('id','asc')->get();  
		//return $parts;
      
		$productCategories = product_category::orderBy('id', 'asc')->get();
    
        $data['productCategories'] = $productCategories;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/storckmanagement', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Job Card Search ', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'My Stock';

        AuditReportsController::store('Stock Management', 'view Stock takeout Page', "Accessed By User", 0);
        return view('stock.search_product_out')->with($data); 
    }
 
}