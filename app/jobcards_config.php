<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcards_config extends Model
{
    protected $table = 'jobcard_config';

    protected $fillable = ['use_procurement'
	, 'mechanic_sms', 'service_file_from'
	, 'service_file_to', 'break_test_from'
	, 'break_test_to'];
}
