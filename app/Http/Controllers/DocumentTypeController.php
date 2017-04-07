<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;

use App\doc_type_category;
use App\Doc_Type;

class DocumentTypeController extends Controller
{
      //
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function viewCategory() {
    	$doc_type = DB::table('doc_type_category')->orderBy('name', 'description')->get();
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

     public function addList(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description'=> 'required',

        ]);

        $docData = $request->all();
        unset($docData['_token']);
        $doc_type = new Doc_Type($docData);
        $doc_type->active = 1;
        $doc_type->save();
        AuditReportsController::store('List Categories', 'List Categories Added', "Actioned By User", 0);
        return response()->json();

    }
}

