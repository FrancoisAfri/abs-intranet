<?php

namespace App\Http\Controllers\Loans;

use App\HRPerson;
use App\Http\Controllers\TaskLibraryController;
use App\Http\Requests\LoanSetupRequest;
use App\Http\Requests\LoanRequest;
use App\Models\StaffLoanSetup;
use App\Models\StaffLoan;
use App\Mail\LoanApproval;
use App\Mail\LoanStaff;
use App\Mail\LoanApproved;
use App\Mail\LoanCommunications;
use App\Mail\LoanRejected;
use Carbon\Carbon;
use Exception;
use HttpException;
use HttpRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\BreadCrumpTrait;
use App\Traits\StoreImageTrait;
use App\Traits\uploadFilesTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Http\Controllers\AuditReportsController;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Facades\Datatables;
use GuzzleHttp;

class StaffLoanController extends Controller
{
	
	use BreadCrumpTrait, StoreImageTrait, uploadFilesTrait;
	
	public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = Auth::user()->load('person');
        $loans = StaffLoan::where('hr_id', $user->person->id)->orderBy('id', 'asc')->get();

        $data['page_title'] = "Loan Applications";
        $data['page_description'] = "View all Loan Applications";
        $data['breadcrumb'] = [
            ['title' => 'Staff Loan Management', 'path' => '/loan/view', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		
        $data['active_mod'] = 'Staff Loan Management';
        $data['active _rib'] = 'My Request';
        $data['loans'] = $loans;
        $data['statuses'] = StaffLoan::STATUS_SELECT;
        $data['user'] = $user;

        AuditReportsController::store('Staff Loan Management', 'Loan Applications Accessed', "Actioned By User", 0);
        return view('loan.view_application')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setUp()
    {
        $loan_configuration = DB::table('staff_loan_setups')->where("id", 1)->first();
        if(empty($loan_configuration))
		{
			$loan_config = new StaffLoanSetup();
			$loan_config->max_amount = 1;
			$loan_config->save();
			return redirect('/loan/setup');
		}
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->get();

        $data['page_title'] = "Loan Setup";
        $data['page_description'] = "setup for Loan ";
        $data['breadcrumb'] = [
            ['title' => 'Staff Loan Management', 'path' => '/loan/view', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Staff Loan Management';
        $data['active _rib'] = 'Setup';
        $data['loan_configuration'] = $loan_configuration;
        $data['employees'] = $employees;

        AuditReportsController::store('Staff Loan Management', 'Setup Page Accessed', "Actioned By User", 0);
        return view('loan.setup')->with($data);
    }
	// store setup
	public function setUpStore(LoanSetupRequest $request, StaffLoanSetup $loan)
    {
        // update setUp
		$loan->max_amount = !empty($request['max_amount']) ? $request['max_amount'] : 1;
		$loan->first_approval = !empty($request['first_approval']) ? $request['first_approval'] : 0;
		$loan->second_approval = !empty($request['second_approval']) ? $request['second_approval'] : 0;
		$loan->hr = !empty($request['hr']) ? $request['hr'] : 0;
		$loan->payroll = !empty($request['payroll']) ? $request['payroll'] : 0;
		$loan->finance = !empty($request['finance']) ? $request['finance'] : 0;
		$loan->finance_second = !empty($request['finance_second']) ? $request['finance_second'] : 0;
		$loan->update();
		
        AuditReportsController::store('Staff Loan Management', 'Setup Page Saved', "Actioned By User", 0);
        Alert::toast('Settings Successfully Changed', 'success');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoanRequest $request)
    {
		// get logged used details
		$employee = Auth::user()->load('person');
		// save application
        StaffLoan::create([
				'amount' => $request['amount'],
				'type' => $request['type'],
				'hr_id' => $employee->person->id,
				'reason' => $request['reason'],
				'repayment_month' => $request['repayment_month'],
				'status' => 1
			]);
		$type = (!empty($request['type']) && $request['type'] == 1) ? 'Advance': 'Loan';
		$setup = StaffLoanSetup::where('id',1)->first();
		$firstSetup = !empty($setup->first_approval) ? $setup->first_approval : 0;
		$secondSetup = !empty($setup->second_approval) ? $setup->second_approval : 0;
		$firstApp = HRPerson::where('id',$firstSetup)->first();
		$secondApp = HRPerson::where('id',$secondSetup)->first();

		// send email to all stakeholders
		// email to approvers
		// first approval
		if (!empty($firstApp['email']))
            Mail::to(
                $firstApp['email'])->send(new LoanApproval(
                $firstApp['first_name'],
                $type
            ));
		// second approval
		if (!empty($secondApp['email']))
            Mail::to(
                $secondApp['email'])->send(new LoanApproval(
                $secondApp['first_name'],
                $type
            ));
		// email to employee. LoanStaff
		
		if (!empty($employee->person->email))
            Mail::to(
                $employee->person->email)->send(new LoanStaff(
                $employee->person->first_name,
                $type
            ));
		
		AuditReportsController::store('Staff Loan Management', "New $type Application Saved", "Actioned By User", 0);
        
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approval()
    {
		// check if user login is a director
		
        $user = Auth::user()->load('person');
		$setup = StaffLoanSetup::where('id',1)->first();
		$firstSetup = !empty($setup->first_approval) ? $setup->first_approval : 0;
		$secondSetup = !empty($setup->second_approval) ? $setup->second_approval : 0;
		// if logged user is a director allow to see page
		if($user->person->id == $firstSetup || $user->person->id == $secondSetup || $user->id == 1)
		{
			$loans = StaffLoan::whereIn('status', array(1, 2))->orderBy('id', 'asc')->get();
			$loans = $loans->load('users');
			//return $loans;
			$data['page_title'] = "Loan Approval";
			$data['page_description'] = "View all Loan Approval";
			$data['breadcrumb'] = [
				['title' => 'Staff Loan Management', 'path' => '/loan/view', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
			];
			
			$data['active_mod'] = 'Staff Loan Management';
			$data['active _rib'] = 'Approval';
			$data['loans'] = $loans;
			$data['statuses'] = StaffLoan::STATUS_SELECT;
			$data['user'] = $user;

			AuditReportsController::store('Staff Loan Management', 'Loan Approval Accessed', "Actioned By User", 0);
			return view('loan.approval')->with($data);
		}
		else
		{
			AuditReportsController::store('Staff Loan Management', 'Unauthorized Loan Approval Page Access Attempt', "Actioned By User", 0);
			Alert::toast('You do not have permission to access this page!!! Please contact system administrator', 'warning');
			return redirect()->route('loan.view');	
		}
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	
	public function AcceptLoan(Request $request, StaffLoan $loan)
    {
		//return $loan;
		$user = Auth::user()->load('person');
		#query the hr person table
		$loan_configuration = DB::table('staff_loan_setups')->where("id", 1)->first();
        $hr = !empty($loan_configuration->hr) ? $loan_configuration->hr : 0;
		$finance = !empty($loan_configuration->finance) ? $loan_configuration->finance : 0;
		$financeSecond = !empty($loan_configuration->finance_second) ? $loan_configuration->finance_second : 0;
		$payroll = !empty($loan_configuration->payroll) ? $loan_configuration->payroll : 0;
		
		$hrUser = HRPerson::where('id',$hr)->first();
		$financeUser = HRPerson::where('id',$finance)->first();
		$financeSecondUser = HRPerson::where('id',$financeSecond)->first();
		$payrollUser = HRPerson::where('id',$payroll)->first();
		$type = (!empty($loan->type) && $loan->type == 1) ? 'Advance': 'Loan';
		
        //update leave application status
        if ($loan->status == 1)
		{
			// check if amoun is lower than max amount
			if ($loan->amount >= $loan_configuration->max_amount)
			{
				// update loan status
				$loan->status = 2;
				$loan->first_approval_date = strtotime(date('Y-m-d H:i:s'));
				$loan->first_approval = $user->person->id;
				$loan->update();
				Alert::toast('Loan application Partially Approved', 'success');
				AuditReportsController::store('Staff Loan Management', "New $type Application Partially Approved", "Actioned By User", 0);
			}
			else
			{
				//send email communication to employee and other stakeholders
				//email employee
				$employee = HRPerson::where('id',$loan->hr_id)->first();
				if (!empty($employee['email']))
					Mail::to(
						$employee['email'])->send(new LoanApproved(
						$employee['first_name'],$type
					));
				// email hr  LoanCommunications
				if (!empty($hrUser['email']))
					Mail::to(
						$hrUser['email'])->send(new LoanCommunications(
						$hrUser['first_name'],$type,$loan->id
					));
					
				if (!empty($financeUser['email']))
					Mail::to(
						$financeUser['email'])->send(new LoanCommunications(
						$financeUser['first_name'],$type,$loan->id
					));
				
				if (!empty($financeSecondUser['email']))
					Mail::to(
						$financeSecondUser['email'])->send(new LoanCommunications(
						$financeSecondUser['first_name'],$type,$loan->id
					));
				
				if (!empty($payrollUser['email']))
					Mail::to(
						$payrollUser['email'])->send(new LoanCommunications(
						$payrollUser['first_name'],$type,$loan->id
					));
				// update loan status
				$loan->status = 3;
				$loan->first_approval_date = strtotime(date('Y-m-d H:i:s'));
				$loan->first_approval = $user->person->id;
				$loan->update();
				Alert::toast('Loan application Approved', 'success');
			}
			
			// audit
			AuditReportsController::store('Staff Loan Management', "New $type Application Approved", "Actioned By User", 0);
        } 
		elseif ($loan->status == 2) 
		{
			//send email communication to employee and other stakeholders
			//email employee
			$employee = HRPerson::where('id',$loan->hr_id)->first();
			if (!empty($employee['email']))
				Mail::to(
					$employee['email'])->send(new LoanApproved(
					$employee['first_name'],$type
				));
			// email hr 
			if (!empty($hrUser['email']))
				Mail::to(
					$hrUser['email'])->send(new LoanCommunications(
					$hrUser['first_name'],$type,$loan->id
				));
				
			if (!empty($financeUser['email']))
				Mail::to(
					$financeUser['email'])->send(new LoanCommunications(
					$financeUser['first_name'],$type,$loan->id
				));
			
			if (!empty($financeSecondUser['email']))
				Mail::to(
					$financeSecondUser['email'])->send(new LoanCommunications(
					$financeSecondUser['first_name'],$type,$loan->id
				));
			
			if (!empty($payrollUser['email']))
				Mail::to(
					$payrollUser['email'])->send(new LoanCommunications(
					$payrollUser['first_name'],$type,$loan->id
				));
				
				// update loan status
				$loan->status = 3;
				$loan->second_approval_date = strtotime(date('Y-m-d H:i:s'));
				$loan->second_approval = $user->person->id;
				$loan->update();
				// audit
			AuditReportsController::store('Staff Loan Management', "New $type Application Approved", "Actioned By User", 0);
			Alert::toast('Loan application Approved', 'success');
        }
        return back()->with('success_application', "Loan application was successful.");
    }

    /**
     * @param Request $request
     * @param leave_application $levReject
     * @return JsonResponse
     */
    public function rejectLoan(Request $request, StaffLoan $loan)
    {
        $this->validate($request, [
            'reason' => 'required',
        ]);
        $loanData = $request->all();
        unset($loanData['_token']);
		
		$user = Auth::user()->load('person');
		$type = (!empty($loan->type) && $loan->type == 1) ? 'Advance': 'Loan';
        $usedetails = HRPerson::where('id',$loan->hr_id)
            ->select('first_name','surname','email')->first();

        #send rejection email
		if (!empty($usedetails['email']))
				Mail::to($usedetails['email'])->send(new LoanRejected(
					$usedetails['first_name'],$type));
		// update loan model
		$loan->rejection_reason = $request->input('reason');
        $loan->rejected_date = strtotime(date('Y-m-d H:i:s'));
        $loan->rejected_by = $user->person->id;
        $loan->status = 4;
        $loan->update();
		
		// audit
		AuditReportsController::store('Staff Loan Management', "New $type Application Rejected", "Actioned By User", 0);
        
        return response()->json();
    }
	
	public function reports(Request $request)
    {
        $status = !empty($request['status_id']) ? $request['status_id'] : 'all';
        $employee_id = !empty($request['employee_id']) ? $request['employee_id'] : 'all';
        $type = !empty($request['type']) ? $request['type'] : 'all';

        $loans = StaffLoan::getAllLoanByStatus($status, $employee_id,$type);

        $employees = HRPerson::all();

        $data = $this->breadCrump(
            "Staff Loan Management",
            "Loan Reports", "fa fa-lock",
            "Staff Loan Management Reports",
            "Staff Loan Management",
            "loan/view",
            "Staff Loan Management",
            "Staff Loan Management Reports"
        );


        $data['employees'] = $employees;
        $data['loans'] = $loans;
		$data['statuses'] = StaffLoan::STATUS_SELECT;

        return view('loan.reports.list-loan')->with($data);
    }
	// search
	public function search()
    {
        $employees = HRPerson::where('status', 1)
            ->orderBy('first_name', 'asc')
            ->orderBy('surname', 'asc')
            ->get();

        $data['employees'] = $employees;

        $data['page_title'] = "Staff Loan Management";
        $data['page_description'] = "Staff Loan Management Search";
        $data['breadcrumb'] = [
            ['title' => 'Staff Loan Management', 'path' => '/loan/view', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Staff Loan Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Staff Loan Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store(
            'Leave Management',
            'Loan Search Page Accessed',
            "Accessed By User",
            0
        );

        return view('loan.search')->with($data);
    }
	
	// search results
	public function SearchResults(Request $request)
    {
        $this->validate($request, [
        ]);
        $request = $request->all();
        unset($request['_token']);

        $actionFrom = $actionTo = 0;
        $hr_person_id = $request['hr_person_id'];
        $type = !empty($request['type']) ? $request['type'] : 0;
        $status = !empty($request['status']) ? $request['status'] : 0;

        $loans = StaffLoan::with('users','firstUsers','secondUsers','rejectedUsers')
			->orderBy('id', 'asc')
            ->where(function ($query) use ($hr_person_id) {
                if (!empty($hr_person_id)) {
                    $query->where('hr_id', $hr_person_id);
                }
            })
            ->where(function ($query) use ($status) {
                if (!empty($status)) {
                    $query->where('status', $status);
                }
            })
            ->where(function ($query) use ($type) {
                if (!empty($type)) {
                    $query->where('type', $type);
                }
            })
            ->get();

        $data['loans'] = $loans;
        $data['active_mod'] = 'Staff Loan Management';
		$data['statuses'] = StaffLoan::STATUS_SELECT;
        $data['active_rib'] = 'Search';
        $data['page_title'] = "Staff Loan Management";
        $data['page_description'] = "Search Results";
        $data['breadcrumb'] = [
            ['title' => 'Staff Loan Management', 'path' => 'loan/view', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search', 'active' => 1, 'is_module' => 0]
        ];

        AuditReportsController::store(
            'Staff Loan Management',
            'Search Results Page Accessed',
            "Accessed By User",
            0
        );

        return view('loan.search_results')->with($data);
    }
	// view loan
	public function viewLoanApplication(StaffLoan $loan)
    {
        if (!empty($loan)) $loan = $loan->load('users','firstUsers','secondUsers','rejectedUsers');

        AuditReportsController::store(
            'Staff Loan Management',
            'Staff Loan Management Viewed',
            "Accessed By User"
        );

        $data['loan'] = $loan;
        $data['statuses'] = StaffLoan::STATUS_SELECT;
        $data['active_mod'] = 'Staff Loan Management';
        $data['active_rib'] = 'Search';
        $data['page_title'] = "Staff Loan Management";
        $data['page_description'] = "View Application";
        $data['breadcrumb'] = [
            ['title' => 'Staff Loan Management', 'path' => 'loan/view', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Loan view', 'active' => 1, 'is_module' => 0]
        ];
        return view('loan.view_loan')->with($data);
    }
}
