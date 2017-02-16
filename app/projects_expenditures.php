<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class projects_expenditures extends Model
{
    //Specify the table name
    public $table = 'projects_expenditures';

    // Mass assignable fields
    protected $fillable = [
        'amount', 'date_added', 'payee', 'payee_id', 'supporting_doc'
    ];

    //Relationship expenditure and programme
    public function project()
    {
        return $this->belongsTo(projects::class, 'project_id');
    }
}
