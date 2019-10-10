<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    public $table = 'policy';

    // Mass assignable fields
    protected $fillable = ['name', 'description', 'document', 'status'
	, 'date', 'category_id'];


    /**
     * Relationship between Quotation and CRMInvoice
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function policyUsers()
    {
        return $this->hasMany(Policy_users::class, 'policy_id')->orderBy('id');
    }
	
	public function policyCategory()
    {
        return $this->belongsTo(Policy_Category::class, 'category_id')->orderBy('id');
    }
}