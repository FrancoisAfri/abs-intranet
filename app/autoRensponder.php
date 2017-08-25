<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class autoRensponder extends Model
{
    public $table = 'auto_rensponder';
    protected $fillable = [ 'responder_messages','response_emails','ticket_completion_req','ticket_completed','helpdesk_id'];
}
