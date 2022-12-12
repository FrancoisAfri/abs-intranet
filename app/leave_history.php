<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class leave_history extends Model
{
    //
    protected $table = 'leave_history';
    protected $fillable = ['hr_id', 'action', 'description_action', 'action_date', 'previous_balance',
        'leave_type_id', 'transcation', 'added_by', 'added_by_name'];

    // #types of leave profiles belonging to leave types
    public function leavehistory()
    {
        return $this->hasMany(HRPerson::class, 'hr_id');
    }

    public $description_action = [
        1 => 'Leave Adjusted By Admin',
        2 => 'Leave Adjusted By System ',
        3 => 'Adjusted By Manager',
        4 => 'Pending'

    ];

    public function geDescriptionActionStrValAttribute($intVal)
    {
        return $this->description_action[$intVal];
    }

    /**
     * @return HasMany
     */
    public function hrPerson()
    {
        return $this->hasMany(HRPerson::class, 'position');
    }

    /**
     * @param string $actionFrom
     * @param string $actionTo
     * @param string $hr_person_id
     * @param string $LevTypID
     * @param string $date
     * @return leave_history[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getLeaveHistory(
        string $actionFrom = "",
        string $actionTo = "",
        string $hr_person_id = "",
        string $LevTypID = "",
        string $date = ""
    )
    {

        return leave_history::select('leave_history.*',
            'hr_people.employee_number as employee_number '
            , 'hr_people.first_name as firstname'
            , 'hr_people.surname as surname', 'leave_types.name as leave_type')
            ->leftJoin('hr_people', 'leave_history.hr_id', '=', 'hr_people.id')
            ->leftJoin('leave_types', 'leave_history.leave_type_id', '=', 'leave_types.id')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('leave_history.action_date', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($date) {
                if (!empty($date)) {
                    $query->where('leave_history.created_at', '>=', $date);
                }
            })
            ->where(function ($query) use ($hr_person_id) {
                if (!empty($hr_person_id)) {
                    $query->where('leave_history.hr_id', $hr_person_id);
                }
            })
            ->where(function ($query) use ($LevTypID) {
                if (!empty($LevTypID)) {
                    $query->where('leave_history.leave_type_id', $LevTypID);
                }
            })
            ->orderBy('hr_people.first_name')
            ->orderBy('hr_people.surname')
            ->orderBy('leave_types.name')
            ->orderBy('leave_types.action_date')
            ->get();

    }

    /**
     * @param string $userID
     * @param string $LevTypID
     * @return HRPerson[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getLeaveBalance(string $userID = "", string $LevTypID = "")
    {
        return HRPerson::select('hr_people.*',
            'leave_credit.hr_id as userID',
            'leave_credit.leave_balance as Balance',
            'leave_credit.leave_type_id as LeaveID',
            'leave_types.name as leaveType'
        )
            ->leftJoin('leave_credit', 'leave_credit.hr_id', '=', 'hr_people.id')
            ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id')
            ->where('hr_people.status', 1)
            ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('hr_people.id', $userID);
                }
            })
            ->where(function ($query) use ($LevTypID) {
                if (!empty($LevTypID)) {
                    $query->where('leave_credit.leave_type_id', $LevTypID);
                }
            })
            ->orderBy('hr_people.first_name')
            ->orderBy('hr_people.surname')
            ->orderBy('leave_types.name')
            ->get();
    }

    /**
     * @param string $userID
     * @param string $LevTypID
     * @return HRPerson[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getLeaveAllowance(string $userID = "", string $LevTypID = "")
    {
        return HRPerson::select(
            'hr_people.*',
            'type_profile.max as max'
            , 'type_profile.min as min'
            , 'leave_types.name as leave_type_name'
        )
            ->leftJoin('type_profile', 'type_profile.leave_profile_id', '=', 'type_profile.leave_profile_id')
            ->leftJoin('leave_types', 'type_profile.leave_type_id', '=', 'leave_types.id')
            ->where('hr_people.status', 1)
            ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('hr_people.id', $userID);
                }
            })
            ->where(function ($query) use ($LevTypID) {
                if (!empty($LevTypID)) {
                    $query->where('type_profile.leave_type_id', $LevTypID);
                }
            })
            ->orderBy('type_profile.leave_type_id')
            ->get();
    }

    /**
     * @param string $userID
     * @param string $LevTypID
     * @param string $actionFrom
     * @param string $actionTo
     * @return leave_application[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getLeaveTaken(
        string $userID = "",
        string $LevTypID = "",
        string $actionFrom = "",
        string $actionTo = ""
    )
    {
        return leave_application::select(
            'leave_application.*',
            'hr_people.employee_number as employee_number'
            , 'hr_people.first_name'
            , 'hr_people.surname'
            , 'leave_types.name as leave_type_name')
            ->leftJoin('hr_people', 'leave_application.hr_id', '=', 'hr_people.id')
            ->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
            ->where('hr_people.status', 1)
            ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('hr_people.id', $userID);
                }
            })
            ->where(function ($query) use ($LevTypID) {
                if (!empty($LevTypID)) {
                    $query->where('leave_application.leave_type_id', $LevTypID);
                }
            })
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('start_date', [$actionFrom, $actionTo]);
                }
            })
            ->orderBy('hr_people.first_name')
            ->orderBy('hr_people.surname')
            ->orderBy('leave_types.name')
            ->orderBy('leave_application.id')
            ->get();
    }

    /**
     * @param string $userID
     * @param string $LevTypID
     * @return HRPerson[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getLeavePaidOut(string $userID = "", string $LevTypID = "")
    {
        return HRPerson::select('hr_people.*',
            'type_profile.max as max',
            'type_profile.leave_type_id as levID',
            'type_profile.leave_profile_id as ProfID',
            'leave_customs.hr_id as empID',
            'leave_customs.number_of_days as Days',
            'leave_types.name as leaveType'
        )
            ->leftJoin(
                'type_profile',
                'type_profile.leave_profile_id',
                '=',
                'type_profile.leave_profile_id'
            )
            ->leftJoin(
                'leave_customs',
                'hr_people.id',
                '=',
                'leave_customs.hr_id'
            )
            ->leftJoin(
                'leave_types',
                'type_profile.leave_type_id',
                '=',
                'leave_types.id'
            )
            ->where('leave_customs.status', 1)
            ->where('hr_people.status', 1)
            ->where(function ($query) use ($userID) {
                if (!empty($userID)) {
                    $query->where('hr_people.id', $userID);
                }
            })
            ->where(function ($query) use ($LevTypID) {
                if (!empty($LevTypID)) {
                    $query->where('type_profile.leave_type_id', $LevTypID);
                }
            })
            ->orderBy('type_profile.leave_type_id')
            ->get();
    }

}