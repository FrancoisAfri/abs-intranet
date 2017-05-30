<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeTasks extends Model
{
    //Specify the table name
    public $table = 'employee_tasks';

    // Mass assignable fields
    protected $fillable = [
        'order_no', 'escalation_id', 'employee_id', 'library_id', 'added_by'
		, 'duration', 'description', 'notes', 'priority'
		, 'task_type', 'upload_required', 'status', 'start_date', 'date_started'
		, 'date_completed', 'date_paused', 'due_date', 'induction_id', 'meeting_id'
		, 'is_dependent'
    ];

    //relationship between EmployeeTasks and employeetasksDocument
    public function tasksDocuments() {
        return $this->hasMany(EmployeeTasksDocuments::class, 'task_id');
    }
	
	 //relationship between EmployeeTasks and employeetasksDocument
    public function tasksInduction() {
		if ($this->task_type === 1 || $this->type === 3) 
		{ //task_type 1== induction
            return $this->belongsTo(ClientInduction::class, 'induction_id');
        }
        /*elseif ($this->task_type === 2) { //2== Meeting tasks
            return $this->belongsTo(ContactPerson::class, 'meeting_id');
        }*/
    }
}