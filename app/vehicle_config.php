<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vehicle_config extends Model
{

    protected $table = 'vehicle_configuration';
    protected $fillable = ['allow_sending_messages', 'use_fleet_number', 'include_inspection_document', 'new_vehicle_approval',
        'include_division_report', 'fuel_auto_approval', 'fuel_require_tank_manager_approval', 'fuel_require_ceo_approval',
        'mechanic_sms', 'permit_days', 'currency', 'approval_manager_capturer', 'approval_manager_driver', 'approval_hod',
        'approval_admin', 'return_due_manager', 'return_due_hod', 'return_due_admin', 'fines_manager', 'fines_hod',
        'fines_admin', 'incident_minor_manager', 'incident_major_manager', 'incident_critical_manager', 'incident_minor_hod',
        'incident_major_hod', 'incident_critical_hod', 'incident_minor_admin', 'incident_major_admin', 'incident_critical_admin',
        'submit_on_behalf', 'allow_past_bookings', 'notification_method', 'service_days', 'service_km', 'service_overdue_days',
        'service_overdue_km', 'no_bookings_days', 'no_bookings_km', 'no_bookings_minor', ' no_bookings_major', 'no_bookings_critical',
        'inforce_vehicle_image','inforce_vehicle_documents','incidents_upload_directory','alert_days'
		,'brake_test_from','brake_test_to','fire_extinguisher_from','fire_extinguisher_to','fuel_transaction_from','fuel_transaction_to'
		,'get_fitment_from','get_fitment_to','ldv_car_inspection_from','ldv_car_inspection_to','ldv_pre_use_inspections_from'
		,'ldv_pre_use_inspections_to','mechanic_plant_inspections_from','mechanic_plant_inspections_to','truck_tractor_rigid_chassis_from'
		,'truck_tractor_rigid_chassis_to','tyre_survey_reports_from','tyre_survey_reports_to'
		,'job_card_inspection_from','job_card_inspection_to'];
}