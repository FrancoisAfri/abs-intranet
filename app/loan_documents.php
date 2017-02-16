<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loan_documents extends Model
{
    //
	protected $table = 'loan_application_documents';
	// Mass assignable fields
	 protected $fillable = [
        'date_uploaded', 'name', 'file_name'
    ];
	//Relationship loan_application and Loan documents
    public function document() {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
