<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    //Specify the table name
    public $table = 'registrations';

    // Mass assignable fields
    protected $fillable = [
        'programme_id', 'project_id', 'learner_id', 'educator_id', 'gen_public_id', 'registration_year', 'course_type', 'registration_semester', 'gen_pub_reg_fee'
    ];

    //Relationship registration and programme
    public function programme() {
        return $this->belongsTo(programme::class, 'programme_id');
    }

    //Relationship registration and project
    public function project() {
        return $this->belongsTo(projects::class, 'project_id');
    }

    //Relationship registration and learner/educator/general public
    public function client() {
        $regType = $this->registration_type;
        if ($regType == 1) return $this->belongsTo(Learner::class, 'learner_id'); //learner
        elseif ($regType == 2) return $this->belongsTo(educator::class, 'educator_id'); //educator
        elseif ($regType == 3) return $this->belongsTo(public_reg::class, 'gen_public_id'); //general public
    }

    //Relationship registration and registration module/subject/ara
    public function subjects() {
        $regType = $this->registration_type;
        if ($regType == 1) return $this->hasMany(RegistrationSubject::class, 'registration_id'); //Subjects (learner)
        elseif ($regType == 2) return $this->hasMany(RegistrationModule::class, 'registration_id'); //Modules (educator)
        elseif ($regType == 3) return $this->hasMany(RegistrationArea::class, 'registration_id'); //area (general public)
    }
    
    //Function to save registration subjects
    public function addSubjects(array $subjectIDs) {
        $subjects = [];
        foreach ($subjectIDs as $subjectID) {
            $subjects[] = new RegistrationSubject(['subject_id' => $subjectID]);
        }
        return $this->subjects()->saveMany($subjects);
    }

    //Function to save registration areas
    public function addAreas(array $areaIDs) {
        $areas = [];
        foreach ($areaIDs as $areaID) {
            $areas[] = new RegistrationArea(['area_id' => $areaID]);
        }
        return $this->subjects()->saveMany($areas);
    }

    //Function to save registration modules
    public function addModules(array $moduleNames, array $moduleFees) {
        $modules = [];
        $count = count($moduleNames);
        for ($i = 0; $i < $count; $i++) {
            $modules = new RegistrationModule(['module_name' => $moduleNames[$i], 'module_fee' => $moduleFees[$i]]);
        }
        return $this->subjects()->saveMany($modules);
    }

}
