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
use App\Province;
use App\doc_type_category;
use App\DivisionLevelTwo;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
//use App\User;

class EmployeeDocumentsController extends Controller
{
    //
      public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewDoc() {

        $data['page_title'] = "Employee Documents";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		
    	$hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $DocType = doc_type::where('active', 1)->get();
        $category = doc_type::where('active', 1)->get();
        $doc_type  = DB::table('doc_type')->where('active',1)->get();
        $document = DB::table('doc_type_category')->orderBy('id')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $division=DivisionLevelTwo::where('active', 1)->get();
        $data['doc_type'] = $doc_type; 
        $data['active_mod'] = 'Employee Records';
        $data['DocType'] = $DocType;
        $data['active_rib'] = 'Employees Documents';
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division_levels'] = $divisionLevels;
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.employee_documents')->with($data);
    }
    #
      public function editUser(User $user) {
        $user->load('person');
        $avatar = $user->person->profile_pic;
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->get();

        //$positions = DB::table('hr_positions')->where('status', 1)->orderBy('name', 'asc')->get();
        $positions = DB::table('hr_positions')->where('status', 1)->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();//->load('divisionLevelGroup');
        $data['page_title'] = "Employee Documents";
        $data['page_description'] = "Employee records";
        $data['back'] = "/users";
        $data['view_by_admin'] = 1;
        $data['user'] = $user;
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : '';
        $data['provinces'] = $provinces;
        $data['active_mod'] = 'Employee Records';
        $data['active_rib'] = 'Search';
        $data['ethnicities'] = $ethnicities;
        $data['positions'] = $positions;
        $data['division_levels'] = $divisionLevels;
        $data['marital_statuses'] = $marital_statuses;
        $data['leave_profile'] = $leave_profile;
        $data['employees'] = $employees;
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

    //return $user;
    AuditReportsController::store('Security', 'User Information Edited', "On Edit Mode", 0);
        return view('hr.view_users')->with($data);
    }
    #
     public function viewQul() {

        $data['page_title'] = "Employee Documents";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

        //$user->load('person');
        //$avatar = $user->person->profile_pic;
        $hr_people = DB::table('hr_people')->orderBy('first_name', 'surname')->get();
        $employees = HRPerson::where('status', 1)->get();
        $category = doc_type::where('active', 1)->get();
        //$document = doc_type_category::where('active', 1)->get();
        $document = DB::table('doc_type_category')->orderBy('id')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $division=DivisionLevelTwo::where('active', 1)->get();
        // return $divisionLevels;
        //$HRPerson = DB::table('HRPerson')->orderBy('first_name', 'surname')->get();
        $doc_type  = DB::table('doc_type')->where('active',1)->get();
          
        $data['doc_type'] = $doc_type;   
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'employees Qualifications';
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.emp_qualifications')->with($data);
    }
    #
    public function acceptDocs(Request $request , employee_documents $empDocs){
         $this->validate($request, [           
         'category_id' =>'bail|required',
         'division_level_2' =>'bail|required',
         'division_level_1' =>'bail|required',
         'hr_person_id' =>'bail|required',
         'doc_description' =>'required',
         'expirydate' =>'required',
         'doc_description' =>'required',
        ]);
            $docs = $request->all();
            unset($docs['_token']);
        
            #save the data from request
            $category_id = $docs['category_id'];
            $DepartmentID = $docs['division_level_2'];
            $DivionID = $docs['division_level_1'];
            $userID = $docs['hr_person_id'];
            $doc_description = $docs['doc_description'];

                #Explode date
            $dates = $docs['expirydate'] = str_replace('/', '-', $docs['expirydate']);
            $expirydate = $docs['expirydate'] = strtotime($docs['expirydate']);
        
            #Save to the table
        $empDocs->category_id = $category_id;
        $empDocs->division_level_2 = $DepartmentID;
        $empDocs->division_level_1 = $DivionID;
        $empDocs->hr_person_id = $userID;
        $empDocs->doc_description = $doc_description;
        $empDocs->expirydate = $expirydate;
        //$empDocs->employeedocs = $employeedocs;       
        $empDocs->save();

         AuditReportsController::store('Employee records', 'Employee Details ', "Accessed By User", 0);
         return back();
    }

        public function uploadDoc(Request $request){
         $this->validate($request, [
            // 'name' => 'required',

        ]);

         $docs = $request->all();
         unset($docs['_token']);

          $DocS = new employee_documents();
          $DocS->doc_type_id =  $request->input('doc_type'); 
          $DocS->doc_description =  $request->input('description'); 
           

           #Explode date
            $dates = $docs['date_from'] = str_replace('/', '-', $docs['date_from']);
            $Date_from = $docs['date_from'] = strtotime($docs['date_from']);

            #
             #Explode date
            $dates = $docs['exp_date'] = str_replace('/', '-', $docs['exp_date']);
            $expirydate = $docs['exp_date'] = strtotime($docs['exp_date']);


            #
             //Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time() . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('Employee_Docs', $fileName);
                $DocS->supporting_docs = $fileName;
                $DocS->update();               
            }
        }

          $DocS->date_from =  $Date_from;
          $DocS->expirydate =  $expirydate;

          $DocS->save();
          return back();

    }

   
    // public function getSearch(){


    // }
    public function Searchdoc(Request $request) {

        $this->validate($request, [           
               // 'division_level_2'=> 'required',
               // 'division_level_1'=> 'required',
               // ' User_name'=> 'required',
               // 'doc_description'=>'bail|required|min:6',
               //         "division_level_2"
               //        division_level_1"
               //       employe_name"
                   
               //     id_number"
               //     passport_number
               //   employee_number"
               // qualification-type
               // document-type"

        ]);
            $docs = $request->all();
            unset($docs['_token']);
             //return $docs;

         $division = trim($request->division_level_2);
         $department = trim($request->division_level_1);
         $userID = trim($request->employe_name);
         $Search = trim($request->document_type);   

            #
             $doc_type  = DB::table('doc_type')->where('active',1)->get();
             //return $doc_type;

                 $document  = DB::table('employee_documents')
        ->select('employee_documents.*','hr_people.status as statas','hr_people.first_name as Name','hr_people.surname as Surname','division_level_ones.name as Department', 'division_level_twos.name as Division' , 'doc_type.name as DocTypeName')
          
          ->leftJoin('doc_type','employee_documents.doc_type_id', '=','doc_type.id')
          ->leftJoin('hr_people', 'employee_documents.hr_person_id', '=', 'hr_people.id')
          ->leftJoin('division_level_ones','employee_documents.division_level_1', '=', 'division_level_ones.id')
          ->leftJoin('division_level_twos', 'employee_documents.division_level_2', '=', 'division_level_twos.id')
          // ->leftJoin()

         ->where('hr_people.status' ,1) 
        ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('employee_documents.hr_person_id', $userID);
                    }
                })
        ->where(function ($query) use ($division) {
                     if (!empty($division)) {
                        $query->where('employee_documents.division_level_2', $division );
                      }
                 })   
        ->where(function ($query) use ($department) {
                      if (!empty($department)) {
                        $query->where('employee_documents.division_level_1', $department );
                      }
                 }) 
        ->where(function ($query) use ($Search) {
                if (!empty($Search)) {
                   $query->where('employee_documents.document_type_id', $Search );
                }
             }) 
                     ->orderBy('Name')
                    ->limit(50)
                    ->get();
                  
                      //return $document;

        $data['doc_type'] = $doc_type;           
        $data['document'] = $document;   
        $data['userID'] = $userID;
        $data['division'] = $division;
        $data['department'] = $department;
        $data['Search'] = $Search;
        $data['page_title'] = "Employee Documents";
        $data['page_description'] = "Employee records";
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        AuditReportsController::store('Employee Documents', 'Employee Documents Search Page Accessed', "Actioned By User", 0);
        //return back();
         return view('hr.doc_searchresults')->with($data);
    }


    public function SearchEmp(Request $request){
             $this->validate($request, [                     
               //  'division_level_2'=> 'required',
               // 'division_level_1'=> 'required',
              //  'employe_name'=> 'required',
               // 'doc_description'=>'bail|required|min:6',
			   ]);
            $emp = $request->all();
            unset($emp['_token']);
           // return $emp;


        $division = trim($request->division_level_2);
        $department = trim($request->division_level_1);
        $employeName = trim($request->user_name);
        $personIDNum = trim($request->id_number);  
        $personPassport = trim($request->passport_number);         
        $personEmployee_no = trim($request->employee_number);  

		$SearchEmp = HRPerson::whereHas('user', function ($query) {
            $query->whereIn('type', [1, 3]);
          })
            ->select('hr_people.*','hr_positions.name as position_title')
            ->leftJoin('hr_positions', 'hr_people.position', '=', 'hr_positions.id')->where('hr_people.status',1)                  
            ->where(function ($query) use ($division) {
				if (!empty($division)) {
					$query->where('hr_people.division_level_2', $division );
				  }
			})   
            ->where(function ($query) use ($department) {
				if (!empty($department)) {
					$query->where('hr_people.division_level_1', $department );
				  }
			}) 
			->where(function ($query) use ($employeName) {
				if (!empty($employeName)) {
						$query->where('first_name', 'ILIKE', "%$employeName%");
					}
			})
			->where(function ($query) use ($personIDNum) {
				if (!empty($personIDNum)) {
						$query->where('id_number', 'ILIKE', "%$personIDNum%");
					}
			})
			->where(function ($query) use ($personPassport) {
				if (!empty($personPassport)) {
						$query->where('passport_number', 'ILIKE', "%$personPassport%");
					}
			})
			->where(function ($query) use ($personEmployee_no) {
				if (!empty($personEmployee_no)) {
					   $query->where('employee_number', 'ILIKE', "%$personEmployee_no%");
					}
			})        
			// ->orderBy('Name')
			->limit(50)
			->get();
			 //return $SearchEmp;
			$data['SearchEmp'] = $SearchEmp;   
			$data['personIDNum'] = $personIDNum;
			$data['division'] = $division;
			$data['department'] = $department;
			$data['employeName'] = $employeName;
			$data['personPassport'] = $personPassport;
			$data['personEmployee_no'] = $personEmployee_no;
			$data['page_title'] = "Employee records";
			$data['page_description'] = "Employee Search";
			$data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
			$data['breadcrumb'] = [
				  ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
				  ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
			];
			$data['active_mod'] = 'Employee Records';
			$data['active_rib'] = 'Search';
			AuditReportsController::store('Employee Search', 'Employee Search Page Accessed', "Actioned By User", 0);
			//return back();
			return view('hr.employee_search')->with($data);



    }


           

    public function uploadDocs(){

    }
       
}
//