<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_incidents extends Model
{

	 protected $table = 'vehicle_incidents';

    protected $fillable = ['date_of_incident','incident_type','severity','reported_by','hours_reading'
			,'odometer_reading','status','claim_number','description','Cost','vehicleID','vehicle_fixed'];
	
	//Relationship Incidents and documents
    public function incidentDoc()
    {
        return $this->hasmany(VehicleIncidentsDocuments::class, 'incident_id');
    }
	
	//Function to save Incidents Documents
    public function addIncidentDocs(VehicleIncidentsDocuments $incident)
    {
        return $this->incidentDoc()->save($incident);
    }
}
