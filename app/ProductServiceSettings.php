<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductServiceSettings extends Model
{
    //Specify the table name
    public $table = 'product_service_settings';

    // Mass assignable fields
    protected $fillable = [
        'service_unit_name', 'service_unit_plural_name'
    ];
}
