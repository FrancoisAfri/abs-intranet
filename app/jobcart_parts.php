<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcart_parts extends Model
{
    protected $table = 'jobcard_parts';

    protected $fillable = ['name', 'description', 'status', 'no_of_parts_available', 'category_id'];

    public function jobcard_category_parts()
    {
        return $this->belongsTo(jobcard_category_parts::class, 'category_id');
    }

    public static function jobcardmodels($whereField, $whereValue, $incInactive)
    {
        $model = jobcart_parts::where(function ($query) use ($whereValue, $whereField) {
            if ($whereValue == 0) $query->whereNull($whereField);
            else $query->where($whereField, $whereValue);
            //$query->where();
        })
            ->where(function ($query) use ($incInactive) {
                if ($incInactive == -1) {
                    $query->where('status', 1);
                }
            })->get()
            ->sortBy('name')
            ->pluck('id', 'name');
        return $model;
    }
}
