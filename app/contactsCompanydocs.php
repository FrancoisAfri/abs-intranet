<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contactsCompanydocs extends Model
{
    public $table = 'company_documents';

    // Mass assignable fields
    protected $fillable = ['name', 'description', 'date_from', 'expirydate', 'supporting_docs', 'company_id', 'status', 'doc_type'];
	
		//
	public function documentType() {
        return $this->belongsTo(CrmDocumentType::class, 'doc_type');
    }
}