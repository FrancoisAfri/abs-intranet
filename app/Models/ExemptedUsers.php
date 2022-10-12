<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExemptedUsers extends Model
{
    public $table = 'exempted_users';

    protected $fillable = [
        'hr_id', 'status'
    ];

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
                'exempted_users.hr_id',
                '=',
                'hr_people.id'
            )
            ->orderBy(
                'exempted_users.id'
            )->get();

    }
}
