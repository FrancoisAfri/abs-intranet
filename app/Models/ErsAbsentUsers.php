<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ErsAbsentUsers extends Model
{
    public $table = 'ers_absent_users';

    protected $fillable = [
        'hr_id', 'is_applied', 'date'
    ];


    public static function getAbsentUsers()
    {
       return ErsAbsentUsers::where([
                'is_applied' => 0
            ]
        )->get();
    }

    public static function checkIfRecordExists(){

    }
}
