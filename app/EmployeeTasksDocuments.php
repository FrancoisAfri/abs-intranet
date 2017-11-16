<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeTasksDocuments extends Model
{
    //Specify the table name
    public $table = 'employee_tasks_documents';

    // Mass assignable fields
    protected $fillable = [
        'task_id', 'employee_id', 'added_by', 'document', 'status'];

    //relationship between tasks and document
    public function employeeDocs()
    {
        return $this->belongsTo(EmployeeTasks::class, 'task_id');
    }

}