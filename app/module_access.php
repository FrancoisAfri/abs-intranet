<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class module_access extends Model
{
	protected $table = 'security_modules_access';
     // Mass assignable fields
	 protected $fillable = [
        'module_id', 'user_id', 'active', 'access_level'
    ];
	//Relationship modules and ribbons
    public function ribbons() {
        return $this->belongsTo(modules::class, 'module_id');
    }
}
