<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contacts_note extends Model
{
   public $table = 'contacts_notes';

    // Mass assignable fields
    protected $fillable = ['originator_type','company_id','hr_person_id','employee_id','date','time','communication_method','rensponse','notes','next_action','follow_date',];
			
}
