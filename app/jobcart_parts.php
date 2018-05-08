<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcart_parts extends Model
{
    protected $table = 'jobcard_parts';

    protected $fillable = [ 'name','description','status','no_of_parts_available','category_id'];
}
