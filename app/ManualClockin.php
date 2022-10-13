<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
}
