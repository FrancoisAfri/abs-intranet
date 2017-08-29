<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRMInvoice extends Model
{
    //Specify the table name
    public $table = 'c_r_m_invoices';

    //Mass assignable fields
    protected $fillable = [
        'quotation_id', 'client_id', 'company_id', 'account_id', 'invoice_number', 'amount'
    ];
}
