<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalEmpKPIResult extends Model
{
    //Specify the table name
    public $table = 'appraisal_emp_k_p_i_results';

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
}
