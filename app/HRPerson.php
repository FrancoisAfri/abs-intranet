<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRPerson extends Model
{
    //Specify the table name
    public $table = 'hr_people';
    
    // Mass assignable fields
    protected $fillable = [
        'first_name', 'surname', 'middle_name', 'maiden_name', 'aka', 'initial', 'email', 'cell_number',
        'phone_number', 'id_number', 'date_of_birth', 'passport_number', 'drivers_licence_number', 'drivers_licence_code',
        'proof_drive_permit', 'proof_drive_permit_exp_date', 'drivers_licence_exp_date', 'gender', 'own_transport', 'marital_status',
        'ethnicity', 'profile_pic', 'status','division_level_1', 'division_level_2', 'division_level_3',
        'division_level_4', 'division_level_5', 'leave_profile'
    ];

    
    //Many to many Relationship Between leavetype and Hr person
            public function leave_types(){
          return $this->belongsToMany('App\LeaveType','leave_credit','hr_id','leave_type_id')->withPivot('leave_balance');
            }
    
    //Relationship hr_person and user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Relationship hr_person and user
    public function programme() {
        return $this->hasMany(programme::class, 'manager_id');
    }
        //relationship betwewn hr person and leave credit
//     public function leave_credit() {
//        return $this->hasMany(leave_credit::class, 'hr_id');
//    }

    
    //Relationship hr_person (manager) and Division level group
    public function divisionLevelGroup() {
        return $this->hasOne(DivisionLevelGroup::class, 'manager_id');
    }

    //Full Name accessor
    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->surname;
    }

    //function ro get people from a specific div level
    public static function peopleFronDivLvl($whereField, $divValue, $incInactive) {
        $hrPeople = HRPerson::where($whereField, $divValue)
            ->where(function ($query) use($incInactive) {
                if ($incInactive == -1) {
                    $query->where('status', 1);
                }
            })->get()
            ->sortBy('full_name')
            ->pluck('id', 'full_name');
        return $hrPeople;
    }
}
