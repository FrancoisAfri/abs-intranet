<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    //Specify the table name
    public $table = 'quotations';

    // Mass assignable fields
    protected $fillable = [
        'company_id', 'client_id', 'division_id', 'division_level', 'hr_person_id', 'approval_person_id', 'status',
        'send_date', 'approval_date', 'discount_percent', 'add_vat'
    ];

    //quotation status
    protected $quoteStatuses = [1 => 'New Quote'];

    /**
     * Relationship between Quotations and Products
     *
     * @return
     */
    public function products()
    {
        return $this->belongsToMany('App\product_products', 'quoted_products', 'quotation_id', 'product_id')->withPivot('price', 'quantity')->withTimestamps();
    }

    /**
     * Relationship between Quotations and Packages
     *
     * @return
     */
    public function packages()
    {
        return $this->belongsToMany('App\product_packages', 'quoted_packages', 'quotation_id', 'package_id')->withPivot('price', 'quantity')->withTimestamps();
    }

    /**
     * Relationship between Quotation and HRPerson
     *
     * @return
     */
    public function person()
    {
        return $this->belongsTo(HRPerson::class, 'hr_person_id');
    }
}
