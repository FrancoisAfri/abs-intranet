<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockDelivery extends Model
{
    protected $table = 'stock_deliveries';

    protected $fillable = ['employee_id', 'delivery_slip', 'request_stocks_id',
						'status', 'date_delivered'];
			
	 // Product & Location
    public function deliverStock()
    {
        return $this->belongsTo(RequestStock::class, 'request_stocks_id')->orderBy('id');
    }
	
}
