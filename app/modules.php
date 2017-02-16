<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class modules extends Model
{
	protected $table = 'security_modules';
	
    	 protected $fillable = [
        'name', 'path', 'active', 'font_awesome'
    ];
	
	 //Relationship module and ribbon
    public function moduleRibbon() {
        return $this->hasmany(module_ribbons::class, 'module_id');
    } 
	//Relationship module and access
    public function moduleAccess() {
        return $this->hasone(module_access::class, 'module_id');
    }
	 //Function to save module's ribbon
    public function addRibbon(module_ribbons $ribbon) {
        return $this->moduleRibbon()->save($ribbon);
    }
	
    //Function to save module access
    public function addModuleAccess(module_access $module) {
        return $this->moduleAccess()->save($module);
    }
}
