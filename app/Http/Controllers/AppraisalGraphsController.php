<?php

namespace App\Http\Controllers;

use App\AppraisalKPIResult;
use App\AppraisalPerk;
use App\DivisionLevel;
use App\HRPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;

class AppraisalGraphsController extends Controller
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
    //returns an employee's monthly appraisal
    public function empMonthlyPerformance($empID)
    {
        $yearResult = [];
        $appraisalMonth = Carbon::now()->day(15)->month(1);
        $currentMonth = date('m');
        for ($i = 1; $i <= $currentMonth; $i++){
            $empMonthResult = AppraisalKPIResult::getEmpMonthAppraisal($empID, $appraisalMonth->format('M Y'));
            $yearResult[] = number_format($empMonthResult, 2);
            $appraisalMonth->addMonth();
        }
        return $yearResult;
    }

    //returns a group's avg performance from jan to last month or a list of emp from that group and their avg performances
    public static function empGroupPerformance($divID, $divLvl, $returnEmpList = false, $topTen = false, $bottomTen = false, $empGroup = [], $rankLimit = 10) {
        //if ($returnEmpList && ($topTen || $bottomTen)) $employees = HRPerson::get()->load('jobTitle');
        if (count($empGroup) > 0) $employees = $empGroup;
        else {
            $employees = HRPerson::where(function ($query) use($divID, $divLvl) {
                if ($divLvl == 5) $query->where('division_level_5', $divID);
                elseif ($divLvl == 4) $query->where('division_level_4', $divID);
                elseif ($divLvl == 3) $query->where('division_level_3', $divID);
                elseif ($divLvl == 2) $query->where('division_level_2', $divID);
                elseif ($divLvl == 1) $query->where('division_level_1', $divID);
            })->get()->load('jobTitle');
        }

        //$employees = HRPerson::get()->load('jobTitle');

        $empAvgs = [];
        foreach ($employees as $employee) {
            $empYearResult = [];
            $appraisalMonth = Carbon::now()->day(15)->month(1);
            $currentMonth = Carbon::now()->day(15);
            while ($appraisalMonth->month != $currentMonth->month) {
                $empYearResult[] = AppraisalKPIResult::getEmpMonthAppraisal($employee->id, $appraisalMonth->format('M Y'));
                $appraisalMonth->addMonth();
            }
            $empAvg = !empty(array_sum($empYearResult)) && !empty(count($empYearResult)) ? array_sum($empYearResult) / count($empYearResult) : 0;
            if ($returnEmpList) {
                $objEmp = (object) [];
                $objEmp->emp_id = $employee->id;
                $objEmp->emp_full_name = $employee->full_name;
                $objEmp->emp_email = $employee->email;
                $objEmp->emp_job_title = ($employee->jobTitle) ? $employee->jobTitle->name : '';
                $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
                $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');
                $objEmp->emp_profile_pic = (!empty($employee->profile_pic)) ? Storage::disk('local')->url("avatars/$employee->profile_pic") : (($employee->gender === 0) ? $f_silhouette : $m_silhouette);
                $objEmp->emp_result = $empAvg;
                $empAvgs[] = $objEmp;
            }
            else $empAvgs[$employee->id] = $empAvg;
        }
        if ($returnEmpList) {
            if ($topTen) {
                //usort($empAvgs, function($a, $b){return $a->emp_result - $b->emp_result;});
                usort($empAvgs, function($a, $b){return $b->emp_result - $a->emp_result;});
                $empAvgs = array_slice($empAvgs, 0, $rankLimit);
            }
            elseif ($bottomTen) {
                //usort($empAvgs, function($a, $b){return $b->emp_result - $a->emp_result;});
                usort($empAvgs, function($a, $b){return $a->emp_result - $b->emp_result;});
                $empAvgs = array_slice($empAvgs, 0, $rankLimit);
            }
            return $empAvgs;
        }
        else {
            $divAvg = (count($empAvgs) > 0) ? array_sum($empAvgs) / count($empAvgs) : 0;
            return number_format($divAvg, 2);
        }
    }

    //returns a list of divisions and their avg performances
    public function divisionsPerformance(DivisionLevel $divLvl, $parentDivisionID = 0, $managerID = 0) {
        //$divisions = $divLvl->divisionLevelGroup->sortBy('name');
        $divLvl->load(['divisionLevelGroup' => function ($query) use($parentDivisionID, $managerID) {
            $query->where('active', 1);
            if ($parentDivisionID > 0) $query->where('parent_id', $parentDivisionID);
            if ($managerID > 0) $query->where('manager_id', $managerID);
            $query->orderBy('name', 'asc');
        }]);
        $divisions = $divLvl->divisionLevelGroup;
        $divAverages = [];
        $parenLevel = $divLvl->level;
        $childLevel = $parenLevel - 1;
        $childLevelDetails = DivisionLevel::where('level', $childLevel)->get();
        $isChildLevelActive = ($childLevel > 0) ? (boolean) $childLevelDetails->first()->active : false;
        $childLevelName = ($childLevel > 0) ? $childLevelDetails->first()->name : '';
        $childLevelPluralName = ($childLevel > 0) ? $childLevelDetails->first()->plural_name : '';
        foreach ($divisions as $division){
            $objResult = (object) [];
            $objResult->div_id = $division->id;
            $objResult->div_name = $division->name;
            //$objResult->div_result = AppraisalGraphsController::empGroupPerformance($division->id, $division->level);
            $objResult->div_result = AppraisalGraphsController::empGroupPerformance($division->id, $parenLevel);
            $objResult->div_level = $parenLevel;
            $objResult->is_child_level_active = $isChildLevelActive;
            $objResult->child_level = $childLevel;
            $objResult->child_level_name = $childLevelName;
            $objResult->child_level_plural_name = $childLevelPluralName;
            $divAverages[] = $objResult;
        }

        return $divAverages;
    }

    //returns a list of divisions and their avg performances (static function)
    public static function parentDivisionPerformance(DivisionLevel $divLvl, $parentDivisionID = 0, $managerID = 0) {
        //$divisions = $divLvl->divisionLevelGroup->sortBy('name');
        $divLvl->load(['divisionLevelGroup' => function ($query) use($parentDivisionID, $managerID) {
            if ($parentDivisionID > 0) $query->where('parent_id', $parentDivisionID);
            if ($managerID > 0) $query->where('manager_id', $managerID);
            $query->orderBy('name', 'asc');
        }]);
        $divisions = $divLvl->divisionLevelGroup;
        $divAverages = [];
        //return $divisions;
        $parenLevel = $divLvl->level;
        $childLevel = $parenLevel - 1;
        $childLevelDetails = DivisionLevel::where('level', $childLevel)->get();
        $isChildLevelActive = ($childLevel > 0) ? (boolean) $childLevelDetails->first()->active : false;
        $childLevelName = ($childLevel > 0) ? $childLevelDetails->first()->name : '';
        $childLevelPluralName = ($childLevel > 0) ? $childLevelDetails->first()->plural_name : '';

        foreach ($divisions as $division){
            $objResult = (object) [];
            $objResult->div_id = $division->id;
            $objResult->div_name = $division->name;
            $objResult->div_result = AppraisalGraphsController::empGroupPerformance($division->id, $parenLevel);
            //$objResult->div_result = AppraisalGraphsController::empGroupPerformance($division->id, $division->level);
            $objResult->div_level = $parenLevel;
            $objResult->is_child_level_active = $isChildLevelActive;
            $objResult->child_level = $childLevel;
            $objResult->child_level_name = $childLevelName;
            $objResult->child_level_plural_name = $childLevelPluralName;
            $divAverages[] = $objResult;
        }

        return $divAverages;
    }

    //returns a list of emp and their avg performances
    public function empListPerformance($divLvl, $divID) {
        return AppraisalGraphsController::empGroupPerformance($divID, $divLvl, true);
    }

    //returns 8 available perks
    public function getAvailablePerks() {
        $formattedPerks = [];
        $perks = AppraisalPerk::where('status', 1)->orderBy('req_percent', 'asc')->limit(8)->get();
        foreach ($perks as $perk) {
            $formattedPerk = $perk;
            $formattedPerk->req_percent = number_format($perk->req_percent, 2);
            $formattedPerk->img_url = $perk->img_url;
            $formattedPerks[] = $formattedPerk;
        }
        return $formattedPerks;
    }

    //returns the top ten employees
    public function getTopTenEmployees($divLevel = 0, $divID = 0) {
        return AppraisalGraphsController::empGroupPerformance($divID, $divLevel, true, true);
    }

    //returns the bottom ten employees
    public function getBottomTenEmployees($divLevel = 0, $divID = 0) {
        return AppraisalGraphsController::empGroupPerformance($divID, $divLevel, true, false, true);
    }

    //returns all employees under someone
    public function getSubordinates($managerID) {
        $employees = HRPerson::where('manager_id', $managerID)->get()->load('jobTitle');
        return AppraisalGraphsController::empGroupPerformance(0, 0, true, false, false, $employees);
    }
}
