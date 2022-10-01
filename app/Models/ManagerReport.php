<?php

namespace App\Models;

use App\HRPerson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManagerReport extends Model
{
    public $table = 'manager_report';

    protected $fillable = [
        'hr_id', 'is_active'
    ];

    public function manager()
    {
        return $this->belongsTo(HRPerson::class, 'hr_id');
    }

    /**
     * @return ManagerReport[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getListOfManagers()
    {

        //ManagerReport::
        return DB::table('manager_report', 'hr')
            ->select('hr_id', 'is_active', 'hr_people.id' ,'hr_people.first_name', 'hr_people.surname')
            ->leftJoin('hr_people', 'manager_report.hr_id', '=', 'hr_people.id')
            ->orderBy('manager_report.id')
            ->get();

    }

}
