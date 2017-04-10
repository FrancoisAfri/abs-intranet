<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class module_ribbons extends Model
{
	protected $table = 'security_modules_ribbons';
    // Mass assignable fields
	 protected $fillable = [
        'module_id', 'sort_order', 'ribbon_name', 'ribbon_path', 'description', 'access_level', 'active'
    ];
	//Relationship modules and ribbons
    public function ribbons() {
        return $this->belongsTo(modules::class, 'module_id');
    }
		//Relationship ribbon and access
   /* public function ribbonAccess() {
        return $this->hasone(ribbons_access::class, 'ribbon_id');
    }
	
	    //Function to save ribbon access
    public function addRibbonAccess(ribbons_access $module) {
        return $this->ribbonAccess()->save($module);
    }*/
}
