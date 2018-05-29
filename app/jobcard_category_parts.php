<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcard_category_parts extends Model
{
    protected $table = 'jobcard_category_parts';

    protected $fillable = ['name', 'description', 'status'];

    public function jobcart_parts_model()
    {
        return $this->hasMany(jobcart_parts::class, 'category_id');
    }
}
