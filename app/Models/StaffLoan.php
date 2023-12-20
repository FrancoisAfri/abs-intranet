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

class StaffLoan extends Model
{
    use Uuids;
	
    protected $table = 'staff_loans';
	
	/**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];
	
    protected $fillable = ['hr_id', 'reason', 'amount', 'status', 'repayment_month'
							, 'type', 'rejection_reason', 'first_approval_date', 'second_approval_date'
							, 'first_approval', 'second_approval', 'rejected_by', 'rejected_date'];
	
	 /**
     * status constants
     */
    const STATUS_SELECT = [
        1 => 'Awaiting Approval',
        2 => 'partially approved',
        3 => 'Approved',
        4 => 'Rejected',
    ];
	
	
	public function users(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'hr_id')->orderBy('id');
    }
	public function firstUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'first_approval')->orderBy('id');
    }
	public function secondUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'second_approval')->orderBy('id');
    }
	public function rejectedUsers(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'rejected_by')->orderBy('id');
    }
	
	public static function getAllLoanByStatus($status, $employee_id,$type)
    {
        $query = StaffLoan::with('users','firstUsers','secondUsers','rejectedUsers')
            ->orderBy('id', 'asc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }
		
		if ($employee_id !== 'all') {
            $query->where('hr_id', $employee_id);
        }

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        return $query->get();

    }
}
