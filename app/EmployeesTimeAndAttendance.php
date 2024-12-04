<?php

namespace App;


use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmployeesTimeAndAttendance extends Model
{
    use Uuids;

    /**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];
	
     //Specify the table name
    public $table = 'employees_time_and_attendance';
	
	// Mass assignable fields
    protected $fillable = [
        'hours_worked', 'hr_id', 'date_of_action', 'clokin_time', 'employee_number', 'clockout_time', 'late_arrival', 'early_clockout'
		, 'absent', 'clockin_locations', 'clockout_locations', 'onleave'];
		
	//hrperson relationship	
	public function user()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
	
	// get Clockout and clockin
	
	public static function getAllattendance($employees, $dates, $late_arrival, $early_clockout, $absent, $onleave)
    {
		// convert date
		if (!empty($dates)) {
            $startExplode = explode('-', $dates);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
        }
		else $actionFrom = $actionTo = 0;
		// query
        $query = EmployeesTimeAndAttendance::with('user')
            ->orderBy('date_of_action', 'asc')
            //->orderBy('hr_id', 'asc')
			->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('date_of_action', [$actionFrom, $actionTo]);
                }
            });
        if (!empty($employees)){
			$query->whereIn('hr_id', $employees);
        }
		if (!empty($late_arrival)){
			$query->where('late_arrival', $late_arrival);
        }
		if (!empty($early_clockout)){
			$query->where('early_clockout', $early_clockout);
        }
		if (!empty($absent)){
			$query->where('absent', $absent);
        }
		if (!empty($onleave)){
			$query->where('onleave', $onleave);
        }
        $query->limit(2000);
        return $query->get();
    }
}
