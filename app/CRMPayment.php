<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRMPayment extends Model
{
    //Specify the table name
    public $table = 'c_r_m_payments';

    //Mass assignable fields
    protected $fillable = [
        'amount', 'payment_date', 'proof_of_payment',
    ];

    /**
     * Relationship between CRMPayment and CRMInvoice
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(CRMInvoice::class, 'invoice_id');
    }
}
