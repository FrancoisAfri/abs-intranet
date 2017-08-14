<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    //Specify the table name
    public $table = 'email_templates';

    // Mass assignable fields
    protected $fillable = [
        'template_key', 'template_content'
    ];
}
