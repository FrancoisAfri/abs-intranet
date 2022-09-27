<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErsAbsentUsers extends Model
{
    public $table = 'ers_absent_users';

    protected $fillable = [
        'hr_id', 'is_applied', 'date'
    ];
}
