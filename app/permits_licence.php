<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class permits_licence extends Model
{
    public $table = 'permits_licence';

    // Mass assignable fields
    protected $fillable = [
        'Supplier', 'permit_licence', 'date_issued', 'exp_date', 'status', 'captured_by', 'date_captured',
        'document', 'vehicleID', 'default_documrnt', 'permits_licence_no'];

    public function vehiclepermitslicence(){
        return $this->belongsTo(permits_licence::class, 'vehicleID');
    }

}
