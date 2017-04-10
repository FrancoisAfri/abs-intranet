<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class appraisalKpas extends Model
{
     //Specify the table name
    public $table = 'appraisal_kpas';
	
	// Mass assignable fields
    protected $fillable = [
        'name', 'status', 'weight', 'category_id'];
		
	//Relationship categories and Kpas
    public function kpascat() {
		return $this->belongsTo(appraisalCategories::class, 'category_id');
    }

    //Relationship categories and Kpas
    public function kpi() {
        return $this->hasMany(appraisalsKpis::class, 'kpa_id');
    }
	
	    //function ro get people from a specific div level
    public static function kpaFronCat() {
        $kpas = appraisalKpas::where('status', 1)
            ->get()
            ->sortBy('name')
            ->pluck('id', 'name');
        return $kpas;
    }
}
