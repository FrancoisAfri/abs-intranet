<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class helpDesk_setup extends Model
{
   public $table = 'helpDeskSetup';

    protected $fillable = ['description','maximum_priority','time_from','time_to','notify_hr_email','notify_hr_sms_sms','notify_manager_email','notify_manager_sms' ,'helpdesk_id' ];
}
