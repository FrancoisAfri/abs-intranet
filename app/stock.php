<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stock extends Model
{

    protected $table = 'stock';

    protected $fillable = ['name', 'description', 'status', 'product_id',
        'category_id', 'avalaible_stock', 'date_added', 'user_id', 'vehicle_id'
		, 'serial_number', 'bar_code', 'store_id'];

}
