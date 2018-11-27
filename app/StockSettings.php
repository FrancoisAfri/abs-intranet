<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockSettings extends Model
{
     //Specify the table name
    public $table = 'stock_settings';

    // Mass assignable fields
    protected $fillable = [
        'unit_of_measurement','require_managers_approval','require_store_manager_approval'
		,'require_department_head_approval','require_ceo_approval'];
}
