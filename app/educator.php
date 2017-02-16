<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class educator extends Model
{
    //Specify the table name
    public $table = 'educators';

    // Mass assignable fields
    protected $fillable = [
        'type', 'first_name', 'surname', 'school_id', 'id_number', 'activity_id', 'project_id', 'ethnicity',
         'gender', 'cell_number', 'highest_qualification', 'highest_professional_qualification', 'physical_address', 'postal_address', 'next_of_keen', 'nok_first_name', 'nok_surname', 'nok_relationship', 'nok_cell_number', 'nok_email', 'course_sponsored', 'email', 'institution', 'engagement_date', 'cv_doc', 'contract_doc', 'result', 'result_doc', 'first_time', 'active'
    ];

    //Relationship educator and user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Relationship educator and school
    public function school() {
        return $this->belongsTo(contacts_company::class, 'school_id');
    }

    //Relationship educator and activity
    public function activity() {
        return $this->belongsTo(activity::class, 'activity_id');
    }

    //Relationship educator and registration
    public function registration() {
        return $this->hasMany(Registration::class, 'educator_id');
    }

    //Full name accessor
    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->surname;
    }
}
