<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerReport extends Model
{
    public $table = 'manager_report';

    protected $fillable = [
         'hr_id', 'is_active'
    ];

    public static function getListOfManagers(){
        return ManagerReport::all();
    }
}
