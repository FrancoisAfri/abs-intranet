<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalCategories extends Model
{
     //Specify the table name
    public $table = 'appraisal_categories';
	
	// Mass assignable fields
    protected $fillable = [
        'name', 'status', 'weight'];
		
	//Relationship categories and Kpas
    public function kpascategory() {
        return $this->hasmany(appraisalKpas::class, 'category_id');
    }
}
