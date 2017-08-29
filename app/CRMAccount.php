<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRMAccount extends Model
{
    //Specify the table name
    public $table = 'c_r_m_accounts';

    //Mass assignable fields
    protected $fillable = [
        'client_id', 'company_id', 'invoice_id', 'account_number',  'balance', 'start_date', 'end_date'
    ];
}
