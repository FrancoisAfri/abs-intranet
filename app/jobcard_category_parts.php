<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcard_category_parts extends Model
{
    protected $table = 'jobcard_category_parts';

    protected $fillable = [ 'name','description','status'];
}
