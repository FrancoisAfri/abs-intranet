<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class AppraisalThreeSixtyController extends AppraisalKPIResultsController
{
    /**
     * Display a listing of the resource.
     *
     * @return AppraisalKPIResultsController@loadEmpAppraisals
     */
    public function index()
    {
        $empID = Auth::user()->person->id;
        $appraisalMonth = Carbon::now()->format('F Y');
        return parent::loadEmpAppraisals($empID, $appraisalMonth, true);
    }
}
