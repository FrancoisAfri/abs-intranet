<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class keytracking extends Model
{

	 protected $table = 'keytracking';

    protected $fillable = [   'issued_to','employee','safe_name','safe_controller','date_issued','date_status_change','issued_by','description','status', 'key_number','','key_status' ,'vehicle_type','vehicle_id','date_lost','reason_loss'];


}
