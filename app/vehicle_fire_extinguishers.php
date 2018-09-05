<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_fire_extinguishers extends Model
{
    protected $table = 'vehicle_fire_extinguisher';

    protected $fillable = ['date_purchased','vehicle_id','supplier_id','bar_code',
                            'item_no','Description','Weight','Serial_number','invoice_number','purchase_order',
                            'Cost','rental_amount','image','Status','notes','capturer_id','attachement'];
}
