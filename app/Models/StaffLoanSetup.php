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

class StaffLoanSetup extends Model
{
	use Uuids;
	
    protected $table = 'staff_loan_setups';
	
	/**
     * @var string[]
     
    protected $hidden = [
        'id'
    ];
	*/
	
    protected $fillable = ['max_amount', 'first_approval', 'second_approval', 'hr', 'finance'
	, 'finance_second', 'payroll', 'loan_upload_directory'];
	
	
	public function firstUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'first_approval')->orderBy('id');
    }
	
	public function secondUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'second_approval')->orderBy('id');
    }
	public function financeSecUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'finance_second')->orderBy('id');
    }
	public function financeUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'finance')->orderBy('id');
    }
	public function payrollUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'payroll')->orderBy('id');
    }
	public function hrUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'hr')->orderBy('id');
    }
				
}
