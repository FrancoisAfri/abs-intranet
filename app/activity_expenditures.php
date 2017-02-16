<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activity_expenditures extends Model
{
    //
      //Specify the table name
    public $table = 'activity_expenditures';

    // Mass assignable fields
    protected $fillable = [
        'amount', 'date_added', 'payee', 'payer_id', 'supporting_doc'
    ];
	
	//Relationship income and programme
    public function activity() {
        return $this->belongsTo(activity_expenditures::class, 'programme_id');
    }

}
