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

    public static function divisionPerformance($divID, $divLvl) {
        $employees = HRPerson::where(function ($query) use($divID, $divLvl) {
            if ($divLvl === 5) $query->where('division_level_5', $divID);
            elseif ($divLvl === 4) $query->where('division_level_4', $divID);
            elseif ($divLvl === 3) $query->where('division_level_3', $divID);
            elseif ($divLvl === 2) $query->where('division_level_2', $divID);
            elseif ($divLvl === 1) $query->where('division_level_1', $divID);
        })->get();

        $empAvgs = [];
        foreach ($employees as $employee) {
            $empYearResult = [];
            $appraisalMonth = Carbon::now()->day(15)->month(1);
            $currentMonth = Carbon::now()->day(15);
            while ($appraisalMonth->month != $currentMonth->month) {
                $empYearResult[] = AppraisalKPIResult::getEmpMonthAppraisal($employee->id, $appraisalMonth->format('M Y'));
                $appraisalMonth->addMonth();
            }
            $empAvg = array_sum($empYearResult) / count($empYearResult);
            $empAvgs[$employee->id] = $empAvg;
        }
        $divAvg = array_sum($empAvgs) / count($empAvgs);
        return number_format($divAvg, 2);
    }
}
