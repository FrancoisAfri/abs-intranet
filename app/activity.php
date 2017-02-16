<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class activity extends Model
{
    //Specify the table name
    public $table = 'activities';

    // Mass assignable fields
    protected $fillable = [
        'project_id', 'name', 'code', 'start_date', 'end_date', 'topic', 'budget', 'description', 'actual_cost', 'sponsor', 'sponsorship_amount', 'contract_amount', 'contract_doc', 'supporting_doc', 'comment'
    ];

    //Relationship activity and programme
    public function programme() {
        return $this->belongsTo(programme::class, 'programme_id');
    }
    //Relationship activity and project
    public function project() {
        return $this->belongsTo(projects::class, 'project_id');
    }
    //Relationship activity and hr person
    public function facilitator() {
        return $this->belongsTo(HRPerson::class, 'facilitator_id');
    }
    //Relationship activity and service provider
    public function serviceProvider() {
        return $this->belongsTo(contacts_company::class, 'service_provider_id');
    }
    //Relationship expenditure and activity
    public function expenditure() {
        return $this->hasMany(activity_expenditures::class, 'activity_id');
    }
     //Relationship income and activity
    public function income() {
        return $this->hasMany(activity_incomes::class, 'activity_id');
    }

    //function to add Expenditures
    public function addExpenditure(activity_expenditures $expenditure) {
        return $this->expenditure()->save($expenditure);
    }
    //function to add Expenditures
    public function addIncome(activity_incomes $income) {
        return $this->income()->save($income);
    }
    //function to return an array of activities from a specifi project
    public static function activitiesFromProject($projectID) {
        return activity::where('project_id', $projectID)
            ->where('status', 2)->get()
            ->sortBy('name')
            ->pluck('id', 'name');
    }
}
