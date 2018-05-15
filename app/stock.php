<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stock extends Model
{

    protected $table = 'stock';

    protected $fillable = ['name', 'description', 'status', 'product_id', 'category_id','avalaible_stock','date_added'];

}
