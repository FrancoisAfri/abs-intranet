<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocuments extends Model
{
    protected $table = 'EmployeeDocuments';

    protected $fillable = ['category_id','doc_description','manager_id', 'expiry_date', 'doc'];



}

