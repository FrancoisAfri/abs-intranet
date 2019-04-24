<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\DivisionLevel;
use App\JobCategory;
use App\Qualification_type;
use App\hr_people;
use App\employee_documents;
use App\doc_type;
use App\User;
use App\HRUserRoles;
use App\Categories;
use App\HRRoles;
use App\doc_type_category;
use App\DivisionLevelTwo;

class HrController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    public function showSetup() {

        $doc_type = DB::table('doc_type')->orderBy('name', 'description')->get();
        $hrRoles = DB::table('hr_roles')->orderBy('description')->get();
        $division_types = DB::table('division_setup')->orderBy('level', 'desc')->get();
        $Qualif_type = DB::table('Qualification_type')->orderBy('status', 1)->get();
        $Doc_type = DB::table('doc_type')->orderBy('active', 1)->get();

        $jobCategories = JobCategory::orderBy('name', 'asc')->get();
        if (!empty($leave_customs))
            $jobCategories = $jobCategories->load('catJobTitle');

        $data['doc_type'] = $doc_type;
        $data['hrRoles'] = $hrRoles;
        $data['jobCategories'] = $jobCategories;
        $data['Qualif_type'] = $Qualif_type;
        $data['Doc_type'] = $Doc_type;
        $data['page_title'] = "HR";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
                ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
                ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];


        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'setup';
        $data['division_types'] = $division_types;
        $data['Qualif_type'] = $Qualif_type;

        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.setup')->with($data);
    }

    public function viewCategory($category) {

        $doc_type = DB::table('doc_type')->where('category_id', $category)->orderBy('name', 'description')->get();
        $doc_type_category = DB::table('doc_type_category')->orderBy('name', 'description')->get();
        $data['page_title'] = "List Documents";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
                ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
                ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'document type';
        $data['category'] = $category;
        $data['doc_type'] = $doc_type;
        $data['doc_type_category'] = $doc_type_category;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.category')->with($data);
    }

    public function viewDoc() {
        $doc_types = DB::table('doc_type')->orderBy('name', 'description')->get();
        //$docs = doc_type::where('status', 1)->get();
        $doc_type_category = DB::table('doc_type_category')->orderBy('name', 'description')->get();
        $data['page_title'] = "List Categories";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
                ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
                ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'document type';
        $data['doc_type'] = $doc_type;
        $data['doc_type_category'] = $doc_type_category;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.document')->with($data);
    }

    public function updateGroupLevel(Request $request, DivisionLevel $groupLevel) {
        //validate name required if active
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'plural_name' => 'bail|required|min:2',
        ]);
        //save the changes
        $groupLevelData = $request->all();
        $groupLevel->update($groupLevelData);
        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
    }

    public function activateGroupLevel(DivisionLevel $groupLevel) {
        if ($groupLevel->active == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $groupLevel->active = $stastus;
        $groupLevel->update();
        return back();
    }

    //}
    public function addRole(Request $request) {

        $this->validate($request, [
            'description' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);
        $HRRoles = new HRRoles($docData);
        $HRRoles->status = 1;
        $HRRoles->save();

        AuditReportsController::store('Employee Records', 'Role added', "Added by User", 0);
        return response()->json();
    }

    public function editRole(Request $request, HRRoles $role) {
        //$user = Auth::user()->load('person');
        $this->validate($request, [
            'description' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $role->description = $request->input('description');
        $role->update();
        AuditReportsController::store('Employee Records', 'Role Updated', "Edited by User", 0);
        return response()->json();
    }
	
	public function assignRole(User $user) {
		
        $user->load('person');
		$userID = $user->person->id;
        $roles = DB::table('hr_roles')->select('hr_roles.id as role_id', 'hr_roles.description as role_name'
		, 'hr_users_roles.id as user_role' , 'hr_users_roles.date_allocated')
		 ->leftjoin("hr_users_roles",function($join) use ($userID) {
                $join->on("hr_roles.id","=","hr_users_roles.role_id")
                    ->on("hr_users_roles.hr_id","=",DB::raw($userID));
            })
		->where('hr_roles.status', 1)
		->orderBy('hr_roles.description', 'asc')
		->get();
		$data['page_title'] = "HR Role";
		$data['page_description'] = "Give user role";
		$data['breadcrumb'] = [
			['title' => 'employee Records', 'path' => '/users', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
			['title' => 'Module Access', 'active' => 1, 'is_module' => 0]
		];
		$data['roles'] = $roles;
		$data['user'] = $user;
		$data['userID'] = $userID;
		AuditReportsController::store('Employee Records', 'Employees Roles Page Accessed', "Accessed By User", 0);
        return view('hr.hr_roles_access')->with($data);
    }
	
	public function userRoleSave(Request $request, User $user) {
		$this->validate($request, [
            'hr_id' => 'required',
        ]);

        $hrID = $request->input('hr_id');
        $roleUsers = $request->input('role_users');
		HRUserRoles::where('hr_id', $hrID)->delete();
        if (count($roleUsers) > 0) {
            
            foreach ($roleUsers as $roleID => $accessLevel) {
                $HRUserRoles = new HRUserRoles();
                $HRUserRoles->hr_id = $hrID;
                $HRUserRoles->role_id = $roleID;
                $HRUserRoles->status = 1;
                $HRUserRoles->date_allocated = time();
                $HRUserRoles->save();
            }
        }
		AuditReportsController::store('Employee Records', 'Employees Roles Saved', "Saved By User", 0);
		return redirect("/hr/role_users/$user->id")->with('changes_saved', "Your changes have been saved successfully.");
    }
	public function roleAct(HRRoles $role) {
        if ($role->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $role->status = $stastus;
        $role->update();
        return back();
    }
	public function addqualType(Request $request, Qualification_type $qultyp) {

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);
        $qultyp = new Qualification_type($docData);
        $qultyp->status = 1;
        $qultyp->save();

        AuditReportsController::store('Leave', 'Leave Qualification_type saved ', "Edited by User", 0);
        return response()->json();
    }

    public function editQualType(Request $request, Qualification_type $qul) {
        //$user = Auth::user()->load('person');
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        $docData = $request->all();
        unset($docData['_token']);

        $qul->name = $request->input('name');
        $qul->description = $request->input('description');
        $qul->update();


        //return $lev;
        AuditReportsController::store('Leave Qualification Type', 'Qualification Type Information Edited', "Edited by User", 0);
        return response()->json();
    }

    public function QualAct(Qualification_type $sta) {
        if ($sta->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $sta->status = $stastus;
        $sta->update();
        return back();
    }

// End Qualification
#Categories
    public function addList(Request $request, Categories $cat) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        // $doc_type = new doc_type($docData);
        $cat->name = $request->input('name');
        $cat->description = $request->input('description');
        $cat->active = 1;
        $cat->save();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();
    }

    public function updateList(Request $request, Categories $cat_type) {

        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'description' => 'bail|required|min:2',
        ]);
        //save the changes
        $docData = $request->all();
        unset($docData['_token']);

        $cat_type->name = $request->input('name');
        $cat_type->description = $request->input('description');
        $cat_type->update();

        AuditReportsController::store('List Categories', 'List Categories updated', "Actioned By User", 0);
        return response()->json();
    }

    public function activateList(Categories $listLevel) {
        if ($listLevel->active == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $listLevel->active = $stastus;
        $listLevel->update();
        return back();
    }

    #Document Type

    public function addDocType(Request $request, Categories $category) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $documentType = new doc_type($docData);
        $documentType->active = 1;
        $category->addDocumenttype($documentType);

        AuditReportsController::store('Document Type', 'Document Type saved ', "Edited by User", 0);
        return response()->json();
    }

    public function editDocType(Request $request, doc_type $edit_DocID) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        //$lev->hr_id = $request->input('hr_id');
        $edit_DocID->name = $request->input('name');
        $edit_DocID->description = $request->input('description');
        $edit_DocID->update();
        //return $lev;
        AuditReportsController::store('Document Type', 'Document  Type Informations Edited', "Edited by User", 0);
        return response()->json();
    }

    public function DocAct(doc_type $sta) {
        if ($sta->active == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $sta->active = $stastus;
        $sta->update();
        return back();
    }

}
