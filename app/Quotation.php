<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    //Specify the table name
    public $table = 'quotations';

    // Mass assignable fields
    protected $fillable = [
        'company_id', 'client_id', 'division_id', 'division_level', 'hr_person_id'
		, 'approval_person_id', 'status','send_date', 'approval_date', 'discount_percent'
		, 'add_vat', 'payment_option', 'payment_term','first_payment_date', 'account_id'
		, 'quote_number', 'quote_type', 'quote_title', 'quote_remarks', 'quote_date'
    ];

    //quotation status
    protected $quoteStatuses = [
        1 => 'Awaiting Manager Approval',
        2 => 'Awaiting Client Approval',
        3 => 'Approved by Manager',
        -3 => 'Declined by Manager',
        4 => 'Approved by Client',
        -4 => 'Declined by Client',
        -1 => 'Cancelled',
        5 => 'Authorised (Client Waiting Invoice)',
        6 => 'Invoice Sent',
        7 => 'Partially Paid',
        8 => 'Paid'
    ];

    //quotation status
    protected $quoteTypes = [
        1 => 'Products/Packages',
        2 => 'Services'
    ];

    //Payment opyions
    protected $paymentOptions = [
        1 => 'Once-Off',
        2 => 'Monthly Term'
    ];

    /**
     * Relationship between Quotation and Contact Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(ContactCompany::class, 'company_id');
    }

    /**
     * Relationship between Quotation and Contact Person (contacts_contacts)
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(ContactPerson::class, 'client_id');
    }

    /**
     * Relationship between Quotations and Products
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\product_products', 'quoted_products', 'quotation_id', 'product_id')->orderBy('category_id')->withPivot('price', 'quantity', 'comment')->withTimestamps();
    }

    /**
     * Relationship between Quotations and Packages
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
    {
        return $this->belongsToMany('App\product_packages', 'quoted_packages', 'quotation_id', 'package_id')->withPivot('price', 'quantity')->withTimestamps();
    }

    /**
     * Relationship between Quotations and Services
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(ProductService::class, 'quotation_id')->orderBy('id');
    }

    /**
     * Relationship between Quotations and Terms and Conditions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function termsAndConditions()
    {
        return $this->belongsToMany('App\QuotesTermAndConditions', 'quotation_terms_and_conditions', 'quotation_id', 'term_condition_id')->withTimestamps();
    }

    /**
     * Relationship between Quotation and HRPerson
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(HRPerson::class, 'hr_person_id');
    }

    /**
     * Relationship between Quotation and CRMAccount
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(CRMAccount::class, 'account_id');
    }

    /**
     * Relationship between Quotation and CRMInvoice
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(CRMInvoice::class, 'quotation_id')->orderBy('id');
    }

    public function divisionName()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_id');
    }

    public function quoteHistory()
    {
        return $this->hasmany(QuoteApprovalHistory::class, 'quotation_id');
    }

    /**
     * Quote status string accessor
     *
     * @return String
     */
    public function getQuoteStatusAttribute()
    {
        return (!empty($this->status)) ? $this->quoteStatuses[$this->status] : null;
    }

    /**
     * Quote payment option string accessor
     *
     * @return String
     */
    public function getStrPaymentOptionAttribute()
    {
        return (!empty($this->payment_option)) ? $this->paymentOptions[$this->payment_option] : null;
    }
}
