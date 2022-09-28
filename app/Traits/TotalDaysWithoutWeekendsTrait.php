<?php

namespace App\Traits;

trait TotalDaysWithoutWeekendsTrait
{

    /**
     * @param $days
     * @param $format
     * @return string
     */
    function addDays($days,$format="Y-m-d"){

        for($i=0;$i<$days;$i++){
            $day = date('N',strtotime("+".($i+1)."day"));
            if($day>5)
                $days++;
        }
        return date($format,strtotime("+$i day"));
    }

}