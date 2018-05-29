<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class packages_product_table extends Model
{
    protected $table = 'packages_product_table';
    protected $fillable = ['product_packages_id', 'product_product_id'];
}
