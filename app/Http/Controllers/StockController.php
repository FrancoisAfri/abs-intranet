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
                    
        $Category = $stocks->first()->category_id;
                    
        $data['stocks'] = $stocks;
        $data['Category'] = $Category;
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
    
    public function add_stock(Request $request ,product_category $category){
        $this->validate($request, [
           
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        unset($results['emp-list-table_length']);
        $CategoryID =  $category->id;

         foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }

        foreach ($results as $sKey => $sValue) {
         if (strlen(strstr($sKey, 'newstock_'))) {
                list($sUnit, $iID) = explode("_", $sKey);
              
                 $productID =  $iID;
                 $newStock = $sValue;
               // if (empty($sValue)) $sValue = $sReasonToReject;
                 
          $row = stock::where('product_id', $productID)->count();
         
          if ($row > 0 ) {
              
              // return 1;
              $currentstock = stock::where('product_id', $productID)->first();
              $available =  !empty($currentstock->avalaible_stock) ? $currentstock->avalaible_stock : 0 ;       
              DB::table('stock')->where('product_id', $productID)->where('category_id' , $CategoryID)->update(['avalaible_stock' => $available + $newStock]);  
              
              return redirect('stock/storckmanagement');
           }else

             $storck = new stock();
             $storck->avalaible_stock = $newStock;
             $storck->category_id = $CategoryID;
             $storck->product_id = $productID;
             $storck->status = 1;
             $storck->date_added = time();
             $storck->save();
         }
        }
        AuditReportsController::store('Job Card Management', 'Job card Approvals Page', "Accessed By User", 0);
      return redirect('stock/storckmanagement');
    }
	public function takeout(){
		
	$parts  =  stock::Orderby('id','asc')->get();  
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
 
    public function stockout(Request $request){
       
        $this->validate($request, [
           
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        
      //  return $results;
        
        $categoryID = $results['product_id'];
        $productID = $results['category_id'];
        
        foreach ($productID as $prodID){
            
            
            $stocks = DB::table('stock')
                    ->select('stock.*','Product_products.*')
                    ->leftJoin('Product_products', 'stock.product_id', '=', 'Product_products.id')
                    ->where(function ($query) use ($categoryID) {
                        if (!empty($categoryID)) {
                            $query->where('stock.category_id', $categoryID);
                        }
                    })
                   ->where(function ($query) use ($prodID) {
                        if (!empty($prodID)) {
                            $query->where('stock.product_id', $prodID);
                        }
                    })
                    ->get();
                    
              $Category = $stocks->first()->category_id;
            
        }
 
    
        $data['stocks'] = $stocks ;
        $data['Category'] = $Category;
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
    
    public function takestockout(Request $request , product_category $category){
          $this->validate($request, [
           
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        unset($results['emp-list-table_length']);
        $CategoryID =  $category->id;

         foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        
       foreach ($results as $sKey => $sValue) {
         if (strlen(strstr($sKey, 'stock_'))) {
                list($sUnit, $iID) = explode("_", $sKey);
                
               // return $iID;
                
                 $productID =  $iID;
                 $newStock = $sValue;

              $currentstock = stock::where('product_id', $productID)->first();
              $available =  !empty($currentstock->avalaible_stock) ? $currentstock->avalaible_stock : 0 ;       
              DB::table('stock')->where('product_id', $productID)->where('category_id' , $CategoryID)->update(['avalaible_stock' => $available - $newStock]);  
                
            }
        AuditReportsController::store('Job Card Management', 'Job card Approvals Page', "Accessed By User", 0);
         return redirect('stock/storckmanagement');
                
       }  
     }
}
 

