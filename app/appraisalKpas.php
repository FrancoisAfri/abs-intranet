<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalKpas extends Model
{
     //Specify the table name
    public $table = 'appraisal_kpas';
	
	// Mass assignable fields
    protected $fillable = [
        'name', 'status', 'weight'];
		
	//Relationship categories and Kpas
    public function kpascat() {
		return $this->belongsTo(appraisalCategories::class, 'category_id');
    }
}