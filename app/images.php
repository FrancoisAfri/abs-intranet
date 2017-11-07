<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class images extends Model
{
     protected $table = 'vehicle_image';

     protected $fillable = [ 'name', 'description','image','upload_date','user_name','status', 'vehicle_maintanace'];
}
