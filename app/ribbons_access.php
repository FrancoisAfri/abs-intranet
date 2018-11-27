<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ribbons_access extends Model
{
	
	protected $table = 'security_ribbon_access';
        // Mass assignable fields
	 protected $fillable = [
        'ribbon_id', 'access_level', 'user_id', 'description'
    ];
	//Relationship modules and ribbons
    public function ribbonsAccess() {
        return $this->belongsTo(module_ribbons::class, 'ribbon_id');
    }
}
