<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\JobTitle;
use App\JobCategory;
use App\doc_type;
use App\product_category;
use App\product_products;
use App\product_packages;
use App\product_price;
use App\product_promotions;
use App\packages_product_table;
// use App\product_category;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Product_categoryController extends Controller
{
    public function index() {

		$jobCategories = JobCategory::orderBy('name', 'asc')->get();
		if (!empty($jobCategories))
			$jobCategories = $jobCategories->load('catJobTitle');

		$ProductCategory = product_category::orderBy('name', 'asc')->get();
		if (!empty($ProductCategory))
			$ProductCategory = $ProductCategory->load('productCategory');
		$row = product_category::count();
		if($row < 1)
     	 {

         $products  = 0;  
                
     	 }else{
		 $products = $ProductCategory -> first()->id ;
		}
		// $names->first()->first_name ;


		 $data['products'] = $products;
        $data['page_title'] = "Product Categories";
        $data['page_description'] = "Manage Product Categories";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/Product/Categories', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Categories', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Categories';
        $data['jobCategories'] = $jobCategories;
        $data['ProductCategory'] = $ProductCategory;
		
		AuditReportsController::store('Employee Records', 'Job titles Page Accessed', "Actioned By User", 0);
        return view('products.product_categories')->with($data);
    }

    #
    public function productView(Product_category $Category) 
	{
        if ($Category->status == 1) 
		{
			$Category->load('productCategory');
			$data['page_title'] = "Manage Products Product";
			$data['page_description'] = "Products page";
			 $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Categories', 'active' => 1, 'is_module' => 0]
        ];
			$data['products'] = $Category;
			$data['active_mod'] = 'Products';
        $data['active_rib'] = 'Categories';
			AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
			return view('products.products')->with($data);
		}
		else return back();
    } 
#

    //packages view

    public function view_packages() {

		$jobCategories = JobCategory::orderBy('name', 'asc')->get();
		if (!empty($jobCategories))
			$jobCategories = $jobCategories->load('catJobTitle');

		$ProductCategory = product_category::orderBy('name', 'asc')->get();
		if (!empty($ProductCategory))
			$ProductCategory = $ProductCategory->load('productCategory');

		$packages = product_packages::orderBy('name', 'asc')->get();
		if (!empty($packages))
			$packages = $packages->load('products_type');

		$Product = product_products::orderBy('name', 'asc')->get();
		//return $Product;

		$row = product_category::count();
		if($row < 1)
     	 {

         $products  = 0;  
                
     	 }else{
		 $products = $ProductCategory -> first()->id ;
		}
		// $names->first()->first_name ;


		$data['Product'] = $Product;
		$data['packages'] = $packages;
		$data['products'] = $products;
        $data['page_title'] = "Product Packages";
        $data['page_description'] = "Manage Product Packages";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/Product/Packages', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Packages', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Packages';
        $data['jobCategories'] = $jobCategories;
        $data['ProductCategory'] = $ProductCategory;
		
		AuditReportsController::store('Employee Records', 'Job titles Page Accessed', "Actioned By User", 0);
        return view('products.product_packages')->with($data);

    }

 public function view_promotions() {

     $productsPromotions = product_promotions::orderBy('name', 'asc')->get();
		 if (!empty($productsPromotions))
		 	// $productsPromotions = $productsPromotions->load('productPromotions');
		//return $productsPromotions;
			
		#
		// $packagePromotions = product_promotions::orderBy('name', 'asc')->get();
		// if (!empty($packagePromotions))
		// 	$packagePromotions = $packagePromotions->load('Promotionspackage');

		 //return $productsPromotions;

		 	$ProductCategory = product_category::orderBy('name', 'asc')->get();
		if (!empty($ProductCategory))
			$ProductCategory = $ProductCategory->load('productCategory');
	
	   $Product = product_products::orderBy('name', 'asc')->get();

	    $package = product_packages::orderBy('name', 'asc')->get();
		//return $package;

		$row = product_category::count();
		if($row < 1)
     	 {

         $products  = 0;  
                
     	 }else{
		 $products = $ProductCategory -> first()->id ;
		}



		$data['package'] = $package;
		$data['productsPromotions'] = $productsPromotions;
		//$data['packagePromotions'] = $packagePromotions;
		 $data['Product'] = $Product;
        $data['page_title'] = "Product Promotions";
        $data['page_description'] = "Manage Product Promotions";
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/Product/Promotions', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Promotions', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Promotions';
       // $data['jobCategories'] = $jobCategories;
        // $data['ProductCategory'] = $ProductCategory;
		
		AuditReportsController::store('Employee Records', 'Job titles Page Accessed', "Actioned By User", 0);
        return view('products.product_promotions')->with($data);

    }

    	#
     public function view_prices(product_products $price) 
	{
        if ($price->status == 1) 
		{
			$priceID = $price->id;
		//$op =	$price->load('productPrice');
		$Productprice = product_price::where('product_product_id', $priceID)->get();
		//return $Productprice;
		//return $Productprice;
			$data['page_title'] = "Manage Package_Products Price";
			$data['page_description'] = "Products page";
			 $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Prices', 'active' => 1, 'is_module' => 0]
        ];
        	

			$data['products'] = $price;
			$data['Productprice'] = $Productprice;
			$data['active_mod'] = 'Products';
        $data['active_rib'] = 'Categories';
			AuditReportsController::store('Employee Records', 'Job Titles Page Accessed', "Accessed by User", 0);
			return view('products.prices')->with($data);
		}
		else return back();
    } 
#
        //add product to packages
     public function viewProducts(product_packages $products) 
    {
        if ($products->status == 1) 
        {
            $packageID = $products->id;
            
        //$op = $price->load('productPrice');
        //$Productprice = product_price::where('product_product_id', $priceID)->get();

          $products = product_packages::where('id', $packageID)->get()->first();

            $product_packages = product_products::orderBy('id', 'asc')->get();
            //return $product_packages;

            $ProductPackages = product_products::orderBy('name', 'asc')->get()->first();

            //  $productss  = DB::table('packages_product_table')
            //  ->select('packages_product_table.*','Product_products.name as name','Product_products.description as description','Product_products.price as price')
            // ->leftJoin('Product_products', 'packages_product_table.product_product_id', '=', 'Product_products.id')
            // ->where( 'packages_product_table.product_packages_id', $packageID)
            // ->orderBy('packages_product_table.id')
            // ->get();

            

        //           $ProductPackages = product_packages::orderBy('id', 'asc')->get(); 
        // if (!empty($ProductPackages))
        //     $ProductPackages = $ProductPackages->load('products_type');

        // return $ProductPackages;

            $productss = DB::table('product_packages')
                      ->select('product_packages.*','Product_products.name as Prodname','Product_products.description as Proddescription' , 'Product_products.price as price')
                      ->leftJoin('Product_products', 'product_packages.products_id', '=', 'Product_products.id')
                      ->where( 'product_packages.id', $packageID)
                      ->orderBy('product_packages.id')
                      ->get();

                       //return $prodctss;

            $data['page_title'] = "Manage Products packages";
            $data['page_description'] = "Products page";
             $data['breadcrumb'] = [
            ['title' => 'Products package', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product package', 'active' => 1, 'is_module' => 0]
        ];
            $data['productss'] = $productss;
            $data['ProductPackages'] = $ProductPackages;
            $data['product_packages'] = $product_packages;
            $data['products'] = $products;
            //$data['Productprice'] = $Productprice;
            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Packages';
            AuditReportsController::store('Package_p Records', 'Job Titles Page Accessed', "Accessed by User", 0);
            return view('products.packages_product')->with($data);
        }
        else return back();
    } 


     public function product_packageSave(Request $request ,product_packages $package ,packages_product_table $associative) 
    {
        $this->validate($request, [
            // 'name' => 'required',
            // 'description' => 'required',
        ]);

           $docData = $request->all();
        unset($docData['_token']);
        $packageID = $package->id;

        $product_id = $request->input('product_id');

   // $associative->updateOrCreate(['product_product_id' => $request->input('product_id')], ['product_packages_id' => $packageID]);

        #writting into the associative table, use attach & deattach to avoid duplicates
        $package->products_type()->detach(['product_product_id' => $request->input('product_id')], ['product_packages_id' => $packageID]);
        $package->products_type()->attach(['product_product_id' => $request->input('product_id')], ['product_packages_id' => $packageID]);

        AuditReportsController::store('Employee Records', 'Category Informations Edited', "Edited by User", 0);
       return response()->json();
    }



		 public function Search() {
        //$user->load('person');
        //$avatar = $user->person->profile_pic;
       $hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
       $employees = HRPerson::where('status', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $qualifications = DB::table('qualification')->orderBy('id')->get();
        $packages = product_packages::where('status', 1)->get();
        $products = product_products::where('status', 1)->get();
         $category = product_category::where('status', 1)->get();
         $promotions = product_promotions::where('status', 1)->get();
        //return $promotions;
        //$HRPerson = DB::table('HRPerson')->orderBy('first_name', 'surname')->get();
         $productss  = DB::table('Product_products')
             ->select('Product_products.*','product_Category.name as catName')
            ->leftJoin('product_Category', 'Product_products.id', '=', 'product_Category.id')
            ->get();

         // return  $productss;
      
       $data['page_title'] = "Search";
        $data['page_description'] = "Manage Product(s) Search";
        $data['breadcrumb'] = [
            ['title' => 'Products search', 'path' => '/Product/Search', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        $data['doc_type'] ='doc_type';  
        $data['qualifications'] = $qualifications;
        $data['employees'] = $employees;
        //$data['DocType'] = $DocType;
        $data['productss'] = $productss;
        $data['products'] = $products;
        $data['packages'] = $packages;
        $data['category'] = $category;
        $data['promotions'] = $promotions;
        $data['hr_people'] = $hr_people;
     
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
         return view('products.products_search')->with($data);

    }

    public function editCategory(Request $request, product_category $Category)
	{
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $Category->name = $request->input('name');
        $Category->description = $request->input('description');
        $Category->update();
        AuditReportsController::store('Employee Records', 'Category Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $Category->name, 'new_description' => $Category->description], 200);
    }


 //    public function categoryAct(product_category $Category) 
	// {
	// 	if ($Category->status == 1) $stastus = 0;
	// 	else $stastus = 1;
		
	// 	$Category->status = $stastus;	
	// 	$Category->update();
		
 //    }



       public function categorySave(Request $request, product_category $cat) {
        $this->validate($request, [
            'name' => 'required',
            'description'=> 'required',

        ]);
    
        $docData = $request->all();
        unset($docData['_token']);

        // $doc_type = new doc_type($docData);
        $cat->name = $request->input('name');
        $cat->description = $request->input('description');
        $cat->status = 1;
        $cat->save();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();

    }

      public function addProductType(Request $request ,product_category $products){
        $this->validate($request, [

            'name' => 'required',
            'description'=> 'required',
            'price'=> 'required',

        ]);
    
        $docData = $request->all();
        unset($docData['_token']);

        $documentType = new product_products($docData);

        $documentType->status = 1;
        $documentType->category_id = $products->id;
        // $products->addProducttype($producttype);

        $documentType->name = $docData['name'];
        $documentType->description = $docData['description'];
         $documentType->price = $docData['price'];
        $documentType->save();

        $newName = $docData['name'];
		$newDescription = $docData['description'];
		$newPrice = $docData['price'];
        AuditReportsController::store('Document Type', 'Document Type saved ', "Edited by User",0);
        return response()->json(['new_name' => $newName, 'new_description' => $newDescription , 'price' =>$newPrice], 200);
    }

 #
 public function editProduct(Request $request, product_products $product)
	{
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
             'price'=> 'required',

        ]);

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->update();
        AuditReportsController::store('Employee Records', 'Category Informations Edited', "Edited by User", 0);
        return response()->json(['new_name' => $product->name, 'new_description' => $product->description , 'price' =>$product->price], 200);
    }
    #
    #packages
        public function packageSave(Request $request, product_packages $packs ) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
			'discount' => 'required',

        ]);
    
        $docData = $request->all();
        unset($docData['_token']);

          $Product = $docData['product_id'];
        
            foreach ($Product as $products){
        $packs->name = $request->input('name');
        $packs->description = $request->input('description');
        $packs->discount = $request->input('discount');
        $packs->status = 1;
        $packs->products_id = $products;
        $packs->save();
      //  AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
    	}

    	return response()->json();

    }

    // 
    public function editPackage(Request $request, product_packages $package)
	{
        $this->validate($request, [
   //           'name' => 'required',
   //          'description' => 'required',
			// 'discount' => 'required',

        ]);

        $docData = $request->all();
        unset($docData['_token']);

          $Product = $docData['product_id'];
        
            foreach ($Product as $products){
        $package->name = $request->input('name');
        $package->description = $request->input('description');
        $package->discount = $request->input('discount');
        $package->status = 1;
        $package->products_id = $products;
        $package->update();
      //  AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
    	}

    }

    #promotions
     public function promotionSave(Request $request, product_promotions $prom) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
			'discount' => 'required',
			'start_date' => 'required',
			'end_date' => 'required',
			'price' => 'required',
			//'product' => 'required',

        ]);
    
        $promData = $request->all();
        unset($promData['_token']);

         $Product = $promData['product'];
        
            foreach ($Product as $products){
          // $StartDate = $promData['start_date'];
          // $EndDate = $promData['end_date'];

           $StartDate = $promData['start_date'] = str_replace('/', '-', $promData['start_date']);
           $StartDate = $promData['start_date'] = strtotime($promData['start_date']);

           $EndDate = $promData['end_date'] = str_replace('/', '-', $promData['end_date']);
           $EndDate = $promData['end_date'] = strtotime($promData['end_date']);
        
            
            $prom->name = $request->input('name');
            $prom->description = $request->input('description');
            $prom->discount = $request->input('discount');
            $prom->price = $request->input('price');
            $prom->product_product_id = $products;
            $prom->start_date = $StartDate;
            $prom->end_date =  $EndDate;
            $prom->status = 1;
            $prom->save();
    }
      //  AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
    	

    	return response()->json();

    }
    #
    #
    public function priceSave(Request $request, product_products $product)
    {
        $this->validate($request, [
            'price' => 'bail|required|numeric|min:0',
        ]);

        $currentDate = time();
        $priceData = $request->all();
        unset($priceData['_token']);
        $price = new product_price($priceData);
        $price->status = 1;
        $price->start_date = $currentDate;

        //get and update previous price
        $product->load(['productPrices' => function ($query) {
            $query->orderBy('id', 'desc');
            $query->limit(1);
        }]);
        $previousPrice = $product->productPrices->first();
        $previousPrice->end_date = $currentDate;
        $previousPrice->update();

        $product->addNewPrice($price);

        AuditReportsController::store('Employee Records', 'Job Title Category Added', "price: $priceData[price]", 0);
        return response()->json();
    }

        public function editPRICE(Request $request, product_packages $products)
	{
   
		$this->validate($request, [
            // 'name' => 'required',
            // 'description' => 'required',        
        ]);

       

		$priceData = $request->all();
		unset($priceData['_token']);
		$price = new product_price($priceData);
		$price->status = 1;
		$price->price = $priceData['price'];
		$price->product_product_id = $products->id;
		$price->start_date = $currentDate;
        $price->update();
		
		AuditReportsController::store('Employee Records', 'Job Title Category Added', "price: $priceData[price]", 0);
		return response()->json();
    }

        #search functions
    public function productSearch(Request $request){

        $this->validate($request, [
           

        ]); 

        $SysData = $request->all();
        unset($SysData['_token']);

      //return $SysData;

        $productName = $request->product_name;
        $productDescription = $request->product_description;
        $productPrice = $request->product_price;
        $categoryID = $request->cat_id;

         $tickets  = DB::table('Product_products')
             ->select('Product_products.*','product_Category.name as catName')
            ->leftJoin('product_Category', 'Product_products.id', '=', 'Product_products.category_id')
             ->where(function ($query) use ($productName) {
            if (!empty($productName)) {
              $query->where('Product_products.id', $productName);
            }
            })
            ->where(function ($query) use ($productDescription) {
              if (!empty($productDescription)) {
                $query->where('description', 'ILIKE', "%$productDescription%");
              }
            })
            ->where(function ($query) use ($productPrice) {
            if (!empty($productPrice)) {
              $query->where('price', $productPrice);
            }
            })
             ->where(function ($query) use ($categoryID) {
            if (!empty($categoryID)) {
              $query->where('product_Category.id', $categoryID);
            }
            })
            ->orderBy('id')
            ->get();

           

           //return $tickets;
            

        //$data['ticketStatus'] = $ticketStatus;
        $data['page_title'] = "Product Search ";
        $data['page_description'] = "Product Search Page";
        $data['breadcrumb'] = [
            ['title' => 'Product Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Product Search Page', 'active' => 1, 'is_module' => 0]
        ];  
        // 
        $data['tickets'] = $tickets;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('products.product_results')->with($data);

        }

        // 
        public function categorySearch(Request $request){

        $this->validate($request, [
           

        ]); 

        $SysData = $request->all();
        unset($SysData['_token']);
       // return $SysData;

         $categoryName = $request->category_name;
         $categoryDescription = $request->category_description;

         $category  = DB::table('product_Category')

             ->where(function ($query) use ($categoryName) {
            if (!empty($categoryName)) {
              $query->where('id', $categoryName);
            }
            })
            ->where(function ($query) use ($categoryDescription) {
              if (!empty($categoryDescription)) {
                $query->where('description', 'ILIKE', "%$categoryDescription%");
              }
            })
            
            ->orderBy('id')
            ->get();

           

          // return $category;
            

       //$data['ticketStatus'] = $ticketStatus;
        $data['page_title'] = "Category Search";
        $data['page_description'] = "Category Search Page";
        $data['breadcrumb'] = [
            ['title' => 'Category Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Category Search Page', 'active' => 1, 'is_module' => 0]
        ];  
        // 
        $data['category'] = $category;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('products.category_results')->with($data);
        }

        // 

        public function packageSearch(Request $request){

        $this->validate($request, [
           

        ]); 

        $SysData = $request->all();
        unset($SysData['_token']);

        $package_name = $request->package_name;
        $package_description = $request->package_description;
        $product_type = $request->product_type;
        $package_discount = $request->package_discount;

            $packageSearch  = DB::table('product_packages')
               ->select('product_packages.*','Product_products.name as product_name')
               ->leftJoin('Product_products', 'product_packages.products_id', '=', 'Product_products.id')

             ->where(function ($query) use ($package_name) {
            if (!empty($package_name)) {
              $query->where('id', $package_name);
                }
            })
            ->where(function ($query) use ($package_description) {
              if (!empty($package_description)) {
                $query->where('description', 'ILIKE', "%$package_description%");
              }
            })
             ->where(function ($query) use ($product_type) {
            if (!empty($product_type)) {
              $query->where('products_id', $product_type);
            }
            })
            ->where(function ($query) use ($package_discount) {
            if (!empty($package_discount)) {
              $query->where('discount', $package_discount);
            }
            })
            ->orderBy('id')
            ->get();



            

       //$data['ticketStatus'] = $ticketStatus;
        $data['page_title'] = "Package Search";
        $data['page_description'] = "Package Search Results Page";
        $data['breadcrumb'] = [
            ['title' => 'Package Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Package Search Page', 'active' => 1, 'is_module' => 0]
        ];  
        // 
        $data['packageSearch'] = $packageSearch;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('products.packages_results')->with($data);
        }

        // 

        public function promotionSearch(Request $request){

        $this->validate($request, [
           

        ]); 

        $SysData = $request->all();
        unset($SysData['_token']);


        $promotion_name = $request->promotion_name;
        $promotion_discription = $request->promotion_discription;
        $actionFrom = $actionTo = 0;
        $actionDate = $request['promo_date'];  

        if (!empty($actionDate))
        {
          $startExplode = explode('-', $actionDate);
          $actionFrom = strtotime($startExplode[0]);
          $actionTo = strtotime($startExplode[1]);
          //return $actionFrom;
        }

        #
         

         $Promotions  = DB::table('product_promotions')
                ->select('product_promotions.*','Product_products.name as product_name')
                ->leftJoin('Product_products', 'product_promotions.product_product_id', '=', 'Product_products.id')
            //     ->where(function ($query) use ($actionFrom, $actionTo) {
            // if ($actionFrom > 0 && $actionTo  > 0) {
            //   $query->whereBetween('ticket_date', [$actionFrom, $actionTo]);
            // }
            // })
                 ->where(function ($query) use ($actionFrom) {
            if (!empty($actionFrom)) {
              $query->where('product_promotions.start_date', $actionFrom);
            }
            })

                  ->where(function ($query) use ($actionTo) {
            if (!empty($actionTo)) {
              $query->where('product_promotions.end_date', $actionTo);
            }
            })

             ->where(function ($query) use ($promotion_name) {
            if (!empty($promotion_name)) {
              $query->where('product_promotions.id', $promotion_name);
            }
            })
           
            ->orderBy('id')
            ->get();

           

           // return $Promotions;
            

       $data['page_title'] = "Promotions Search";
        $data['page_description'] = "Promotions Search Results Page";
        $data['breadcrumb'] = [
            ['title' => 'Promotions Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Promotions Search Page', 'active' => 1, 'is_module' => 0]
        ];  
        // 
        $data['Promotions'] = $Promotions;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('products.promotions_results')->with($data);
        }


          public function CategoryAct(product_category $cat) {
        if ($cat->status == 1) $stastus = 0;
        else $stastus = 1;

        $cat->status = $stastus;    
        $cat->update();
        return back();
    }




}
