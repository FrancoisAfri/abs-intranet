<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class operator extends Model
{
    protected $table = 'operator';
    protected $fillable = ['operator_id', 'helpdesk_id', 'status'];
}
