<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientInduction extends Model
{
    //Specify the table name
    public $table = 'client_inductions';

    // Mass assignable fields
    protected $fillable = [
        'induction_title', 'company_id', 'status', 'create_by', 'notes'
    ];

    //relationship between contact_company and contact person (contacts_contacts)
    public function TasksList() {
        return $this->hasMany(EmployeeTasks::class, 'induction_id');
    }

	
}