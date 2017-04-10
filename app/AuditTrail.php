<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    //Specify the table name
    public $table = 'audit_trail';
	
	// Mass assignable fields
    protected $fillable = [
        'module_name', 'user_id', 'action', 'action_date', 'notes', 'reference_id'];
		
	//Relationship educator and user
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
