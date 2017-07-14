<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AppraisalKPIResult extends Model
{
    //Specify the table name
    public $table = 'appraisal_k_p_i_results';

    // Mass assignable fields
    protected $fillable = ['score', 'percent', 'date_uploaded', 'comment', 'hr_id', 'template_id', 'appraiser_id'];

    //Relationship result and kpi
    public function kpi() {
        return $this->belongsTo(appraisalsKpis::class, 'kpi_id');
    }

    /**
     * Accessor function to return an appraisal score in percentage.
     *
     * @param  boolean  $weighted (optional)
     * @return double $percentage
     */
    public function getPercentageAttribute($weighted = false) {
        $kpi = $this->kpi;
        $percentage = 0;

        if ($kpi->kpi_type === 1) { //Range [$kpi->is_upload === 2 && ]
            $lowestRange = $kpi->kpiranges->where('status', 1)->min('range_from');
            $highestRange = $kpi->kpiranges->where('status', 1)->max('range_to');
            $highestPercentage = $kpi->kpiranges->where('status', 1)->max('percentage');

            if ($this->score < $lowestRange) $percentage = 0;
            elseif ($this->score > $highestRange) $percentage = $highestPercentage;
            else {
                $percentage = ($kpi->kpiranges && $kpi->kpiranges->where('status', 1) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $this->score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $this->score)->where('range_to', '>=', $this->score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $this->score)->where('range_to', '>=', $this->score)->first()) ? $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $this->score)->where('range_to', '>=', $this->score)->first()->percentage : 0;
            }
        }
        elseif ($kpi->kpi_type === 2) { //Number
            $latestNumber = ($kpi->kpiNumber && $kpi->kpiNumber->where('status', 1)->sortBy('id') && $kpi->kpiNumber->where('status', 1)->sortBy('id')->last()) ? $kpi->kpiNumber->where('status', 1)->sortBy('id')->last() : null;
            $lowesNumber = ($latestNumber) ? $latestNumber->min_number : 0;
            $highestNumber = ($latestNumber) ? $latestNumber->max_number : 1;
            $highestPercentage = 100;

            if ($this->score < $lowesNumber) $percentage = 0;
            elseif ($highestNumber > 1 && $this->score > $highestNumber) $percentage = $highestPercentage;
            else {
                $percentage = ($this->score / $highestNumber) * 100;
            }
        }
        elseif ($kpi->kpi_type === 3) { //1 To ...
            $lowestScore = $kpi->kpiIntScore->where('status', 1)->min('score');
            $highestScore = $kpi->kpiIntScore->where('status', 1)->max('score');
            $highestPercentage = $kpi->kpiIntScore->where('status', 1)->max('percentage');

            if ($this->score < $lowestScore) $percentage = 0;
            elseif ($this->score > $highestScore) $percentage = $highestPercentage;
            else {
                $percentage = ($kpi->kpiIntScore && $kpi->kpiIntScore->where('status', 1) && $kpi->kpiIntScore->where('status', 1)->where('score', $this->score) && $kpi->kpiIntScore->where('status', 1)->where('score', $this->score)->first()) ? $kpi->kpiIntScore->where('status', 1)->where('score', $this->score)->first()->percentage : 0;
            }
        }

        if ($weighted) $percentage = ($percentage * $kpi->weight) / 100;

        return $percentage;
    }

    /**
     * Accessor function to return an appraisal weighted score in percentage.
     *
     * @return double $percentage
     */
    public function getWeightedPercentageAttribute() {
        return $this->getPercentageAttribute(true);
    }

    /**
     * Helper function to return an employee's total appraisal for a specific month.
     *
     * @param  int  $empID
     * @param  string  $appraisalMonth (e.g. January 2017)
     * @param  boolean  $groupByKPA
     * @param  int  $kpaID
     * @return array $kpaResults or double sum($kpaResults)
     */
    public static function getEmpMonthAppraisal($empID, $appraisalMonth, $groupByKPA = false, $kpaID = null) {
        $monthStart = strtotime(new Carbon("first day of $appraisalMonth"));
        $monthEnd = new Carbon("last day of $appraisalMonth");
        $monthEnd = strtotime($monthEnd->endOfDay());

        $emp = HRPerson::where('id', $empID)
            ->with(['jobTitle.kpiTemplate.kpi.results' => function ($query) use ($empID, $monthStart, $monthEnd) {
                $query->where('hr_id', $empID);
                $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
            }])
            ->with(['jobTitle.kpiTemplate.kpi.empResults' => function ($query) use ($empID, $monthStart, $monthEnd) {
                $query->where('hr_id', $empID);
                //$query->where('appraiser_id', $empID);
                $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
            }])
            ->with('jobTitle.kpiTemplate.kpi.kpiskpas')
            ->first();

        if ($emp->jobTitle && $emp->jobTitle->kpiTemplate && $emp->jobTitle->kpiTemplate->kpi) $empKPIs = $emp->jobTitle->kpiTemplate->kpi->sortBy('kpa_id')->groupBy('kpa_id');
        else return 0;

        $kpaResults = [];
        $kpaResult = 0;
        foreach ($empKPIs as $groupKey => $kpaGroup) {
            if ($kpaID != null && $groupKey != $kpaID) continue;
            $kpiResults = [];
            foreach ($kpaGroup as $kpi) {
                if ($kpi->is_upload === 1 && $kpi->upload_type === 2) { //uploaded attendance
                    //$percentage = 0;
                    $score = AppraisalClockinResults::where('hr_id', $empID)
                        ->where('kip_id', $kpi->id)
                        ->where('attendance', 1)
                        ->whereBetween('date_uploaded', [$monthStart, $monthEnd])
                        ->count();
                    $lowestRange = $kpi->kpiranges->where('status', 1)->min('range_from');
                    $highestRange = $kpi->kpiranges->where('status', 1)->max('range_to');
                    //$highestDeduction = $kpi->kpiranges->where('status', 1)->max('lowest');
                    $highestDeduction = ($kpi->kpiranges && $kpi->kpiranges->where('status', 1) && $kpi->kpiranges->where('status', 1)->sortByDesc('range_to') && $kpi->kpiranges->where('status', 1)->sortByDesc('range_to')->first()) ? $kpi->kpiranges->where('status', 1)->sortByDesc('range_to')->first()->percentage : 0;

                    if ($score < $lowestRange) $percentage = 0;
                    elseif ($score > $highestRange) $percentage = $highestDeduction;
                    else {
                        $percentage = ($kpi->kpiranges && $kpi->kpiranges->where('status', 1) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score)->first()) ? $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score)->first()->percentage : 0;
                    }
                    $percentage = ($kpi->weight > 0) ? ($percentage * $kpi->weight) / 100 : $percentage;
                    $kpiResults[$kpi->id] = $percentage;
                    $kpaResult += $percentage;
                }
                elseif ($kpi->is_upload === 1 && $kpi->upload_type === 3) { //uploaded query reports
                    //$percentage = 0;
                    $score = AppraisalQuery_report::where('hr_id', $empID)
                        ->where('kip_id', $kpi->id)
                        ->whereBetween('date_uploaded', [$monthStart, $monthEnd])
                        ->count();
                    $lowestRange = $kpi->kpiranges->where('status', 1)->min('range_from');
                    $highestRange = $kpi->kpiranges->where('status', 1)->max('range_to');
                    //$highestDeduction = $kpi->kpiranges->where('status', 1)->max('lowest');
                    $highestDeduction = ($kpi->kpiranges && $kpi->kpiranges->where('status', 1) && $kpi->kpiranges->where('status', 1)->sortByDesc('range_to') && $kpi->kpiranges->where('status', 1)->sortByDesc('range_to')->first()) ? $kpi->kpiranges->where('status', 1)->sortByDesc('range_to')->first()->percentage : 0;

                    if ($score < $lowestRange) $percentage = 0;
                    elseif ($score > $highestRange) $percentage = $highestDeduction;
                    else {
                        $percentage = ($kpi->kpiranges && $kpi->kpiranges->where('status', 1) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score)->first()) ? $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score)->first()->percentage : 0;
                    }
                    $percentage = ($kpi->weight > 0) ? ($percentage * $kpi->weight) / 100 : $percentage;
                    $kpiResults[$kpi->id] = $percentage;
                    $kpaResult += $percentage;
                }
                elseif ($kpi->is_upload === 2 && $kpi->is_task_kpi === 1 && in_array($kpi->kpi_task_type, [1, 2])) { //tasks KPI (Both induction and meeting tasks)
                    $score = EmployeeTasks::where('employee_id', $empID)
                        //->where('kip_id', $kpi->id)
                        ->where('task_type', $kpi->kpi_task_type)
                        ->where('date_completed', '>', 0)
                        ->whereRaw('date_completed<=due_date')
                        ->whereBetween('due_date', [$monthStart, $monthEnd])
                        ->count();
                    $lowestRange = $kpi->kpiranges->where('status', 1)->min('range_from');
                    $highestRange = $kpi->kpiranges->where('status', 1)->max('range_to');
                    //$highestDeduction = $kpi->kpiranges->where('status', 1)->max('lowest');
                    $highestDeduction =  ($kpi->kpiranges && $kpi->kpiranges->where('status', 1) && $kpi->kpiranges->where('status', 1)->sortByDesc('range_to') && $kpi->kpiranges->where('status', 1)->sortByDesc('range_to')->first()) ? $kpi->kpiranges->where('status', 1)->sortByDesc('range_to')->first()->percentage : 0;

                    if ($score < $lowestRange) $percentage = 0;
                    elseif ($score > $highestRange) $percentage = $highestDeduction;
                    else {
                        $percentage = ($kpi->kpiranges && $kpi->kpiranges->where('status', 1) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score) && $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score)->first()) ? $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $score)->where('range_to', '>=', $score)->first()->percentage : 0;
                    }
                    $percentage = ($kpi->weight > 0) ? ($percentage * $kpi->weight) / 100 : $percentage;
                    $kpiResults[$kpi->id] = $percentage;
                    $kpaResult += $percentage;
                }
                elseif ($kpi->is_upload === 2 && $kpi->is_task_kpi === 1 && $kpi->kpi_task_type === 3) { //Survey KPI
                    $empFeedbacks = AppraisalSurvey::where('hr_person_id', $empID)
                        ->whereBetween('feedback_date', [$monthStart, $monthEnd])
                        ->get();
                    $empTotalRating = 0;
                    $ratingsCount = 0;
                    foreach ($empFeedbacks as $empFeedback) {
                        if ($empFeedback->avg_rating) {
                            $empTotalRating += $empFeedback->avg_rating;
                            $ratingsCount += 1;
                        }
                    }
                    $score = ($ratingsCount > 0) ? (int) round($empTotalRating / $ratingsCount) : 0;

                    $lowestScore = $kpi->kpiIntScore->where('status', 1)->min('score');
                    $highestScore = $kpi->kpiIntScore->where('status', 1)->max('score');
                    $highestPercentage = $kpi->kpiIntScore->where('status', 1)->max('percentage');

                    if ($score < $lowestScore) $percentage = 0;
                    elseif ($score > $highestScore) $percentage = $highestPercentage;
                    else {
                        $percentage = ($kpi->kpiIntScore && $kpi->kpiIntScore->where('status', 1) && $kpi->kpiIntScore->where('status', 1)->where('score', $score) && $kpi->kpiIntScore->where('status', 1)->where('score', $score)->first()) ? $kpi->kpiIntScore->where('status', 1)->where('score', $score)->first()->percentage : 0;
                    }
                    $percentage = ($kpi->weight > 0) ? ($percentage * $kpi->weight) / 100 : $percentage;
                    $kpiResults[$kpi->id] = $percentage;
                    $kpaResult += $percentage;
                }
                else { //Normal KPIs
                    $appraisalSettings = AppraisalSetup::first(); //Get Appraisal Settings

                    $managerScore =  (count($kpi->results) > 0) ? $kpi->results->first()->weighted_percentage : 0;
                    $empScore =  ($kpi->empResults && $kpi->empResults->where('appraiser_id', $empID) && $kpi->empResults->where('appraiser_id', $empID)->first()) ? $kpi->empResults->where('appraiser_id', $empID)->first()->weighted_percentage : 0;
                    $colleaguesResults = $kpi->empResults;
                    $totalColleaguesScore = $colleaguesCount = 0;
                    foreach ($colleaguesResults as $colleaguesResult) {
                        if ($colleaguesResult->appraiser_id != $empID && $colleaguesResult->weighted_percentage > 0) {
                            $totalColleaguesScore += $colleaguesResult->weighted_percentage;
                            $colleaguesCount++;
                        }
                    }
                    $colleaguesScore =  ($colleaguesCount > 0) ? $totalColleaguesScore / $colleaguesCount : 0;

                    if ($appraisalSettings) {
                        $isThreeSixtyActive = $appraisalSettings->three_sixty_status;
                        $managerWeight = $appraisalSettings->manager_appraisal_weight;
                        $empWeight = $appraisalSettings->employee_appraisal_weight;
                        if ($isThreeSixtyActive === 1) { //360 is active
                            $colleaguesWeight = $appraisalSettings->colleagues_appraisal_weight;
                            $totalScore = (($managerScore * $managerWeight) / 100) + (($empScore * $empWeight) / 100) + (($colleaguesScore * $colleaguesWeight) / 100);
                        } else { //360 is not active
                            $totalScore = (($managerScore * $managerWeight) / 100) + (($empScore * $empWeight) / 100);
                        }
                    } else {
                        $totalScore = $managerScore;
                    }

                    $kpiResults[$kpi->id] = $totalScore;
                    $kpaResult += $totalScore;
                }
            }
            if ($kpaID != null && $groupKey == $kpaID) return $kpiResults;
            $kpaWeight = (appraisalKpas::find($groupKey)) ? appraisalKpas::find($groupKey)->weight : 1; //get the KPA's weight from the database
            $kpaResult = ($kpaResult * $kpaWeight) / 100; //weighted KPA result
            $kpaResults[$groupKey] = $kpaResult;
            $kpaResult = 0;
        }
        if ($groupByKPA) return $kpaResults;
        else return array_sum($kpaResults);
    }

    /**
     * Helper function to return an employee's total appraisal result.
     *
     * @param  int  $empID
     * @param  string  $appraisalMonth (optional e.g. January 2017)
     * @return HRPerson $emp (with ->year_appraisal)
     */
    public static function empAppraisal($empID, $appraisalMonth = null) {
        $emp = HRPerson::find($empID);

        if ($appraisalMonth != null) {
            $yearResult = ["Jan"=>0,"Feb"=>0,"Mar"=>0,"Apr"=>0,"May"=>0,"Jun"=>0,"Jul"=>0,"Aug"=>0,"Sep"=>0,"Oct"=>0,"Nov"=>0,"Dec"=>0];

            $yearResult[substr($appraisalMonth, 0, 3)] = AppraisalKPIResult::getEmpMonthAppraisal($empID, $appraisalMonth);
            $emp->year_appraisal = $yearResult;
            return $emp;
        }
        else {
            $yearResult = [];
            $appraisalMonth = Carbon::now()->day(15)->month(1);
            for ($i = 1; $i <= 12; $i++){
                $yearResult[$appraisalMonth->format('M')] = AppraisalKPIResult::getEmpMonthAppraisal($empID, $appraisalMonth->format('M Y'));
                $appraisalMonth->addMonth();
            }
            $emp->year_appraisal = $yearResult;
            return $emp;
        }
    }

    /**
     * Helper function to return an employee's appraisal result grouped by KPA.
     *
     * @param  int  $empID
     * @param  string  $appraisalMonth
     * @return HRPerson $emp (with ->kpa_appraisal)
     */
    public static function empAppraisalByKPA($empID, $appraisalMonth) {
        $emp = HRPerson::find($empID);

        $kpaResult = AppraisalKPIResult::getEmpMonthAppraisal($empID, $appraisalMonth, true);
        $kpas = [];
        foreach ($kpaResult as $kpaID => $result){
            $kpa = appraisalKpas::find($kpaID);
            $kpa->appraisal_result = $result;
            $kpas[] = $kpa;
        }
        $emp->kpa_appraisal = $kpas;
        return $emp;
    }

    /**
     * Helper function to return an employee's kpi appraisal result from a specific KPA.
     *
     * @param  int      $empID
     * @param  string   $appraisalMonth
     * @param  int      $kpaID
     * @return HRPerson $emp (with ->kpa_appraisal->kpi_appraisal)
     */
    public static function empAppraisalForKPA($empID, $appraisalMonth, $kpaID) {
        $emp = HRPerson::find($empID);

        $kpaResult = AppraisalKPIResult::getEmpMonthAppraisal($empID, $appraisalMonth, false, $kpaID);
        $kpis = [];
        foreach ($kpaResult as $kpiID => $result){
            $kpi = appraisalsKpis::find($kpiID);
            $kpi->appraisal_result = $result;
            $kpis[] = $kpi;
        }
        $kpa = appraisalKpas::find($kpaID);
        //$kpis = Collection::make($kpis);
        //$kpis->sortBy('id');
        $kpa->kpi_appraisal = $kpis;
        $emp->kpa_appraisal = $kpa;
        return $emp;
    }
}
