<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactsCommunication extends Model
{
    //Specify the table name
    public $table = 'contacts_communications';

    // Mass assignable fields
    protected $fillable = [
        'communication_type', 'message', 'contact_id', 'status', 'sent_by', 'communication_date', 'company_id', 'time_sent'];
}