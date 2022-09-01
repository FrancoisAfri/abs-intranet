<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreRoom extends Model
{
    public $table = 'store_room';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
