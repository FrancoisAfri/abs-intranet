<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientInduction extends Model
{
    //Specify the table name
    public $table = 'client_inductions';

    // Mass assignable fields
    protected $fillable = [
        'induction_title', 'company_id', 'status', 'create_by', 'notes', 'date_created'
    ];

    //relationship between contact_company and contact person (contacts_contacts)
    public function TasksList() {
        return $this->hasMany(EmployeeTasks::class, 'induction_id');
    }
	//relationship between contact_company and contact person (contacts_contacts)
    public function ClientName() {
        return $this->belongsTo(ContactCompany::class, 'company_id');
    }

     //Total task accessor
    public function getTOTALTASKAttribute() {
        $this->load('TasksList');
        return $this->TasksList->count();
    }

        //Total completed task accessor
     public function getCompletedTaskAttribute() {
        $this->load(['TasksList' => function($query) {
            $query->where('status', 4);
        }]);
        return $this->TasksList->count();
    }

   



	
}