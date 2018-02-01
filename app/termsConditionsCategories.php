<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class termsConditionsCategories extends Model
{
    //Specify the table name
    public $table = 'quote_terms_categories';

    // Mass assignable fields
    protected $fillable = [
        'name', 'status', 'description'
    ];

    /**
     * Relationship between Terms & Conditions and Quotations
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function terms()
    {
        return $this->hasMany(QuotesTermAndConditions::class, 'category_id');
    }
}
