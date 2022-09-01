<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicensesType extends Model
{
    public $table = 'licence_type';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
