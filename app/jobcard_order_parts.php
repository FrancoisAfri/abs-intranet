<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcard_order_parts extends Model
{
    public $table = 'jobcard__order_parts';
    protected $fillable = ['no_of_parts_used', 'jobcard_parts_id', 'jobcard_card_id'
	, 'product_id', 'category_id', 'avalaible_transaction', 'created_by'
	, 'date_created', 'vehicle_id'];


}

