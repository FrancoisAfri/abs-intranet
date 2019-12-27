<?php

namespace App\Http\Controllers;
use App\HRPerson;
use App\DmsSetup;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DMSSetupController extends Controller
{
	/// only allow log in users to access this page
	public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dmsSetup = DmsSetup::where('id', 1)->first();

        $data['page_title'] = "Document Management";
        $data['page_description'] = "DMS Set Up ";
        $data['breadcrumb'] = [
                ['title' => 'Document Management', 'path' => '/dms/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Document Management';
        $data['active_rib'] = 'Setup';
        $data['dmsSetup'] = $dmsSetup;
		//return $dmsSetup;
        AuditReportsController::store('Document Management', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('dms.setup')->with($data);
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
    public function store(Request $request, DmsSetup $setup)
    {
        $this->validate($request, [
        ]);
        $setupData = $request->all();
        unset($setupData['_token']);

		$row = DmsSetup::count();
        if ($row > 0) {
            DB::table('dms_setups')->where('id', $setup->id)->update($setupData);
        } 
		else 
		{
            $dmsSetup = new DmsSetup($setupData);
            $dmsSetup->save();
        }
        return back();
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

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
