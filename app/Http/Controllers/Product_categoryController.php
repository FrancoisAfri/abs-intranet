<?php

namespace App\Http\Controllers;

use App\doc_type;
use App\HRPerson;
use App\JobCategory;
use App\Mail\confirm_collection;
use App\packages_product_table;
use App\product_category;
use App\product_packages;
use App\product_price;
use App\product_products;
use App\stockLevel;
use App\StockSettings;
use App\ContactCompany;
use App\stockInfo;
use App\stock;
use App\Stock_location;
use App\productsPrefferedSuppliers;
use App\productsPreferredSupplier;
use App\product_promotions;
use App\stockhistory;
use App\ProductServiceSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Product_categoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $jobCategories = JobCategory::orderBy('name', 'asc')->get();
        if (!empty($jobCategories)) {
            $jobCategories = $jobCategories->load('catJobTitle');
        }

        $ProductCategory = product_category::orderBy('name', 'asc')->get();
        if (!empty($ProductCategory)) {
            $ProductCategory = $ProductCategory->load('productCategory');
        }
        $row = product_category::count();
        if ($row < 1) {
            $products = 0;
        } else {
            $products = $ProductCategory->first()->id;
        }
		$stockTypeArray = array(1 => 'Stock Item', 2 => 'Non Stock Item', 3 => 'Both');
        $data['products'] = $products;
        $data['page_title'] = 'Product Categories';
        $data['page_description'] = 'Manage Product Categories';
        $data['breadcrumb'] = [
            ['title' => 'Products', 'path' => '/Product/Categories', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Categories', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Categories';
		$data['stockTypeArray'] = $stockTypeArray;
        $data['jobCategories'] = $jobCategories;
        $data['ProductCategory'] = $ProductCategory;

        AuditReportsController::store('Products', 'Job titles Page Accessed', 'Actioned By User', 0);
        return view('products.product_categories')->with($data);
    }

    public function productView(Product_category $Category)
    {
        if ($Category->status == 1) {
            $userAccess = DB::table('security_modules_access')
                ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
                ->where('code_name', 'quote')
                ->where('user_id', Auth::user()->person->user_id)
                ->first();
            $jobCategories = product_category::orderBy('name', 'asc')->get();

			$products = product_products::with(['productPrices' => function ($query) {
                    $query->orderBy('id', 'desc');
                    $query->limit(1);
                }])
			->orderBy('Product_products.name', 'asc')
			->where('Product_products.category_id', $Category->id)
            ->get();
			///
			foreach ($products as $product) {
                $currentPrice = ($product->productPrices->first())
                    ? $product->productPrices->first()->price : $product->price;
                $product->current_price = $currentPrice;
            }
			//return $products;
            $data['page_title'] = 'Manage Products Product';
            $data['page_description'] = 'Products page';
            $data['breadcrumb'] = [
                ['title' => 'Products', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Product Categories', 'active' => 1, 'is_module' => 0]
            ];

			$stockTypeArray = array(1 => 'Stock Item', 2 => 'Non Stock Item', 3 => 'Both');
            $data['userAccess'] = $userAccess;
            $data['category'] = $Category;
            $data['products'] = $products;
            $data['stockTypeArray'] = $stockTypeArray;
            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Categories';
            AuditReportsController::store('Products', 'Job Titles Page Accessed', 'Accessed by User', 0);
            return view('products.products')->with($data);
        } else {
            return back();
        }
    }

    //packages view

    public function view_packages()
    {
        $jobCategories = JobCategory::orderBy('name', 'asc')->get();
        if (!empty($jobCategories)) {
            $jobCategories = $jobCategories->load('catJobTitle');
        }

        $ProductCategory = product_category::orderBy('name', 'asc')->get();
        if (!empty($ProductCategory)) {
            $ProductCategory = $ProductCategory->load('productCategory');
        }

        $packages = product_packages::orderBy('name', 'asc')->get();
        if (!empty($packages)) {
            $packages = $packages->load('products_type');
        }

        $Product = product_products::orderBy('name', 'asc')->get();
        //return $Product;

        $row = product_category::count();
        if ($row < 1) {
            $products = 0;
        } else {
            $products = $ProductCategory->first()->id;
        }
        // $names->first()->first_name ;

        $data['Product'] = $Product;
        $data['packages'] = $packages;
        $data['products'] = $products;
        $data['page_title'] = 'Product Packages';
        $data['page_description'] = 'Manage Product Packages';
        $data['breadcrumb'] = [
            ['title' => 'Products', 'path' => '/Product/Packages', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Packages', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Packages';
        $data['jobCategories'] = $jobCategories;
        $data['ProductCategory'] = $ProductCategory;

        AuditReportsController::store('Products', 'Product Package Page Accessed', 'Accessed By User', 0);
        return view('products.product_packages')->with($data);
    }

    public function view_promotions()
    {
        $currentTime = time();
        $productsPromotions = product_promotions::where('status', 1)
            ->whereRaw("start_date < $currentTime")
            ->whereRaw("end_date > $currentTime")
            ->with('product', 'package')
            ->orderBy('start_date', 'asc')
            ->get();


        $products = product_products::whereDoesntHave('promotions', function ($query) use ($currentTime) {
            $query->where('status', 1)
                ->whereRaw("start_date < $currentTime")
                ->whereRaw("end_date > $currentTime");
        })->get();
        $packages = product_packages::whereDoesntHave('promotions', function ($query) use ($currentTime) {
            $query->where('status', 1)
                ->whereRaw("start_date < $currentTime")
                ->whereRaw("end_date > $currentTime");
        })->get();

        $data['package'] = $packages;
        $data['productsPromotions'] = $productsPromotions;
        $data['Product'] = $products;
        $data['page_title'] = 'Product Promotions';
        $data['page_description'] = 'Manage Product Promotions';
        $data['breadcrumb'] = [
            ['title' => 'Products', 'path' => '/Product/Promotions', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Promotions', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Promotions';

        AuditReportsController::store('Product', 'Promotion Page Accessed', 'Actioned By User', 0);

        return view('products.product_promotions')->with($data);
    }

    //
    public function view_prices(product_products $price)
    {
        if ($price->status == 1) {
            $priceID = $price->id;
            $Productprice = product_price::where('product_product_id', $priceID)->get();
            $data['page_title'] = 'Manage Package_Products Price';
            $data['page_description'] = 'Products page';
            $data['breadcrumb'] = [
                ['title' => 'Products', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Product Prices', 'active' => 1, 'is_module' => 0]
            ];

            $data['products'] = $price;
            $data['Productprice'] = $Productprice;
            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Categories';
            AuditReportsController::store('Products', 'Job Titles Page Accessed', 'Accessed by User', 0);
            return view('products.prices')->with($data);
        } else {
            return back();
        }
    }
	
	public function stockInfos(product_products $product)
    {
        if ($product->status == 1) {
			$stockSettings = StockSettings::orderBy('id', 'desc')->first();
			$stockLevels = stockLevel::where('active', 1)->orderBy('id', 'desc')->get();
			//return $stockLevels;
			$product = $product->load('infosProduct');

			$ContactCompany = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
            $data['page_title'] = 'Product Stock';
            $data['page_description'] = ' Informations';
            $data['breadcrumb'] = [
                ['title' => 'Products', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Product', 'active' => 1, 'is_module' => 0]
            ];
			
			$productActivities = stockhistory::select('stock_history.*','vehicle_details.id as id'
					,'vehicle_details.fleet_number as fleet_number'
					,'vehicle_details.vehicle_registration as vehicle_registration' 
					,'Product_products.name as product_name', 'hr_people.first_name as name',
					'hr_people.surname as surname', 'hr.first_name as allocated_firstname'
					, 'hr.surname as allocated_surname')
            ->leftJoin('hr_people', 'stock_history.user_id', '=', 'hr_people.id')
            ->leftJoin('hr_people as hr', 'stock_history.user_allocated_id', '=', 'hr.id')
            ->leftJoin('Product_products', 'stock_history.product_id', '=', 'Product_products.id')
            ->leftJoin('vehicle_details', 'stock_history.vehicle_id', '=', 'vehicle_details.id')
             ->orderBy('product_name', 'asc')
            ->orderBy('stock_history.id', 'asc')
			->where('product_id', $product->id)
			->limit(100)
            ->get();

			$productPreferreds = productsPreferredSupplier::select('products_preferred_suppliers.*'
					,'Product_products.name as product_name', 'contact_companies.name as com_name')
            ->leftJoin('contact_companies', 'products_preferred_suppliers.supplier_id', '=', 'contact_companies.id')
            ->leftJoin('Product_products', 'products_preferred_suppliers.product_id', '=', 'Product_products.id')
			->orderBy('products_preferred_suppliers.order_no', 'asc')
			->where('product_id', $product->id)
			->limit(100)
            ->get();
			
            $data['products'] = $product;
            $data['stockSettings'] = $stockSettings;
            $data['stock_levels'] = $stockLevels ;
            $data['productPreferreds'] = $productPreferreds;
            $data['productActivities'] = $productActivities;
            $data['ContactCompany'] = $ContactCompany;
            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Categories';
            AuditReportsController::store('Products', 'Job Titles Page Accessed', 'Accessed by User', 0);
            return view('products.stock_info')->with($data);
        } else {
            return back();
        }
    }
	
	public function addStockInfo(Request $request, product_products $product)
    {
       /* $this->validate($request, [
        ]);*/

        $docData = $request->all();
        unset($docData['_token']);

        $stock = new stockInfo();
        $stock->allow_vat = $request->input('allow_vat');
        $stock->mass_net = $request->input('mass_net');
        $stock->minimum_level = $request->input('minimum_level');
        $stock->maximum_level = $request->input('maximum_level');
        $stock->bar_code = $request->input('bar_code');
        $stock->unit = $request->input('unit');
        $stock->commodity_code = $request->input('commodity_code');
        $stock->product_id = $product->id;
        $stock->save();
		
		//Upload Image picture
        if ($request->hasFile('picture')) {
            $fileExt = $request->file('picture')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('picture')->isValid()) {
                $fileName = time() . "picture." . $fileExt;
                $request->file('picture')->storeAs('Producrs/images', $fileName);
                //Update file name in the database
                $stock->picture = $fileName;
                $stock->update();
            }
        }
		
        AuditReportsController::store('Products', 'Stock Info Added', 'Actioned By User', 0);
        return response()->json();
    }
	
	public function updateStockInfo(Request $request, stockInfo $stock)
    {
        /*$this->validate($request, [
        ]);*/

        $docData = $request->all();
        unset($docData['_token']);
        $stock->allow_vat = $request->input('allow_vat');
        $stock->mass_net = $request->input('mass_net');
        $stock->minimum_level = $request->input('minimum_level');
        $stock->maximum_level = $request->input('maximum_level');
        $stock->bar_code = $request->input('bar_code');
        $stock->unit = $request->input('unit');
        $stock->commodity_code = $request->input('commodity_code');
        $stock->update();
		
		//Upload Image picture
        if ($request->hasFile('picture')) {
            $fileExt = $request->file('picture')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('picture')->isValid()) {
                $fileName = time() . "picture." . $fileExt;
                $request->file('picture')->storeAs('Producrs/images', $fileName);
                //Update file name in the database
                $stock->picture = $fileName;
                $stock->update();
            }
        }
		
        AuditReportsController::store('Products', 'Stock Info Updated', 'Actioned By User', 0);
        return response()->json();
    }

	public function addPreferredSupplier(Request $request, product_products $product)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'inventory_code' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $productsPrefferedSuppliers = new productsPrefferedSuppliers();
        $productsPrefferedSuppliers->order_no = $request->input('order_no');
        $productsPrefferedSuppliers->supplier_id = $request->input('supplier_id');
        $productsPrefferedSuppliers->status = 1;
        $productsPrefferedSuppliers->description = $request->input('description');
        $productsPrefferedSuppliers->inventory_code = $request->input('inventory_code');
        $productsPrefferedSuppliers->product_id = $product->id;
        $productsPrefferedSuppliers->save();
		
        AuditReportsController::store('Products', 'Product Preferred Suppliers Added', 'Actioned By User', 0);
        return response()->json();
    }
	
	public function updatePreSupplier(Request $request, productsPrefferedSuppliers $preferred)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'inventory_code' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $preferred->order_no = $request->input('order_no');
        $preferred->supplier_id = $request->input('supplier_id');
        $preferred->description = $request->input('description');
        $preferred->inventory_code = $request->input('inventory_code');
        $preferred->update();
		
        AuditReportsController::store('Products', 'Stock Info Updated', 'Actioned By User', 0);
        return response()->json();
    }
	
   	public function stockLocation(product_products $product)
    {
        if ($product->status == 1) {
			$stockSettings = StockSettings::orderBy('id', 'desc')->first();
			$stockLevels = stockLevel::where('active', 1)->orderBy('id', 'desc')->get();

			$product = $product->load('productLocation','productLocation.stockLevelFive'
			,'productLocation.stockLevelFour','productLocation.stockLevelThree'
			,'productLocation.stockLevelTwo','productLocation.stockLevelOne');

            $data['products'] = $product;
            $data['stockSettings'] = $stockSettings;
            $data['stock_levels'] = $stockLevels;
            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Categories';
            AuditReportsController::store('Products', 'Product Location Page Accessed', 'Accessed by User', 0);
            return view('products.stock_location')->with($data);
			$data['page_title'] = 'Product Stock Location';
			$data['page_description'] = 'Product Stock Location';
			$data['breadcrumb'] = [
				['title' => 'Products', 'path' => '/products', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
				['title' => 'Setup', 'active' => 1, 'is_module' => 0]
			];
			$data['active_mod'] = 'Products';
			$data['active_rib'] = 'Categories';
        } 
		else return back();
    } 
	public function addStockLocation(Request $request, product_products $product)
    {
        $this->validate($request, [
            'stock_level_5' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $stock = new Stock_location();
        $stock->stock_level_5 = !empty($request->input('stock_level_5')) ? $request->input('stock_level_5') : 0;
        $stock->stock_level_4 = !empty($request->input('stock_level_4')) ? $request->input('stock_level_4') : 0;
        $stock->stock_level_3 = !empty($request->input('stock_level_3')) ? $request->input('stock_level_3') : 0;
        $stock->stock_level_2 = !empty($request->input('stock_level_2')) ? $request->input('stock_level_2') : 0;
        $stock->stock_level_1 = !empty($request->input('stock_level_1')) ? $request->input('stock_level_1') : 0;
        $stock->product_id = $product->id;
        $stock->save();
				
        AuditReportsController::store('Products', 'Stock Location Added', 'Actioned By User', 0);
        return response()->json();
    }
	
	public function updateStockLocation(Request $request, Stock_location $stock)
    {
        $this->validate($request, [
            'stock_level_5' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);
		$stock->stock_level_5 = !empty($request->input('stock_level_5')) ? $request->input('stock_level_5') : 0;
        $stock->stock_level_4 = !empty($request->input('stock_level_4')) ? $request->input('stock_level_4') : 0;
        $stock->stock_level_3 = !empty($request->input('stock_level_3')) ? $request->input('stock_level_3') : 0;
        $stock->stock_level_2 = !empty($request->input('stock_level_2')) ? $request->input('stock_level_2') : 0;
        $stock->stock_level_1 = !empty($request->input('stock_level_1')) ? $request->input('stock_level_1') : 0;
        $stock->update();
		
        AuditReportsController::store('Products', 'Stock Location Updated', 'Actioned By User', 0);
        return response()->json();
    }
	//add product to packages
    public function viewProducts(product_packages $package)
    {
        if ($package->status == 1) {
            $products = DB::table('packages_product_table')
                ->select('packages_product_table.*', 'Product_products.name as Prodname', 'Product_products.description as Proddescription', 'Product_products.price as price')
                ->leftJoin('Product_products', 'packages_product_table.product_product_id', '=', 'Product_products.id')
                ->where('packages_product_table.product_packages_id', $package->id)
                ->orderBy('Product_products.name')
                ->get();

            $newProducts = DB::table('Product_products')
                ->orderBy('Product_products.name')
                ->get();

            $data['page_title'] = 'Manage Products packages';
            $data['page_description'] = 'Products page';
            $data['breadcrumb'] = [
                ['title' => 'Products package', 'path' => '/Product/Product', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
                ['title' => 'Manage Product package', 'active' => 1, 'is_module' => 0]
            ];
            $data['products'] = $products;
            $data['package'] = $package;
            $data['newProducts'] = $newProducts;

            $data['active_mod'] = 'Products';
            $data['active_rib'] = 'Packages';
            AuditReportsController::store('Stock Management', 'Products Packadges Accessed', 'Accessed by User', 0);
            return view('products.packages_product')->with($data);
        } else {
            return back();
        }
    }

    public function product_packageSave(Request $request, product_packages $package)
    {
        $this->validate($request, [
            'product.*' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        // Save Products linked to package
        $Products = $docData['product'];
        foreach ($Products as $product) {
            //writting into the joining table, use attach & deattach to avoid duplicates
            $package->products_type()->detach(['product_product_id' => $product], ['product_packages_id' => $package->id]);
            $package->products_type()->attach(['product_product_id' => $product], ['product_packages_id' => $package->id]);
        }

        AuditReportsController::store('Products', 'Category Informations Edited', 'Edited by User', 0);
        return response()->json();
    }

    public function Search()
    {
        $hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $qualifications = DB::table('qualification')->orderBy('id')->get();
        $packages = product_packages::where('status', 1)->get();
        $products = product_products::where('status', 1)->get();
        $category = product_category::where('status', 1)->get();
        $promotions = product_promotions::where('status', 1)->get();
        $productss = DB::table('Product_products')
            ->select('Product_products.*', 'product_Category.name as catName')
            ->leftJoin('product_Category', 'Product_products.id', '=', 'product_Category.id')
            ->get();

        $data['page_title'] = 'Search';
        $data['page_description'] = 'Manage Product(s) Search';
        $data['breadcrumb'] = [
            ['title' => 'Products search', 'path' => '/Product/Search', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Manage Product Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        $data['doc_type'] = 'doc_type';
        $data['qualifications'] = $qualifications;
        $data['employees'] = $employees;
        $data['productss'] = $productss;
        $data['products'] = $products;
        $data['packages'] = $packages;
        $data['category'] = $category;
        $data['promotions'] = $promotions;
        $data['hr_people'] = $hr_people;

        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', 'Actioned By User', 0);
        return view('products.products_search')->with($data);
    }

    public function categorySave(Request $request, product_category $cat)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        // $doc_type = new doc_type($docData);
        $cat->name = $request->input('name');
        $cat->description = $request->input('description');
        $cat->stock_type = $request->input('stock_type');
        $cat->status = 1;
        $cat->save();
        AuditReportsController::store('Products', 'Category Added', 'Actioned By User', 0);
        return response()->json();
    }

    public function editCategory(Request $request, product_category $Category)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $Category->name = $request->input('name');
        $Category->description = $request->input('description');
		$Category->stock_type = $request->input('stock_type');
        $Category->update();
        AuditReportsController::store('Products', 'Category Informations Edited', 'Edited by User', 0);
        return response()->json(['new_name' => $Category->name, 'new_description' => $Category->description], 200);
    }

    public function categoryAct(product_category $Category)
    {
        if ($Category->status == 1) {
            $stastus = 0;
        } else {
            $stastus = 1;
        }

        $Category->status = $stastus;
        $Category->update();
        AuditReportsController::store('Products', 'Category status changed', 'Edited by User', 0);
        return back();
    }

    public function addProductType(Request $request, product_category $products)
    {
        $this->validate($request, [
            'name' => 'required',
            'product_code' => 'required',
            'price' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $documentType = new product_products($docData);
        $documentType->status = 1;
        $documentType->category_id = $products->id;
        $documentType->name = $docData['name'];
        $documentType->price = $docData['price'];
        $documentType->product_code = $docData['product_code'];
        $documentType->stock_type = !empty($products->stock_type) ? $products->stock_type : 0;
        $documentType->is_vatable = !empty($docData['is_vatable']) ? $docData['is_vatable'] : 0;
        $documentType->save();

        $newName = $docData['name'];
        $newPrice = $docData['price'];
        $newProductcode = $docData['product_code'];
        AuditReportsController::store('Products', 'product created', 'Edited by User', 0);
        return response()->json(['new_name' => $newName, 'price' => $newPrice, 'product_code' => $newProductcode], 200);
    }

    public function editProduct(Request $request, product_products $product)
    {
        $this->validate($request, [
            'name' => 'required',
            'product_code' => 'required',
            'price' => 'required',
        ]);

        $product->name = $request->input('name');
        $product->product_code = $request->input('product_code');
        $product->price = $request->input('price');
		$product->is_vatable = $request->input('is_vatable');
        $product->update();

        $newName = $request->input('name');
        $newproductCode = $request->input('product_code');
        $newPrice = $request->input('price');
        AuditReportsController::store('Products', 'Product Edited', 'Edited by User', 0);
        return response()->json(['new_name' => $newName, 'new_product_code' => $newproductCode, 'price' => $newPrice], 200);
    }

    //packages
    public function packageSave(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'discount' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $Product = $docData['product_id'];
        // Add Package
        $packs = new product_packages();
        $packs->name = $request->input('name');
        $packs->description = $request->input('description');
        $packs->discount = $request->input('discount');
        $packs->status = 1;
        $packs->save();
        //Save Products linked to package
        foreach ($Product as $products) {
            $pack_prod = new packages_product_table();
            $pack_prod->product_packages_id = $packs->id;
            $pack_prod->product_product_id = $products;
            $pack_prod->save();
        }
        return response()->json();
    }

    //
    public function editPackage(Request $request)
    {
        $this->validate($request, [
            //           'name' => 'required',
            //          'description' => 'required',
            // 'discount' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $Product = $docData['product_id'];
        // Add Package
        $packs = new product_packages();
        $packs->name = $request->input('name');
        $packs->description = $request->input('description');
        $packs->discount = $request->input('discount');
        $packs->status = 1;
        $packs->save();
        //Save Products linked to package
        foreach ($Product as $products) {
            $pack_prod = new packages_product_table();
            $pack_prod->product_packages_id = $packs->id;
            $pack_prod->product_product_id = $products;
            $pack_prod->update();
        }
        return response()->json();
    }

    //promotions
    public function promotionSave(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'discount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'package' => 'required_if:promotion_type,1',
            'product' => 'required_if:promotion_type,2',
        ]);

        $promData = $request->all();
        unset($promData['_token']);

        $package = $promData['package'];
        $product = $promData['product'];
        $startDate = str_replace('/', '-', $promData['start_date']);
        $startDate = strtotime($startDate);
        $endDate = str_replace('/', '-', $promData['end_date']);
        $endDate = strtotime($endDate);

        $prom = new product_promotions();
        $prom->name = $request->input('name');
        $prom->description = $request->input('description');
        $prom->discount = $request->input('discount');
        $prom->start_date = $startDate;
        $prom->end_date = $endDate;
        $prom->status = 1;
        if ($promData['promotion_type'] == 1) {
            $prom->product_packages_id = $package;
        } elseif ($promData['promotion_type'] == 2) {
            $prom->product_product_id = $product;
        }
        $prom->save();
        AuditReportsController::store('Product', "New Promotion Created ($prom->id)", 'Actioned By User', 0);

        return response()->json();
    }

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
        # check if old price exist
        $previousPrice = $product->productPrices->first();
        if (!empty($previousPrice)) {
            $previousPrice->end_date = $currentDate;
            $previousPrice->update();
        }
        $product->addNewPrice($price);

        AuditReportsController::store('Products', 'Job Title Category Added', "price: $priceData[price]", 0);
        return response()->json();
    }

    public function editPRICE(Request $request, product_packages $products)
    {
        $this->validate($request, [
            // 'name' => 'required',
            // 'description' => 'required',
        ]);

        $currentDate = time();
        $priceData = $request->all();
        unset($priceData['_token']);
        $price = new product_price($priceData);
        $price->status = 1;
        $price->price = $priceData['price'];
        $price->product_product_id = $products->id;
        $price->start_date = $currentDate;
        $price->update();

        AuditReportsController::store('Products', 'Job Title Category Added', "price: $priceData[price]", 0);
        return response()->json();
    }

    //search functions
    public function productSearch(Request $request)
    {
        $this->validate($request, [
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $productName = $request->product_name;
        $productDescription = $request->product_description;
        $productPrice = $request->product_price;
        $categoryID = $request->cat_id;
		$stockType = $request->cat_id;

        $tickets = DB::table('Product_products')
            ->select('Product_products.*', 'product_Category.name as catName')
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
			->where(function ($query) use ($stockType) {
                if (!empty($stockType)) {
                    $query->where('Product_products.stock_type', $stockType);
                }
            })
            ->orderBy('id')
            ->get();

        $data['page_title'] = 'Product Search ';
        $data['page_description'] = 'Product Search Page';
        $data['breadcrumb'] = [
            ['title' => 'Product Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Product Search Page', 'active' => 1, 'is_module' => 0]
        ];
        //
        $data['tickets'] = $tickets;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', 'Actioned By User', 0);
        return view('products.product_results')->with($data);
    }

    //
    public function categorySearch(Request $request)
    {
        $SysData = $request->all();
        unset($SysData['_token']);

        $categoryName = $request->category_name;
        $categoryDescription = $request->category_description;

        $category = DB::table('product_Category')
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

        $data['page_title'] = 'Category Search';
        $data['page_description'] = 'Category Search Page';
        $data['breadcrumb'] = [
            ['title' => 'Category Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Category Search Page', 'active' => 1, 'is_module' => 0]
        ];
        //
        $data['category'] = $category;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', 'Actioned By User', 0);
        return view('products.category_results')->with($data);
    }

    //
    public function packageSearch(Request $request)
    {
        $SysData = $request->all();
        unset($SysData['_token']);

        $package_name = $request->package_name;
        $package_description = $request->package_description;
        $product_type = $request->product_type;
        $package_discount = $request->package_discount;

        $packageSearch = DB::table('product_packages')
            ->select('product_packages.*', 'Product_products.name as product_name')
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

        $Products = product_products::orderBy('id', 'asc')->get();
        if (!empty($Products)) {
            $Products = $Products->load('PackadgesTypes');
        }

        $data['page_title'] = 'Package Search';
        $data['page_description'] = 'Package Search Results Page';
        $data['breadcrumb'] = [
            ['title' => 'Package Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Package Search Page', 'active' => 1, 'is_module' => 0]
        ];
        //
        $data['packageSearch'] = $packageSearch;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', 'Actioned By User', 0);
        return view('products.packages_results')->with($data);
    }

    //
    public function promotionSearch(Request $request)
    {
        $SysData = $request->all();
        unset($SysData['_token']);

        $promotion_name = $request->promotion_name;
        $promotion_discription = $request->promotion_discription;
        $actionFrom = $actionTo = 0;
        $actionDate = $request['promo_date'];

        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
            //return $actionFrom;
        }

        $Promotions = DB::table('product_promotions')
            ->select('product_promotions.*', 'Product_products.name as product_name')
            ->leftJoin('Product_products', 'product_promotions.product_product_id', '=', 'Product_products.id')
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

        $data['page_title'] = 'Promotions Search';
        $data['page_description'] = 'Promotions Search Results Page';
        $data['breadcrumb'] = [
            ['title' => 'Promotions Search', 'path' => '/Help Desk', 'icon' => 'fa fa-ticket', 'active' => 0, 'is_module' => 1],
            ['title' => 'Promotions Search Page', 'active' => 1, 'is_module' => 0]
        ];
        //
        $data['Promotions'] = $Promotions;
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'Search';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', 'Actioned By User', 0);
        return view('products.promotions_results')->with($data);
    }

    public function endPromotion(product_promotions $promotion)
    {
        $promotion->status = 0;
        $promotion->end_date = time();
        $promotion->update();
        return back()->with(['success_end' => 'The promotion has been successfully ended!']);
    }

    //##product activation!!
    public function ProdAct(product_products $Category)
    {
        if ($Category->status == 1) {
            $stastus = 0;
        } else {
            $stastus = 1;
        }

        $Category->status = $stastus;
        $Category->update();
        return back();
    }

    public function ProdPackAct(product_packages $product)
    {
        if ($product->status == 1) {
            $stastus = 0;
        } else {
            $stastus = 1;
        }

        $product->status = $stastus;
        $product->update();
        return back();
    }

    public function productpackagesAct(product_products $product)
    {
        if ($product->status == 1) {
            $stastus = 0;
        } else {
            $stastus = 1;
        }

        $product->status = $stastus;
        $product->update();

        return back();
    }

    /**
     * Show the Products/Services setup page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function setupIndex()
    {
        $serviceSettings = ProductServiceSettings::first();

        $data['serviceSettings'] = $serviceSettings;
        $data['page_title'] = 'Products';
        $data['page_description'] = 'Products/Services Settings';
        $data['breadcrumb'] = [
            ['title' => 'Products', 'path' => '/products', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Products';
        $data['active_rib'] = 'setup';
        AuditReportsController::store('Products', 'Products Setup Page Accessed', 'Accessed By User', 0);

        return view('products.setup')->with($data);
    }

    /**
     * Save the Products/Services settings.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function setupSave(Request $request)
    {
        $this->validate($request, [
            'service_rate' => 'bail|required|numeric|min:0.1'
        ]);
        $serviceData = $request->all();

        $serviceSettings = ProductServiceSettings::first();
        if ($serviceSettings) {
            $serviceSettings->service_rate = $serviceData['service_rate'];
            $serviceSettings->update($serviceData);
        } else {
            $serviceSettings = new ProductServiceSettings($serviceData);
            $serviceSettings->service_rate = $serviceData['service_rate'];
            $serviceSettings->save();
        }

        AuditReportsController::store('Products', 'Products/Services Settings Updated', 'Updated By User', 0);

        return back()->with('changes_saved', 'Services settings successfully changed.');
    }
}