<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activity_incomes extends Model
{
    //
    
      //Specify the table name
    public $table = 'activity_incomes';

    // Mass assignable fields
    protected $fillable = [
        'amount', 'date_added', 'payer', 'payer_id', 'supporting_doc'
    ];
	
	//Relationship income and programme
    public function activity() {
        return $this->belongsTo(activity::class, 'programme_id');
    }
}
