<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\hr_people;
use App\DivisionLevel;
use App\employee_documents;
use App\doc_type;
use App\User;
use App\leave_custom;
use App\business_card;
use App\Province;
use App\modules;
use App\module_access;
USE App\module_ribbons;
use App\doc_type_category;
use App\DivisionLevelTwo;
use App\companyidentity;
use App\product_products;
// use App\Http\Controllers\modules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class BusinessCardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view()
    {

        $data['page_title'] = "Business Cards";
        $data['page_description'] = "User Business Cards";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/business_card', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Business Cards', 'active' => 1, 'is_module' => 0]
        ];

        $hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $DocType = doc_type::where('active', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $doc_type = DB::table('doc_type')->where('active', 1)->get();
        $document = DB::table('doc_type_category')->orderBy('id')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $division = DivisionLevelTwo::where('active', 1)->get();
        $Product = product_products::orderBy('name', 'asc')->get();
        $data['Product'] = $Product;


        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Business card';
        $data['DocType'] = $DocType;
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.search_users')->with($data);
    }

    public function userCard()
    {
        $data['page_title'] = " ";
        $data['page_description'] = "User Business Card";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/business_card', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Business Cards', 'active' => 1, 'is_module' => 0]
        ];

        $userLogged = Auth::user()->person->id;

        $names = HRPerson::where('id', $userLogged)->get();
        $name = $names->first()->first_name;
        $row = CompanyIdentity::count();
        if ($row < 1) {

            $website = 'www.afrixcel.co.za';

        } else {
            $componyIdenities = companyidentity::orderBy('id', 'desc')->get();

            $website = $componyIdenities->first()->company_website;

        }

        $companyDetails = CompanyIdentity::systemSettings();


        $surname = $names->first()->surname;


        // $avatar = $user->person->profile_pic;
        $person = DB::table('hr_people')
            ->select('hr_people.*', 'business_card.status as card_status')
            ->leftJoin('business_card', 'hr_people.id', '=', 'business_card.hr_id')
            ->where('hr_people.id', $userLogged)
            ->orderBy('first_name')
            ->orderBy('surname')
            ->get();
        // return $person;


        #compony logo
        view()->composer('layouts.sidebar', function ($view) use ($companyDetails) {
            $user = Auth::user();
            $modulesAccess = modules::whereHas('moduleRibbon', function ($query) {
                $query->where('active', 1);
            })->where('active', 1)
                ->orderBy('name', 'ASC')->get();

            $data['company_logo'] = $companyDetails['company_logo_url'];
            $data['modulesAccess'] = $modulesAccess;
            $view->with($data);
        });

        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['company_logo'] = $companyDetails['company_logo_url'];
        // $data['componyIdenities'] = $componyIdenities;
        $data['website'] = 'www.afrixcel.co.za';
        $data['name'] = $name;
        $data['surname'] = $surname;
        $data['company_logos'] = url('/') . Storage::disk('local')->url('logos/logo.jpg');
        $data['person'] = $person;
        $data['active_mod'] = 'Employee Records';

        $data['active_rib'] = 'Business card';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.usercard')->with($data);

    }

    # print card
    public function busibess_card()
    {
        $data['page_title'] = " ";
        $data['page_description'] = "User Business Card";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/business_card', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Business Cards', 'active' => 1, 'is_module' => 0]
        ];

        $userLogged = Auth::user()->person->id;
        $companyDetails = CompanyIdentity::systemSettings();
        $componyIdenities = companyidentity::orderBy('id', 'desc')->get();
        $names = HRPerson::where('id', $userLogged)->get();
        $name = $names->first()->first_name;
        $surname = $names->first()->surname;
        $website = $componyIdenities->first()->company_website;

        $person = DB::table('hr_people')
            ->select('hr_people.*', 'business_card.status as card_status')
            ->leftJoin('business_card', 'hr_people.id', '=', 'business_card.hr_id')
            // ->leftJoin()
            ->where('hr_people.id', $userLogged)
            ->orderBy('first_name')
            ->orderBy('surname')
            ->get();
        #compony logo
        view()->composer('layouts.sidebar', function ($view) use ($companyDetails) {
            $user = Auth::user();
            $modulesAccess = modules::whereHas('moduleRibbon', function ($query) {
                $query->where('active', 1);
            })->where('active', 1)
                ->orderBy('name', 'ASC')->get();

            $data['company_logo'] = $companyDetails['company_logo_url'];
            $data['modulesAccess'] = $modulesAccess;
            $view->with($data);
        });

        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['company_logo'] = $companyDetails['company_logo_url'];
        $data['componyIdenities'] = $componyIdenities;
        $data['website'] = $website;
        $data['name'] = $name;
        $data['surname'] = $surname;
        $data['company_logos'] = url('/') . Storage::disk('local')->url('logos/logo.jpg');
        $data['person'] = $person;
        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Business card';
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.user_card_report')->with($data);

    }

    public function cards(User $user)
    {

        $data['page_title'] = "Activate Business Cards";
        $data['page_description'] = "User Business Cards";

        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/business_card', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Business Cards', 'active' => 1, 'is_module' => 0]
        ];

        $userLogged = Auth::user()->person->id;
        //$user->load('person');
        //$avatar = $user->person->profile_pic;
        $hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $DocType = doc_type::where('active', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $doc_type = DB::table('doc_type')->where('active', 1)->get();
        //$document = doc_type_category::where('active', 1)->get();
        $document = DB::table('doc_type_category')->orderBy('id')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $division = DivisionLevelTwo::where('active', 1)->get();
        $user = Auth::user()->load('person');

        $avatar = $user->person->profile_pic;

        $persons = DB::table('hr_people')
            ->select('hr_people.*', 'business_card.status as card_status')
            ->leftJoin('business_card', 'hr_people.id', '=', 'business_card.hr_id')
            ->orderBy('first_name')
            ->orderBy('surname')
            ->get();

        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Hr Admin';
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['persons'] = $persons;
        $data['DocType'] = $DocType;
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.usersAdmin')->with($data);
    }

    public function getEmail(Request $request)
    {
        $this->validate($request, [
            // 'name' => 'required',

        ]);
        $userLogged = Auth::user()->person->id;
        $emaildata = $request->all();
        unset($leaveData['_token']);

        $email = new business_card();

        $userDetails = HRPerson::where('id', $userLogged)->where('status', 1)->select('first_name', 'surname')->first();
        $surname = $userDetails->surname;
        $firstname = $userDetails->first_name;

        $emails = business_card::where('hr_id', $userLogged)->select('email')->get()->first();
        $email = $emails->email;


        $email->email = $request->input('email');
        //$email->hr_id = $userLogged;
        $email->save();

        #send email
        Mail::to($email)->send(new business_cardMail($firstname, $surname, $email));

        return response()->json();
    }


    public function getSearch(Request $request)
    {

        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();

        unset($results['_token']);
        //	return $results;

        $personName = trim($request->employe_name);

        $aPositions = [];

        $business_card = new business_card();

        $business_card = $business_card::orderBy('hr_id', 'asc')->get();
        if (!empty($business_card))
            $business_card = $business_card->load('HrPersons');

        //return $business_card;

        $division5 = !empty($results['division_level_5']) ? $results['division_level_5'] : 0;
        $division4 = !empty($results['division_level_4']) ? $results['division_level_4'] : 0;
        $division3 = !empty($results['division_level_3']) ? $results['division_level_3'] : 0;
        $division2 = !empty($results['division_level_2']) ? $results['division_level_2'] : 0;
        $division1 = !empty($results['division_level_1']) ? $results['division_level_1'] : 0;
        $hrPersonID = !empty($results['hr_person_id']) ? $results['hr_person_id'] : 0;
        $dateUploaded = !empty($results['date_uploaded']) ? $results['date_uploaded'] : 0;

        // $persons = HRPerson::where('status', 1)
        $persons = DB::table('hr_people')
            ->select('hr_people.*', 'business_card.status as card_status')
            ->leftJoin('business_card', 'hr_people.id', '=', 'business_card.hr_id')
            // ->where('hr_people.status' ,1)
            ->where(function ($query) use ($division5) {
                if (!empty($division5)) {
                    $query->where('division_level_5', $division5);
                }
            })
            ->where(function ($query) use ($division4) {
                if (!empty($division4)) {
                    $query->where('division_level_4', $division4);
                }
            })
            ->where(function ($query) use ($division3) {
                if (!empty($division3)) {
                    $query->where('division_level_3', $division3);
                }
            })
            ->where(function ($query) use ($division2) {
                if (!empty($division2)) {
                    $query->where('division_level_2', $division2);
                }
            })
            ->where(function ($query) use ($division1) {
                if (!empty($division1)) {
                    $query->where('division_level_1', $division1);
                }
            })
            ->where(function ($query) use ($personName) {
                if (!empty($personName)) {
                    $query->where('hr_people.id', $personName);
                }
            })
            ->orderBy('first_name')
            ->orderBy('surname')
            ->get();

        $data['business_card'] = "business_card";
        $data['page_title'] = "Business Cards";
        $data['page_description'] = "List of users found";
        $data['persons'] = $persons;
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/business_card', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Business Cards', 'active' => 1, 'is_module' => 0]
        ];
        $data['positions'] = $aPositions;
        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Business card';
        AuditReportsController::store('Security', 'User Search Results Accessed', "By User", 0);
        return view('hr.users')->with($data);
    }

    public function activeCard(Request $request, business_card $business_card)
    {

        $this->validate($request, [
            // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        unset($results['_token']);
        foreach ($results as $key => $value) {
            if (empty($results[$key])) {
                unset($results[$key]);
            }
        }
        $emp = $count = $depID = 0;
        $user = Auth::user();
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'selected'))) {
                $aValue = explode("_", $key);
                $unit = $aValue[0];
                $persons_id = $aValue[1];

                if (count($sValue) > 1) {
                    $status = $sValue[1];
                } else $status = $sValue[0];
                $business_card->updateOrCreate(['hr_id' => $persons_id], ['status' => $status]);
            }
        }
        return redirect("/hr/active_card");
    }
}


