<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DivisionLevel;
use App\HRPerson;
use App\HRRoles;
use App\ProcurementHistory;
use App\ProcurementQuotations;
use App\ProcurementApproval_steps;
use App\stockLevelFive;
use App\stockLevel;
use App\CompanyIdentity;
use App\product_products;
use App\ProcurementRequest;
use App\ProcurementSetup;
use App\ProcurementRequestItems;
use App\ContactCompany;
use App\ContactPerson;
use App\Mail\ProcurementNextStep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class procurementRequestController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}
	// Procurement Request index Page
	public function index()
    {
		$hrID = Auth::user()->person->id;
		$procurements = ProcurementRequest::where('employee_id',$hrID)->orderBy('date_created', 'desc')->limit(50)->get();
		if (!empty($procurements)) $procurements = $procurements->load('procurementItems','employees','employeeOnBehalf','requestStatus');

        $data['procurements'] = $procurements;
        $data['page_title'] = 'My Request(s)';
        $data['page_description'] = 'Procurement Request';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => '/procurement/create-request', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create Request', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Create Request';

        AuditReportsController::store('Procurement', 'Create Request', 'Accessed By User', 0);
        return view('procurement.create_request')->with($data);
    }
	
	// Procurement Request index Page
	public function create()
    {
		$hrID = Auth::user()->person->id;
		$products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
		$procurements = ProcurementRequest::where('employee_id',$hrID)->orderBy('date_created', 'asc')->get();
		if (!empty($procurements)) $procurements = $procurements->load('procurementItems','employees','employeeOnBehalf','requestStatus');
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

		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
		$data['products'] = $products;
        $data['procurements'] = $procurements;
        $data['page_title'] = 'Procurement Request';
        $data['page_description'] = 'Request Procurement';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => '/procurement/create-request', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Request Procurement', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Create Request';

        AuditReportsController::store('Procurement', 'Create Request', 'Accessed By User', 0);
        return view('procurement.new_request')->with($data);
    }
	// adjust 
	public function adjustProcurement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_type' => 'bail|required|integer|min:1',
            'title_name' => 'required',
        ]);
        $itemType = $request->input('item_type');

        $currentTime = time();
        //Get products
        $productIDs = $request->input('product_id');
        $products = [];
        if (count($productIDs) > 0) {
            $products = product_products::whereIn('id', $productIDs)
                ->with(['ProductPackages', 'productPrices' => function ($query) {
                    $query->orderBy('id', 'desc');
                    $query->limit(1);
                }])
                ->orderBy('category_id')
                ->orderBy('name')
                ->get();
            //get products current prices
            foreach ($products as $product) {
                $currentPrice = ($product->productPrices->first())
                    ? $product->productPrices->first()->price : (($product->price) ? $product->price : 0);
                $product->current_price = $currentPrice;
                $product->total_price = $currentPrice ;
            }
        }
		
        $data['page_title'] = 'Procurement';
        $data['page_description'] = 'Create Request';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => '/procurement', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Create', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'create Request';
        $data['delivery_type'] = $request->input('delivery_type');
        $data['item_type'] = $request->input('item_type');
        $data['date_created'] = $request->input('date_created');
        $data['title_name'] = $request->input('title_name');
        $data['employee_id'] = $request->input('employee_id');
        $data['on_behalf'] = $request->input('on_behalf');
        $data['on_behalf_employee_id'] = $request->input('on_behalf_employee_id');
        $data['special_instructions'] = $request->input('special_instructions');
        $data['justification_of_expenditure'] = $request->input('justification_of_expenditure');
        $data['detail_of_expenditure'] = $request->input('detail_of_expenditure');
        $data['products'] = $products;
        AuditReportsController::store('Procurement', 'Create Procurement Page Accessed', 'Accessed By User', 0);
        return view('procurement.adjust_procurement')->with($data);
    }
	// save request
    public function saveRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_type' => 'bail|required|integer|min:1',
            'employee_id' => 'bail|required|integer|min:1',
            'title_name' => 'bail|required',
            'quantity.*' => 'bail|required|integer|min:1',
            'service_quantity.*' => 'bail|required|integer|min:1',
            'price.*' => 'bail|required|integer|min:1',
            'description' => 'bail|required_if:quote_type,2',
            'description.*' => 'bail|required|max:1000',
        ]);
        $validator->validate();

        //return $request->all();
        $user = Auth::user()->load('person');
		$flow = ProcurementApproval_steps::where('status',1)->orderBy('id', 'asc')->first();
        $flowprocee = !empty($flow->step_number) ? $flow->step_number : 0;

        //save procurement request
        $procurement = new ProcurementRequest();
        DB::transaction(function () use ($procurement, $request, $user, $flowprocee) {
            $itemType = $request->input('item_type');
            $procurement->item_type = ($itemType > 0) ? $itemType : 0;
            $procurement->on_behalf_of = ($request->input('on_behalf_of')) ? $request->input('on_behalf_of') : 0;
            $procurement->delivery_type = ($request->input('delivery_type')) ? $request->input('delivery_type') : 0;
            $procurement->on_behalf_employee_id = ($request->input('on_behalf_employee_id')) ? $request->input('on_behalf_employee_id') : 0;
            $procurement->date_created =  time();
            $procurement->employee_id = $user->person->id;
            $procurement->title_name = ($request->input('title_name')) ? $request->input('title_name') : '';
            $procurement->add_vat = ($request->input('add_vat')) ? $request->input('add_vat') : 0;
            $procurement->special_instructions = ($request->input('special_instructions')) ? $request->input('special_instructions') : '';
            $procurement->detail_of_expenditure = ($request->input('detail_of_expenditure')) ? $request->input('detail_of_expenditure') : '';
            $procurement->justification_of_expenditure = ($request->input('justification_of_expenditure')) ? $request->input('justification_of_expenditure') : '';
            $procurement->status = $flowprocee;
            $procurement->save();

			/*$poNumber = 'PO' . sprintf('%07d', $procurement->id);
            $procurement->po_number = $poNumber;
            $procurement->update();
*/
            if ($itemType == 1) {
                //save procurement's Procurement items
                $prices = $request->input('price');
                $quantities = $request->input('quantity');
                $itemNames = $request->input('item_names');
                if ($prices) {
                    foreach ($prices as $productID => $price) {
						$price = preg_replace('/(?<=\d)\s+(?=\d)/', '', $price);
						$price = (double) $price;
						$products = product_products::where('id', $productID)->first();
						$item = new ProcurementRequestItems();
						$item->procurement_id = $procurement->id;
						$item->product_id = $productID;
						$item->category_id = $products->category_id;
						$item->quantity = $quantities[$productID];
						$item->item_name = $itemNames[$productID];
						$item->item_price = $price;
						$item->date_added = time();
						$item->save();
                    }
                }
            } 
			elseif ($itemType == 2) {
                //save procurement's Non Procurement Items
                $descriptions = $request->input('description');
                $prices = $request->input('no_price');
                $quantities = $request->input('no_quantity');
                if ($descriptions) {
                    foreach ($descriptions as $key => $description) {
                        $item = new ProcurementRequestItems();
                        $item->procurement_id = $procurement->id;
						$item->item_name = $description;
                        $item->quantity = $quantities[$key];
						$item->item_price = $prices[$key];
						$item->date_added = time();
                        $item->save();
                    }
                }
            }
			// Save procurement history
			$history = new ProcurementHistory();
			$history->action_date = time();
			$history->user_id = Auth::user()->person->id;
			$history->action = 'Procurement Request Created';
			$history->procurement_id = $procurement->id;
			$history->status = $flowprocee;
			$history->save();
        });
		$setup = ProcurementSetup::orderBy('id', 'desc')->first();
        // get approver ID and send them email
		if (!empty($flow->employee_id))
			$ApproverDetails = HRPerson::where('id', $flow->employee_id)->where('status', 1)->first();
		elseif (!empty($flow->role_id))
		{
			if (empty($setup->is_role_general) && $flow->division_id)
			{
				$users = DB::table('hr_users_roles')
				->leftJoin('hr_people', 'hr_users_roles.hr_id', '=', 'hr_people.id')
				->where('status', 1)
				->where('hr_people.division_level_5', $flow->division_id)
				->where('hr_users_roles.role_id', $flow->role_id)
				->pluck('hr_users_roles.hr_id');
			}
			else
			{
				$users = DB::table('hr_users_roles')
				->where('status', 1)
				->where('role_id', $flow->role_id)
				->pluck('hr_id');
			}
			foreach ($users as $user) {
				$usedetails = HRPerson::where('id', $user)->select('first_name','email')->first();
				$email = !empty($usedetails->email) ? $usedetails->email : ''; 
				$firstname = !empty($usedetails->first_name) ? $usedetails->first_name : ''; 
				if (!empty($email))
					Mail::to($email)->send(new ProcurementNextStep($firstname));
            }
		}
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
		// send email
		if (!empty($ApproverDetails->email) && !empty($ApproverDetails->first_name))
			Mail::to($ApproverDetails->email)->send(new ProcurementNextStep($ApproverDetails->first_name));

		AuditReportsController::store('Procurement', 'New Procurement Created', 'Create by user', 0);
        return redirect("/procurement/viewrequest/$procurement->id")->with(['success_add' => 'The request has been successfully Created!']);
    }
	
	// View Request items
	public function viewRequest(ProcurementRequest $procurement, $back='', $app='')
    {
		if (!empty($procurement)) $procurement = $procurement->load('procurementItems','procurementItems.products','procurementItems.categories','employees','employeeOnBehalf','requestStatus','histories','histories.historyEmployees','histories.statusHistory','quotations','quotations.companyQuote','quotations.clientQuote');
		//return $procurement;
		$hrID = Auth::user()->person->id;
		// Save procurement history
		$history = new ProcurementHistory();
		$history->action_date = time();
		$history->user_id = Auth::user()->person->id;
		$history->action = 'Procurement Request Viewed';
		$history->procurement_id = $procurement->id;
		$history->status = $procurement->status;
		$history->save();
			
		$products = product_products::where('stock_type', '<>',2)->whereNotNull('stock_type')->orderBy('name', 'asc')->get();
		$flow = ProcurementApproval_steps::where('status', 1)->orderBy('id', 'desc')->latest()->first();
		
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
		$companies = ContactCompany::where('status', 1)->orderBy('name', 'asc')->get();
        $contactPeople = ContactPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
		// calculate totals and subtotal
		$subtotal = 0;
		foreach ($procurement->procurementItems as $item) {
            $subtotal += ($item->item_price * $item->quantity);
		}
		// calculate VAT amount
        $vatAmount = ($procurement->add_vat == 1) ? $subtotal * 0.15 : 0;
		// calculate total amount
		$total = $subtotal + $vatAmount;

		$data['back'] = '';
		if (!empty($back) && empty($app)) $data['back'] = "/procurement/seach_request";
		if (!empty($app)) $data['back'] = "procurement";
		$deleiveryType = array(1 => "Delivery", 2=> "Collection");
		$data['total'] = $total;
		$data['vatAmount'] = $vatAmount;
		$data['subtotal'] = $subtotal;
		$data['deleiveryType'] = $deleiveryType;
		$data['flow'] = $flow;
		$data['companies'] = $companies;
        $data['contactPeople'] = $contactPeople;
		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
		$data['products'] = $products;
        $data['procurement'] = $procurement;
		$data['page_title'] = 'View Procurment Request';
        $data['page_description'] = 'Request Procurement Items';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => '/procurement/create-request', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Request Procurement Items', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Procurement', 'View Request', 'Accessed By User', 0);
        return view('procurement.view_request')->with($data);
    }
	//
	// ViewPrintrequest
	public function printRequest(procurement $procurement)
    {
		if (!empty($procurement)) $procurement = $procurement->load('procurementItems','procurementItems.products');
		return $procurement;
		$data['procurement'] = $procurement;	

		AuditReportsController::store('Procurement', 'Request Printed For Email', "Accessed By User");
		$companyDetails = CompanyIdentity::systemSettings();
		$companyName = $companyDetails['company_name'];
		$user = Auth::user()->load('person');

		$data['support_email'] = $companyDetails['support_email'];
		$data['company_name'] = $companyName;
		$data['full_company_name'] = $companyDetails['full_company_name'];
		$data['company_logo'] = url('/') . $companyDetails['company_logo_url'];
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		$data['file_name'] = 'ProcurementResquest';
		$view = view('procurement.pdf_procurement', $data)->render();
		$pdf = resolve('dompdf.wrapper');
		$pdf->getDomPDF()->set_option('enable_html5_parser', true);
		$pdf->loadHTML($view);
		return $pdf->output();
    }
	// save procurement quotation
	public function saveQuote(Request $request, ProcurementRequest $procurement)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'contact_person_id' => 'required',
            'attachment' => 'required',
        ]);

        $quotation = new ProcurementQuotations();
        $quotation->procurement_id = $procurement->id;
        $quotation->supplier_id = ($request->input('company_id')) ? $request->input('company_id') : 0;;
        $quotation->contact_id = ($request->input('contact_person_id')) ? $request->input('contact_person_id') : 0;;
        $quotation->comment = ($request->input('comment')) ? $request->input('comment') : 0;;
        $quotation->date_added = time();
        $quotation->total_cost = ($request->input('total_cost')) ? $request->input('total_cost') : 0;;
        $quotation->save();

        //Upload the quotation's doc
        if ($request->hasFile('attachment')) {
            $fileExt = $request->file('attachment')->extension();
            if (in_array($fileExt, ['pdf', 'doc']) && $request->file('attachment')->isValid()) {
                $fileName = time() . "_procurement_document_" . '.' . $fileExt;
                $request->file('attachment')->storeAs('Procurement Quotations', $fileName);
                //Update file name in the table
                $quotation->attachment = $fileName;
                $quotation->update();  
            }
        }
		// Save procurement history
		$history = new ProcurementHistory();
		$history->action_date = time();
		$history->user_id = Auth::user()->person->id;
		$history->action = 'New Quotation Added';
		$history->procurement_id = $procurement->id;
		$history->status = $procurement->status;
		$history->save();
        AuditReportsController::store('Procurement', 'Procurement Quotation Added', "Added By User", 0);
        return response()->json();
    }
	// update quotation
	
	/*public function updateQuote(Request $request, AppraisalPerk $perk)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'req_percent' => 'bail|required|numeric|min:1',
        ]);

        $perkData = $request->all();
        $perk->update($perkData);

        //Upload the perk's image
        if ($request->hasFile('img')) {
            $fileExt = $request->file('img')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('img')->isValid()) {
                $fileName = $perk->id . "_perk_img_" . '.' . $fileExt;
                $request->file('img')->storeAs('perks', $fileName);
                //Update file name in the appraisal_perks table
                $perk->img = $fileName;
                $perk->update();
            }
        }

        AuditReportsController::store('Performance Appraisal', 'Perk Details Edited By User', "Perk ID: $perk->id, Perk Name: $perk->name", 0);
        return response()->json(['perk_id' => $perk->id, 'perk_name' => $perk->name], 200);
    }*/
	
	//update
	public function updateRequest(Request $request, ProcurementRequest $procurement) {
        $this->validate($request, [
			'title_name' => 'required',
			'store_id' => 'required',
			'employee_id' => 'required',
			'on_behalf_employee_id' => 'required_if:on_behalf,1',
        ]);
        $stockRequest = $request->all();
        unset($stockRequest['_token']);

		// Update
        $procurement->employee_id = !empty($stockRequest['employee_id']) ? $stockRequest['employee_id'] : 0;
        $procurement->on_behalf_of = !empty($stockRequest['on_behalf_of']) ? $stockRequest['on_behalf_of'] : 0;
        $procurement->on_behalf_employee_id = !empty($stockRequest['on_behalf_employee_id']) ? $stockRequest['on_behalf_employee_id'] : 0;
        $procurement->date_created = time();
        $procurement->status = 1;
        $procurement->title_name = !empty($stockRequest['title_name']) ? $stockRequest['title_name'] : 0;
        $procurement->store_id = !empty($stockRequest['store_id']) ? $stockRequest['store_id'] : 0;
        $procurement->request_remarks = !empty($stockRequest['request_remarks']) ? $stockRequest['request_remarks'] : 0;
        $procurement->update();
		// Save Procurement Items
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
			$requestStockItems->request_stocks_id = $procurement->id;
			$requestStockItems->save();
			// next
            $numFiles++;
        }
        AuditReportsController::store('Procurement', 'Procurement Request Updated', 'Updated by User', 0);
        return response()->json();
    }
	
	// remove items
	public function removeItems(Request $request, RequestStockItems $item)
    {
		$procurementID = $item->request_stocks_id;
        $item->delete();

        AuditReportsController::store('Procurement', 'Requested Item Removed', "Removed By User");
        return response()->json();

    }
	//
	// Cancel Request 
	public function cancelRequest(Request $request, ProcurementRequest $procurement)
    {
        if ($procurement && in_array($procurement->status, [2, 3, 4, 5])) {
            $this->validate($request, [
                'cancellation_reason' => 'required'
            ]);
            $user = Auth::user()->load('person');
            $procurement->status = 10;
            $procurement->canceller_id = $user->person->id;
            $procurement->cancellation_reason = $request->input('cancellation_reason');
            $procurement->update();

            return response()->json(['success' => 'Request application successfully cancelled.'], 200);
        }
    }
	public function requestSearch()
    {
        $hrID = Auth::user()->person->id;
		$approvals = ProcurementApproval_steps::where('status', 1)->orderBy('id', 'desc')->get();
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

		$data['approvals'] = $approvals;
		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
		$data['page_title'] = 'Search Request';
        $data['page_description'] = 'Search Request Items';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => '/procurement/seach_request', 'icon' => 'fa fa-cart-arrow-down', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search Request', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Procurement', 'Job Card card search Page Accessed', "Job Card card search Page Accessed", 0);
        return view('procurement.search_request')->with($data);
    }
	// Search request	
	public function requestResults(Request $request)
    {
        $SysData = $request->all();
        unset($SysData['_token']);
        $titleName = $request['title_name'];
        $poOrder = $request['po_order'];
        $employeeID = $request['employee_id'];
        $onBehalf = $request['on_behalf_employee_id'];
        $status = $request['status'];
        $createFrom = $createTo = $approvedFrom = $approvedTo = 0;
        $createdDate = $request['requested_date'];
        $approvedDate = $request['approved_date'];
        if (!empty($createdDate)) {
            $createExplode = explode('-', $createdDate);
            $createFrom = strtotime($createExplode[0]);
            $createTo = strtotime($createExplode[1]);
        }        
		if (!empty($approvedDate)) {
            $approedExplode = explode('-', $approvedDate);
            $approvedFrom = strtotime($approedExplode[0]);
            $approvedTo = strtotime($approedExplode[1]);
        }
        $procurements = DB::table('procurement_requests')
            ->select('procurement_requests.*','hr_people.first_name as firstname'
			, 'hr_people.surname as surname'
			, 'procurement_approval_steps.step_name as status_name')
            ->leftJoin('hr_people', 'procurement_requests.employee_id', '=', 'hr_people.id')
            ->leftJoin('procurement_approval_steps','procurement_requests.status', '=', 'procurement_approval_steps.id')
			->where(function ($query) use ($poOrder) {
                if (!empty($poOrder)) {
                    $query->where('procurement_requests.po_number', 'ILIKE', "%$poOrder%");
                }
            })
            ->where(function ($query) use ($titleName) {
                if (!empty($titleName)) {
                    $query->where('procurement_requests.title_name', 'ILIKE', "%$titleName%");
                }
            })
            ->where(function ($query) use ($employeeID) {
                if (!empty($employeeID)) {
                    $query->where('procurement_requests.employee_id', $employeeID);
                }
            })
			->where(function ($query) use ($onBehalf) {
                if (!empty($onBehalf)) {
                    $query->where('procurement_requests.on_behalf_employee_id', $onBehalf);
                }
            })
			->where(function ($query) use ($status) {
                if (!empty($status)) {
                    $query->where('procurement_requests.status', $status);
                }
            })
			->where(function ($query) use ($createFrom, $createTo) {
				if ($createFrom > 0 && $createTo > 0) {
						$query->whereBetween('procurement_requests.date_created', [$createFrom, $createTo]);
				}
			})			
			->where(function ($query) use ($approvedFrom, $approvedTo) {
				if ($approvedFrom > 0 && $approvedTo > 0) {
						$query->whereBetween('procurement_requests.date_approved', [$createFrom, $createTo]);
				}
			})
            ->orderBy('procurement_requests.date_created', 'desc')
            ->get();
			//return $procurements;  
        $data['procurements'] = $procurements;
        $data['page_title'] = "Request Search Results";
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Search Request';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => 'procurement/seach_request', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Request Search ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Search';
		
        AuditReportsController::store('Procurement', 'Job Card Search Page Accessed', "Job Card card search Page Accessed", 0);
        return view('procurement.search_request_result')->with($data);
    }
	// Request Approval landing page
	public function requestApprovals()
    {
		$roleArray = array();
        $user_id = Auth::user()->person->user_id;
		$userID = Auth::user()->person->id;
		$roles = DB::table('hr_roles')
			->select('hr_roles.id as role_id')
			->leftJoin('hr_users_roles', 'hr_roles.id', '=', 'hr_users_roles.role_id')
			->where('hr_roles.status', 1)
			->where('hr_users_roles.hr_id',$userID)
			->get();
		//
		foreach ($roles as &$role) {
			$roleArray[] = $role->role_id;
		}
		// get role status
        $userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'procurement')
            ->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $user_id)
            ->pluck('user_id')->first();
        //
		$processflow = ProcurementApproval_steps::where('status', 1)->Where('employee_id',$userID)->orderBy('id', 'asc')
		->get();
		 // get status from roles
		 $roleFlow = ProcurementApproval_steps::where('status', 1)
		->Where(function ($query) use ($roleArray) {
			if (!empty($roleArray)) {
				$query->whereIn('role_id', $roleArray);
			}
        })
		->orderBy('id', 'asc')
		->get();
        $lastProcess = ProcurementApproval_steps::where('status', 1)->orderBy('id', 'desc')->first();
        $lastStepNumber = !empty($lastProcess->step_number) ? $lastProcess->step_number : 0;

        $statuses = $statusesRole = $statusArray = array();
        $status = $statusRole = '';
        $rowcolumn = $processflow->count();
		
        if ($rowcolumn > 0 || !empty($userAccess)) 
		{
            if (empty($userAccess))
			{
				foreach ($roleFlow as $process) {
                    $statusRole .= $process->step_number . ',';
                }
                $statusRole = rtrim($statusRole, ",");
                $statusesRole = (explode(",", $statusRole));
				
                foreach ($processflow as $process) {
                    $status .= $process->step_number . ',';
                }
                $status = rtrim($status, ",");
                $statuses = (explode(",", $status));
				$statusArray  = array_merge($statuses, $statusesRole);
            }

            $procurements = DB::table('procurement_requests')
                ->select('procurement_requests.*','hr_people.first_name as firstname', 'hr_people.surname as surname'
				,'hp.first_name as hp_firstname', 'hp.surname as hp_surname'
				, 'procurement_approval_steps.step_name')
               ->leftJoin('hr_people', 'procurement_requests.employee_id', '=', 'hr_people.id')
                ->leftJoin('hr_people as hp', 'procurement_requests.on_behalf_employee_id', '=', 'hp.id')
				->leftJoin('procurement_approval_steps', 'procurement_requests.status', '=', 'procurement_approval_steps.step_number')
				->where(function ($query) use ($statusArray) {
                    if (!empty($statusArray)) {
                        $query->whereIn('procurement_requests.status', $statusArray);
                    }
                })
                ->where(function ($query) use ($lastStepNumber) {
                    if (!empty($lastStepNumber)) {
                        $query->where('procurement_requests.status', '!=', $lastStepNumber);
                    }
                })
                ->orderBy('procurement_requests.date_created', 'desc')
                ->get();

            $steps = ProcurementApproval_steps::latest()->first();
            $stepnumber = !empty($steps->step_number) ? $steps->step_number : 0;

            $data['page_title'] = "Procurement";
            $data['page_description'] = "Request Management";
            $data['breadcrumb'] = [
                ['title' => 'Procurement', 'path' => 'procurement/request_approval', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Request Approval ', 'active' => 1, 'is_module' => 0]
            ];

            $data['stepnumber'] = $stepnumber;
            $data['procurements'] = $procurements;
            $data['active_mod'] = 'Procurement';
			$data['active_rib'] = 'Request Approval';
		
            AuditReportsController::store('Procurement', 'Job Card Approvals Page Accessed', "Accessed By User", 0);
            return view('procurement.procurement_approval')->with($data);
        }
		else return back()->with('success_edit', "you are not permitted to view this page.");
    }
	// Approve Request
	public function appoveRequest(Request $request)
    {
        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
		$setup = ProcurementSetup::orderBy('id', 'desc')->first();
        //$setup->email_po_to_supplier
		$results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);

        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'procurementappprove_'))) 
			{
                $aValue = explode("_", $key);
                $name = $aValue[0];
                $procurementID = $aValue[1];
				$procurement = ProcurementRequest::where('id', $procurementID)->first();
				if (!empty($procurement)) $procurement = $procurement->load('procurementItems');

				$subtotal = 0;
				foreach ($procurement->procurementItems as $item) {
					$subtotal += ($item->item_price * $item->quantity);
				}
				// calculate VAT amount
				$vatAmount = ($procurement->add_vat == 1) ? $subtotal * 0.15 : 0;
				// calculate total amount
				$total = $subtotal + $vatAmount;
				
				// get max amount allow for this step
				$currentStep = ProcurementApproval_steps::where('step_number', $sValue)->where('status', 1)->first();
				$maxAmount = !empty($currentStep->max_amount) ? $currentStep->max_amount : 0; 
				if ($total <= $maxAmount)
				{
					// Approve request
					$steps = ProcurementApproval_steps::latest()->first();
					$procurement->status = $steps->step_number;
					$procurement->update();
					if (!empty($setup->email_po_to_supplier) && !empty($setup->email_role))
					{
						$jcAttachment = $this->viewRequestPrint($procurement);
						$users = HRUserRoles::
						leftJoin('procurement_history,', 'hr_users_roles.hr_id', '=', 'hr_people.id')
						->where('status', 1)
						->where('role_id', $setup->email_role)->pluck('hr_id');
						foreach ($users as $userID) {
							$userDetails = HRPerson::where('id', $userID)->select('first_name', 'email')->first();
							Mail::to($userDetails->email)->send(new stockDeliveryNote($userDetails->first_name, $jcAttachment,$procurement->request_number));
						}
					}
					//// Send email to employee who did the request
					if (!empty($procurement->employee_id))
					{
						$empDetails = HRPerson::where('id', $procurement->employee_id)->select('first_name', 'email')->first();
						if (!empty($empDetails->email))
						Mail::to($empDetails->email)->send(new StockRequestApproved($empDetails->first_name,$procurement->request_number));
					}
					// Save procurement history
					$history = new ProcurementHistory();
					$history->action_date = time();
					$history->user_id = Auth::user()->person->id;
					$history->action = 'Procurement Request Approved';
					$history->procurement_id = $procurement->id;
					$history->status = $steps->step_number;
					$history->save();
				}
				else
				{
					$processflow = ProcurementApproval_steps::where('step_number', '>', $sValue)->where('status', 1)->orderBy('step_number', 'asc')->first();
					$procurement->status = $processflow->step_number;
					$procurement->update();
					// Save procurement history
					$history = new ProcurementHistory();
					$history->action_date = time();
					$history->user_id = Auth::user()->person->id;
					$history->action = 'Procurement Request Approved';
					$history->procurement_id = $procurement->id;
					$history->status = $processflow->step_number;
					$history->save();
					// get approver ID and send them email
					if (!empty($processflow->employee_id))
						$ApproverDetails = HRPerson::where('id', $processflow->employee_id)->where('status', 1)->first();
					elseif (!empty($processflow->role_id))
					{
						if (empty($setup->is_role_general) && $processflow->division_id)
						{
							$users = DB::table('hr_users_roles')
							->leftJoin('hr_people', 'hr_users_roles.hr_id', '=', 'hr_people.id')
							->where('status',1)
							->where('hr_people.division_level_5', $processflow->division_id)
							->where('hr_users_roles.role_id', $processflow->role_id)
							->pluck('hr_users_roles.hr_id');
						}
						else
						{
							$users = DB::table('hr_users_roles')
							->where('status', 1)
							->where('role_id', $processflow->role_id)
							->pluck('hr_id');
						}
						foreach ($users as $user) {
							$usedetails = HRPerson::where('id', $user)->select('first_name','email')->first();
							$email = !empty($usedetails->email) ? $usedetails->email : ''; 
							$firstname = !empty($usedetails->first_name) ? $usedetails->first_name : ''; 
							if (!empty($email))
								Mail::to($email)->send(new ProcurementNextStep($firstname));
						}
					}
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
					// send email
					if (!empty($ApproverDetails->email) && !empty($ApproverDetails->first_name))
						Mail::to($ApproverDetails->email)->send(new ProcurementNextStep($ApproverDetails->first_name));
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

					$stockReject = ProcurementRequest::where('id', $iID)->first();
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

        AuditReportsController::store('Procurement', 'Job card Approvals Page', "Accessed By User",0);
        return back();
    }
	
	// Approve Request
	public function appoveRequestSingle(ProcurementRequest $procurement)
    {
		$totalPrice = 0;
		// get all product attached to this request and calculate total price pluck('product_id')
		$items = RequestStockItems::where('request_stocks_id',$procurement->id)->get();
	
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
		$currentStep = ProcurementApproval_steps::where('step_number', $procurement->status)->where('status', 1)->first();
		$maxAmount = !empty($currentStep->max_amount) ? $currentStep->max_amount : 0; 
		
		if ($totalPrice <= $maxAmount)
		{
			// Approve request
			$steps = ProcurementApproval_steps::latest()->first();
			$procurement->status = $steps->step_number;
			$procurement->update();
			// Procurement History and deduct from procurement
			foreach ($items as $item) {
				
				$stockA = procurement::where('product_id', $item->product_id)->where('store_id',$procurement->store_id)->first();
				if (!empty($stockA->id))
				{
					$availblebalance = !empty($stockA->avalaible_stock) ? $stockA->avalaible_stock : 0;
					$transactionbalance = $availblebalance - $item->quantity;
					// Update procurement availble balance
					$stockA->avalaible_stock = $transactionbalance;
					$stockA->update();
					// Update procurement history
					$category = product_products::where('id',$item->product_id)->first();
					$history = new stockhistory();
					$history->product_id = $item->product_id;
					$history->category_id = $category->category_id;
					$history->avalaible_stock = $transactionbalance;
					$history->action_date = time();
					$history->balance_before = $availblebalance;
					$history->balance_after = $transactionbalance;
					$history->action = 'Procurement Items Out';
					$history->user_id = Auth::user()->person->id;
					$history->user_allocated_id = 0;
					$history->save();
				}
			}
			// send email for request approval to request creator
			
			// sendemail to procurement controller delivery note
			$jcAttachment = $this->viewRequestPrint($procurement);
			$role = HRRoles::where('description', 'Procurement Controller')->first();
			$users = HRUserRoles::where('role_id', $role->id)->pluck('hr_id');
			foreach ($users as $userID) {
				$userDetails = HRPerson::where('id', $userID)->select('first_name', 'email')->first();
				Mail::to($userDetails->email)->send(new stockDeliveryNote($userDetails->first_name, $jcAttachment,$procurement->request_number));
			}
			//// Send sms and email to mechanic
			if (!empty($procurement->employee_id))
			{
				$empDetails = HRPerson::where('id', $procurement->employee_id)->select('first_name', 'email')->first();
				if (!empty($empDetails->email))
				Mail::to($empDetails->email)->send(new StockRequestApproved($empDetails->first_name,$procurement->request_number));
			}
		}
		else
		{
			$processflow = ProcurementApproval_steps::where('step_number', '>', $procurement->status)->where('status', 1)->orderBy('step_number', 'asc')->first();
			$procurement->status = $processflow->step_number;
			$procurement->update();
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
				Mail::to($ApproverDetails->email)->send(new stockApprovals($ApproverDetails->first_name, $procurement->request_number));
		}
        return back();
    }
	// 	// Approve Request
	public function rejectRequestSingle(Request $request, ProcurementRequest $procurement)
    {
		$this->validate($request, [
			'rejection_reason' => 'required',
        ]);
        $stockRequest = $request->all();
        unset($stockRequest['_token']);

		$procurement->status = 0;
		$procurement->rejection_reason = $stockRequest['rejection_reason'];
		$procurement->rejected_by = Auth::user()->person->id;
		$procurement->rejection_date = time();
		$procurement->update();

		//// Send sms and email to mechanic
		if (!empty($procurement->employee_id))
		{
			$empDetails = HRPerson::where('id', $procurement->employee_id)->select('first_name', 'email')->first();
			if (!empty($empDetails->email))
				Mail::to($empDetails->email)->send(new stockRequestRejected($empDetails->first_name,$procurement->request_number));
		}
        return back();
    }
	
	public function showSetup()
    {
        $procurementSetup = ProcurementSetup::orderBy('id', 'desc')->first();
		$roles = DB::table('hr_roles')
				->where('status', 1)
				->get();
        $data['procurementSetup'] = $procurementSetup;
        $data['roles'] = $roles;
        $data['page_title'] = "Procurement";
        $data['page_description'] = " Procurement Setup";
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => 'procurement/setup', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Procurement', 'view Stock Setup Page', "Accessed By User", 0);
        return view('procurement.setup')->with($data);
    }
	
	public function addSetup(Request $request,ProcurementSetup $setup)
    {
         $this->validate($request, [
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $setup->is_role_general = $request->input('is_role_general');
        $setup->email_po_to_supplier = $request->input('email_po_to_supplier');
        $setup->amount_required_double = $request->input('amount_required_double');
        $setup->email_role = $request->input('email_role');
        $setup->save();
        return back();

        AuditReportsController::store('Stock Management', 'Add Stock Settinfs', "Added By User", 0);
    }
	
	// procurement edit
	public function updateproIndex(ProcurementRequest $procurement)
    {
        $procurement->load('procurementItems','procurementItems.products');
		$products = product_products::where('status', 1)->where('stock_type', '<>',1)->orderBy('name', 'asc')->get();
		$employees = DB::table('hr_people')
                ->select('hr_people.*')
                ->where('hr_people.status', 1)
                ->where('hr_people.id', $procurement->employee_id)
				->orderBy('first_name', 'asc')
				->orderBy('surname', 'asc')
				->get();

		$employeesOnBehalf = DB::table('hr_people')
			->select('hr_people.*')
			->where('hr_people.status', 1)
			->orderBy('first_name', 'asc')
			->orderBy('surname', 'asc')
			->get();

		$data['employees'] = $employees;
		$data['employeesOnBehalf'] = $employeesOnBehalf;
        $data['page_title'] = 'Procurement';
        $data['page_description'] = 'Modify Request';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => '/procurement/seach_request', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Modify', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'search';
        $data['procurement'] = $procurement;
        $data['products'] = $products;
        AuditReportsController::store('Procurement', 'Update Procurement Request page Accessed', 'Accessed By User', 0);

        return view('procurement.edit_procurement')->with($data);
    }
	
	public function adjustProcurementEdit(Request $request, ProcurementRequest $procurement)
    {
        $procurement->load('procurementItems','procurementItems.products');
		$validator = Validator::make($request->all(), [
            'item_type' => 'bail|required|integer|min:1',
            'title_name' => 'required',
        ]);
		
        $itemType = $request->input('item_type');

        $validator->after(function ($validator) use ($request, $itemType) {
            $products = $request->input('product_id');

            if (($itemType == 1) && !$products) {
                $validator->errors()->add('product_id', 'Please make sure you select at least a product or a package.');
            }
        });

        $validator->validate();
        //Get products
        $productIDs = $request->input('product_id');
        $products = [];
        if (count($productIDs) > 0) {
            $products = product_products::whereIn('id', $productIDs)
                ->with(['ProductPackages', 'productPrices' => function ($query) {
                    $query->orderBy('id', 'desc');
                    $query->limit(1);
                }])
                ->orderBy('category_id')
                ->orderBy('name')
                ->get();
            //get products current prices
            foreach ($products as $product) {
                $currentPrice = ($product->productPrices->first())
                    ? $product->productPrices->first()->price : (($product->price) ? $product->price : 0);
                $product->current_price = $currentPrice;
                $product->total_price = $currentPrice ;
            }
        }

        $data['page_title'] = 'Procurement';
        $data['page_description'] = 'Modify Request';
        $data['breadcrumb'] = [
            ['title' => 'Procurement', 'path' => '/procurement/seach_request', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Procurement';
        $data['active_rib'] = 'Search';
        $data['itemType'] = $itemType;
        $data['delivery_type'] = $request->input('delivery_type');
        $data['title_name'] = $request->input('title_name');
        $data['employee_id'] = $request->input('employee_id');
        $data['on_behalf'] = $request->input('on_behalf');
        $data['on_behalf_employee_id'] = $request->input('on_behalf_employee_id');
        $data['special_instructions'] = $request->input('special_instructions');
        $data['justification_of_expenditure'] = $request->input('justification_of_expenditure');
        $data['detail_of_expenditure'] = $request->input('detail_of_expenditure');
        $data['procurement'] = $procurement;
        $data['products'] = $products;
        AuditReportsController::store('Procurement', 'Create Quote Page Accessed', 'Accessed By User', 0);

        return view('procurement.edit_adjust_procurement')->with($data);
    }

    /**
     * Update a procurement
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function updateProcurement(Request $request, ProcurementRequest $procurement)
    {
        $validator = Validator::make($request->all(), [
            'item_type' => 'bail|required|integer|min:1',
            'title_name' => 'required',
        ]);
        $validator->validate();
		
		DB::transaction(function () use ($procurement, $request) {
            $itemType = $request->input('item_type');
            $procurement->item_type = ($itemType > 0) ? $itemType : null;
            $procurement->title_name = ($request->input('title_name')) ? $request->input('title_name') : '';
            $procurement->delivery_type = ($request->input('delivery_type')) ? $request->input('delivery_type') : 0;
            $procurement->employee_id = $request->input('employee_id');
            $procurement->on_behalf_of = ($request->input('on_behalf') > 0) ? $request->input('on_behalf') : null;
            $procurement->on_behalf_employee_id = $request->input('on_behalf_employee_id');
			$procurement->special_instructions = $request->input('special_instructions');
            $procurement->justification_of_expenditure = $request->input('justification_of_expenditure');
            $procurement->detail_of_expenditure = $request->input('detail_of_expenditure');
            $procurement->add_vat = ($request->input('add_vat')) ? $request->input('add_vat') : null;
            $procurement->update();

            if ($itemType == 1) {
                //save procurement's Procurement items
                $prices = $request->input('price');
                $quantities = $request->input('quantity');
                $itemNames = $request->input('item_names');
				// delete all previous record from table
				DB::table('procurement_request_items')->where('procurement_id', $procurement->id)->delete();
                if ($prices) {
                    foreach ($prices as $productID => $price) {
						$price = preg_replace('/(?<=\d)\s+(?=\d)/', '', $price);
						$price = (double) $price;
						$products = product_products::where('id', $productID)->first();
						$item = new ProcurementRequestItems();
						$item->procurement_id = $procurement->id;
						$item->product_id = $productID;
						$item->category_id = $products->category_id;
						$item->quantity = $quantities[$productID];
						$item->item_name = $itemNames[$productID];
						$item->item_price = $price;
						$item->date_added = time();
						$item->save();
                    }
                }
				
            } 
			elseif ($itemType == 2) {
                //save procurement's Non Procurement Items
                $descriptions = $request->input('description');
                $prices = $request->input('no_price');
                $quantities = $request->input('no_quantity');
				// delete all previous record from table
				DB::table('procurement_request_items')->where('procurement_id', $procurement->id)->delete();
                if ($descriptions) {
                    foreach ($descriptions as $key => $description) {
                        $item = new ProcurementRequestItems();
                        $item->procurement_id = $procurement->id;
						$item->item_name = $description;
                        $item->quantity = $quantities[$key];
						$item->item_price = $prices[$key];
						$item->date_added = time();
                        $item->save();
                    }
                }
            }
			});
			// Save procurement history
			$history = new ProcurementHistory();
			$history->action_date = time();
			$history->user_id = Auth::user()->person->id;
			$history->action = 'Procurement Request Modified';
			$history->procurement_id = $procurement->id;
			$history->status = $procurement->status;
			$history->save();
		
        AuditReportsController::store('Procurement', 'Procurement Request Modified', 'Modified by user', 0);
        return redirect("/procurement/viewrequest/$procurement->id")->with(['success_add' => 'The procurement request has been successfully updated!']);
	}
}