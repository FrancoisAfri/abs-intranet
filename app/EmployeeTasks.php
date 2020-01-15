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
        , 'is_dependent', 'administrator_id', 'check_by_id', 'checked', 'client_id'
		, 'manager_duration', 'due_time', 'document_on_task'
    ];

    //relationship between EmployeeTasks and employeetasksDocument
    public function tasksDocuments()
    {
        return $this->hasMany(EmployeeTasksDocuments::class, 'task_id');
    }

    //relationship between meeting and induction tasks
    public function tasksInduction()
    {
        if ($this->task_type === 1 || $this->type === 3) { //task_type 1== induction
            return $this->belongsTo(ClientInduction::class, 'induction_id');
        } elseif ($this->task_type === 2) { //2== Meeting tasks
            return $this->belongsTo(MeetingMinutes::class, 'meeting_id');
        }
    }

    //Relationship categories and Kpas
    public function employeesTasks()
    {
        return $this->belongsTo(HRPerson::class, 'employee_id');
    }

    //Relationship categories and Kpas
    public function checkedBy()
    {
        return $this->belongsTo(HRPerson::class, 'checked_by');
    }

    //Relationship client and task
    public function clientTasksName()
    {
        return $this->belongsTo(ContactCompany::class, 'client_id');
    }

    //human friendly duration accessor
    public function getHumanDurationAttribute()
    {
        $duration = ($this->duration > 0) ? $this->duration : 0;
        $humanDuration = gmdate('H:i:s.0', $duration);
        return $humanDuration;
    }
}