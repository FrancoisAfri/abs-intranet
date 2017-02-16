<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    //Specify the table name
    public $table = 'learners';

    // Mass assignable fields
    protected $fillable = [
        'type','first_name', 'surname', 'grade', 'id_number', 'school_id', 'date_of_birth', 'gender', 'cell_number','project_id', 'field_of_choice', 'activity_id', 'first_time', 'physical_address', 'learning_condition', 'physical_disability', 'medical_condition', 'parent_name', 'parent_number', 'educator_id', 'toy_library', 'attendance_reg_doc', 'result_doc', 'active', 'date_started_project', 'ethnicity'
    ];

    //Relationship learner and school
    public function school() {
        return $this->belongsTo(contacts_company::class, 'school_id');
    }

    //Relationship learner and activity
    public function activity() {
        return $this->belongsTo(activity::class, 'activity_id');
    }

    //Relationship learner and educator
    public function educator() {
        return $this->belongsTo(educator::class, 'educator_id');
    }
    
    //Relationship learner and registration
    public function registration() {
        return $this->hasMany(Registration::class, 'learner_id');
    }

    //Full name accessor
    public function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->surname;
    }

}
