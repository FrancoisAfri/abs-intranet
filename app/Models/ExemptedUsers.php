<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExemptedUsers extends Model
{
    public $table = 'exempted_users';

    protected $fillable = [
        'hr_id', 'status', 'employee_number'
    ];


    /**
     * @param $id
     * @return \Illuminate\Support\Collection
     */
    public static function getExemptedUsers()
    {
        return ExemptedUsers::where('status', 1)->pluck('employee_number');
    }


    /**
     * @return mixed
     */
    public static function getListOfExemptedUsers()
    {

        //ManagerReport::
        return DB::table('exempted_users', 'hr')
            ->select('hr_id',
                'hr_people.status',
                'hr_people.id',
                'hr_people.first_name',
                'hr_people.surname'
            )
            ->leftJoin('hr_people',
                'exempted_users.employee_number',
                '=',
                'hr_people.employee_number'
            )
            ->orderBy(
                'exempted_users.id'
            )
			->get();

    }
}