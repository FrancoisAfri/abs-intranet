<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelOne;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\EmployeeTasks;
use App\HRPerson;
use App\HelpDesk;
use App\ticket;
use App\cms_rating;
use App\leave_application;
use App\leave_credit;
use App\module_access;
use App\product_category;
use App\product_products;
use App\product_packages;
use App\Http\Requests;
use App\projects;
use App\activity;
use App\ClientInduction;
use App\modules;
use App\programme;
use App\Policy;
use App\PolicyRefreshed;
use App\ContactPerson;
use App\policy_users; 
use App\CRMAccount;
use App\ceoNews;
use App\Quotation;
use App\Cmsnews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
		$user = Auth::user()->load('person');
		// check if there is a policy that needs refresh
		$today =  strtotime(date("Y-m-d"));
		$policies = PolicyRefreshed::where('status', 1)->where('hr_id',$user->person->id)
							->where('date_refreshed','<=', $today)->first();
		//return $policies;
		if (!empty($policies->id))
		{
			// get policy name
			$policy = Policy::where('id',$policies->policy_id)->first();
			//get policy users table 
			$policy_users = policy_users::where('policy_id',$policies->policy_id)->where('user_id',$user->person->id)->first();
			//return $policy_users;
			Alert::toast("You have a policy that need your attention!!! Please go through $policy->name policy", 'warning');
			return redirect("/policy/read-policy-document/$policy_users->id");
		}

        $data['breadcrumb'] = [
            ['title' => 'Dashboard', 'path' => '/', 'icon' => 'fa fa-dashboard', 'active' => 1, 'is_module' => 1]
        ];
        $data['active_mod'] = 'dashboard';

        //  CRMAccounts
        $ClientsCompanyId = ContactPerson::where('id', $user->person->id)->pluck('company_id')->first();
        $Accounts = CRMAccount::where('company_id', $ClientsCompanyId)->get();
        $account = $Accounts->load('company', 'client', 'quotations.products.ProductPackages', 'quotations.packages.products_type');
        $purchaseStatus = ['' => '', 5 => 'Client Waiting Invoice', 6 => 'Invoice Sent', 7 => 'Partially Paid', 8 => 'Paid'];
        $labelColors = ['' => 'danger', 5 => 'warning', 6 => 'primary', 7 => 'primary', 8 => 'success'];

        $activeModules = modules::where('active', 1)->get();

        if ($user->type === 1 || $user->type === 3) {
            $topGroupLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->first();
            $totNumEmp = HRPerson::count();

            //check if user can view the company performance widget (must be superuser or div head or have people reporting to him/her)
            $objAppraisalModAccess = module_access::where('module_id', 6)->where('user_id', $user->id)->get();
            if ($objAppraisalModAccess && count($objAppraisalModAccess) > 0)
                $appraisalModAccess = $objAppraisalModAccess->first()->access_level;
            else
                $appraisalModAccess = 0;
            //$appraisalModAccess = module_access::where('module_id', 6)->where('user_id', $user->id)->get()->first()->access_level;
            $numManagedDivs5 = DivisionLevelFive::where('manager_id', $user->person->id)->count();
            $numManagedDivs4 = DivisionLevelFour::where('manager_id', $user->person->id)->count();
            $numManagedDivs3 = DivisionLevelThree::where('manager_id', $user->person->id)->count();
            $numManagedDivs2 = DivisionLevelTwo::where('manager_id', $user->person->id)->count();
            $numManagedDivs1 = DivisionLevelOne::where('manager_id', $user->person->id)->count();
            $numSupervisedEmp = HRPerson::where('manager_id', $user->person->id)->count();
            $isSuperuser = ($appraisalModAccess == 5) ? true : false;
            //$managedDivsIDs = [];
            if ($numManagedDivs5 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs5 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 5)->orderBy('level', 'desc')->limit(1)->first();
            } elseif ($numManagedDivs4 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs4 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 4)->orderBy('level', 'desc')->limit(1)->first();
            } elseif ($numManagedDivs3 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs3 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 3)->orderBy('level', 'desc')->limit(1)->first();
            } elseif ($numManagedDivs2 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs2 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 2)->orderBy('level', 'desc')->limit(1)->first();
            } elseif ($numManagedDivs1 > 0) {
                $isDivHead = true;
                //foreach ($managedDivs1 as $divLevel) $managedDivsIDs[] = $divLevel->id;
                $managedDivsLevel = DivisionLevel::where('level', 1)->orderBy('level', 'desc')->limit(1)->first();
            } else {
                $isDivHead = false;
                $managedDivsLevel = (object)[];
                $managedDivsLevel->level = 0;
            }

            $isSupervisor = ($numSupervisedEmp > 0) ? true : false;
            $canViewCPWidget = ($isSuperuser || $isDivHead || $isSupervisor) ? true : false;
            $canViewTaskWidget = ($isSuperuser || $isDivHead || $isSupervisor) ? true : false;
            $canViewEmpRankWidget = ($isSuperuser || $isDivHead) ? true : false;

            if ($isSuperuser)
                $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get(); //->load('divisionLevelGroup');
            elseif ($isDivHead) {
                $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')
                    ->where('level', $managedDivsLevel->level)
                    ->get();
            } else
                $divisionLevels = (object)[];

            $statusLabels = [10 => "label-danger", 50 => "label-warning", 80 => 'label-success', 100 => 'label-info'];

            //Get Employees on leave this month
            $monthStart = new Carbon('first day of this month');
            $monthStart->startOfDay();
            $monthStart = $monthStart->timestamp;
            $monthEnd = new Carbon('last day of this month');
            $monthEnd->endOfDay();
            $monthEnd = $monthEnd->timestamp;
            $today = Carbon::now();
            $todayStart = $today->copy()->startOfDay()->timestamp;
            $todayEnd = $today->copy()->endOfDay()->timestamp;
            $onLeaveThisMonth = HRPerson::select('hr_people.id', 'hr_people.first_name', 'hr_people.surname', 'hr_people.profile_pic',
                'leave_application.start_date', 'leave_application.start_time', 'leave_application.end_date', 'leave_application.end_time'
				, 'leave_application.leave_taken')
                ->join('leave_application', 'hr_people.id', '=', 'leave_application.hr_id')
                ->where('leave_application.status', 1)
                ->where(function ($query) use ($todayStart) {
                    $query->whereRaw('leave_application.start_date >= ' . $todayStart);
                    $query->orWhereRaw('leave_application.end_date >= ' . $todayStart);
                })
                ->where(function ($query) use ($monthEnd) {
                    $query->whereRaw('leave_application.start_date <= ' . $monthEnd);
                    $query->orWhereRaw('leave_application.end_date <= ' . $monthEnd);
                })
                ->orderBy('leave_application.start_date')
                ->get();
            //Flag employees that are on leave today
            foreach ($onLeaveThisMonth as $employee) {
                $isOnLeaveToday = false;
                if (($employee->start_date <= $todayStart && $employee->end_date >= $todayStart) || ($employee->start_date >= $todayStart && $employee->start_date <= $todayEnd)) $isOnLeaveToday = true;
                $employee->is_on_leave_today = $isOnLeaveToday;
            }
            //return $onLeaveThisMonth;
			//Employee birthday
			$birthdays = array();
			$birthdayThisMonth = HRPerson::select('hr_people.id'
						, 'hr_people.first_name', 'hr_people.surname'
						, 'hr_people.profile_pic'
						, 'hr_people.gender'
						,'hr_people.date_of_birth')
                ->where('hr_people.status',1)
				->whereNotNull('hr_people.date_of_birth')
                ->orderBy('hr_people.first_name')
                ->orderBy('hr_people.surname')
                ->get();

            //Flag employees that are on leave today
            foreach ($birthdayThisMonth as $employee) {
				if (date('n',$employee->date_of_birth) == date('n')) 
				{
					$birthdays[$employee->id]['names'] = $employee->first_name." ".$employee->surname;
					$birthdays[$employee->id]['birthday_month'] = date('j M',$employee->date_of_birth);
					$m_silhouette = Storage::disk('local')->url("avatars/m-silhouette.jpg");
					$f_silhouette = Storage::disk('local')->url("avatars/f-silhouette.jpg");
					$birthdays[$employee->id]["profile_pic_ur"] = (!empty($employee->profile_pic)) ? Storage::disk('local')->url("avatars/$employee->profile_pic") : (($employee->gender === 2) ? $f_silhouette : $m_silhouette);
					if (date('j',$employee->date_of_birth) === date('j')) $birthdays[$employee->id]['is_birthday_today'] =  1;
				}
            }
			//return $birthdays;
			//Staff anniversary
			$staffAnniversaries = array();
			$staffs = HRPerson::select('hr_people.id'
						, 'hr_people.first_name', 'hr_people.surname'
						, 'hr_people.profile_pic'
						, 'hr_people.gender'
						,'hr_people.date_joined')
                ->where('hr_people.status',1)
				->whereNotNull('hr_people.date_joined')
                ->orderBy('hr_people.first_name')
                ->orderBy('hr_people.surname')
                ->get();

            //Flag employees that are on leave today
            foreach ($staffs as $employee) {
				if (date('n',$employee->date_joined) == date('n')) 
				{
					$staffAnniversaries[$employee->id]['names'] = $employee->first_name." ".$employee->surname;
					$staffAnniversaries[$employee->id]['birthday_month'] = date('j M',$employee->date_joined);
					$m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
					$f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');
					$staffAnniversaries[$employee->id]['profile_pic_ur'] = (!empty($employee->profile_pic)) ? Storage::disk('local')->url("avatars/$employee->profile_pic") : (($employee->gender === 2) ? $f_silhouette : $m_silhouette);
					
					if (date('j',$employee->date_joined) === date('j')) $staffAnniversaries[$employee->id]['is_birthday_today'] =  1;
				}
            }

            $ticketStatus = array('' => '', 1 => 'Pending Assignment', 2 => 'Assigned to operator', 3 => 'Completed by operator', 4 => 'Submited to Admin for review');

            $ticketLabels = [1 => "label-danger", 2 => "label-warning", 3 => 'label-success', 4 => 'label-info'];

            $tickets = DB::table('ticket')
                ->where('user_id', $user->person->id)
                ->orderBy('id', 'asc')
                ->get();

            $email = $user->email;

            $Helpdesk = HelpDesk::orderBy('name', 'asc')->get();

            $helpdeskTickets = HelpDesk::orderBy('id', 'asc')->distinct()->get();
            if (!empty($helpdeskTickets))
                $helpdeskTickets->load('ticket');

            $names = $user->person->first_name;
            $surname = $user->person->surname;
			
            #Product_Category-------->
            $ProductCategory = product_category::orderBy('id', 'asc')->get();
            if (!empty($ProductCategory))
                $ProductCategory = $ProductCategory->load('productCategory');

            $row = product_category::count();
            if ($row < 1) {

                $products = 0;
            } else {
                $products = $ProductCategory->first()->id;
            }
            #------------------>
            #Package_Product
            $packages = product_packages::orderBy('name', 'asc')->get();
            if (!empty($packages))
                $packages = $packages->load('products_type');

            //  $Product = product_products::orderBy('name', 'asc')->get();
            //return $Product;

            $row = product_packages::count();
            if ($row < 1) {

                $package = 0;
            } else {
                $package = $packages->first()->id;
            }
            #cms
            $Div4 = $user->person->division_level_4;
            $Div3 = $user->person->division_level_3;
            $Div2 = $user->person->division_level_2;
            $Div1 = $user->person->division_level_1;

            $today = time();

            $news = Cmsnews::orderBy('id', 'asc')
                ->where('status', 1)
                ->where('expirydate', '>=', $today)
                ->where(function ($query) use ($Div4) {
                    if (!empty($Div4)) {
                        $query->where('division_level_4', '=', $Div4);
                        $query->orWhere('division_level_4', '=', 0);
                    }
                })
                ->where(function ($query) use ($Div3) {
                    if (!empty($Div3)) {
                        $query->where('division_level_3', '=', $Div3);
                        $query->orWhere('division_level_3', '=', 0);
                    }
                })
                ->where(function ($query) use ($Div2) {
                    if (!empty($Div2)) {
                        $query->where('division_level_2', '=', $Div2);
                        $query->orWhere('division_level_2', '=', 0);
                    }
                })
                ->where(function ($query) use ($Div1) {
                    if (!empty($Div1)) {
                        $query->where('division_level_1', '=', $Div1);
						$query->orWhere('division_level_1', '=', 0);
                    }
                })
                ->get();

			$cms_rating =   cms_rating::all();
			//return $cms_rating;
            $Cmsnews = Cmsnews::orderBy('id', 'asc')->get();
            $ceonews = ceoNews::where('status', 1)->latest()->first();
            $ClientInduction = ClientInduction::
            select('client_inductions.*', 'hr_people.first_name as firstname', 'hr_people.surname as surname', 'contact_companies.name as company_name')
                ->leftJoin('hr_people', 'client_inductions.create_by', '=', 'hr_people.user_id')
                ->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
                ->get();
				
            $data['staffAnniversaries'] = $staffAnniversaries;
            $data['birthdays'] = $birthdays;
            $data['ceonews'] = $ceonews;
            $data['ClientInduction'] = $ClientInduction;
            $data['$ticketLabels'] = $ticketLabels;
            $data['news'] = $news;
            $data['account'] = $account;
            //$data['Ribbon_module'] = $Ribbon_module;
            $data['activeModules'] = $activeModules;
            $data['ProductCategory'] = $ProductCategory;
            $data['packages'] = $packages;
            $data['products'] = $products;
            $data['Helpdesk'] = $Helpdesk;
            $data['email'] = $email;
            $data['names'] = $names;
            $data['surname'] = $surname;
            $data['ticketStatus'] = $ticketStatus;
            $data['helpdeskTickets'] = $helpdeskTickets;
            $data['tickets'] = $tickets;
            $data['statusLabels'] = $statusLabels;
            $data['onLeaveThisMonth'] = $onLeaveThisMonth;
            $data['user'] = $user;
            $data['totNumEmp'] = $totNumEmp;
            $data['topGroupLvl'] = $topGroupLvl;
            $data['isSuperuser'] = $isSuperuser;
            $data['isDivHead'] = $isDivHead;
            $data['managedDivsLevel'] = $managedDivsLevel;
            $data['isSupervisor'] = $isSupervisor;
            $data['canViewCPWidget'] = $canViewCPWidget;
            $data['canViewTaskWidget'] = $canViewTaskWidget;
            $data['canViewEmpRankWidget'] = $canViewEmpRankWidget;
            $data['divisionLevels'] = $divisionLevels;
            $data['page_title'] = "Dashboard";
            $data['page_description'] = "This is your main Dashboard";

            return view('dashboard.admin_dashboard')->with($data); //Admin Dashboard
        }
		else {
           
            $tickets = DB::table('ticket')
                ->where('client_id', $user->person->id)
                ->orderBy('id', 'asc')
                ->get();
				
            $Helpdesk = HelpDesk::orderBy('name', 'asc')->get();
            //
            $helpdeskTickets = HelpDesk::orderBy('id', 'asc')->distinct()->get();
            $ticketcount = ticket::where('client_id', $user->person->id)->count();
            $ticketStatus = array('' => '', 1 => 'Pending Assignment', 2 => 'Assigned to operator', 3 => 'Completed by operator', 4 => 'Submited to Admin for review');

            //getclient names
            $clientname = ContactPerson::where('id', $user->person->id)->select('first_name', 'surname')->first();
            $names = $clientname->first_name;
            $surname = $clientname->surname;

            $purchaseStatus = ['' => '', 5 => ' Waiting For My Invoice', 6 => 'Invoice Sent', 7 => 'Partially Paid', 8 => 'Paid'];
            $labelColors = ['' => 'danger', 5 => 'warning', 6 => 'primary', 7 => 'primary', 8 => 'success'];
            //$packages = product_packages::where('status', 1)->orderBy('name', 'asc')->get();
            $packages = product_packages::orderBy('name', 'asc')->get();
            if (!empty($packages))
                $packages = $packages->load('products_type');
            //calculate the package price
            foreach ($packages as $package) {
                $packageProducts = $package->products_type;
                $packageCost = 0;
                foreach ($packageProducts as $packageProduct) {
                    $packageProduct->current_price = ($packageProduct->productPrices && $packageProduct->productPrices->first()) ? $packageProduct->productPrices->first()->price : (($packageProduct->price) ? $packageProduct->price : 0);

                    $packageCost += $packageProduct->current_price;
                }
                $packageDiscount = ($package->discount) ? $package->discount : 0;
                $promoDiscount = ($package->promotions->first()) ? $package->promotions->first()->discount : 0;
                $packagePrice = $packageCost - (($packageCost * $packageDiscount) / 100);
                //$packagePrice = $packagePrice - (($packagePrice * $promoDiscount) / 100);
                $package->price = $packagePrice;
            }
            //Get products
            $products = product_products::where('status', 1)->orderBy('category_id', 'asc')->get();
            if (!empty($products))
                $products = $products->load('promotions');
            foreach ($products as $product) {
                $promoDiscount = ($product->promotions->first()) ? $product->promotions->first()->discount : 0;
                $currentPrice = ($product->productPrices->first()) ? $product->productPrices->first()->price : (($product->price) ? $product->price : 0);
                $currentPrice = $currentPrice - (($currentPrice * $promoDiscount) / 100);
                $product->current_price = $currentPrice;
            }
            //return $currentPrice;
            $data['products'] = $products;
            $data['packages'] = $packages;
            $data['helpdeskTickets'] = $helpdeskTickets;
            $data['ticketStatus'] = $ticketStatus;
            $data['account'] = $account;
            $data['Helpdesk'] = $Helpdesk;
            $data['ticketcount'] = $ticketcount;
            $data['names'] = $names;
            $data['email'] = $user->email;
            $data['labelColors'] = $labelColors;
            $data['purchaseStatus'] = $purchaseStatus;
            $data['surname'] = $surname;
            $data['tickets'] = $tickets;
            $data['page_title'] = "Dashboard";
            $data['page_description'] = "Main Dashboard";
            //$data['Ribbon_module'] = $Ribbon_module;
            $data['activeModules'] = $activeModules;
            return view('dashboard.client_dashboard')->with($data); //Clients Dashboard
        }
    }
}