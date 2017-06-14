<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskLibrary extends Model
{
        //Specify the table name
    public $table = 'task_libraries';
	
	// Mass assignable fields
    protected $fillable = [
        'order_no', 'description', 'upload_required', 'active', 'dept_id'];
		
	//Relationship library and tasks
    public function TaskLibraryTasks() {
        return $this->hasmany(appraisalKpas::class, 'library_id');
    }
}
