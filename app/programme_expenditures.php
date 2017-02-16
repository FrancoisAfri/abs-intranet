<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class programme_expenditures extends Model
{
    //Specify the table name
    public $table = 'programme_expenditures';

    // Mass assignable fields
    protected $fillable = [
        'amount', 'date_added', 'payee', 'payee_id', 'supporting_doc'
    ];
	
	//Relationship expenditure and programme
    public function programme() {
        return $this->belongsTo(programme::class, 'programme_id');
    }
}
