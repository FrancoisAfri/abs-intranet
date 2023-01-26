<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingDocuments extends Model
{
    public $table = 'training_documents';

    protected $fillable = [
        'name', 'description', 'document', 'date_added', 'status', 'division_level_1', 'division_level_2'
		, 'division_level_3', '', 'division_level_5'
    ];

    public static function getDocuments($division_level_3, $division_level_4, $division_level_5)
    {
       return TrainingDocuments
				   ::where('status',1)
				   ->where(function ($query)  use ($division_level_3,$division_level_4,$division_level_5)  {
						$query->where('division_level_3', '=', $division_level_3)
							  ->orWhere('division_level_4', '=', $division_level_4)
							  ->orWhere('division_level_5', '=', $division_level_5);
					})
					->get();

    }
	//Relationship hr person and province
    public function division()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_level_5');
    }

    //Relationship hr person and province
    public function department()
    {
        return $this->belongsTo(DivisionLevelFour::class, 'division_level_4');
    }
	public function section()
    {
        return $this->belongsTo(DivisionLevelThree::class, 'division_level_3');
    }
}
