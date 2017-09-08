<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CRMInvoice extends Model
{
    //Specify the table name
    public $table = 'c_r_m_invoices';

    //Mass assignable fields
    protected $fillable = [
        'quotation_id', 'client_id', 'company_id', 'account_id', 'invoice_number', 'amount', 'invoice_date', 'status'
    ];

    //Invoice status
    private $invoiceStatuses = ['' => '', 1 => 'Invoice Created', 2 => 'Invoice Sent To Client', 3 => 'Invoice Partially Paid', 4 => 'Invoice Paid'];

    /**
     * Relationship between CRMInvoice and Quotation
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /**
     * Relationship between CRMInvoice and CRMPayment
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(CRMPayment::class, 'invoice_id');
    }

    /**
     * Invoice status accessor
     *
     * @return  string
     */
    public function getInvoiceStatusAttribute()
    {
        return ($this->status) ? $this->invoiceStatuses[$this->status] : null;
    }
}
