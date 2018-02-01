<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehiclemodel extends Model
{
    protected $table = 'vehicle_model';

    protected $fillable = ['name', 'description', 'status','vehiclemake_id'];

    public function vehiclemake() {
        return $this->belongsTo(vehiclemake::class, 'make_id');
    }

     public static function movhedels($whereField, $whereValue, $incInactive) {
        $model = vehiclemodel::where(function ($query) use ($whereValue, $whereField) {
            if ($whereValue == 0) $query->whereNull($whereField);
            else $query->where($whereField, $whereValue);
            //$query->where();
        })
            ->where(function ($query) use($incInactive) {
                if ($incInactive == -1) {
                    $query->where('status', 1);
                }
            })->get()
            ->sortBy('name')
            ->pluck('id', 'name');
        return $model;
    }
}
