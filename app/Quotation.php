<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    //Specify the table name
    public $table = 'quotations';

    // Mass assignable fields
    protected $fillable = [
        'company_id', 'client_id', 'division_id', 'division_level', 'hr_person_id', 'approval_person_id', 'status', 'send_date', 'approval_date'
    ];

    /**
     * Relationship between Quotations and Products
     *
     * @return product_products
     */
    public function products()
    {
        return $this->belongsToMany('App\product_products', 'quoted_products', 'quotation_id', 'product_id')->withPivot();
    }
}
