<?php

namespace App\Models;

use App\HRPerson;
use App\Traits\Uuids;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;

class StaffLoanDocuments extends Model
{
    use Uuids;
	
    protected $table = 'staff_loan_documents';
	
	/**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];
	
    protected $fillable = ['loan_id', 'supporting_docs', 'status', 'doc_name'];
	
	 /**
     * status constants
     */	
	
	public function loan(): BelongsTo
    {
        return $this->belongsTo(StaffLoan::class, 'loan_id')->orderBy('id');
    }
	
}
