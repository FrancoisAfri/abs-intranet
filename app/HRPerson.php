<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HRPerson extends Model
{
    //Specify the table name
    public $table = 'hr_people';
    
    // Mass assignable fields
    protected $fillable = [
        'first_name', 'surname', 'middle_name', 'maiden_name', 'aka', 'initial', 'email', 'cell_number', 'phone_number', 'id_number', 'date_of_birth', 'passport_number', 'drivers_licence_number', 'drivers_licence_code', 'proof_drive_permit', 'proof_drive_permit_exp_date', 'drivers_licence_exp_date', 'gender', 'own_transport', 'marital_status', 'ethnicity', 'profile_pic', 'status'
    ];

    //Relationship hr_person and user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Relationship hr_person and user
    public function programme() {
        return $this->hasMany(programme::class, 'manager_id');
    }

    //Relationship hr_person (manager) and Division level group
    public function divisionLevelGroup() {
        return $this->hasOne(DivisionLevelGroup::class, 'manager_id');
    }
}
