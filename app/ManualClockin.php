<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class ManualClockin extends Model
{
	use Uuids;

    /**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];
	
     //Specify the table name
    public $table = 'manual_clockins';
	
	// Mass assignable fields
    protected $fillable = [
        'ip_addresss', 'hr_id', 'clockin_type', 'clockin_time', 'location', 'employee_number'];
		
	
	//hrperson relationship	
	public function user()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
	
	//get clockin
	public static function checkClockin($employeeNo)
    {
        return ManualClockin::where(['employee_number' => $employeeNo, 'clockin_type' => 1])
			->where('created_at', '>=', date('Y-m-d').' 00:00:00')
            ->select('id', 'created_at', 'ip_addresss', 'clockin_type', 'clockin_time', 'location')
            ->first();
    }
	// get Clockout
	public static function checkClockout($employeeNo)
    {
        return ManualClockin::where(['employee_number' => $employeeNo, 'clockin_type' => 2])
			->where('created_at', '>=', date('Y-m-d').' 00:00:00')
            ->select('id', 'created_at', 'ip_addresss', 'clockin_type', 'clockin_time', 'location')
            ->first();
    }
	// get Clockout and clockin
	
	public static function getAllattendance($clockinType , $employeeNo, $dates)
    {
		// convert date
		if (!empty($dates)) {
            $startExplode = explode('-', $dates);
            $actionFrom = strtotime(str_replace('/', '-', $startExplode[0]));
            $actionTo = strtotime(str_replace('/', '-', $startExplode[1]));
        }
		else $actionFrom = $actionTo = 0;
		// query
        $query = ManualClockin::with('user')
            ->orderBy('clockin_time', 'desc')
            ->orderBy('hr_id', 'asc')
			->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('clockin_time', [$actionFrom, $actionTo]);
                }
            });
        if (!empty($employeeNo)){
            $query->where('employee_number', $employeeNo);
        } 
		if (!empty($clockinType)){
            $query->where('clockin_type', $clockinType);
        }
        $query->limit(2000);
        return $query->get();

    }
	// get user who clokin to remove cron job

    public static function getclokinUsers()
    {
		$today = Carbon::today();
		//where('created_at', '>=', date('Y-m-d').' 00:00:00')
		return ManualClockin::where('clockin_type', 1)
			->whereDate('created_at', $today)
            ->pluck('employee_number');
    }
}
