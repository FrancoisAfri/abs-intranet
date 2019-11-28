<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplaintsCompliments extends Model
{
    //Specify the table name
    public $table = 'complaints_compliments';

    //Mass assignable fields
    protected $fillable = [
        'office', 'error_type', 'pending_reason', 'summary_corrective_measure'
		, 'summary_complaint_compliment','company_id', 'client_id'
		, 'type', 'type_complaint_compliment', 'employee_id', 'created_by'
		,'responsible_party','date_complaint_compliment'
		,'date_created','status','supplier','closing_comment','manager_id','document_upload'
    ];
	
	public function employees()
    {
        return $this->belongsTo(HRPerson::class, 'employee_id');
    }
	
	public function createdBy()
    {
        return $this->belongsTo(HRPerson::class, 'created_by');
    }
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
}