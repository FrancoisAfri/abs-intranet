<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleCommunications extends Model
{
        //Specify the table name
    public $table = 'fleet_communications';

    // Mass assignable fields
    protected $fillable = [
        'communication_type', 'message', 'vehicle_id', 'status', 'sent_by', 'communication_date', 'time_sent', 'contact_id'
		, 'company_id', 'employee_id', 'send_type'];
		
	//Relationship vehicle communication and contact person
    public function contact() {
        return $this->belongsTo(ContactPerson::class, 'contact_id');
    }
	
	//Relationship vehicle communication and company
    public function company() {
        return $this->belongsTo(ContactCompany::class, 'company_id');
    }
	
	//Relationship vehicle communication and sender
    public function sender() {
        return $this->belongsTo(HRPerson::class, 'sent_by');
    }
	//Relationship vehicle communication and Employee
    public function employees() {
        return $this->belongsTo(HRPerson::class, 'employee_id');
    }
}
