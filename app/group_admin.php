<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class group_admin extends Model
{
    //
    protected $table = 'group_admin';

    protected $fillable = [ 'name', 'description','status'];
}
