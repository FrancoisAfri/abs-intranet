<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class images extends Model
{
    protected $table = 'vehicle_image';

    protected $fillable = ['name', 'description', 'image', 'upload_date', 'user_name', 'status', 'vehicle_maintanace', 'default_image' , 'jobcard_id'];

    //image - vehicle_maintance relationship
    public function vehicle_maintenance()
    {
        return $this->belongsTo(vehicle_maintenance::class, 'vehicle_maintanace');
    }

}
