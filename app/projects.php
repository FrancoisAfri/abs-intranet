<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class projects extends Model
{
    //Specify the table name
    public $table = 'projects';

    // Mass assignable fields
    protected $fillable = [
        'name', 'code', 'start_date', 'end_date', 'description', 'programme_id', 'facilitator_id'
        , 'sponsor', 'manager_id', 'service_provider_id', 'budget', 'sponsorship_amount'
        , 'contract_amount', 'supporting_doc', 'contract_doc', 'user_id', 'approver_id', 'rejection_reason', 'status'
    ];

    //Relationship project and service provider
    public function serviceProvider()
    {
        return $this->belongsTo(contacts_company::class, 'service_provider_id');
    }

    //Relationship project and manager (HR Person)
    public function manager()
    {
        return $this->belongsTo(HRPerson::class, 'manager_id');
    }

    //Relationship project and facilitator (HR Person)
    public function facilitator()
    {
        return $this->belongsTo(HRPerson::class, 'facilitator_id');
    }

    //Relationship project and programme
    public function programme()
    {
        return $this->belongsTo(programme::class, 'programme_id');
    }

    //Relationship project and activity
    public function activity()
    {
        return $this->hasMany(activity::class, 'project_id');
    }

    //Relationship project and registration
    public function registration()
    {
        return $this->hasMany(Registration::class, 'project_id');
    }

    //Relationship between projects and expenditure
    public function expenditure()
    {
        return $this->hasMany(projects_expenditures::class, 'project_id');
    }

    //Relationship between project and income
    public function income()
    {
        return $this->hasMany(projects_incomes::class, 'project_id');
    }

    //Function to return projects from a certain programme
    public static function projectsFromProgramme($programmeID)
    {
        return projects::where('programme_id', $programmeID)
            ->where('status', 2)->get()
            ->sortBy('name')
            ->pluck('id', 'name');
    }

    //function to add Expenditures
    public function addExpenditure(projects_expenditures $expenditure) {
        return $this->expenditure()->save($expenditure);
    }

    //function to add Expenditures
    public function addIncome(projects_incomes $income) {
        return $this->income()->save($income);
    }
}
