<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activities_incomes extends Model
{
    //
     //
      //Specify the table name
    public $table = 'activities_incomes';

    // Mass assignable fields
    protected $fillable = [
        'amount', 'date_added', 'payer', 'payer_id', 'supporting_doc'
    ];
	
	//Relationship income and programme
    public function programme() {
        return $this->belongsTo(activity_incomes::class, 'programme_id');
    }
}
