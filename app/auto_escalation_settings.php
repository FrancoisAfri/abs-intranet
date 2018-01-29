<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class auto_escalation_settings extends Model
{
   public $table = 'auto_escalation_settings';
	
	// Mass assignable fields
    protected $fillable = [
     'auto_low','office_hrs_low', 'notify_level_low', 'office_hrs_low_email', 'office_hrs_low_sms'
	 ,'aftoffice_hrs_low_email', 'aftoffice_hrs_low_sms','auto_mormal', 'office_hrs_normal'
	 ,'notify_level_normal', 'office_hrs_normal_email','office_hrs_normal_sms'
	 ,'aftoffice_hrs_normal_email', 'aftoffice_hrs_normal_sms',  'auto_high', 'office_hrs_hihg',
     'notify_level_high', 'office_hrs_high_email', 'office_hrs_high_sms', 'aftoffice_hrs_high_email'
	 ,'aftoffice_hrs_high_sms','auto_critical', 'office_hrs_critical', 'notify_level_critical'
	 , 'office_hrs_critical_email',  'office_hrs_critical_sms', 'aftoffice_hrs_critical_email'
	 ,'aftoffice_hrs_critical_sms' ,'helpdesk_id'];    
         
}
