<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class public_reg extends Model
{
     //Specify the table name
    public $table = 'public_regs';

    // Mass assignable fields
    protected $fillable = [
        'type', 'names', 'id_number', 'ethnicity', 'gender', 'cell_number', 'activity_id', 'phys_address', 'postal_address', 'email', 'highest_qualification', 'previous_computer_exp', 'programme_discovery', 'completed_certificates', 'date', 'attendance_doc', 'results', 'registration_fee', 'employment_status'];
    //Relationship educator and user
    public function activity() {
        return $this->belongsTo(activity::class, 'activity_id');
    }

    //Relationship general public and registration
    public function registration() {
        return $this->hasMany(Registration::class, 'gen_public_id');
    }

    //Full name accessor
    public function getFullNameAttribute() {
        return $this->names;
    }
}
