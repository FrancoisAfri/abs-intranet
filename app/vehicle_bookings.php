<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_bookings extends Model
{
    protected $table = 'vehicle_bookings';

    protected $fillable = ['capturer_id', 'driver_id', 'purpose', 'status', 'vehicle_id', 'reject_reason', 'require_datetime', 'project_id', 'destination', 'start_mileage_id', 'end_mileage_id', 'approver1_id', 'approver1_timestamp', 'approver2_id', 'approver2_timestamp', 'approver3_id', 'approver3_timestamp', 'rejector_id', 'rejector_timestamp', 'return_datetime', 'actual_from_datetime', 'actual_to_datetime', 'booking_type', 'approver4_id', 'collector_id', 'returner_id', 'canceller_id', 'canceller_timestamp', 'approver4_timestamp', 'required_time', 'return_time', 'usage_type', 'UserID'];
}