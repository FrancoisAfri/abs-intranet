<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalSetup extends Model
{
     //Specify the table name
    public $table = 'appraisal_setup';
	
	// Mass assignable fields
    protected $fillable = [
        'number_of_times', 'percentage', 'active'];
		

    
}
