<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_fleet_cards extends Model
{
    //

     protected $table = 'vehicle_fleet_cards';

    protected $fillable = [ 'card_type_id','company_id','holder_id','card_number','expiry_date','cvs_number','issued_date','registration_number','fleet_number','status'];
}
