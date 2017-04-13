<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class leave_application extends Model
{
       protected $table = 'leave_application';
    protected $fillable = ['application','notes','supporting_docs','start_date',
                          'end_date','status','hr_id','leave_type_id','start_time',
                          'end_time','manager_id'
                          ];
                          
    
    

    // #types of leave profiles belonging to leave types
    
}
