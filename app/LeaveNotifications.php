<?php

namespace App;

use App\HRPerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeaveNotifications extends Model
{
    public $table = 'leave_notifications';

    protected $fillable = [
        'hr_id', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }
	
	public static function getListOfUsers()
    {
        //ManagerReport::
        return DB::table('leave_notifications', 'hr')
            ->select('hr_id','leave_notifications.id as userID',
                'leave_notifications.status',
                'hr_people.id',
                'hr_people.first_name',
                'hr_people.surname'
            )
            ->leftJoin('hr_people',
                'leave_notifications.hr_id',
                '=',
                'hr_people.id'
            )
            ->orderBy(
                'leave_notifications.id'
            )->get();

    }
}
