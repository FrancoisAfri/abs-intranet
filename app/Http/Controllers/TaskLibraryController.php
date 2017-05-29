<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use App\Users;
use App\HRPerson;
use App\TaskLibrary;
use App\ribbons_access;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;

class TaskLibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	 public function __construct(){

		$this->middleware('auth');
    }
	
    public function index()
    {
        $libraries = DB::table('task_libraries')->orderBy('order_no', 'asc')->get();
        $data['page_title'] = "Tasks Library";
        $data['page_description'] = "Tasks Library";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
             ['title' => ' ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Tasks Library';
        $data['libraries'] = $libraries;

        AuditReportsController::store('Induction', 'Taks Library Page Accessed', "Accessed By User", 0);

        return view('induction.library_view')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'order_no' => 'integer',
            'description' => 'required',
            'upload_required' => 'integer',
        ]);

		$taskLibrary = $request->all();
		unset($taskLibrary['_token']);
		$library = new TaskLibrary($taskLibrary);
		$library->active = 1;
        $library->save();
		AuditReportsController::store('Induction', 'Task Library Added', "Task Description: $library->description", 0);
    }
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
	
	public function actDeact(TaskLibrary $TaskLibrary) 
    {
        if ($TaskLibrary->active == 1) $stastus = 0;
        else $stastus = 1;

        $TaskLibrary->active = $stastus;    
        $TaskLibrary->update();
        return back();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskLibrary $TaskLibrary)
    {
        $this->validate($request, [
            'order_no' => 'integer',
            'description' => 'required',
            'upload_required' => 'integer',

        ]);

        $TaskLibrary->order_no = $request->input('order_no');
        $TaskLibrary->description = $request->input('description');
        $TaskLibrary->upload_required = $request->input('upload_required');
        $TaskLibrary->update();
        return $TaskLibrary;
        AuditReportsController::store('Induction', 'Library Tasks Informations Edited', "Edited by User: $TaskLibrary->description", 0);
        return response()->json(['new_description' => $TaskLibrary->description, 'order_no' => $TaskLibrary->order_no], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /* public function update(Request $request, $id)
    {
        //
    }
*/
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
}
