<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcards_config extends Model
{
    protected $table = 'jobcard_config';

    protected $fillable = ['use_procurement','mechanic_sms'];
}
