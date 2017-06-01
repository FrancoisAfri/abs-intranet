<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\HRPerson;
use App\hr_people;
use App\DivisionLevel;
use App\employee_documents;
use App\doc_type;
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
     
     
      
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'employees documents';
        $data['employees'] = $employees;
        $data['category'] = $category;
        $data['document'] = $document;
        $data['hr_people'] = $hr_people;
        $data['division_levels'] = $divisionLevels;
		AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.employee_documents')->with($data);
    }

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

        //Upload supporting Documents
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['doc', 'docx', 'pdf']) && $request->file('supporting_docs')->isValid()) {
                $fileName = $empDocs->id . "_supporting_docs." . $fileExt;
                $request->file('supporting_docs')->storeAs('Employee_Docs', $fileName);
                $empDocs->supporting_docs = $fileName;
                $empDocs->update();               
            }
        }
        
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
         return back()->with('success_application', "Employee Details successful Updated.");
    }


}
//