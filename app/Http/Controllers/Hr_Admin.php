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

class Hr_Admin extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    public function view() {

        $data['page_title'] = "Hr Admin";
        $data['page_description'] = "Hr Admin Page";
        $data['breadcrumb'] = [
                ['title' => 'HR', 'path' => '/Hr Admin', 'icon' => 'fa-lock', 'active' => 0, 'is_module' => 1],
                ['title' => 'Administrator Page', 'active' => 1, 'is_module' => 0]
        ];

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
        // return $divisionLevels;
        //$HRPerson = DB::table('HRPerson')->orderBy('first_name', 'surname')->get();
        $Product = product_products::orderBy('name', 'asc')->get();
        $data['Product'] = $Product;


        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Hr Admin';
        $data['DocType'] = $DocType;
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.search_employees')->with($data);
    }

    public function search_employees(Request $request) {

        $this->validate($request, [
                // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();

        unset($results['_token']);
        //return $results;

        $personName = trim($request->employe_name);

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
                ->select('hr_people.*')
                // ->leftJoin('business_card','hr_people.id', '=','business_card.hr_id')
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


        //return  $persons;

        $data['page_title'] = "Employees ";
        $data['page_description'] = "List of users found";
        $data['persons'] = $persons;
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
                ['title' => 'HR', 'path' => '/business_card', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
                ['title' => 'Business Cards', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Business card';
        AuditReportsController::store('Security', 'User Search Results Accessed', "By User", 0);
        return view('hr.employees')->with($data);
    }

    public function cards(User $user) {

        $data['page_title'] = "Employees ";
        $data['page_description'] = "List of users found";
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
        $data['active_rib'] = 'Business card';
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

    public function activeEmployee(Request $request, HRPerson $persons) {

        $this->validate($request, [
                // 'date_uploaded' => 'required',
        ]);
        $results = $request->all();
        //Exclude empty fields from query
        //return $results;
        unset($results['_token']);
       
        $user = Auth::user();
        foreach ($results as $key => $sValue) {
            if (strlen(strstr($key, 'selected'))) {
                $aValue = explode("_", $key);
                $unit = $aValue[0];
                $persons_id = $aValue[1];
                $status = $sValue;

                $persons->updateOrCreate(['id' => $persons_id], ['status' => $status]);
            }
        }
        return redirect("/hr/active_user");
    }

}
