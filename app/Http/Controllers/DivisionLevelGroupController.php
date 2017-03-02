<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DivisionLevelGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function divLevelGroupDD($request) {
        $programmeID = $request->input('option');
        $incComplete = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
        $loadAll = $request->input('load_all');
        $projects = [];
        if ($programmeID > 0 && $loadAll == -1)	$projects = projects::projectsFromProgramme($programmeID, $incComplete);
        elseif ($loadAll == 1) {
            $projects = projects::where('status', 2)->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }
        return $projects;
    }
}
