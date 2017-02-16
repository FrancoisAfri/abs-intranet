<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class programme_incomes extends Model
{
    //
    //Specify the table name
    public $table = 'programme_incomes';

    // Mass assignable fields
    protected $fillable = [
        'amount', 'date_added', 'payer', 'payer_id', 'supporting_doc'
    ];
	
	//Relationship income and programme
    public function programme() {
        return $this->belongsTo(programme::class, 'programme_id');
    }
}
