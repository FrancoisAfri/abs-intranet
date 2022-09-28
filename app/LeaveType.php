<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class LeaveType extends Model
{

    /**
     * @var string
     */
    protected $table = 'leave_types';

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'status', 'description'];


    /**
     * @return BelongsToMany
     * types of leave profiles belonging to leave types
     */
    public function leave_profle()
    {
        return $this->belongsToMany('App\leave_profile', 'type_profile', 'leave_type_id', 'leave_profile_id')->withPivot('max', 'min');
    }

    /**
     * @return BelongsToMany
     */
    public function hr_person()
    {
        return $this->belongsToMany('App\HRPerson', 'leave_credit', 'leave_type_id', 'hr_id')->withPivot('leave_balance');
    }

    /**
     * @return HasMany
     */
    public function leaveApp()
    {
        return $this->hasmany(leave_application::class, 'leave_type_id');
    }

    /**
     * @param $type
     * @return LeaveType|Model|Builder|null
     */
    public static function getAllLeaveTypes($type)
    {
        return LeaveType::where(
            [
                'id' => $type,
                'status' => 1,

            ])->first();
    }
}
