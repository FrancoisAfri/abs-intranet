<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendanceRegister extends Model
{
    //Specify the table name
    public $table = 'attendance_register';
	
	// Mass assignable fields
    protected $fillable = [
        'registration_type', 'programme_id', 'project_id', 'learner_id', 'educator_id', 'gen_public_id'
		, 'attendance', 'registration_year', 'date_attended', 'course_type', 'registration_semester'];
		
	//Relationship educator and user
   /* public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }*/
}
