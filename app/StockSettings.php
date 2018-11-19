<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockSettings extends Model
{
     //Specify the table name
    public $table = 'stock_settings';

    // Mass assignable fields
    protected $fillable = [
        'unit_of_measurement'];
}
