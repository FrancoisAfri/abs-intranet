<?php

namespace App\Http\Controllers;

use App\AppraisalPerk;
use Illuminate\Http\Request;

use App\Http\Requests;

class AppraisalPerksController extends Controller
{
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
        $perks = AppraisalPerk::orderBy('id', 'desc')->get(); //where('status', 1)->
        $data['page_title'] = "Perks";
        $data['page_description'] = "Manage Appraisal Perks";
        $data['breadcrumb'] = [
            ['title' => 'Performance Appraisal', 'path' => '/appraisal/categories', 'icon' => 'fa fa-line-chart', 'active' => 0, 'is_module' => 1],
            ['title' => 'Perks', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Performance Appraisal';
        $data['active_rib'] = 'perks';
        $data['perks'] = $perks;
        AuditReportsController::store('Performance Appraisal', 'Perks Page Accessed', "Actioned By User", 0);
        return view('appraisals.perks')->with($data);
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
            'name' => 'bail|required|min:2',
            'req_percent' => 'bail|required|numeric|min:1',
        ]);

        $perk = new AppraisalPerk($request->all());
        $perk->status = 1;
        $perk->save();

        //Upload the perk's image
        if ($request->hasFile('img')) {
            $fileExt = $request->file('img')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('img')->isValid()) {
                $fileName = time() . "_perk_img_" . '.' . $fileExt;
                $request->file('img')->storeAs('perks', $fileName);
                //Update file name in the appraisal_perks table
                $perk->img = $fileName;
                $perk->update();
                
                
            }
        }

        AuditReportsController::store('Performance Appraisal', 'New Perk Added By User', "Perk ID: $perk->id, Perk Name: $perk->name", 0);
        return response()->json(['perk_id' => $perk->id, 'perk_name' => $perk->name], 200);
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
     * @param  \App\AppraisalPerk $perk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppraisalPerk $perk)
    {
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'req_percent' => 'bail|required|numeric|min:1',
        ]);

        $perkData = $request->all();
        $perk->update($perkData);

        //Upload the perk's image
        if ($request->hasFile('img')) {
            $fileExt = $request->file('img')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('img')->isValid()) {
                $fileName = $perk->id . "_perk_img_" . '.' . $fileExt;
                $request->file('img')->storeAs('perks', $fileName);
                //Update file name in the appraisal_perks table
                $perk->img = $fileName;
                $perk->update();
            }
        }

        AuditReportsController::store('Performance Appraisal', 'Perk Details Edited By User', "Perk ID: $perk->id, Perk Name: $perk->name", 0);
        return response()->json(['perk_id' => $perk->id, 'perk_name' => $perk->name], 200);
    }

    /**
     * Change the specified resource's status.
     *
     * @param  AppraisalPerk  $perk
     * @return \Illuminate\Http\Response
     */
    public function activate(AppraisalPerk $perk)
    {
        $status = ($perk->status === 1) ? 0 : 1;
        $perk->status = $status;
        $perk->update();
        return back();
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
