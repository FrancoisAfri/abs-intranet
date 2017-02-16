<?php

namespace App\Http\Controllers;

use App\HRPerson;
use App\InterestRate;
use App\LoanStatement;
use App\Mail\ApprovedLoanApplication;
use App\Mail\RejectedLoanApplication;
use App\Mail\RequestDirectorApproval;
use App\PrimeRate;
use App\User;
use Carbon\Carbon;
use Faker\Provider\lv_LV\Person;
use Illuminate\Http\Request;

use App\Http\Requests;
//use Storage;
use App\Mail\loanMail;
use App\Loan;
use App\loan_assets;
use App\loan_company;
use App\loan_documents;
use App\loan_employment_history;
use App\loan_application_contacts;
use App\loan_income;
use App\loan_insurance;
use App\loan_liability;
use App\loan_status;
use App\LoanApplicationStatusTrail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanApplicationController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
   /* public function index() {
        $data['page_title'] = "Loan Application";
        return view('laon.application_form')->with($data);
    }*/

    public function create()
	{
        $data['page_title'] = "Loan Application";
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Application', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'loan';
		$data['active_rib'] = 'apply';
        return view('loan.application')->with($data);
    }

	public function loan_view(Loan $loan)
	{
		$loan->load('loanCompany', 'loanDoc', 'loanHistory', 'loanInsurance', 'loanLiabilities', 'loanStatus', 'loanAssets', 'loanIncome', 'loanContacts');
		$applicationTypeArray = array(1 => 'Company', 2 => 'Close corporation', 3 => 'Sole proprietor', 4 => 'Individual', 5 => 'Surety');
		$periodArray = array(1 => '1 Year', 2 => '2 Years', 3 => '3 Years', 4 => '4 Years', 5 => '5 Years', 6 => '6 Years', 7 => '7 Years', 8 => '8 Years', 9 => '9 Years', 10 => '10 Years');
		$bondTypeArray = array(1 => 'Bond free', 2 => 'Bonded', 3 => 'In your name', 4 => 'In your spouse’s name', 5 => 'Both', 6 => 'Other');
		$propertyTypeArray = array(1 => 'House', 2 => 'Town house', 3 => 'Flat');
		$assetStateArray = array(1 => 'New', 2 => 'Used');
		$howMarriedArray = array(1 => 'ANC', 2 => 'COP');
		$yesNoArray = array(1 => 'Yes', 2 => 'No');
		$data['page_title'] = "Loan Application View";
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Application Details', 'active' => 1, 'is_module' => 0]
		];
		$user = Auth::user()->load('person');
		/*
		$type = $user['type'];
		if ($loan->loanStatus->status == 0 && $user->person->position == 2) $howbutton = 1;
		elseif ($loan->loanStatus->status == 1 && $user->person->position == 1) $howbutton = 1;
		elseif  ($type == 3) $howbutton = 1;
		else  $howbutton = 0;
		*/

		if ($user->id != $loan->user_id && empty($user->person->position) && $user->type != 3)
			die("Sorry you do not have access to view data on this page please return to dashboard");
		$dirApprovalAudit = LoanApplicationStatusTrail::where('loan_id', $loan->id)
			->where('action', 4)
			->orderBy('id', 'desc')
			->limit(1)
			->get();
		$howbutton = 0;
		$approvalLvl = 0;
		$canDoSecApproval = 0;
		if ($loan->loanStatus->status == 1 && $user->person->position == 2) { //Administrator
			$howbutton = 1;
			$approvalLvl = 1;
		}
		elseif ($loan->loanStatus->status == 3 && $user->person->position == 1) { //Director
			$howbutton = 1;
			$approvalLvl = 2;
			if(count($dirApprovalAudit) > 0 && $dirApprovalAudit->user_id == $user-id) $canDoSecApproval = 1;
		}
		elseif  ($user->type == 3) { //Dev Team
			$howbutton = 1;
			if($loan->loanStatus->status == 1) $approvalLvl = 1;
			elseif ($loan->loanStatus->status == 3) {
				$approvalLvl = 2;
				if(count($dirApprovalAudit) > 0) $canDoSecApproval = 1;
			}
			//elseif (empty($loan->submitted)) $howbutton = 1;
			else $howbutton = 0;
		}
		if (empty($loan->submitted)) $showbuttonEdit = 1;
		else $showbuttonEdit = 0;
		$dirApprovalCount = LoanApplicationStatusTrail::where('loan_id', $loan->id)
			->where('action', 4)
			->count();

		$primeRate = DB::table('loan_prime_rates')->where('current', 1)->orderBy('id')->first();
		$data['loan'] = $loan;
		$data['showbuttonEdit'] = $showbuttonEdit;
		$data['applicationtype'] = $applicationTypeArray;
		$data['bondtype'] = $bondTypeArray;
		$data['period'] = $periodArray;
		$data['propertytype'] = $propertyTypeArray;
		$data['assetstate'] = $assetStateArray;
		$data['howmarried'] = $howMarriedArray;
		$data['yesno'] = $yesNoArray;
		$data['showbtton'] = $howbutton;
		$data['prime_rate'] = $primeRate;
		$data['approval_lvl'] = $approvalLvl;
		$data['dir_Approval_count'] = $dirApprovalCount;
		$data['can_do_sec_approval'] = $canDoSecApproval;
		$data['active_mod'] = 'loan';
		$data['active_rib'] = 'search';
		//return $data;
        return view('loan.view')->with($data);
    }

	public function store(Request $request)
	{
		$loanData = $request->all();
		unset($loanData['_token']);
		foreach ($loanData as $key => $value)
		{
			if (empty($loanData[$key])) {
				unset($loanData[$key]);
			}
        }
		$submitted = !empty($loanData['command']) && $loanData['command'] == 'submit' ? 1 : 0;
		//$appliactionType = !empty($loanData['applicant_type'])? $loanData['applicant_type'] : 0;
        //Save Loan
		$dateCreated = strtotime(date('Y-m-d'));
		$Loan = new Loan($loanData);
		$Loan->submitted = $submitted;
		$user = Auth::user();
		$Loan->user_id = $user['id'];
		//$Loan->applicant_type = $appliactionType;
		$Loan->created_date = $dateCreated;
        $Loan->save();

        //Save Other Loan related table
        $company = new loan_company($loanData);
        $Loan->addCompany($company);
		# document
		$numFiles  = $index = 0;
		$totalFiles = !empty($loanData['total_files']) ? $loanData['total_files'] : 0;
		$Extensions = array ('jpg', 'png', 'jpeg', 'bmp', 'doc', 'pdf', 'ods', 'exe', 'csv', 'odt', 'xls', 'xlsx', 'docx', 'txt');

		$Files	= isset($_FILES['loan_file'])	?	$_FILES['loan_file'] : array();
		while ($numFiles != $totalFiles)
		{
			$index++;
			$Name =  $request->name[$index];
			if(isset($Files['name'][$index]) && $Files['name'][$index] != '')
			{
				$fileName = $Loan->id.'_'.$Files['name'][$index];
				$Explode = array();
				$Explode = explode('.', $fileName);
				$Ext = end($Explode);
				$Ext = strtolower($Ext);
				if (in_array($Ext, $Extensions))
				{
					if (!is_dir('/home/devloansafrixcel/loansystem/storage/app/loanDocs')) mkdir('/home/devloansafrixcel/loansystem/storage/app/loanDocs', 0775);
					move_uploaded_file($Files['tmp_name'][$index], '/home/devloansafrixcel/loansystem/storage/app/loanDocs/'.$fileName ) or  die('Could not move file!');

					$document = new loan_documents($loanData);
					$document->date_uploaded = !empty($loanData['date_uploaded']) ? strtotime(str_replace('/', '-', $loanData['date_uploaded'])) : time();
					$document->name = $Name;
					$document->file_name = $fileName;
					$Loan->addDocs($document);
				}
			}
			$numFiles ++;
		}
		$history = new loan_employment_history($loanData);
        $Loan->addHistory($history);

		$insurance = new loan_insurance($loanData);
		$insurance->renewal_date = !empty($loanData['renewal_date']) ? strtotime(str_replace('/', '-', $loanData['renewal_date'])) : 0;
        $Loan->addInsurance($insurance);

		$liability = new loan_liability($loanData);
        $Loan->addLiabilities($liability);

		$status = new loan_status($loanData);
		if ($submitted == 1) $status->status = 1;
		else $status->status = 0;
        $Loan->addStatus($status);

		$assets = new loan_assets($loanData);
		$assets->asset_maf_date = !empty($loanData['asset_maf_date']) ? strtotime(str_replace('/', '-', $loanData['asset_maf_date'])) : 0;
        $Loan->addAssets($assets);

		$income = new loan_income($loanData);
        $Loan->addIncome($income);

		$contacts = new loan_application_contacts($loanData);
        $Loan->addContacts($contacts);
		//Send email
		if ($submitted == 1)
		{
			$adminUser = DB::table('hr_people')->where('position', 2)->orderBy('id', 'asc')->get();
			//; ->toSql()
			foreach($adminUser as $user)
			{
				Mail::to($user->email)->send(new loanMail($user->first_name, $Loan->id));
			}
		}
        return redirect('/');
    }
	public function update(Request $request, Loan $loan)
	{
		$loanData = $request->all();
		unset($loanData['_token']);
		foreach ($loanData as $key => $value)
		{
			if (empty($loanData[$key])) {
				unset($loanData[$key]);
			}
        }
		$submitted = !empty($loanData['command']) && $loanData['command'] == 'submit' ? 1 : 0;

		//update Loan
		$loan->submitted = $submitted;
		$user = Auth::user();
        $loan->update($loanData);

        //Save Other Loan related table
        $loan->addOrUpdateCompany($loanData);
		# document
		$numFiles  = $index = 0;
		$totalFiles = !empty($loanData['total_files']) ? $loanData['total_files'] : 0;
		$Extensions = array ('jpg', 'png', 'jpeg', 'bmp', 'doc', 'pdf', 'ods', 'exe', 'csv', 'odt', 'xls', 'xlsx', 'docx', 'txt');

		$Files	= isset($_FILES['loan_file'])	?	$_FILES['loan_file'] : array();
		while ($numFiles != $totalFiles)
		{
			$index++;
			$Name =  $request->name[$index];
			if(isset($Files['name'][$index]) && $Files['name'][$index] != '')
			{
				$fileName = $Loan->id.'_'.$Files['name'][$index];
				$Explode = array();
				$Explode = explode('.', $fileName);
				$Ext = end($Explode);
				$Ext = strtolower($Ext);
				if (in_array($Ext, $Extensions))
				{
					if (!is_dir('/home/devloansafrixcel/loansystem/storage/app/loanDocs')) mkdir('/home/devloansafrixcel/loansystem/storage/app/loanDocs', 0775);
					move_uploaded_file($Files['tmp_name'][$index], '/home/devloansafrixcel/loansystem/storage/app/loanDocs/'.$fileName ) or  die('Could not move file!');

					$document = new loan_documents($loanData);
					$document->date_uploaded = !empty($loanData['date_uploaded']) ? strtotime(str_replace('/', '-', $loanData['date_uploaded'])) : time();
					$document->name = $Name;
					$document->file_name = $fileName;
					$Loan->addDocs($document);
				}
			}
			$numFiles ++;
		}
        $loan->addOrUpdateHistory($loanData);

		if(isset($loanData['renewal_date'])) $loanData['renewal_date'] = strtotime(str_replace('/', '-', $loanData['renewal_date']));
        $loan->addOrUpdateInsurance($loanData);

        $loan->addOrUpdateLiabilities($loanData);

		if ($submitted == 1) $loanData['status'] = 1;
		else $loanData['status'] = 0;
        $loan->addOrUpdateStatus($loanData);

		if(isset($loanData['asset_maf_date'])) $loanData['asset_maf_date'] = strtotime(str_replace('/', '-', $loanData['asset_maf_date']));
        $loan->addOrUpdateAssets($loanData);

        $loan->addOrUpdateIncome($loanData);

        $loan->addOrUpdateContracts($loanData);

		//Send email
		if ($submitted == 1)
		{
			$adminUser = DB::table('hr_people')->where('position', 2)->orderBy('id', 'asc')->get();
			//; ->toSql()
			foreach($adminUser as $user)
			{
				Mail::to($user->email)->send(new loanMail($user->first_name, $Loan->id));
			}
		}
        return redirect('/');
    }

	public function edit(Loan $loan) {
		$loan->load('loanCompany', 'loanDoc', 'loanHistory', 'loanInsurance', 'loanLiabilities', 'loanStatus', 'loanAssets', 'loanIncome', 'loanContacts');
		$applicationTypeArray = array(1 => 'Company', 2 => 'Close corporation', 3 => 'Sole proprietor', 4 => 'Individual', 5 => 'Surety');
		$periodArray = array(1 => '1 Year', 2 => '2 Years', 3 => '3 Years', 4 => '4 Years', 5 => '5 Years', 6 => '6 Years', 7 => '7 Years', 8 => '8 Years', 9 => '9 Years', 10 => '10 Years');
		$bondTypeArray = array(1 => 'Bond free', 2 => 'Bonded', 3 => 'In your name', 4 => 'In your spouse’s name', 5 => 'Both', 6 => 'Other');
		$propertyTypeArray = array(1 => 'House', 2 => 'Town house', 3 => 'Flat');
		$assetStateArray = array(1 => 'New', 2 => 'Used');
		$howMarriedArray = array(1 => 'ANC', 2 => 'COP');
		$yesNoArray = array(1 => 'Yes', 2 => 'No');
		$data['page_title'] = "Loan Application View";
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Application Details', 'active' => 1, 'is_module' => 0]
		];
		$user = Auth::user();
		$type = $user['type'];
		$userID = $user['id'];
		$adminUser = DB::table('hr_people')->find($userID);
		if ($loan->loanStatus->status == 0 && $adminUser->position == 2) $howbutton = 1;
		elseif ($loan->loanStatus->status == 1 && $adminUser->position == 1) $howbutton = 1;
		elseif  ($type == 3) $howbutton = 1;
		else  $howbutton = 0;
		$data['loan'] = $loan;
		$data['applicationtype'] = $applicationTypeArray;
		$data['bondtype'] = $bondTypeArray;
		$data['period'] = $periodArray;
		$data['propertytype'] = $propertyTypeArray;
		$data['assetstate'] = $assetStateArray;
		$data['howmarried'] = $howMarriedArray;
		$data['yesno'] = $yesNoArray;
		$data['showbtton'] = $howbutton;
		$data['active_mod'] = 'loan';
		$data['active_rib'] = 'search';
        return view('loan.application')->with($data);
    }
	public function search() {
		$data['page_title'] = "Loan Search";
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Search', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'loan';
		$data['active_rib'] = 'search';
        return view('loan.search')->with($data);
    }

	public function searchResult(Request $request) {

		$applicationTypeArray = array(1 => 'Company', 2 => 'Close corporation', 3 => 'Sole proprietor', 4 => 'Individual', 5 => 'Surety');
		$bondTypeArray = array(1 => 'Bond free', 2 => 'Bonded', 3 => 'In your name', 4 => 'In your spouse’s name', 5 => 'Both', 6 => 'Other');
		$propertyTypeArray = array(1 => 'House', 2 => 'Town house', 3 => 'Flat');
		$assetStateArray = array(1 => 'New', 2 => 'Used');
		$howMarriedArray = array(1 => 'ANC', 2 => 'COP');
		$yesNoArray = array(1 => 'Yes', 2 => 'No');
		$appliedFrom = $appliedTo = $approvedFrom = $approvedTo = 0;
		$dateApllied = $request->date_applied;
		$dateApproved = $request->date_approved;
		$status = $request->status;
		$aplicantType = $request->applicant_type;
		if (!empty($dateApllied))
		{
			$appliedExplode = explode('-', $dateApllied);
			$appliedFrom = strtotime($appliedExplode[0]);
			$appliedTo = strtotime($appliedExplode[1]);
		}
		if (!empty($dateApproved))
		{
			$approvedExplode = explode('-', $dateApproved);
			$approvedFrom = strtotime($approvedExplode[0]);
			$approvedTo = strtotime($approvedExplode[1]);
		}
		 $loans = DB::table('loan_application')
		->leftJoin('loan_application_status', 'loan_application.id', '=', 'loan_application_status.loan_id')
		->leftJoin('loan_application_company', 'loan_application.id', '=', 'loan_application_company.loan_id')
		->leftJoin('loan_application_contacts', 'loan_application.id', '=', 'loan_application_contacts.loan_id')
		->where('loan_application_status.status', $status)
		->where(function ($query) use ($appliedFrom, $appliedTo) {
		if ($appliedFrom > 0 && $appliedTo  > 0) {
			$query->whereBetween('loan_application.created_date', [$appliedFrom, $appliedTo]);
		}
		})
		->where(function ($query) use ($approvedFrom, $approvedTo) {
		if ($approvedFrom  > 0 && $approvedTo  > 0) {
			$query->whereBetween('loan_application.approved_date', [$approvedFrom, $approvedTo]);
		}
		})
		->where(function ($query) use ($aplicantType) {
		if (!empty($aplicantType)) {
			$query->where('loan_application.applicant_type', $aplicantType);
		}
		})
		->orderBy('loan_application.applicant_type')
		->get();
		foreach ($loans as $key => $value)
		{
			if (empty($loans[$key]) || $value =='null') {
				unset($loans[$key]);
			}
        }
		//->toSql();
		$data['page_title'] = "Loan Search Results";
		$data['loans'] = $loans;
		$data['applications'] = $applicationTypeArray;
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Search Results', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'loan';
		$data['active_rib'] = 'search';
		return view('loan.search_results')->with($data);
    }

	public function setup() {
		$prime_rates = DB::table('loan_prime_rates')->orderBy('id', 'desc')->take(10)->get();
		$prime_rates = $prime_rates->reverse();

		$data['page_title'] = "Loans Setup";
		$data['page_description'] = "Admin page for loans related settings";
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Setup', 'active' => 1, 'is_module' => 0]
		];
		$data['prime_rates'] = $prime_rates;
		$data['active_mod'] = 'loan';
		$data['active_rib'] = 'setup';

		return view('loan.setup')->with($data);
	}

	public function addPrimeRate(Request $request) {

		//Validate rate
		$this->validate($request, [
			'prime_rate' => 'bail|required|numeric|min:0.1'
		]);

		//Add rate
		PrimeRate::where('current', 1)
			->update(['current' => 0]);

		$dateAdded = strtotime(date('Y-m-d'));
		$primeRate = $request['prime_rate'];

		$rate = new PrimeRate;
		$rate->prime_rate = $primeRate;
		$rate->date_added = $dateAdded;
		$rate->current = 1;
		$rate->save();

		//Get all active loans
		$activeLoans = Loan::whereHas('loanStatus', function($query){
			$query->where('status', 4);
		})->get();
		if(count($activeLoans) > 0) {
			foreach ($activeLoans as $loan) {
				$lastStatement = $this->loanSummary($loan, true); // Get last statement
				//Insert new statement record (loan amount)
				$jEntry = new LoanStatement;
				$jEntry->entry_type = 3;
				$jEntry->beginning_balance = $lastStatement->beginningBal;
				$jEntry->ending_balance = $lastStatement->beginningBal;
				$jEntry->total_due = $lastStatement->totalPayment;
				$jEntry->amount = $lastStatement->totalPayment - $lastStatement->totalLoanCost;
				$loan->addLoanStatement($jEntry);

				//audit the action
				$statusTrail = new LoanApplicationStatusTrail;
				$statusTrail->user_id = Auth::user()->id;
				$statusTrail->action = 6;
				$statusTrail->status = 4;
				$loan->addStatusTrsail($statusTrail);
			}
		}

		return response()->json(['new_prime_rate' => $primeRate, 'date_added' => date('d-m-Y',$dateAdded)], 200);
	}

	public function rejectLoanApplication(Request $request, Loan $loan) {

		$loan->load('loanStatus');
		$user = Auth::user()->load('person');

		//check if logged in user is allowed to reject the application
		if ((in_array($loan->loanStatus->status, [0,1])) && ($user->type === 3 || ($user->type === 1 && in_array($user->person->position, [1,2])))) {
			//Validate reason
			$this->validate($request, [
				'rejection_reason' => 'required'
			]);

			//Update status to rejected | Audit loan application status
			if($user->type === 1 && $user->person->position === 2) { //Admin reject

				$loan->submitted = 2;
				$loan->update();
				$loan->loanStatus->status = -1;
				$loan->loanStatus->update();

				$statusTrail = new LoanApplicationStatusTrail;
				$statusTrail->user_id = $user->id;
				$statusTrail->reason = $request['rejection_reason'];
				$statusTrail->action = -1;
				$statusTrail->status = -1;
				$loan->addStatusTrsail($statusTrail);

				//Notify the applicant about the rejection
				$client = User::find("$loan->user_id")->load('person');
				$clientEmail = $client->person->email;
				Mail::to("$clientEmail")->send(new RejectedLoanApplication($client, $request['rejection_reason'], $loan->id));

				return response()->json(['new_loan_status_trail' => $statusTrail], 200);
			}
			elseif($user->type === 1 && $user->person->position === 1) { //Director reject
				$loan->submitted = 2;
				$loan->update();
				$loan->loanStatus->status = -2;
				$loan->loanStatus->update();

				$statusTrail = new LoanApplicationStatusTrail;
				$statusTrail->user_id = $user->id;
				$statusTrail->reason = $request['rejection_reason'];
				$statusTrail->action = -2;
				$statusTrail->status = -2;
				$loan->addStatusTrsail($statusTrail);

				//Notify the applicant about the rejection
				$client = User::find("$loan->user_id")->load('person');
				$clientEmail = $client->person->email;
				Mail::to("$clientEmail")->send(new RejectedLoanApplication($client, $request['rejection_reason'], $loan->id));

				return response()->json(['new_loan_status_trail' => $statusTrail], 200);
			}
		}
		else return response()->json(['error' => ['unauthorized user or Illegal loan status type']], 422);
	}

	public function approveLoanApplication(Request $request, Loan $loan) {
		$loan->load('loanStatus');
		$user = Auth::user()->load('person');

		//Admin approval (lvl 1 of approval)
		if($loan->loanStatus->status === 1 && (($user->type === 1 && $user->person->position === 2) || $user->type === 3)) {

			$loan->loanStatus->status = 3;
			$loan->loanStatus->update();

			$statusTrail = new LoanApplicationStatusTrail;
			$statusTrail->user_id = $user->id;
			$statusTrail->action = 3;
			$statusTrail->status = 3;
			$loan->addStatusTrsail($statusTrail);

			//Notify directors about the approval
			$directors = HRPerson::whereHas('user', function($query) {
				$query->where('type', 1);
			})->where('position', 1)->get();
			if(count($directors) > 0) {
				$directors->load('user');
				foreach ($directors as $director) {
					$directorEmail = $director->email;
					Mail::to($directorEmail)->send(new RequestDirectorApproval($director->user, $loan->id));
				}
			}

			return redirect("/loan/view/$loan->id")->with('success_modal', "This Loan Application has been successfully approved! \nAn email notification has been sent to the director(s) for further approval.");
		}
		//Director approval (lvl 2 of approval)
		elseif($loan->loanStatus->status === 3 && (($user->type === 1 && $user->person->position === 1) || $user->type === 3)) {
			//Check if the loan has had its first director approval
			$dirApprovalCount = LoanApplicationStatusTrail::where('loan_id', $loan->id)
				->where('action', 4)
				->count();
			if($dirApprovalCount > 0) { //Second director approval
				//update loan status
				$loan->loanStatus->status = 4;
				$loan->loanStatus->update();

				//Get last statement record from the database and calculate values
				$lastStatement = $this->loanSummary($loan, true);
				$beginningBal = $lastStatement->beginningBal;
				$monthlyInstalment = $lastStatement->monthlyInstalment;
				$effectiveInterest = $lastStatement->effectiveInterest;
				$interest = $beginningBal * $effectiveInterest;
				$capital = $monthlyInstalment - $interest;
				$endingBal = $beginningBal - $capital;

				//Insert new statement record (loan amount)
				$jEntry = new LoanStatement;
				$jEntry->entry_type = 2;
				//$jEntry->beginning_balance = $beginningBal;
				$jEntry->beginning_balance = $loan->amount_wanted;
				//$jEntry->ending_balance = $endingBal;
				$jEntry->ending_balance = $loan->amount_wanted;
				$jEntry->total_due = $loan->amount_wanted;
				$jEntry->amount = $loan->amount_wanted;
				$loan->addLoanStatement($jEntry);

				//Insert new statement record (interest)
				$jEntryI = new LoanStatement;
				$jEntryI->entry_type = 4;
				$jEntryI->beginning_balance = $loan->amount_wanted;
				$jEntryI->ending_balance = $loan->amount_wanted;
				$jEntryI->total_due = $lastStatement->totalLoanCost;
				$jEntryI->amount = $lastStatement->totalLoanCost - $loan->amount_wanted;
				$loan->addLoanStatement($jEntryI);

				//audit the action
				$statusTrail = new LoanApplicationStatusTrail;
				$statusTrail->user_id = $user->id;
				$statusTrail->action = 4;
				$statusTrail->status = 4;
				$loan->addStatusTrsail($statusTrail);

				//Notify the applicant about the approval
				$client = User::find("$loan->user_id")->load('person');
				$clientEmail = $client->person->email;
				Mail::to($clientEmail)->send(new ApprovedLoanApplication($client, $loan));

				return redirect("/loan/view/$loan->id")->with('success_modal_dir2', "This Loan Application has been successfully approved! \nThe applicant will receive an email notification confirming the approval.");
			}
			else { //First Director Approval
				//Validate rates
				$this->validate($request, [
					'prime_rate' => 'bail|required|numeric|min:0.1',
					'variable_rate' => 'bail|numeric|min:0',
					'loan_interest_rate' => 'bail|required|numeric|min:0.1'
				]);

				//save rates in db
				$plus_minus = ($request->input('plus_minus') == '+') ? 1 : 0;
				$interestRate = new InterestRate;
				$interestRate->prime_rate = (double) $request->input('prime_rate');
				$interestRate->plus_minus = $plus_minus;
				$interestRate->variable_rate = (double) $request->input('variable_rate');
				$interestRate->loan_interest_rate = (double) $request->input('loan_interest_rate');
				$loan->addInterestRate($interestRate);

				//audit the action
				$statusTrail = new LoanApplicationStatusTrail;
				$statusTrail->user_id = $user->id;
				$statusTrail->action = 4;
				$statusTrail->status = 4;
				$loan->addStatusTrsail($statusTrail);

				return response()->json(['new_loan_status_trail' => $statusTrail], 200);
			}
		}
		else return response()->json(['error' => ['unauthorized user or Illegal loan status type']], 422);
	}

	public function loanSummary(Loan $loan, $returnVal = false) {
		$loan->load('interestRate', 'loanStatusTrail');//Get the loan and its related data
		$user = Auth::user()->load('person');
		if ($user->id != $loan->user_id && empty($user->person->position) && $user->type != 3)
			die("Sorry you do not have access to view data on this page please return to dashboard");
		$primeRate = PrimeRate::where('current', 1)->orderBy('id', 'desc')->limit(1)->first();//Get the prime rate
		$approvalDate = $loan->loanStatusTrail->where('action', 4)->first()->created_at;//Get approval date
		$plusMinus = ($loan->interestRate->plus_minus === 1) ? '+' : '-';//prime plus/minus
		$variableRate = $loan->interestRate->variable_rate;//variable rate
		$interestRate = ($plusMinus == '+') ? $primeRate->prime_rate + $variableRate : $primeRate->prime_rate - $variableRate;//calculate the interest rate (per year)

		$term = $loan->loan_period;//loan term
		$numPayments = $term * 12;//number of payments to be made
		$loanStatement = LoanStatement::where('loan_id', $loan->id)->get();//Get loan statement
		//put statements info in readable format
		$formattedStatement = array();
		$indexCount = 1;
		foreach ($loanStatement as $statement) {
			$fStatement = (object) array();
			$fStatement->index = $indexCount;
			$fStatement->balance = $statement->total_due;
			$fStatement->isPayment = false;
			if($statement->entry_type === 1) {//Payment
				$fStatement->date = Carbon::createFromTimestamp($statement->date_added);
				$fStatement->activity = 'Payment';
				$fStatement->amount = $statement->total_payment;
				$fStatement->isPayment = true;
			}
			elseif ($statement->entry_type === 2) { //Loan Amount
				$fStatement->date = $statement->created_at;
				$fStatement->activity = 'Journal Entry: Loan Amount';
				$fStatement->amount = $statement->amount;
			}
			elseif ($statement->entry_type === 3) { //Rate change
				$fStatement->date = $statement->created_at;
				$fStatement->activity = 'Journal Entry: Rate Change';
				$fStatement->amount = $statement->amount;
			}
			elseif ($statement->entry_type === 4) { //Interest charge
				$fStatement->date = $statement->created_at;
				$fStatement->activity = 'Journal Entry: Interest Charge';
				$fStatement->amount = $statement->amount;
			}
			$formattedStatement[] = $fStatement;
			$indexCount++;
		}
		$payments = $loanStatement->where('entry_type', 1);//number of payments made
		$numPaymentsMade = $payments->count();// number of payments made
		$nextPaymentDate = $approvalDate->copy();
		$nextPaymentDate->addMonths($numPaymentsMade + 1);
		$numRemPayments = $numPayments - $numPaymentsMade;//number of remaining payments
		$principal = (count($loanStatement) < 1) ? $loan->amount_wanted : $loanStatement->last()->ending_balance;//amount borrowed or current balance
		$effectiveInterest = (($interestRate / 100) / 12);//rate for each payment (rate per month)
		$monthlyInstalment = $principal * ($effectiveInterest / (1 - pow(1 + $effectiveInterest, (-1) * $numRemPayments)));//monthly instalments
		//Check if the rate has been changed within the month and calculate effective rates
		$pmtMonthStart = $nextPaymentDate->copy()->subMonths(1)->addDay(1)->setTime(0, 0,0);
		$pmtMonthEnd = $nextPaymentDate->copy()->setTime(0, 0,0);
		$PRDateAdded = Carbon::createFromTimestamp($primeRate->date_added);
		//$PRDateAdded = Carbon::create(2016, 12, 10);
		$bRateChanged = false;
		if($PRDateAdded->between($pmtMonthStart, $pmtMonthEnd)){
			$bRateChanged = true;
			$prevPrimeRate = PrimeRate::where('current', '<>', 1)->orderBy('id', 'desc')->limit(1)->first(); //Get Previous prime rate
			$prevInterestRate = ($plusMinus == '+') ? $prevPrimeRate->prime_rate + $variableRate : $prevPrimeRate->prime_rate - $variableRate; //Calculate previous interest rate
			$daysOldRate = $PRDateAdded->diffInDays($pmtMonthStart);
			$daysNewRate = $pmtMonthEnd->diffInDays($PRDateAdded) + 1;
			$totDays = $daysOldRate + $daysNewRate;
			$oldEffInt = (($prevInterestRate / 100) / 12); //(rate per month)
			$newEffInt = (($interestRate / 100) / 12); //(rate per month)
			$oldDailyInst = ($principal * ($oldEffInt / (1 - pow(1 + $oldEffInt, (-1) * $numRemPayments)))) / $totDays; //(Instalment per day)
			$newDailyInst = ($principal * ($newEffInt / (1 - pow(1 + $newEffInt, (-1) * $numRemPayments)))) / $totDays; //(Instalment per day)
			$oldInst = $oldDailyInst * $daysOldRate; //(Instalment for n days with old rate)
			$newInst = $newDailyInst * $daysNewRate; //(Instalment for n days with new rate)
			$mixedMonthInst = $oldInst + $newInst;
			$oldDailyInt = $principal * ($oldEffInt / $totDays); //(Interest per day with old rate)
			$newDailyInt = $principal * ($newEffInt / $totDays); //(Interest per day with new rate)
			$oldInt = $oldDailyInt * $daysOldRate;
			$newInt = $newDailyInt * $daysNewRate;
			$mixedMonthInt = $oldInt + $newInt;

			$nextPrincipal = $principal - ($mixedMonthInst - $mixedMonthInt);
			$monthlyInstalment = $nextPrincipal * ($effectiveInterest / (1 - pow(1 + $effectiveInterest, (-1) * ($numRemPayments - 1))));
		}

		$totalLoanCost = (count($loanStatement) < 1) ? $monthlyInstalment * $numPayments : $loanStatement->last()->total_due;//total cost of loan

		//Loan amortization
		$loanAmortization = array();
		$beginningBal = ($bRateChanged) ? $nextPrincipal : $principal;
		$amorStartDate = $nextPaymentDate->copy();
		$iCount = 1;
		$totalPayment = 0;
		//while ($beginningBal > 0) {
		while ($iCount <= $numRemPayments) {
			$amortization = array();
			$amortization['amr_date'] = $amorStartDate->copy();
			$amortization['beginning_bal'] = $beginningBal;
			$monthInst = ($iCount === 1 && $bRateChanged) ? $mixedMonthInst : $monthlyInstalment;
			$amortization['total_pmt'] = $monthInst;
			$interest = ($iCount === 1 && $bRateChanged) ? $mixedMonthInt : $beginningBal * $effectiveInterest;
			$amortization['interest'] = $interest;
			$capital = $monthInst - $interest;
			$amortization['capital'] = $capital;
			$endingBal = ($iCount === 1 && $bRateChanged) ? $beginningBal : $beginningBal - $capital;
			$amortization['ending_bal'] = $endingBal;

			$loanAmortization[] = $amortization;
			$amorStartDate = $amorStartDate->addMonth();
			$beginningBal = $endingBal;
			$iCount++;
			$totalPayment += $monthInst;
		}

		//Return the calculated values so that they could be used in other functions
		if ($returnVal == true){
			$values = (object) array();
			$values->beginningBal = $principal;
			$values->monthlyInstalment = $monthlyInstalment;
			$values->effectiveInterest = $effectiveInterest;
			$values->totalLoanCost = $totalLoanCost;
			$values->totalPayment = $totalPayment;
			$values->numRemPayments = $numRemPayments;
			if ($bRateChanged) {
				$values->mixedInstalment = $mixedMonthInst;
				$values->mixedInterest = $mixedMonthInt;
			}
			return $values;
		}

		$data['user'] = $user;
		$data['loan'] = $loan;
		$data['prime_rate'] = $primeRate;
		$data['interest_rate'] = number_format($interestRate, 2) . " % (Prime $plusMinus " . number_format($variableRate, 2) . " %)";
		$data['term'] = $term;
		$data['approval_date'] = $approvalDate;
		$data['next_pmt_date'] = $nextPaymentDate;
		$data['next_pmt_amt'] = ($bRateChanged) ? $mixedMonthInst : $monthlyInstalment;
		$data['month_instalment'] = $monthlyInstalment;
		$data['num_payments'] = $numPayments;
		$data['num_rem_payments'] = $numRemPayments;
		$data['tot_loan_cost'] = $totalLoanCost;
		$data['effective_interest'] = $effectiveInterest;
		$data['loan_statement'] = $formattedStatement;
		$data['loan_amortization'] = $loanAmortization;
		$data['page_title'] = "Loan Statement";
		$data['page_description'] = "Monthly Instalments";
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Summary', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'loan';
		//$data['active_rib'] = 'search';
		return view('loan.loan_summary')->with($data);
	}
	public function printStatement(Loan $loan) {
		$loan->load('interestRate', 'loanStatusTrail');//Get the loan and its related data
		
		$clientID = $loan->user_id;
        $client = DB::table('users')
		 ->leftjoin("contacts_contacts",function($join) use ($clientID) {
                $join->on("users.id","=","contacts_contacts.user_id")
                    ->on("contacts_contacts.user_id","=",DB::raw($clientID));
            })
		->where('users.id', $clientID)
		->get();		

		$user = Auth::user()->load('person');
		if ($user->id != $loan->user_id && empty($user->person->position) && $user->type != 3)
			die("Sorry you do not have access to view data on this page please return to dashboard");
		//return $loan;
		$primeRate = PrimeRate::where('current', 1)->orderBy('id', 'desc')->limit(1)->first();//Get the prime rate
		$approvalDate = $loan->loanStatusTrail->where('action', 4)->first()->created_at;//Get approval date
		$plusMinus = ($loan->interestRate->plus_minus === 1) ? '+' : '-';//prime plus/minus
		$variableRate = $loan->interestRate->variable_rate;//variable rate
		$interestRate = ($plusMinus == '+') ? $primeRate->prime_rate + $variableRate : $primeRate->prime_rate - $variableRate;//calculate the interest rate (per year)

		$term = $loan->loan_period;//loan term
		$numPayments = $term * 12;//number of payments to be made
		$loanStatement = LoanStatement::where('loan_id', $loan->id)->get();//Get loan statement
		//put statements info in readable format
		$formattedStatement = array();
		$indexCount = 1;
		foreach ($loanStatement as $statement) {
			$fStatement = (object) array();
			$fStatement->index = $indexCount;
			$fStatement->balance = $statement->total_due;
			$fStatement->isPayment = false;
			if($statement->entry_type === 1) {//Payment
				$fStatement->date = Carbon::createFromTimestamp($statement->date_added);
				$fStatement->activity = 'Payment';
				$fStatement->amount = $statement->total_payment;
				$fStatement->isPayment = true;
			}
			elseif ($statement->entry_type === 2) { //Loan Amount
				$fStatement->date = $statement->created_at;
				$fStatement->activity = 'Journal Entry: Loan Amount';
				$fStatement->amount = $statement->amount;
			}
			elseif ($statement->entry_type === 3) { //Rate change
				$fStatement->date = $statement->created_at;
				$fStatement->activity = 'Journal Entry: Rate Change';
				$fStatement->amount = $statement->amount;
			}
			elseif ($statement->entry_type === 4) { //Interest charge
				$fStatement->date = $statement->created_at;
				$fStatement->activity = 'Journal Entry: Interest Charge';
				$fStatement->amount = $statement->amount;
			}
			$formattedStatement[] = $fStatement;
			$indexCount++;
		}
		$payments = $loanStatement->where('entry_type', 1);//number of payments made
		$numPaymentsMade = $payments->count();// number of payments made
		$nextPaymentDate = $approvalDate->copy();
		$nextPaymentDate->addMonths($numPaymentsMade + 1);
		$numRemPayments = $numPayments - $numPaymentsMade;//number of remaining payments
		$principal = (count($loanStatement) < 1) ? $loan->amount_wanted : $loanStatement->last()->ending_balance;//amount borrowed or current balance
		$effectiveInterest = (($interestRate / 100) / 12);//rate for each payment (rate per month)
		$monthlyInstalment = $principal * ($effectiveInterest / (1 - pow(1 + $effectiveInterest, (-1) * $numRemPayments)));//monthly instalments
		//Check if the rate has been changed within the month and calculate effective rates
		$pmtMonthStart = $nextPaymentDate->copy()->subMonths(1)->addDay(1)->setTime(0, 0,0);
		$pmtMonthEnd = $nextPaymentDate->copy()->setTime(0, 0,0);
		$PRDateAdded = Carbon::createFromTimestamp($primeRate->date_added);
		//$PRDateAdded = Carbon::create(2016, 12, 10);
		$bRateChanged = false;
		if($PRDateAdded->between($pmtMonthStart, $pmtMonthEnd)){
			$bRateChanged = true;
			$prevPrimeRate = PrimeRate::where('current', '<>', 1)->orderBy('id', 'desc')->limit(1)->first(); //Get Previous prime rate
			$prevInterestRate = ($plusMinus == '+') ? $prevPrimeRate->prime_rate + $variableRate : $prevPrimeRate->prime_rate - $variableRate; //Calculate previous interest rate
			$daysOldRate = $PRDateAdded->diffInDays($pmtMonthStart);
			$daysNewRate = $pmtMonthEnd->diffInDays($PRDateAdded) + 1;
			$totDays = $daysOldRate + $daysNewRate;
			$oldEffInt = (($prevInterestRate / 100) / 12); //(rate per month)
			$newEffInt = (($interestRate / 100) / 12); //(rate per month)
			$oldDailyInst = ($principal * ($oldEffInt / (1 - pow(1 + $oldEffInt, (-1) * $numRemPayments)))) / $totDays; //(Instalment per day)
			$newDailyInst = ($principal * ($newEffInt / (1 - pow(1 + $newEffInt, (-1) * $numRemPayments)))) / $totDays; //(Instalment per day)
			$oldInst = $oldDailyInst * $daysOldRate; //(Instalment for n days with old rate)
			$newInst = $newDailyInst * $daysNewRate; //(Instalment for n days with new rate)
			$mixedMonthInst = $oldInst + $newInst;
			$oldDailyInt = $principal * ($oldEffInt / $totDays); //(Interest per day with old rate)
			$newDailyInt = $principal * ($newEffInt / $totDays); //(Interest per day with new rate)
			$oldInt = $oldDailyInt * $daysOldRate;
			$newInt = $newDailyInt * $daysNewRate;
			$mixedMonthInt = $oldInt + $newInt;

			$nextPrincipal = $principal - ($mixedMonthInst - $mixedMonthInt);
			$monthlyInstalment = $nextPrincipal * ($effectiveInterest / (1 - pow(1 + $effectiveInterest, (-1) * ($numRemPayments - 1))));
		}

		$totalLoanCost = (count($loanStatement) < 1) ? $monthlyInstalment * $numPayments : $loanStatement->last()->total_due;//total cost of loan

		//Loan amortization
		$loanAmortization = array();
		$beginningBal = ($bRateChanged) ? $nextPrincipal : $principal;
		$amorStartDate = $nextPaymentDate->copy();
		$iCount = 1;
		$totalPayment = 0;
		//while ($beginningBal > 0) {
		while ($iCount <= $numRemPayments) {
			$amortization = array();
			$amortization['amr_date'] = $amorStartDate->copy();
			$amortization['beginning_bal'] = $beginningBal;
			$monthInst = ($iCount === 1 && $bRateChanged) ? $mixedMonthInst : $monthlyInstalment;
			$amortization['total_pmt'] = $monthInst;
			$interest = ($iCount === 1 && $bRateChanged) ? $mixedMonthInt : $beginningBal * $effectiveInterest;
			$amortization['interest'] = $interest;
			$capital = $monthInst - $interest;
			$amortization['capital'] = $capital;
			$endingBal = ($iCount === 1 && $bRateChanged) ? $beginningBal : $beginningBal - $capital;
			$amortization['ending_bal'] = $endingBal;

			$loanAmortization[] = $amortization;
			$amorStartDate = $amorStartDate->addMonth();
			$beginningBal = $endingBal;
			$iCount++;
			$totalPayment += $monthInst;
		}
		
		$data['date'] = date("d-m-Y");
		$data['user'] = $user;
		$data['loan'] = $loan;
		$data['prime_rate'] = $primeRate;
		$data['interest_rate'] = number_format($interestRate, 2) . " % (Prime $plusMinus " . number_format($variableRate, 2) . " %)";
		$data['term'] = $term;
		$data['approval_date'] = $approvalDate;
		$data['next_pmt_date'] = $nextPaymentDate;
		$data['next_pmt_amt'] = ($bRateChanged) ? $mixedMonthInst : $monthlyInstalment;
		$data['month_instalment'] = $monthlyInstalment;
		$data['num_payments'] = $numPayments;
		$data['num_rem_payments'] = $numRemPayments;
		$data['tot_loan_cost'] = $totalLoanCost;
		$data['effective_interest'] = $effectiveInterest;
		$data['loan_statement'] = $formattedStatement;
		$data['loan_amortization'] = $loanAmortization;
		$data['page_title'] = "Loan Statement";
		$data['page_description'] = "Monthly Instalments";
		$data['breadcrumb'] = [
			['title' => 'Loan', 'path' => '/loan/search', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Summary', 'active' => 1, 'is_module' => 0]
		];
		$data['active_mod'] = 'loan';
		$data['support_email'] = 'support@afrixcel.co.za';
        $data['company_name'] = 'NU-LAXMI LEASING';
        $data['company_logo'] = 'http://devloans.afrixcel.co.za' . Storage::disk('local')->url('logos/logo.jpg');
		$data['client'] = $client->first();
		//return $data;
		return view('loan.statements')->with($data);
	}

	public function capturePayment(Request $request, Loan $loan) {
		//Validate amount and date
		$this->validate($request, [
			'date_added' => 'bail|required|date_format:"d/m/Y"',
			'amount_paid' => 'bail|required|numeric|min:0.1'
		]);

		//Get last statement record from the database and calculate values
		$lastStatement = $this->loanSummary($loan, true);
		$beginningBal = $lastStatement->beginningBal;
		$monthlyInstalment = $lastStatement->monthlyInstalment;
		$effectiveInterest = $lastStatement->effectiveInterest;
		$interest = $beginningBal * $effectiveInterest;
		$capital = $monthlyInstalment - $interest;
		$endingBal = $beginningBal - $capital;
		$totalPaid = (double) $request->input('amount_paid');

		$status = ($lastStatement->numRemPayments === 1) ? 5 : 4;

		//Insert new payment record
		$payment = new LoanStatement;
		$payment->entry_type = 1;
		$payment->beginning_balance = $beginningBal;
		$payment->ending_balance = $endingBal;
		$payment->total_payment = $totalPaid;
		$payment->interest = $interest;
		$payment->capital = $capital;
		$payment->total_due = $lastStatement->totalLoanCost - $totalPaid;
		$paymentDate = strtotime(str_replace('/', '-', $request->input('date_added')));
		$payment->date_added = $paymentDate;
		$loan->addLoanStatement($payment);

		//audit the action
		$statusTrail = new LoanApplicationStatusTrail;
		$statusTrail->user_id = Auth::user()->id;
		$statusTrail->action = 5;
		$statusTrail->status = $status;
		$loan->addStatusTrsail($statusTrail);

		return response()->json(['new_loan_action_trail' => $statusTrail], 200);
	}
}
