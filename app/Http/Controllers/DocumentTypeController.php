<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\doc_type_category;
use App\doc_type;
use App\job_categories;

class DocumentTypeController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    public function viewDoc() {
		die;
        $doc_types = DB::table('doc_type')->orderBy('name', 'description')->get();
        //$docs = doc_type::where('status', 1)->get();
        $doc_type_category = DB::table('doc_type_category')->orderBy('name', 'description')->get();
        $data['page_title'] = "List Categories";
        $data['page_description'] = "Employee records";
        $data['breadcrumb'] = [
                ['title' => 'HR', 'path' => '/hr', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
                ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
		return $doc_types;
        $data['active_mod'] = 'Employee records';
        $data['active_rib'] = 'document type';
        $data['doc_type'] = $doc_type;
        $data['doc_type_category'] = $doc_type_category;
        AuditReportsController::store('Employee records', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('hr.document')->with($data);
    }

    public function addList(Request $request, doc_type_category $doc_type) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $doc_type = new doc_type($docData);
        $doc_type->name = $request->input('name');
        $doc_type->description = $request->input('description');
        $doc_type->save();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();
    }

    public function activateList(doc_type $listLevel) {
        if ($listLevel->active == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $listLevel->active = $stastus;
        $listLevel->update();
        return back();
    }

    public function updateList(Request $request, doc_type $doc_type) {

        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'description' => 'bail|required|min:2',
        ]);
        //save the changes
        $docData = $request->all();
        $doc_type->update($docData);
        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
    }

    public function viewCategory() {
		
        $doc_type = DB::table('doc_type')->orderBy('name', 'description')->get();
        $doc_type_category = DB::table('doc_type_category')->orderBy('name', 'description')->get();
        $data['page_title'] = "List Documents";
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
        return view('hr.category')->with($data);
    }

    public function addDoc(Request $request, doc_type_category $doc_type_category) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        //$doc_type_category = new doc_type_category($docData);
        $docType = new doc_type($docData);
        $doc_type_category->addDocue($docType);

        // $doc_type_category->name = $request->input('name');
        //  $doc_type_category->description = $request->input('description');
        //  $doc_type_category->save();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();
    }

    public function activateDoc(doc_type_category $listLevel) {
        if ($listLevel->active == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $listLevel->active = $stastus;
        $listLevel->update();
        return back();
    }

    public function updateDoc(Request $request, doc_type_category $doc_type_category) {

        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'description' => 'bail|required|min:2',
        ]);
        //save the changes
        $catData = $request->all();
        $doc_type_category->addDocue($catData);
        AuditReportsController::store('Employee records', 'Employee Group Level Modified', "Actioned By User", 0);
    }

}
