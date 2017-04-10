<?php

namespace App\Http\Controllers;

use App\AppraisalKPIResult;
use App\HRPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class AppraisalGraphsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function empMonthlyPerformance($empID)
    {
        $yearResult = [];
        $appraisalMonth = Carbon::now()->day(15)->month(1);
        $currentMonth = date('m');
        for ($i = 1; $i <= $currentMonth; $i++){
            $yearResult[] = AppraisalKPIResult::getEmpMonthAppraisal($empID, $appraisalMonth->format('M Y'));
            $appraisalMonth->addMonth();
        }
        return $yearResult;
    }
}
