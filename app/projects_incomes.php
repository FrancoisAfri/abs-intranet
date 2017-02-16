<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class projects_incomes extends Model
{
    //  //Specify the table name
    public $table = 'projects_incomes';

    // Mass assignable fields
    protected $fillable = [
        'amount', 'date_added', 'payer', 'payer_id', 'supporting_doc'
    ];

    //Relationship expenditure and project
    public function project()
    {
        return $this->belongsTo(projects::class, 'project_id');
    }

}
