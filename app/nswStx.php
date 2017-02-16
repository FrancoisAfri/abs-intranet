<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nswStx extends Model
{
    protected $table = 'nsw_stxes';
    
    //Mass assignable fields
    protected $fillable = [
        'school_id', 'date_attended'
    ];
    //Relationship 
    public function nswStxesGrades() {
        return $this->hasMany(nswStx_grade::class, 'nsw_id');
    }
	public function nswStxesEducators() {
        return $this->hasone(nswStx_educator::class, 'nsw_id');
    }
	//Function to save Grades
    public function addStxesGrades(nswStx_grade $nswStx) {
        return $this->nswStxesGrades()->save($nswStx);
    }
    public function addOrUpdateStxesGrades(array $nswStx) {
        return $this->nswStxesGrades()->updateOrCreate([], $nswStx);
    }
	//Function to save school's Educators
    public function addStxesEducator(nswStx_educator $nswStx) {
        return $this->nswStxesEducators()->save($nswStx);
    }
    public function addOrUpdateStxesEducator(array $nswStx) {
        return $this->nswStxesEducators()->updateOrCreate([], $nswStx);
    }
}
