<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stockhistory extends Model
{
    
	protected $table = 'stock_history';

    protected $fillable = ['action', 'status', 'product_id',
        'category_id', 'avalaible_stock', 'action_date', 'user_id', 'vehicle_id',
        'user_allocated_id', 'balance_before', 'balance_after'];
}
