<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AppraisalKPIResult extends Model
{
    //Specify the table name
    public $table = 'appraisal_k_p_i_results';

    // Mass assignable fields
    protected $fillable = ['score', 'percent', 'date_uploaded', 'comment', 'hr_id', 'template_id'];

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

        if ($kpi->is_upload === 2 && $kpi->kpi_type === 1) { //Range
            $lowestRange = $kpi->kpiranges->where('status', 1)->min('range_from');
            $highestRange = $kpi->kpiranges->where('status', 1)->max('range_to');
            $highestPercentage = $kpi->kpiranges->where('status', 1)->max('percentage');

            if ($this->score < $lowestRange) $percentage = 0;
            elseif ($this->score > $highestRange) $percentage = $highestPercentage;
            else {
                $percentage = $kpi->kpiranges->where('status', 1)->where('range_from', '<=', $this->score)->where('range_to', '>=', $this->score)->first()->percentage;
            }
        }
        elseif ($kpi->is_upload === 2 && $kpi->kpi_type === 2) { //Number
            $latestNumber = $kpi->kpiNumber->where('status', 1)->sortBy('id')->last();
            $lowesNumber = $latestNumber->min_number;
            $highestNumber = $latestNumber->max_number;
            $highestPercentage = 100;

            if ($this->score < $lowesNumber) $percentage = 0;
            elseif ($this->score > $highestNumber) $percentage = $highestPercentage;
            else {
                $percentage = ($this->score / $highestNumber) * 100;
            }
        }
        elseif ($kpi->is_upload === 2 && $kpi->kpi_type === 3) { //1 To ...
            $lowestScore = $kpi->kpiIntScore->where('status', 1)->min('score');
            $highestScore = $kpi->kpiIntScore->where('status', 1)->max('score');
            $highestPercentage = $kpi->kpiIntScore->where('status', 1)->max('percentage');

            if ($this->score < $lowestScore) $percentage = 0;
            elseif ($this->score > $highestScore) $percentage = $highestPercentage;
            else {
                $percentage = $kpi->kpiIntScore->where('status', 1)->where('score', $this->score)->first()->percentage;
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
     * Helper function to return an employee's appraisal.
     *
     * @param  int  $empID
     * @param  string  $appraisalMonth (e.g. January 2017)
     * @return HRPerson $emp (with ->year_appraisal or ->month_appraisal)
     */
    public static function empAppraisal($empID, $appraisalMonth = null) {
        //$emp = HRPerson::find($empID);

        if ($appraisalMonth != null) {
            $monthStart = strtotime(new Carbon("first day of $appraisalMonth"));
            $monthEnd = new Carbon("last day of $appraisalMonth");
            $monthEnd = strtotime($monthEnd->endOfDay());

//---
            $emp = HRPerson::where('id', $empID)
                ->with(['jobTitle.kpiTemplate.kpi.results' => function ($query) use ($empID, $monthStart, $monthEnd) {
                    $query->where('hr_id', $empID);
                    $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
                }])
                ->with('jobTitle.kpiTemplate.kpi.kpiskpas')
                ->first();

            $empKPIs = $emp->jobTitle->kpiTemplate->kpi->sortBy('kpa_id')->groupBy('kpa_id');

            $kpaResults = [];
            $kpaResult = 0;
            foreach ($empKPIs as $kpaGroups) {
                foreach ($kpaGroups as $kpi) {
                    $kpaID = $kpi->kpa_id;
                    $kpaResult += (count($kpi->results) > 0) ? $kpi->results->first()->weighted_percentage : 0;
                }
                $kpaResult = ($kpaResult * $kpi->weight) / 100;
                $kpaResults[$kpaID] = $kpaResult;
                $kpaResult = 0;
            }

            return array_sum($kpaResults);
//---
        }
        else {
            $yearResult = [];
            $appraisalMonth = Carbon::now()->day(15)->month(1);
            for ($i = 1; $i <= 12; $i++){

                $monthStart = strtotime(new Carbon("first day of $appraisalMonth"));
                $monthEnd = new Carbon("last day of $appraisalMonth");
                $monthEnd = strtotime($monthEnd->endOfDay());

//---
                $emp = HRPerson::where('id', $empID)
                    ->with(['jobTitle.kpiTemplate.kpi.results' => function ($query) use ($empID, $monthStart, $monthEnd) {
                        $query->where('hr_id', $empID);
                        $query->whereBetween('date_uploaded', [$monthStart, $monthEnd]);
                    }])
                    ->with('jobTitle.kpiTemplate.kpi.kpiskpas')
                    ->first();

                $empKPIs = $emp->jobTitle->kpiTemplate->kpi->sortBy('kpa_id')->groupBy('kpa_id');

                $kpaResults = [];
                $kpaResult = 0;
                foreach ($empKPIs as $kpaGroups) {
                    foreach ($kpaGroups as $kpi) {
                        $kpaID = $kpi->kpa_id;
                        $kpaResult += (count($kpi->results) > 0) ? $kpi->results->first()->weighted_percentage : 0;
                    }
                    $kpaResult = ($kpaResult * $kpi->weight) / 100;
                    $kpaResults[$kpaID] = $kpaResult;
                    $kpaResult = 0;
                }

                //return array_sum($kpaResults);
//---
                $yearResult[$appraisalMonth->format('M')] = array_sum($kpaResults);
                $appraisalMonth->addMonth();
            }
            return $yearResult;
        }
    }
}
