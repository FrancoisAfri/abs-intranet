<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class programme extends Model
{
    //Specify the table name
    public $table = 'programmes';

    // Mass assignable fields
    protected $fillable = [
        'name', 'code', 'start_date', 'end_date', 'budget_expenditure', 'budget_income', 'description', 'sponsor', 'sponsorship_amount', 'contract_amount', 'contract_doc', 'supporting_doc', 'comment'
    ];

    //Relationship programme and service provider
    public function serviceProvider() {
        return $this->belongsTo(contacts_company::class, 'service_provider_id');
    }
    //Relationship programme and manager (HR Person)
    public function manager() {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }
    //Relationship programme and project
    public function project() {
        return $this->hasMany(projects::class, 'programme_id');
    }
    //Relationship programme and registration
    public function registration() {
        return $this->hasMany(Registration::class, 'programme_id');
    }
	//Relationship expenditure and programme
    public function expenditure() {
        return $this->hasMany(programme_expenditures::class, 'programme_id');
    }
    //..

    //Relationship income and programme
    public function income() {
        return $this->hasMany(programme_incomes::class, 'programme_id');
    }
	
	//function to add Expenditures
	public function addExpenditure(programme_expenditures $expenditure) {
		return $this->expenditure()->save($expenditure);
	}

    // function to add income
    public function addIncome(programme_incomes $income){
        return $this->income()->save($income);
    }
}
