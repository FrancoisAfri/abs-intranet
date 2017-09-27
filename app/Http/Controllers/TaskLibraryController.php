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
		//->leftJoin('hr_people', 'audit_trail.user_id', '=', 'hr_people.user_id')
        $libraries = DB::table('task_libraries')
		->select('task_libraries.*','division_level_fours.name as deptname')
		->leftJoin('division_level_fours', 'task_libraries.dept_id', '=', 'division_level_fours.id')
		->orderBy('dept_id', 'asc')
		->orderBy('order_no', 'asc')
		->get();
        $deparments = DB::table('division_level_fours')->where('active', 1)->orderBy('name', 'asc')->get();
        $dept = DB::table('division_setup')->where('level', 4)->first();
        $data['page_title'] = "Tasks Library";
        $data['page_description'] = "Tasks Library";
        $data['breadcrumb'] = [
            ['title' => 'Induction', 'path' => '/induction/search', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
             ['title' => ' ', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Induction';
        $data['active_rib'] = 'Tasks Library';
        $data['libraries'] = $libraries;
        $data['deparments'] = $deparments;
        $data['dept'] = $dept;

        AuditReportsController::store('Induction', 'Taks Library Page Accessed', "Accessed By User", 0);

        return view('induction.library_view')->with($data);
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
            'order_no' => 'bail|required|integer|min:1',
            'description' => 'required',
            'upload_required' => 'bail|required|integer|min:1',
            'dept_id' => 'bail|required|integer|min:1',
        ]);

		$taskLibrary = $request->all();
		unset($taskLibrary['_token']);
		$library = new TaskLibrary($taskLibrary);
		$library->active = 1;
        $library->save();
		AuditReportsController::store('Induction', 'Task Library Added', "Task Description: $library->description", 0);
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
            'order_no' => 'bail|required|integer|min:1',
            'description' => 'required',
            'upload_required' => 'bail|required|integer|min:1',
            'dept_id' => 'bail|required|integer|min:1',

        ]);

        $TaskLibrary->order_no = $request->input('order_no');
        $TaskLibrary->description = $request->input('description');
        $TaskLibrary->dept_id = $request->input('dept_id');
        $TaskLibrary->upload_required = $request->input('upload_required');
        $TaskLibrary->update();
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
