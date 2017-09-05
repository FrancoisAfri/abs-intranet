<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRMAccount extends Model
{
    //Specify the table name
    public $table = 'c_r_m_accounts';

    //Mass assignable fields
    protected $fillable = [
        'account_number',  'balance', 'start_date', 'end_date'
    ];

    //account status array
    private $statuses = ['' => '', 1 => 'Open'];

    /**
     * Relationship between Account and Contact Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(ContactCompany::class, 'company_id');
    }

    /**
     * Relationship between Account and Contact Person
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(ContactPerson::class, 'client_id');
    }

    /**
     * Relationship between Account and quotations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'account_id');
    }

    /**
     * This accessor returns a string value of the status
     *
     * @return string
     */
    public function getStrStatusAttribute()
    {
        return $this->statuses[$this->status];
    }
}
