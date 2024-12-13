<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class leave_application extends Model
{
    protected $table = 'leave_application';
    protected $fillable = ['notes', 'supporting_docs', 'start_date',
        'end_date', 'status', 'hr_id', 'leave_type_id', 'start_time',
        'end_time', 'manager_id', 'reject_reason', 'leave_days', 'leave_hours'
        , 'leave_taken'
    ];

    /**
     * @return string[]
     */
    public static function getAllLeaveStatus()
    {
        return [
            1 => 'Approved',
            2 => 'Awaiting managers approval ',
            3 => 'Awaiting department head approval',
            4 => 'Awaiting hr approval',
            5 => 'Awaiting payroll approval',
            6 => 'rejected',
            7 => 'rejectd_by_department_head',
            8 => 'rejectd_by_hr',
            9 => 'rejectd_by_payroll',
            10 => 'Cancelled'
        ];

    }

    public function leavetpe()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    //Relationship leave_application and hr people
    public function manager()
    {
        return $this->hasMany(HRPerson::class, 'manager_id');
    }

    //Relationship leave_application and hr people
    public function person()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }

    //Relationship leave_application and canceller (hr people)
    public function canceller()
    {
        return $this->belongsTo(HRPerson::class, 'canceller_id');
    }
    // //Return leave status string valu
    // public function getLeaveStatusAttribute () {
    //   return $status[$this->status];
    // }
    public static function getUnapprovedApplications($date, $managerId)
    {
        return leave_application::where('status', '>=', 2)
            ->where('created_at', '>=', $date)
            ->where('manager_id', $managerId)
            ->count();
    }

    /**
     * @param $userID
     * @param $date
     * @return Builder|leave_application|Model
     */
    public static function checkIfUserApplied($userID, $startDate, $endDate)
    {
		
		#check if the manager is on leave or not
		/*return leave_application::where('hr_id', $userID)

			->whereIn('status', [1, 2, 3, 4, 5])
			->where(function ($query) use ($startDate, $endDate) {
				$query->wherebetween('start_date', [$startDate, $endDate])
					->orwherebetween('end_date', [$startDate, $endDate]);
			})->first();*/

        return leave_application::with('leavetpe')
			->where('hr_id', $userID)
            ->whereIn('status', [1, 2, 3, 4, 5])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                // Add the new condition here
                ->orWhere(function($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                });
            })->first();
    }
    // check if the employee is on maternity leave

}
