<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppraisalThreeSixtyPerson extends Model
{
    //Specify the table name
    public $table = 'appraisal_three_sixty_people';

    // Mass assignable fields
    protected $fillable = ['appraiser_id', 'appraisal_month'];

    //relationship with HRPerson
    public function hrPerson() {
        return $this->belongsTo(AppraisalThreeSixtyPerson::class, 'hr_id');
    }
}
