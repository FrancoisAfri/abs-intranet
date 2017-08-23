<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotesTermAndConditions extends Model
{
    //Specify the table name
    public $table = 'quotes_terns_conditions';

    // Mass assignable fields
    protected $fillable = [
        'type_id', 'term_name', 'status', 'vat_number'
    ];

    /**
     * Relationship between Terms & Conditions and Quotations
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quotations()
    {
        return $this->belongsToMany('App\Quotation');
    }
}
