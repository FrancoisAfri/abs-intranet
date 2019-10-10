<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy_Category extends Model
{
    public $table = 'policy_category';

    // Mass assignable fields
    protected $fillable = ['name', 'description','status'];

    /**
     * Relationship between Quotation and CRMInvoice
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function policies()
    {
        return $this->hasMany(Policy::class, 'category_id')->orderBy('id');
    }
}