<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class unresolved_tickets_settings extends Model
{
    public $table = 'unresolved_tickets_settings';
	
	// Mass assignable fields
    protected $fillable = [
      'tickets_low', 'tickets_critical', 'critical_oficehrs', 'low_ah', 'esc_low_email', 'esc_low_sms','aftoffice_hrs_low_email'
	  ,'aftoffice_hrs_low_sms','tickets_normal','normal_oficehrs', 'office_hrs_normal_email'
	  ,'office_hrs_normal_sms', 'aftoffice_hrs_nomal_email','aftoffice_hrs_nomal_sms',
	  'tickets_high', 'high_oficehrs','office_hrs_high_email',  'office_hrs_high_sms',
	  'aftoffice_hrs_high_email',  'aftoffice_hrs_high_sms',  'auto_critical', 
	  'office_hrs_critical', 'notify_level_critical','office_hrs_critical_email',
	  'office_hrs_critical_sms','aftoffice_hrs_critical_email','aftoffice_hrs_critical_sms',
	  'helpdesk_id'];
}
