<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmS_Configuration extends Model
{
     //Specify the table name
    public $table = 'contacts_setup';
	
	// Mass assignable fields
    protected $fillable = [
        'sms_provider', 'sms_username', 'sms_password'];
		
}
